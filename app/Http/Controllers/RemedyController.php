<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\State;
use App\Models\Remedy;
use App\Models\TierTable;
use App\Models\RemedyDate;
use App\Models\RemedyStep;
use App\Models\ProjectRole;
use App\Models\ProjectType;
use App\Models\CustomerCode;
use Illuminate\Http\Request;
use App\Models\RemedyQuestion;
use App\Models\TierRemedyStep;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

/**
 * For Show All Remedy Tables in admin panel
 * Class RemedyController
 * @package App\Http\Controllers
 */
class RemedyController extends Controller
{
    /**
     * Customer view From admin
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getCustomer(Request $request)
    {
        try {
            $customers = CustomerCode::all();
            $roles = ProjectRole::all();
            $searchTier = $request->get('projectTier');
            $queryTier = TierTable::orderBy('tier_code', 'ASC');
            if (is_null($searchTier)) {
                $tier = $queryTier->paginate(15, ['*'], 'tierPage');
            } else {
                $tier = $queryTier->where('tier_limit', 'LIKE', '%' . $searchTier . '%')
                    ->orWhere('tier_code', 'LIKE', '%' . $searchTier . '%')
                    ->paginate(15, ['*'], 'tierPage');
            }
            return view('project.customer', [
                'roles' => $roles,
                'tiers' => $tier,
                'customers' => $customers
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Get Remedy view form admin panel
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getRemedy()
    {
        try {
            $states = State::all();
            $projectTypes = ProjectType::all();
            $search = request()->get('remedySearch');

            if (is_null($search)) {
                $queryRemedy = Remedy::orderBy('remedy', 'ASC');
                $remedies = $queryRemedy->paginate(15);
            } else {
                $remedies = Remedy::join('states', 'remedies.state_id', '=', 'states.id')
                    ->where('states.name', 'LIKE', '%' . $search . '%')
                    ->paginate(100);
            }
            return view('remedy.remedy', [
                'remedies' => $remedies,
                'states' => $states,
                'projectTypes' => $projectTypes
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Show Remedy_dates table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getRemedyDates()
    {
        try {
            $remedies = Remedy::all();
            $flag = 0;
            $search = request()->get('remedyDateSearch');
            $search_private = request()->get('remedyDateSearchPrivate');
            $state = State::all();
            $state1 = State::all();
            $filter = request()->get('state_id');
            $filter_private = request()->get('state_id1');
            $tab = request()->get('tab');
            // if($filter == 'All City' || $filter_private == 'All City')
            // {
            //     return redirect()->back();
            // }
            if (is_null($filter)) {
                $queryRemedyDates = RemedyDate::where('status', '1')->orderBy('remedy_id', 'ASC');
                // $flag = 1;
            } else {
                if ($filter == 'All City') {
                    $queryRemedyDates = RemedyDate::where('status', '1')->orderBy('remedy_id', 'ASC');
                } else {
                    $remedies = Remedy::where('state_id', $filter);
                    //dd($remedies->get());
                    $queryRemedyDates = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedies->pluck('id'))->orderBy('remedy_id', 'ASC');
                }
                //$flag = 1;
                //dd($queryRemedyDates->get());
            }
            if (is_null($search)) {
                if (is_null($filter)) {
                    $remedyDates = $queryRemedyDates->paginate(15);
                } else {
                    $remedyDates = $queryRemedyDates->paginate(30);
                    // $flag = 1;
                }
            } else {
                $remedyDates = $queryRemedyDates->join('remedies', "remedy_dates.remedy_id", "=", 'remedies.id')
                    ->join("states", "remedies.state_id", "=", "states.id")
                    ->where("states.name", "LIKE", "%" . $search . "%")
                    ->paginate(100);
            }
            if (is_null($filter_private)) {
                $queryRemedyDatesPrivate = RemedyDate::where('status', '0')->orderBy('remedy_id', 'ASC');
                // $flag =2;
            } else {
                if ($filter_private == 'All City') {
                    $queryRemedyDatesPrivate = RemedyDate::where('status', '0')->orderBy('remedy_id', 'ASC');
                    // $flag= 2;
                } else {
                    $remedies = Remedy::where('state_id', $filter_private);
                    //dd($remedies->get());
                    $queryRemedyDatesPrivate = RemedyDate::where('status', '0')->whereIn('remedy_id', $remedies->pluck('id'))->orderBy('remedy_id', 'ASC');
                    // $flag = 2;
                }
            }
            if (is_null($search_private)) {
                if (is_null($filter)) {
                    // $remedyDates = $queryRemedyDates->paginate(15);
                    $remedyDatesPrivate = $queryRemedyDatesPrivate->paginate(15);
                    //  $flag = 2;
                } else {
                    $remedyDatesPrivate = $queryRemedyDatesPrivate->join('remedies', "remedy_dates.remedy_id", "=", 'remedies.id')
                        ->join("states", "remedies.state_id", "=", "states.id")
                        ->where("states.name", "LIKE", "%" . $search . "%")
                        ->paginate(30);
                    //$flag =2;
                }

                //$remedyDatesPrivate = $queryRemedyDatesPrivate->paginate(15);
            } else {
                $remedyDatesPrivate = $queryRemedyDatesPrivate->where('date_name', 'LIKE', '%' . $search_private . '%')
                    ->paginate(15);
                // $flag = 2;
            }
            if ($filter || $search) {
                $flag = 1;
            } else if ($filter_private || $search_private) {
                # code...
                $flag = 2;
            } else {
                $flag = 0;
            }
//            dd($remedyDates);
            return view('remedy.remedy_dates', [
                'remedies' => $remedies,
                'remedyDates' => $remedyDates,
                'remedyDatesPrivate' => $remedyDatesPrivate,
                'search' => $search,
                'search_private' => $search_private,
                'state' => $state,
                'state1' => $state1,
                'filter' => $filter,
                'filter_private' => $filter_private,
                'flag' => $flag,
                'tab' => $tab
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Show Remedy_dates private table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getRemedyDatesPrivate()
    {
        try {
            $remedies = Remedy::all();
            $search = request()->get('remedyDateSearch');
            $queryRemedyDates = RemedyDate::where('status', '0')->orderBy('remedy_id', 'ASC');
            if (is_null($search)) {
                $remedyDates = $queryRemedyDates->paginate(15);
            } else {
                $remedyDates = $queryRemedyDates->where('date_name', 'LIKE', '%' . $search . '%')
                    ->paginate(100);
            }
            return view('remedy.remedy_dates_private', [
                'remedies' => $remedies,
                'remedyDates' => $remedyDates,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }


    /**
     * Get Remedy Question views
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getRemedyQuestions()
    {
        try {
            $states = State::all();
            $projectTypes = ProjectType::all();
            $tiers = TierTable::all();
            $search = request()->get('remedyQuestion');
            if (is_null($search)) {
                $queryRemedyQuestion = RemedyQuestion::orderBy('created_at', 'DESC');
                $remedyQuestion = $queryRemedyQuestion->paginate(15);
            } else {
                $remedyQuestion = RemedyQuestion::join('states', 'remedy_questions.state_id', '=', 'states.id')
                    ->where('states.name', 'LIKE', '%' . $search . '%')
                    ->orderBy('remedy_questions.created_at', 'DESC')
                    ->paginate(100);
                // $remedyQuestion = $queryRemedyQuestion->where('question', 'LIKE', '%' . $search . '%')
                //     ->paginate(15);
            }
            return view('remedy.remedy_question', [
                'remedyQuestions' => $remedyQuestion,
                'states' => $states,
                'types' => $projectTypes,
                'tiers' => $tiers
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Get Remedy Steps view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getRemedySteps()
    {
        try {
            $remedyDate = RemedyDate::all();
            $remedy = Remedy::all();
            $flag = 0;
            $search = request()->get('remedyStep');
            $search_private = request()->get('remedyStepPrivate');
            $filter = request()->get('state_id');
            $filter_private = request()->get('state_id1');
            $state = State::all();
            $state1 = State::all();
            $tab = request()->get('tab');


            if (is_null($filter)) {
                $queryRemedyStep = RemedyStep::where('status', '1')->orderBy('remedy_id', 'ASC');
            } else {
                if ($filter == 'All City') {
                    $queryRemedyStep = RemedyStep::where('status', '1')->orderBy('remedy_id', 'ASC');
                } else {
                    $remedies = Remedy::where('state_id', $filter);
                    //dd($remedies->get());
                    $queryRemedyStep = RemedyStep::where('status', '1')->whereIn('remedy_id', $remedies->pluck('id'))->orderBy('remedy_id', 'ASC');
                    //dd($queryRemedyStep->get());
                }
            }
            // $queryRemedyStep = RemedyStep::where('status','1')->orderBy('created_at', 'DESC');
            if (is_null($search)) {
                // $remedyStep = $queryRemedyStep->paginate(15);
                if (is_null($filter)) {
                    $remedyStep = $queryRemedyStep->paginate(15);
                } else {
                    $remedyStep = $queryRemedyStep->paginate(100);
                }
            } else {
                $remedyStep = $queryRemedyStep->join('remedies', "remedy_steps.remedy_id", "=", 'remedies.id')
                    ->join("states", "remedies.state_id", "=", "states.id")
                    ->where("states.name", "LIKE", "%" . $search . "%")
                    ->paginate(100);
            }
            //$queryRemedyStepPrivate = RemedyStep::where('status','0')->orderBy('remedy_id', 'ASC');
            //dd($queryRemedyStepPrivate->get());
            if (is_null($filter_private)) {
                $queryRemedyStepPrivate = RemedyStep::where('status', '0')->orderBy('remedy_id', 'ASC');
            } else {
                if ($filter_private == 'All City') {
                    $queryRemedyStepPrivate = RemedyStep::where('status', '0')->orderBy('remedy_id', 'ASC');
                } else {
                    $remedies = Remedy::where('state_id', $filter_private);
                    //dd($remedies->get());
                    $queryRemedyStepPrivate = RemedyStep::where('status', '0')->whereIn('remedy_id', $remedies->pluck('id'))->orderBy('remedy_id', 'ASC');
                }
            }
            if (is_null($search_private)) {
                if (is_null($filter)) {
                    $remedyStepPrivate = $queryRemedyStepPrivate->paginate(15);
                } else {
                    $remedyStepPrivate = $queryRemedyStepPrivate->join('remedies', "remedy_steps.remedy_id", "=", 'remedies.id')
                        ->join("states", "remedies.state_id", "=", "states.id")
                        ->where("states.name", "LIKE", "%" . $search . "%")
                        ->paginate(100);
                    // $remedyStepPrivate = $queryRemedyStepPrivate->paginate(50);
                }
                //$remedyStepPrivate = $queryRemedyStepPrivate->paginate(15);
            } else {
                $remedyStepPrivate = $queryRemedyStepPrivate->where('short_description', 'LIKE', '%' . $search_private . '%')
                    ->paginate(15);
            }
            if ($filter || $search) {
                $flag = 1;
            } else if ($filter_private || $search_private) {
                # code...
                $flag = 2;
            } else {
                $flag = 0;
            }
            return view('remedy.remedy_step', [
                'remedySteps' => $remedyStep,
                'remedyStepPrivate' => $remedyStepPrivate,
                'remedyDates' => $remedyDate,
                'remedies' => $remedy,
                'search' => $search,
                'search_private' => $search_private,
                'state' => $state,
                'state1' => $state1,
                'filter' => $filter,
                'filter_private' => $filter_private,
                'flag' => $flag,
                'tab' => $tab
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Get Tier Remedy Steps view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getTierRemedySteps()
    {
        try {
            $search = request()->get('tierRemedySearch');
            $tiers = TierTable::all();
            $remedySteps = RemedyStep::all();
            $queryTierRemedyStep = TierRemedyStep::orderBy('created_at', 'DESC');
            if (is_null($search)) {
                $tierRemedyStep = $queryTierRemedyStep->paginate(15);
            } else {
                $tierRemedyStep = TierRemedyStep::join('remedy_steps', "tier_remedy_steps.remedy_step_id", "=", 'remedy_steps.id')
                    ->join("remedies", "remedies.id", "=", "remedy_steps.remedy_id")
                    ->join("states", "remedies.state_id", "=", "states.id")
                    ->where("states.name", "LIKE", "%" . $search . "%")
                    ->orderBy('remedy_steps.created_at', 'DESC')
                    ->paginate(100);
                // $tierRemedyStep = $queryTierRemedyStep->where('answer1', 'LIKE', '%' . $search . '%')
                //     ->where('answer2', 'LIKE', '%' . $search . '%')->paginate(15);
            }
            return view('remedy.tier_remedy_step', [
                'tierRemedySteps' => $tierRemedyStep,
                'tiers' => $tiers,
                'remedySteps' => $remedySteps,

            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Hide remedy dates
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function hideRemedyDates(Request $request)
    {
        try {
            if ($request->hideRemedyDates != null) {

                foreach ($request->hideRemedyDates as $key => $value) {

                    $remedy = RemedyDate::find($value);
                    $remedy->status = 0;
                    $remedy->save();
                }
                return redirect()->back()->with('success', 'Records are moved to private successfully');
            } else {
                return redirect()->back()->with('try-error-rem', 'No row is selected');
            }
            //$request->hideRemedyDates);


        } catch (Exception $e) {

            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $e) {

            return redirect()->back()->with('try-error', $e->getMessage());
        }
    }

    /**
     * Remedy dates
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function publicRemedyDates(Request $request)
    {
        try {
            if ($request->hideRemedyDates != null) {

                foreach ($request->hideRemedyDates as $key => $value) {

                    $remedy = RemedyDate::find($value);
                    $remedy->status = 1;
                    $remedy->save();
                }
                return redirect()->back()->with('success', 'Records are moved to public successfully');
            } else {
                return redirect()->back()->with('try-error-rem', 'No row is selected');
            }
            //$request->hideRemedyDates);


        } catch (Exception $e) {

            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $e) {

            return redirect()->back()->with('try-error', $e->getMessage());
        }
    }

    /**
     * hide remedy step
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function hideRemedySteps(Request $request)
    {
        try {
            if ($request->hideRemedySteps != null) {

                foreach ($request->hideRemedySteps as $key => $value) {

                    $remedy = RemedyStep::find($value);
                    $remedy->status = 0;
                    $remedy->save();
                }
                return redirect()->back()->with('success', 'Records are moved to private successfully');
            } else {
                return redirect()->back()->with('try-error-rem', 'No row is selected');
            }
            //$request->hideRemedyDates);


        } catch (Exception $e) {

            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $e) {

            return redirect()->back()->with('try-error', $e->getMessage());
        }
    }

    /**
     * public remedy steps
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function publicRemedySteps(Request $request)
    {
        try {
            if ($request->hideRemedySteps != null) {

                foreach ($request->hideRemedySteps as $key => $value) {

                    $remedy = RemedyStep::find($value);
                    $remedy->status = 1;
                    $remedy->save();
                }
                return redirect()->back()->with('success', 'Records are moved to public successfully');
            } else {
                return redirect()->back()->with('try-error-rem', 'No row is selected');
            }
            //$request->hideRemedyDates);


        } catch (Exception $e) {

            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $e) {

            return redirect()->back()->with('try-error', $e->getMessage());
        }
    }

    public function getRemedies(Request $request) {
        if ($request->header('Authorization') != config('services.EXTERNAL_API_KEY')) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
        $tierRemedyStep = RemedyStep::select(DB::raw('remedy_steps.*, states.name as state_name'))
            ->join("remedies", "remedies.id", "=", "remedy_steps.remedy_id")
            ->join("states", "remedies.state_id", "=", "states.id")
            ->where("remedies.project_type_id", "=", '3')
            ->orderBy('remedies.state_id', 'ASC')
            ->get();

        return response()->json([
            'remedies' => $tierRemedyStep,
            'status' => true,
            'message' => 'Remedies fetched successfully',
        ], 200);
    }
}
