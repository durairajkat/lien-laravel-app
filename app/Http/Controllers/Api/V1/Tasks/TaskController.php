<?php

namespace App\Http\Controllers\Api\V1\Tasks;

use App\Http\Controllers\Controller;
use App\Models\ProjectTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{

    public function __construct() {}

    public function count()
    {
        $counts = DB::table('project_tasks')
            ->whereNull('deleted_at')
            ->selectRaw("
                        COUNT(*) as total_count,
                        SUM(CASE 
                            WHEN due_date >= CURDATE() 
                            AND due_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                            THEN 1 ELSE 0 END) as upcoming_count,
                        SUM(CASE 
                            WHEN due_date < CURDATE()
                            THEN 1 ELSE 0 END) as overdue_count
                    ")
            ->first();

        return response()->json([
            'status' => true,
            'message' => 'Project count fetched successfully',
            'data' => $counts,
        ], 200);
    }

    public function index(Request $request)
    {
        $baseQuery = ProjectTask::query()
            ->join('project_details', 'project_details.id', '=', 'project_tasks.project_id')
            ->where('project_details.user_id', auth()->id());

        $overallTotal = (clone $baseQuery)->count();

        $query = $baseQuery
            ->join('task_actions', 'task_actions.id', '=', 'project_tasks.task_action_id')
            ->with('assignedToUser')
            ->select(
                'project_tasks.id',
                'project_tasks.task_name',
                'project_tasks.task_action_id',
                'project_tasks.due_date',
                'project_tasks.complete_date',
                'project_tasks.status',
                'project_details.project_name',
                'project_tasks.comment',
                'task_actions.name as task_action_name',
                'project_tasks.assigned_at',
                'project_tasks.assigned_by',
                'project_tasks.assigned_to',
                'project_tasks.created_at',
                'project_tasks.email_alert',
                DB::raw("DATEDIFF(project_tasks.due_date, CURDATE()) as days_difference")
            );

        /* -------- SEARCH -------- */
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('project_details.project_name', 'like', "%{$request->search}%")
                    ->orWhere('project_tasks.task_name', 'like', "%{$request->search}%")
                    ->orWhere('project_tasks.due_date', 'like', "%{$request->search}%")
                    ->orWhere('project_tasks.complete_date', 'like', "%{$request->search}%");
            });
        }

        /* -------- FILTER STATUS -------- */
        if ($request->status) {
            $query->where('project_tasks.status', $request->status);
        }
        $query->when($request->action_id, function ($q) use ($request) {
            $q->where('project_tasks.task_action_id', $request->action_id);
        });
        $query->when($request->projectId, function ($q) use ($request) {
            $q->where('project_tasks.project_id', $request->projectId);
        });
        /* -------- SORT -------- */
        $sortBy = $request->sort_by ?? 'project_tasks.created_at';
        $sortDir = $request->sort_dir ?? 'desc';

        $query->orderBy($sortBy, $sortDir);

        /* -------- PAGINATION -------- */
        $projects = $query->paginate($request->per_page ?? 10);

        $projects->getCollection()->transform(function ($task) {

            if ($task->days_difference > 0) {
                $task->due_status = $task->days_difference . ' days remaining';
            } elseif ($task->days_difference < 0) {
                $task->due_status = abs($task->days_difference) . ' days overdue';
            } else {
                $task->due_status = 'Due today';
            }

            return $task;
        });

        return response()->json([
            'success' => true,
            'data' => $projects,
            'overall_total' => $overallTotal,
        ]);
    }

    public function view(ProjectTask $task)
    {
        $task = ProjectTask::with(['actionType', 'assignedToUser', 'assignedByUser', 'project'])
            ->find($task->id);

        return response()->json([
            'success' => true,
            'data' => $task,
        ]);
    }

}
