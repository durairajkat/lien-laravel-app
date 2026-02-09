<?php

namespace App\Http\Controllers;

use App\User;
use Response;
use Exception;
use App\Models\State;
use App\Models\ClaimData;
use Illuminate\Http\Request;
use App\Models\ProjectDetail;
use Illuminate\Support\Facades\Auth;
use App\Models\ClaimFormProjectDataSheet;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ClaimController
 * @package App\Http\Controllers
 */
class ClaimController extends Controller
{
    /**
     * Claim Data Sheet
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function newClaim()
    {
        try {
            $state = State::all();
            return view('basicUser.claim.new_clam1', [
                'states' => $state
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * New Claim
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getNewClaim()
    {
        try {
            $state = State::all();
            return view('basicUser.claim.claim_data_sheet', [
                'states' => $state
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * show claim amount admin
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showClaimAdmin()
    {
        # code...
        $allData = ClaimData::orderBy('created_at', 'DESC')
            ->paginate(10);
        // dd($allData);
        return view('admin.claim.list', [
            'claimData' => $allData,
            'pageName' => 'Claim Details'
        ]);
    }

    /**
     * new claim step
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function newClaimStep1()
    {
        try {
            $projectId = request()->get('project_id');
            $state = State::all();
            $project = ProjectDetail::findOrFail($projectId);
            return view('basicUser.claim.claim_step1', [
                'states' => $state,
                'project' => $project
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function newClaimStep2()
    {
        try {
            // dd($states);
            $state = State::all();
            return view('basicUser.claim.claim_step2', [
                'states' => $state
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function newClaimStep3()
    {
        try {

            $state = State::all();
            // dd($state);
            return view('basicUser.claim.claim_step3', [
                'states' => $state
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function newClaimStep4()
    {
        try {
            $state = State::all();
            return view('basicUser.claim.claim_step4', [
                'states' => $state
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitNewClaimOld(Request $request)
    {
        try {
            if ($request->claim_id == 0) {
                $claimForm = new ClaimFormProjectDataSheet();
                $claimForm->project_id = Auth::user()->id;
            } else {
                $claimForm = ClaimFormProjectDataSheet::findOrFail($request->claim_id);
            }
            $claimForm->fill($request->all());
            $claimForm->status = 1;
            $claimForm->save();
            return redirect()->back()->with('success', 'Claim data successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function submitNewClaim(Request $request)
    {
        //dd($request->all());

        try {
            // dd($request->all());
            $state = State::all();
            if ($request->step == 1) {
                //dd($request->all());
                if ($request->claim_id == 0) {
                    $claimForm = new ClaimData();
                    $claimForm->user_id = Auth::user()->id;
                } else {
                    $claimForm = ClaimData::findOrFail($request->claim_id);
                }
                //$claimForm->fill($request->all());
                $claimForm->project_name = $request->project_name;
                $claimForm->filling_type = $request->filing_type;
                $claimForm->contact_name = $request->contract_type;
                $claimForm->original = $request->original;
                $claimForm->base_amount = $request->base_amount;
                $claimForm->extra_amount = $request->extra_amount;
                $claimForm->payment = $request->payment;
                // $claimForm->notice_step1 = $request->notice_step1;
                if ($request->has('notice_step1')) {
                    $file = request()->file('notice_step1');
                    //dd($file);
                    $destinationPath = public_path() . '/upload/';
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $upload_success = request()->file('notice_step1')->move($destinationPath, $filename);
                    //$claimForm->notice_step1 = $request->notice_step1;
                    if ($upload_success) {
                        $fileName = $filename;
                        $claimForm->notice_step1 = $fileName;
                    } else {
                        $fileName = '';
                        $claimForm->notice_step1 = $fileName;
                    }
                } else {
                    $fileName = '';
                    //$claimForm->notice_step1 = $request->notice_step1;
                }
                $claimForm->save();
                //dd($claimForm->id);
                return view('basicUser.claim.claim_step2', [
                    'id' => $claimForm->id,
                    'project_name' => $claimForm->project_name,
                    'states' => $state
                ]);
                // return redirect()->route('admin.new.claim_step2',['id' => $claimForm->id]);

            } elseif ($request->step == 2) {
                # code...
                // dd($request->all());
                if ($request->id == 0) {
                    $claimForm = new ClaimData();
                    $claimForm->user_id = Auth::user()->id;
                } else {
                    $claimForm = ClaimData::findOrFail($request->id);
                }
                $claimForm->project_name = $request->project_name;
                $claimForm->status = $request->status;
                $claimForm->custom = $request->custom;
                $claimForm->preliminary = $request->preliminary;
                $claimForm->lien = $request->lien;
                if ($request->has('myfile_date')) {
                    $file = request()->file('myfile_date');
                    //dd($file);
                    $destinationPath = public_path() . '/upload/';
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $upload_success = request()->file('myfile_date')->move($destinationPath, $filename);
                    //$claimForm->notice_step1 = $request->notice_step1;
                    if ($upload_success) {
                        $fileName = $filename;
                        $claimForm->myfile_date = $fileName;
                    } else {
                        $fileName = '';
                        $claimForm->myfile_date = $fileName;
                    }
                } else {
                    $fileName = '';
                    //$claimForm->notice_step1 = $request->notice_step1;
                }
                if ($request->has('myfile_preliminary')) {
                    $file = request()->file('myfile_preliminary');
                    //dd($file);
                    $destinationPath = public_path() . '/upload/';
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $upload_success = request()->file('myfile_preliminary')->move($destinationPath, $filename);
                    //$claimForm->notice_step1 = $request->notice_step1;
                    if ($upload_success) {
                        $fileName = $filename;
                        $claimForm->myfile_preliminary = $fileName;
                    } else {
                        $fileName = '';
                        $claimForm->myfile_preliminary = $fileName;
                    }
                } else {
                    $fileName = '';
                    //$claimForm->notice_step1 = $request->notice_step1;
                }
                if ($request->has('myfile_lien')) {
                    $file = request()->file('myfile_lien');
                    //dd($file);
                    $destinationPath = public_path() . '/upload/';
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $upload_success = request()->file('myfile_lien')->move($destinationPath, $filename);
                    //$claimForm->notice_step1 = $request->notice_step1;
                    if ($upload_success) {
                        $fileName = $filename;
                        $claimForm->myfile_lien = $fileName;
                    } else {
                        $fileName = '';
                        $claimForm->myfile_lien = $fileName;
                    }
                } else {
                    $fileName = '';
                    //$claimForm->notice_step1 = $request->notice_step1;
                }
                if ($request->has('myfile')) {
                    $file = request()->file('myfile');
                    //dd($file);
                    $destinationPath = public_path() . '/upload/';
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $upload_success = request()->file('myfile')->move($destinationPath, $filename);
                    //$claimForm->notice_step1 = $request->notice_step1;
                    if ($upload_success) {
                        $fileName = $filename;
                        $claimForm->myfile = $fileName;
                    } else {
                        $fileName = '';
                        $claimForm->myfile = $fileName;
                    }
                } else {
                    $fileName = '';
                    //$claimForm->notice_step1 = $request->notice_step1;
                }
                $claimForm->save();
                // return redirect()->route('admin.new.claim_step3');
                //      return view('basicUser.claim.claim_step3',[
                //     'states' => $state
                // ]);
                return view('basicUser.claim.claim_step3', [
                    'id' => $claimForm->id,
                    'project_name' => $claimForm->project_name,
                    'states' => $state
                ]);
            } elseif ($request->step == 3) {
                # code...
                // dd($request->all());
                if ($request->id == 0) {
                    $claimForm = new ClaimData();
                    $claimForm->user_id = Auth::user()->id;
                } else {
                    $claimForm = ClaimData::findOrFail($request->id);
                }
                //$claimForm->fill($request->all());
                $claimForm->project_name = $request->project_name;
                $claimForm->construction = $request->construction;
                $claimForm->first_date = $request->first_date;
                $claimForm->last_date = $request->last_date;
                $claimForm->shipping = $request->shipping;
                $claimForm->whole = $request->whole;
                // $claimForm->notice_step1 = $request->notice_step1;
                if ($request->has('myfile')) {
                    $file = request()->file('myfile');
                    //dd($file);
                    $destinationPath = public_path() . '/upload/';
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $upload_success = request()->file('myfile')->move($destinationPath, $filename);
                    //$claimForm->notice_step1 = $request->notice_step1;
                    if ($upload_success) {
                        $fileName = $filename;
                        $claimForm->myfile_next = $fileName;
                    } else {
                        $fileName = '';
                        $claimForm->myfile_next = $fileName;
                    }
                } else {
                    $fileName = '';
                    //$claimForm->notice_step1 = $request->notice_step1;
                }
                $claimForm->save();
                return view('basicUser.claim.claim_step4', [
                    'states' => $state,
                    'project_name' => $claimForm->project_name,
                    'id' => $claimForm->id
                ]);
                //return redirect()->route('admin.new.claim_step4');

            } elseif ($request->step == 4) {
                # code...
                // / dd($request->all());
                // $this->validate($request, [
                // 'agree' => 'required',
                // ]);
                if ($request->id == 0) {
                    $claimForm = new ClaimData();
                    $claimForm->user_id = Auth::user()->id;
                } else {
                    $claimForm = ClaimData::findOrFail($request->id);
                }
                $claimForm->project_name = $request->project_name;
                $claimForm->state_id = $request->state;
                $claimForm->save();
                return view('basicUser.claim.claim_step5', [
                    'states' => $state,
                    'project_name' => $claimForm->project_name,
                    'success' => 'Claim submitted successfully'
                ]);
            }
            // return redirect()->back()->with('success','Claim data successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewClaim($id)
    {
        // dd($id);
        $allData = ClaimData::where('id', $id)->orderBy('created_at', 'DESC')->first();

        //dd($allData);
        return view('admin.claim.details', [
            'claimData' => $allData,
            'pageName' => 'Claim Details'
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveNewClaim(Request $request)
    {
        //dd($request->all());
        try {
            if ($request->claim_id == 0) {
                $claimForm = new ClaimFormProjectDataSheet();
                $claimForm->user_id = Auth::user()->id;
            } else {
                $claimForm = ClaimFormProjectDataSheet::findOrFail($request->claim_id);
            }
            $claimForm->fill($request->all());
            $claimForm->status = 1;
            $claimForm->save();
            return response()->json([
                'success' => true,
                'message' => 'Saved',
                'data' => $claimForm->id
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => $exception->getMessage()
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * Delete claim from Admin
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteClaim(Request $request)
    {
        try {
            $this->validate($request, [
                'id' => 'required'
            ]);

            $ClaimData = ClaimData::findOrFail($request->id);
            $ClaimData->delete();

            return response()->json([
                'status' => true,
                'message' => 'deleted'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 200);
        }
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getDownload($name)
    {
        //PDF file is stored under project/public/download/info.pdf
        //dd($name);
        $file = public_path() . "/upload/" . $name;

        $headers = array(
            'Content-Type: application/pdf',
            'Content-Type: text/plain',
            'Content-Type: image/jpeg',
            'Content-Type: image/png',
        );
        return Response::download($file, $name, $headers);
    }
}
