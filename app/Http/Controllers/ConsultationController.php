<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ConsultationController for Consultation management
 * @package App\Http\Controllers
 */
class ConsultationController extends Controller
{
    /**
     * Get Consultation View from Member
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getConsultation()
    {
        return view('basicUser.contacts.consultation');
    }

    /**
     * Post Consultation Member
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postConsultation(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required|numeric',
            'email' => 'required|email',
            'claim_amount' => 'required|numeric',
            'description' => 'required'

        ]);
        try {

            $consultation = new Consultation();
            //$consultation->user_id = $request->user_id;
            $consultation->first_name = $request->first_name;
            $consultation->last_name = $request->last_name;
            $consultation->phone_number = $request->phone_number;
            $consultation->email = $request->email;
            $consultation->claim_amount = $request->claim_amount;
            $consultation->description = $request->description;
            $consultation->type = $request->type;
            $consultation->save();

            if ($request->type == 1) {
                $message = 'Consultation submitted successfully';
            } else {
                $message = 'Collect Receivables submitted successfully';
            }

            return redirect()->route('member.get.consultation')->with('success', $message);
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Get Collect Receivable from Member
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCollectReceivables()
    {
        return view('basicUser.contacts.collect-receivable');
    }

    /**
     * Get Consultation From Admin
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getAdminConsultation()
    {
        try {
            $consultation = Consultation::where('type', 1)
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
            //dd($consultation);
            return view('admin.consultation.list', [
                'consultations' => $consultation,
                'pageName' => 'Consultation'
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Reply Consultation from Admin
     * @param $consultation_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function replyConsultation($consultation_id)
    {

        try {
            $consultation = Consultation::findOrFail($consultation_id);
            return view('admin.consultation.reply', [
                'consultation' => $consultation,
                'pageName' => 'Consultation'
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Post reply consultation from Admin
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendReplyConsultation(Request $request)
    {
        try {
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $destinationPath = public_path() . '/upload/';
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $upload_success = $file->move($destinationPath, $filename);
                if ($upload_success) {
                    $fileName = $filename;
                } else {
                    $fileName = '';
                }
            } else {
                $fileName = '';
            }
            //  $id=$request->consultation_id);
            $consultation = Consultation::findOrFail($request->consultation_id);

            $email = $consultation->email;

            $subject = $request->subject;

            Mail::send('admin.contact.replyMail', ['mes' => $request->message], function ($m) use ($email, $subject, $fileName) {
                $m->from("reply@nlb-access.dev", "Reply From National Lien & Bound");
                $m->to($email)->subject($subject);
                if ($fileName != '') {
                    $m->attach(env('ASSET_URL') . '/upload/' . $fileName);
                }
            });
            return redirect()->back()->with('success', 'Mail Send Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Delete Consultation from Admin
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteConsultation(Request $request)
    {
        try {
            $this->validate($request, [
                'id' => 'required'
            ]);

            $consultation = Consultation::findOrFail($request->id);
            $consultation->delete();

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
     * View Collect Receivable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function viewCollectReceivable()
    {
        try {
            $consultation = Consultation::where('type', 2)
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
            return view('admin.consultation.list', [
                'consultations' => $consultation,
                'pageName' => 'Collect Receivable'
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }
}
