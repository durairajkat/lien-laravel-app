<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use App\Models\State;
use App\Models\Company;
use App\Models\UserDetails;
use App\Jobs\SendInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class InviteController for Invitation system for member
 * @package App\Http\Controllers
 */
class InviteController extends Controller
{
    /**
     * Get Invite page from member section
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getInvite()
    {
        return view('basicUser.user.invite');
    }

    /**
     * Action for Invitation form
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postInvite(Request $request)
    {  //dd($request->all());
        //        $this->validate($request, [
        //            'message' => 'required'
        //        ]);
        try {
            $flag = true;
            //            foreach ($request->email as $key => $email) {
            if ($request->address == '') {
                $flag = false;
            }
            if ($request->companyName == '') {
                $flag = false;
            }
            if ($request->firstName == '') {
                $flag = false;
            }
            if ($request->lastName == '') {
                $flag = false;
            }
            if ($request->message == '') {
                $flag = false;
            }
            //            }
            // dd($flag);
            if ($flag) {
                $id = base64_encode($request->user_id);
                $url = route('member.invite.url', [$id]);

                //                foreach ($request->email as $key => $email) {
                //                    SendInvitation::dispatch($email, $request->firstName[$key], $request->companyName[$key], $request->lastName[$key], $url);
                //                }
                SendInvitation::dispatch($request->address, $request->firstName, $request->companyName, $request->lastName, $request->message, $url);

                return redirect()->back()->with('success', 'Invitation Send Successfully');
            } else {
                return redirect()->back()->with('error', 'An error occurred while processing your request. Please check your information and try again.');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $ex) {
            return redirect()->back()->with('try-error', $ex->getMessage());
        }
    }

    /**
     * Get Registration page fr a invited users
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getLoginInvite($id)
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::check() && Auth::user()->isMember()) {
            return redirect()->route('member.dashboard');
        } else {
            $id = base64_decode($id);
            $states = State::all();
            return view('basicUser.user.invite_registration', [
                'parent_id' => $id,
                'states' => $states
            ]);
        }
    }

    /**
     * Action for Register form Submit for Invited Users
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRegistration(Request $request)
    {
        $this->validate($request, [
            // 'companyName' => 'required|min:3',
            // 'firstName' => 'required|min:3',
            // 'lastName' => 'required|min:3',
            // 'address' => 'required',
            // 'city' => 'required',
            // 'state' => 'required',
            // 'zip' => 'required|numeric',
            // 'phone' => 'required|numeric',
            'email' => 'required|email|unique:users,email',
            // 'userName' => 'required|unique:users,user_name',
            'password' => 'required|min:6',
            // 'confirmPassword' => 'required|same:password'
        ]);
        try {
            $newUser = new User();
            // $newUser->name = $request->firstName . ' ' . $request->lastName;
            $newUser->email = $request->email;
            // $newUser->user_name = $request->userName;
            $newUser->password = $request->password;
            $newUser->role = '6';
            $newUser->status = '0';
            $newUser->parent_id = $request->parent_id;
            $newUser->save();

            $company = new Company();
            $company->user_id = $newUser->id;
            // $company->company = $request->companyName;
            // $company->address = $request->address;
            // $company->city = $request->city;
            // $company->state_id = $request->state;
            // $company->zip = $request->zip;
            $company->save();


            $userDetails = new UserDetails();
            // $userDetails->company = $request->companyName;
            $userDetails->company_id = $company->id;
            // $userDetails->first_name = $request->firstName;
            // $userDetails->last_name = $request->lastName;
            // $userDetails->address = $request->address;
            // $userDetails->city = $request->city;
            // $userDetails->state_id = $request->state;
            $userDetails->user_id = $newUser->id;
            // $userDetails->zip = $request->zip;
            // $userDetails->phone = $request->phone;
            $userDetails->save();


            /* $userDetails = new UserDetails();
             $userDetails->company = $request->companyName;
             $userDetails->first_name = $request->firstName;
             $userDetails->last_name = $request->lastName;
             $userDetails->address = $request->address;
             $userDetails->city = $request->city;
             $userDetails->state_id = $request->state;
             $userDetails->user_id = $newUser->id;
             $userDetails->zip = $request->zip;
             $userDetails->phone = $request->phone;
             $userDetails->save();

             $company = new Company();
             $company->user_id = $newUser->id;
             $company->company = $request->companyName;
             $company->address = $request->address;
             $company->city = $request->city;
             $company->state_id = $request->state;
             $company->user_id = $newUser->id;
             $company->zip = $request->zip;
             $company->phone = $request->phone;
             $company->save();*/

            return redirect()->route('member.login')->with('success', 'Registration successful.Please login');
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }
}
