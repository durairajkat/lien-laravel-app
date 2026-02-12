<?php

namespace App\Http\Controllers\Api\V1\Project;

use App\Http\Controllers\Controller;
use App\Http\Resources\Project\RemedyDatesResource;
use App\Models\LienLawSlideChart;
use App\Models\ProjectDetail;
use App\Models\Remedy;
use App\Models\RemedyDate;
use App\Models\RemedyStep;
use App\Models\TierRemedyStep;
use App\Models\TierTable;
use App\Services\Project\DeadlineService;
use Illuminate\Http\Request;

class DeadlineApiController extends Controller
{

    public function __construct(protected DeadlineService $deadlineService) {}

    public function getRemedyDates(Request $request)
    {
        $stateId       = $request->state_id;
        $projectTypeId = $request->project_type_id;
        $roleId        = $request->role_id;
        $customerId    = $request->customer_type_id;

        $remedy = Remedy::where('state_id', $stateId)
            ->where('project_type_id', $projectTypeId);

        $remedySteps = RemedyStep::whereIn('remedy_id', $remedy->pluck('id'));

        $tiers = TierTable::where('role_id', $roleId)
            ->where('customer_id', $customerId)->firstOrFail();

        $tierRemedySteps = TierRemedyStep::where('tier_id', $tiers->id)
            ->whereIn('remedy_step_id', $remedySteps->pluck('id'));

        $remedyStepsNew = $remedySteps->whereIn('id', $tierRemedySteps->pluck('remedy_step_id'));

        $remedyDates = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy->pluck('id'))
            ->whereIn('id', $remedyStepsNew->pluck('remedy_date_id'))
            ->orderBy('date_order', 'ASC')->get();

        return response()->json([
            'status' => true,
            'data' => RemedyDatesResource::collection($remedyDates),
            'message' => 'Remedy Dates Retrieved Successfully',
        ], 200);
    }


    public function getDeadlineInfo(Request $request)
    {
        $furnishingDates = [];
        if ($request->filled('project_id')) {
            $project = ProjectDetail::findOrFail($request->project_id);

            $answer        = $project->answer1 ?? null;
        } else {
            $stateId       = $request->state_id;
            $projectTypeId = $request->project_type_id;
            $roleId        = $request->role_id;
            $customerId    = $request->customer_id;
            $answer        = $request->answer1 ?? null;
            $furnishingDates = $request->furnishing_dates ?? [];
            if (is_string($furnishingDates)) {
                $furnishingDates = json_decode($furnishingDates, true);
            }

            $project = (object) [
                'state_id'        => $stateId,
                'project_type_id' => $projectTypeId,
                'role_id'         => $roleId,
                'customer_id'     => $customerId,
                'answer1'         => $answer,
            ];
        }
        /**
         * Map answer → allowed values
         */
        $answerMap = [
            'Yes'         => ['Yes', ''],
            'Commercial'  => ['Commercial', ''],
            'No'          => ['No', ''],
            'Residential' => ['Residential', ''],
        ];

        /**
         * Get liens
         */
        $liens = LienLawSlideChart::where([
            'state_id'     => $project->state_id,
            'project_type' => $project->project_type_id,
        ])->select('remedy', 'description', 'tier_limit')->get();

        /**
         * Tier ID
         */
        $tierId = TierTable::where([
            'role_id'     => $project->role_id,
            'customer_id' => $project->customer_id,
        ])->value('id');

        /**
         * Remedy + dates
         */
        $remedy = Remedy::with('remedyDates:id,remedy_id')
            ->where([
                'state_id'        => $project->state_id,
                'project_type_id' => $project->project_type_id,
            ]);

        $remedyDateIds = RemedyDate::whereIn('remedy_id', $remedy->pluck('id'))
            ->where('status', 1)
            ->pluck('id');

        /**
         * Tier remedy steps
         */
        $tierRemedyQuery = TierRemedyStep::where('tier_id', $tierId);

        if (isset($answerMap[$answer])) {
            $tierRemedyQuery->whereIn('answer1', $answerMap[$answer]);
        }

        $tierRemedyStepIds = $tierRemedyQuery->pluck('remedy_step_id');

        /**
         * Remedy steps (deadlines)
         */
        $deadline = RemedyStep::select([
            'id',
            'short_description',
            'remedy_id',
            'remedy_date_id',
            'long_description',
            'years',
            'months',
            'days',
            'day_of_month',
            'notes',
            'status',
            'email_alert',
            'legal_completion_date',
        ])
            ->where('status', 1)
            ->whereIn('remedy_id', $remedy->pluck('id'))
            ->whereIn('remedy_date_id', $remedyDateIds)
            ->whereIn('id', $tierRemedyStepIds)
            ->get();

        /**
         * Days remaining
         */
        $daysRemain = $this->deadlineService->calculateDaysRemaining($deadline, $project, $furnishingDates);
        $finalData = [];
        if (count($deadline) > 0) {
            foreach ($deadline as $key => $dline) {
                if (strlen($daysRemain[$key]['preliminaryDates']) > 5) {
                    $today = date('Y-m-d');
                    $today = new \DateTime($today);
                    $prelimDead = $daysRemain[$key]['preliminaryDates'];
                    $formatPrelim = new \DateTime($prelimDead);
                    $daysUntilDeadline = date_diff($formatPrelim, $today);
                    $daysUntilDeadline = $daysUntilDeadline->format('%a');
                    $late = date_diff($formatPrelim, $today);
                    $late = $late->format('%R');
                } else {
                    $daysUntilDeadline = 'N/A';
                    $late = 0;
                }

                $tmp = [
                    'title' => $dline->getRemedy->remedy,
                    'date' => strlen($daysRemain[$key]['preliminaryDates']) > 5 ? date('M d, Y', strtotime($daysRemain[$key]['preliminaryDates'])) : 'N/A',
                    'is_late' =>  strlen($daysRemain[$key]['preliminaryDates']) > 5 && $late === '+' ? true : false,
                    'daysRemaining' => strlen($daysRemain[$key]['preliminaryDates']) > 5 ? $daysUntilDeadline : 'N/A',
                    'remedies' => [
                        'title' => $dline->getRemedy->remedy,
                        'description' => $dline->short_description,
                    ],
                    'requirement' => $dline->short_description
                ];
                $finalData[] = $tmp;
            }
        }

        return response()->json([
            'status'          => true,
            'data' => [
                'deadlines'       => $finalData,
            ],
            'message'         => 'Deadline Info Retrieved Successfully',
        ], 200);
    }
}
