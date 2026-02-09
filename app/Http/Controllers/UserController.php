<?php

namespace App\Http\Controllers;

use App\Jobs\SendInvitationOnRegisterAPI;
use App\Models\LienProviderStates;
use App\Models\ProjectContract;
use App\Models\ProjectDates;
use App\Models\ProjectEmail;
use App\Models\ProjectTask;
use App\Models\Remedy;
use App\Models\RemedyDate;
use App\Models\RemedyStep;
use App\Models\TierRemedyStep;
use App\Models\TierTable;
use DB;
use File;
use Illuminate\Support\Facades\Session;
use Log;
use DateTime;
use App\User;
use Exception;
use Validator;
use App\Models\Role;
use App\Models\State;
use App\Models\Company;
use App\Models\ProjectRole;
use App\Models\ProjectType;
use App\Models\UserDetails;
use Illuminate\Support\Str;
use App\Models\CustomerCode;
use App\Models\PackagePrice;
use App\Models\LienProvider;
use Illuminate\Http\Request;
use App\Models\MemberLienMap;
use App\Models\PasswordReset;
use App\Models\ProjectDetail;
use App\Models\MapCompanyContact;
use App\Models\NotificationSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendInvitationOnRegister;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use GuzzleHttp\Client;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;

/**
 * Class UserController for user Management
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    
    public function index(Request $request)
    {
        return view('welcome');
    }
    public function about(Request $request)
    {
        return view('about_us');
    }
    public function contact(Request $request)
    {
        return view('contact_us');
    }
    /**
     * Get Member Login Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getLogin()
    {
        if (Auth::check() && Auth::user()->isMember()) {
            $plan = $this->getUserSubscription();
            if ($plan != '') {
                return redirect()->route('member.dashboard');
            } else {
                Auth::logout();
                $states = State::all();
                return view('basicUser.user.login', compact('states'));
                // return redirect()->back()->with('error', 'Payment Record not found please contact your admin');
            }
        } elseif (Auth::check() && Auth::user()->checkLienProvider()) {
            return redirect()->route('lien.dashboard');
        } else {
            $states = State::all();
            return view('basicUser.user.login', compact('states'));
        }
    }

    public function getBasicSignup()
    {
        if (Auth::check() && Auth::user()->isMember()) {
            return redirect()->route('member.dashboard');
        } elseif (Auth::check() && Auth::user()->checkLienProvider()) {
            return redirect()->route('lien.dashboard');
        } else {
            $states = State::all();
            return view('basicUser.user.signup_basic', compact('states'));
        }
    }

    public function getProSignup()
    {
        if (Auth::check() && Auth::user()->isMember()) {
            return redirect()->route('member.dashboard');
        } elseif (Auth::check() && Auth::user()->checkLienProvider()) {
            return redirect()->route('lien.dashboard');
        } else {
            $states = State::all();
            return view('basicUser.user.signup_pro', compact('states'));
        }
    }

    public function getUserSubscription()
    {
        $subscription = '';
        $memberSubscription = Auth::user()->subscriptions()->whereNull('ends_at')->first();

        if (Auth::user()->actual_plan == 'basic' && isset($memberSubscription)) {
            $subscription = 'basic';
        }

        if (Auth::user()->actual_plan == 'pro' && isset($memberSubscription)) {
            $subscription = 'pro';
        }
        return $subscription;
    }

    public function checkTrialAvailbility()
    {
        return true;
    }

    public function getUserState()
    {
        $user = Auth::user();
        $userDetails = $user->details()->firstOrFail();
        if (isset($userDetails)) {
            return $userDetails->state_id;
        } else {
            return 0;
        }
    }

    /**
     * Get Admin login Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function adminLogin()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } else {
            return view('user.login');
        }
    }

    /**
     * Process Login Request for admin
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postLoginAdmin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        $remember = false;

        if ($request->has('remember')) {
            $remember = true;
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            if (Auth::user()->status == 0) {
                if (Auth::user()->isAdmin()) {
                    return redirect()->route('admin.dashboard');
                } else {
                    Auth::logout();
                    return redirect()->back()->with('error', 'Please check your Email or Password');
                }
            } else {
                Auth::logout();
                return redirect()->back()->with('error', 'Your account is inactive. Contact your administrator to activate it.')->withInput();
            }
        } elseif (Auth::attempt(['user_name' => $request->email, 'password' => $request->password], $remember)) {
            if (Auth::user()->status == 0) {
                if (Auth::user()->isAdmin()) {
                    return redirect()->route('admin.dashboard');
                } else {
                    Auth::logout();
                    return redirect()->back()->with('error', 'Please check your Email or Password');
                }
            } else {
                Auth::logout();
                return redirect()->back()->with('error', 'Your account is inactive. Contact your administrator to activate it.')->withInput();
            }
        } else {
            return redirect()->back()->with('error', 'Wrong Email or Password')->withInput();
        }
    }

    /**
     * Submit Sub User add
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitSubuser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'company' => 'required',
                'email' => 'required|email',
                'phone' => 'numeric',
                'fax' => 'numeric'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid data given'
                ], 200);
            }
            if ($request->type == 'edit') {
                $user = User::findOrFail($request->id);
            } else {
                $user = new User();
            }

            $user->name = $request->firstName . ' ' . $request->lastName;
            $user->user_name = $request->username;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->role = 6;
            $user->status = '0';
            $user->parent_id = Auth::user()->id;

            if ($request->type == 'edit') {
                $user->update();
                $userDetails = UserDetails::where('user_id', $user->id)->first();
                $message = "Subuser updated successfully";
            } else {
                $user->save();
                $userDetails = new UserDetails();
                $userDetails->user_id = $user->id;
                $message = "Subuser saved successfully";
            }
            $userDetails->company = $request->company;
            $userDetails->first_name = $request->firstName;
            $userDetails->last_name = $request->lastName;
            $userDetails->address = $request->address;
            $userDetails->city = $request->city;
            $userDetails->state_id = $request->state;
            $userDetails->zip = $request->zip;
            $userDetails->phone = $request->phone;
            $userDetails->save();
            return response()->json([
                'status' => true,
                'message' => $message
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
     * Submit sub user details.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createSubUser(Request $request)
    {
        try {
            if ($request->type == 'edit') {
                $validator = Validator::make($request->all(), [
                    'company' => 'required',
                    'email' => 'required|email|unique:users,email,' . $request->subuser_id,
                    'username' => 'required|unique:users,user_name,' . $request->subuser_id,
                    'phone' => 'numeric',
                    'fax' => 'numeric'
                ], [
                    'username.unique' => 'username is already taken',
                    'email.unique' => 'email is already taken'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => $validator->messages()->toArray()
                    ], 200);
                }

                $user = User::findOrFail($request->subuser_id);
            } else {
                $validator = Validator::make($request->all(), [
                    'company' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'username' => 'required|unique:users,user_name',
                    'phone' => 'numeric',
                    'fax' => 'numeric'
                ], [
                    'username.unique' => 'username is already taken',
                    'email.unique' => 'email is already taken'
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => $validator->messages()->toArray()
                    ], 200);
                }
                $user = new User();
            }

            $user->name = $request->firstName . ' ' . $request->lastName;
            $user->user_name = $request->username;
            $user->email = $request->email;
            if(isset($request->password) && !empty($request->password)) {
                $user->password = $request->password;
            }
            $user->role = 6;
            $user->status = '0';
            $user->parent_id = Auth::user()->id;

            if ($request->type == 'edit') {
                $user->update();
                $userDetails = UserDetails::where('user_id', $user->id)->first();
                $mapSubUserDetails = MapCompanyContact::where('company_id', $request->company_id)->where('user_id', $user->id)->where('is_user', '1')->first();
                if (is_null($mapSubUserDetails)) {
                    $mapSubUserDetails = new MapCompanyContact();
                } else {
                    $mapSubUserDetails->delete();
                    $mapSubUserDetails = new MapCompanyContact();
                }
                $mapSubUserDetails->user_id = $user->id;
                $message = "Subuser updated successfully";
            } else {
                $user->save();
                $userDetails = new UserDetails();
                $mapSubUserDetails = new MapCompanyContact();
                $userDetails->user_id = $user->id;
                $mapSubUserDetails->user_id = $user->id;
                $message = "Subuser saved successfully";
            }
            $userDetails->company = $request->company;
            $userDetails->first_name = $request->firstName;
            $userDetails->last_name = $request->lastName;
            $userDetails->address = $request->address;
            $userDetails->city = $request->city;
            $userDetails->state_id = $request->state;
            $userDetails->zip = $request->zip;
            $userDetails->company_id = $request->company_id;
            $userDetails->phone = $request->phone;
            $userDetails->save();

            $mapSubUserDetails->company_id = $request->company_id;
            $mapSubUserDetails->is_user = '1';
            $mapSubUserDetails->address = $request->address;
            $mapSubUserDetails->city = $request->city;
            $mapSubUserDetails->state_id = $request->state;
            $mapSubUserDetails->zip = $request->zip;
            $mapSubUserDetails->phone = $request->phone;
            $mapSubUserDetails->save();

            return response()->json([
                'status' => true,
                'message' => $message
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
     * Delete SubUser
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSubuser(Request $request)
    {
        try {
            $this->validate($request, [
                'id' => 'required'
            ]);

            $subuser = User::findOrFail($request->id);
            $subuser->delete();
            $subuserDetails = UserDetails::where('user_id', $request->id);
            $subuserDetails->delete();
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
     * Member Login Method
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postLoginMember(Request $request)
    {
        $remember = false;
        if ($request->has('remember')) {
            $remember = true;
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            if (Auth::user()->status == 0) {
                if (Auth::user()->checkMember()) {
                    $plan = $this->getUserSubscription();
                    // if ($plan != '') {
                    //     if ($this->checkTrialAvailbility()) {
                    //         return redirect()->route('member.dashboard');
                    //     } else {
                    //         Auth::logout();
                    //         return redirect()->back()->with('error', 'Trial Period expired');
                    //     }
                    // } else {
                    //     Auth::logout();
                    //     return redirect()->back()->with('error', 'Payment Record not found please contact your admin');
                    // }
                    return redirect()->route('member.dashboard');
                } elseif (Auth::user()->checkLienProvider()) {
                    return redirect()->route('lien.dashboard');
                } else {
                    Auth::logout();
                    return redirect()->back()->with('error', 'You are not allowed to login in Members area');
                }
            } else {
                Auth::logout();
                return redirect()->back()->with('error', 'Your account is inactive. Contact your administrator to activate it.')->withInput();
            }
        } elseif (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            if (Auth::user()->status == 0) {
                if (Auth::user()->checkMember()) {
                    $plan = $this->getUserSubscription();
                    // if ($plan != '') {
                    //     if ($this->checkTrialAvailbility()) {
                    //         return redirect()->route('member.dashboard');
                    //     } else {
                    //         Auth::logout();
                    //         return redirect()->back()->with('error', 'Trial Period expired');
                    //     }
                    // } else {
                    //     Auth::logout();
                    //     return redirect()->back()->with('error', 'Payment Record not found please contact your admin');
                    // }
                    return redirect()->route('member.dashboard');
                } elseif (Auth::user()->checkLienProvider()) {
                    return redirect()->route('lien.dashboard');
                } else {
                    Auth::logout();
                    return redirect()->back()->with('error', 'You are not allowed to login in Members area');
                }
            } else {
                Auth::logout();
                return redirect()->back()->with('error', 'Your account is inactive. Contact your administrator to activate it.')->withInput();
            }
        } else {
            return redirect()->back()->with('error', 'Wrong Email or Password')->withInput();
        }
    }

    /**
     * New Member  Method
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postNewMember(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        try {
            $user = User::where('email', $request->email)->count();
            $email = $request->email;
            $pass = $request->password;
            if ($user) {
                return redirect()->back()->with('error1', 'email allready exists');
            }
            $token = Str::random(32) . time();
            $url = route('get.register', [$email, $pass, $token]);
            //dd($url);
            Mail::send('basicUser.user.signup_request', ['email' => $email, 'link' => $url], function ($message) use ($email) {
                $message->from("signup@nlb-access.dev", "Signup");
                $message->to($email)->subject('Signup Request!');
            });
            if (Mail::failures()) {
                return redirect()->back()->with('error1', 'Something wrong. Please try again after some time');
            }
            return redirect()->back()->with('success', 'Signup request successfull .Check your mail for further details');
            //return 'xggf';
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Get Forget Password Password
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getForgetPassword()
    {
        if (Auth::check()) {
            return redirect()->route('app.index');
        } else {
            return view('basicUser.user.forgot_password');
        }
    }

    /**
     * Send forget password email
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postForgetPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email'
        ]);

        try {
            $user = User::where('email', $request->email)->firstOrFail();
            $token = Str::random(32) . time();
            $url = route('password.reset', [$token]);
            $reset = new PasswordReset();
            $reset->email = $user->email;
            $reset->token = $token;
            $reset->created_at = date('Y-m-d H:m:s');
            $reset->save();

            Mail::send('basicUser.user.password_reset', ['name' => $user->name, 'link' => $url], function ($message) use ($user) {
                $message->from("admin@slynerds.com", "Admin");
                $message->to($user->email, $user->name)->subject('Password Reset!');
            });
            if (Mail::failures()) {
                return redirect()->back()->with('error', 'Something wrong. Please try again after some time');
            }
            return redirect()->route('member.login')->with('success', 'Password reset link send to your email.Please check your email.');
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Get Password reset form
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getPasswordReset($token)
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::check() && Auth::user()->isMember()) {
            return redirect()->route('member.dashboard');
        } elseif (Auth::check() && Auth::user()->checkLienProvider()) {
            return redirect()->route('lien.dashboard');
        } else {
            return view('basicUser.user.reset', ['token' => $token]);
        }
    }

    /**
     * Action of Password reset request
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postPasswordReset(Request $request)
    {
        $this->validate($request, [
            'password_token' => 'required',
            'password' => 'required|min:6',
            'cPassword' => 'required|same:password'
        ]);
        try {
            $reset = PasswordReset::where('token', $request->password_token)->firstOrFail();
            if ($reset->created_at) {
                $user = User::where('email', $reset->email)->firstOrFail();
                $user->password = $request->password;
                $user->update();
            } else {
                return redirect()->back()->with('error', 'Token timeout! Please request for another token');
            }
            return redirect()->route('member.login')->with('success', 'Password reset successfully .');
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Get Member registration form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getRegistration($email, $pass)
    {
        if (Auth::check() && Auth::user()->isMember()) {
            return redirect()->route('member.dashboard');
        } elseif (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::check() && Auth::user()->checkLienProvider()) {
            return redirect()->route('lien.dashboard');
        } else {
            $states = State::all();
            return view('basicUser.user.register', [
                'states' => $states,
                'email' => $email,
                'pass' => $pass
            ]);
        }
    }

    /**
     * Registration Page Submit Action
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postRegistration(Request $request)
    {
        //DB::beginTransaction();
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            // 'companyName' => 'required|min:3',
            // 'firstName' => 'required|min:3',
            // 'lastName' => 'required|min:3',
            // 'phone' => 'required|min:10',
            'email' => 'required|email|unique:users,email',
            // 'userName' => 'required|min:3|unique:users,user_name',
            'password' => 'required|min:6',
            // 'confirmPassword' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }
        try {
            $newUser = new User();
            // $newUser->name = $request->firstName . ' ' . $request->lastName;
            $newUser->email = $request->email;
            // $newUser->user_name = $request->userName;
            $newUser->password = $request->password;
            $newUser->role = '5';
            $newUser->status = '0';
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

            SendInvitationOnRegister::dispatch($newUser);

            // DB::commit();
            Auth::attempt(['email' => $request->email, 'password' => $request->password], true);

            return redirect()->route('member.dashboard');

            // return redirect()->route('member.login')->with('success', 'Registration successful.Wait for the activation of your account ');
            if ($request->plan_type == 'basic') {
                return redirect()->route('plans.show', $request->plan_type)->with('success', 'Registration successful. Please pay your payment');

                // return redirect()->route('plans.show')->with('success', 'Registration successful. Please pay your payment');
            } else {
                return redirect()->route('plans.show', $request->plan_type)->with('success', 'Registration successful. Please pay your payment');
            }
            // return redirect()->route('member.login')->with('success', 'Registration successful.Wait for the activation of your account ');
        } catch (Exception $e) {
            // DB::rollback();
            Log::info($e);
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            // DB::rollback();
            Log::info($exception);
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    function generateRandomPassword($length = 8) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';
        $charactersLength = strlen($characters);
        $randomPassword = '';

        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomPassword;
    }

    public function postRegistrationAPI(Request $request)
    {
        Log::info('postRegistrationAPI');
        if ($request->header('Authorization') != config('services.EXTERNAL_API_KEY')) {
            Log::info('postRegistrationAPI: Unauthorized');
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            Log::info('postRegistrationAPI: Email already exists');
            return response()->json([
                'status' => false,
                'message' => 'Email already exists'
            ], 422);
        }

        if(!isset($request->state) || empty($request->state)) {
            Log::info('postRegistrationAPI: The field Location By State cannot be empty!');
            return response()->json([
                'status' => false,
                'message' => 'The field Location By State cannot be empty!'
            ], 200);
        }

        $state = State::find($request->state);
        if (!$state) {
            Log::info('postRegistrationAPI: Invalid state');
            return response()->json([
                'status' => false,
                'message' => 'Invalid state'
            ], 200);
        }

        if(!isset($request->type) || empty($request->type)) {
            Log::info('postRegistrationAPI: The field Project Type cannot be empty!');
            return response()->json([
                'status' => false,
                'message' => 'The field Project Type cannot be empty!'
            ], 200);
        }

        $projectType = ProjectType::find($request->type);
        if (!$projectType) {
            Log::info('postRegistrationAPI: Invalid project type');
            return response()->json([
                'status' => false,
                'message' => 'Invalid project type'
            ], 200);
        }

        if(!isset($request->role) || empty($request->role)) {
            Log::info('postRegistrationAPI: The field Role cannot be empty!');
            return response()->json([
                'status' => false,
                'message' => 'The field Role cannot be empty!'
            ], 200);
        }

        $role = ProjectRole::find($request->role);
        if (!$role) {
            Log::info('postRegistrationAPI: Invalid role');
            return response()->json([
                'status' => false,
                'message' => 'Invalid role'
            ], 200);
        }

        if(!isset($request->customer) || empty($request->customer)) {
            Log::info('postRegistrationAPI: The field Customer cannot be empty!');
            return response()->json([
                'status' => false,
                'message' => 'The field Customer cannot be empty!'
            ], 200);
        }

        $customer = CustomerCode::find($request->customer);
        if (!$customer) {
            Log::info('postRegistrationAPI: Invalid customer');
            return response()->json([
                'status' => false,
                'message' => 'Invalid customer'
            ], 200);
        }

        if (isset($request->password) && !empty($request->password)) {
            if (strlen($request->password) < 6) {
                Log::info('postRegistrationAPI: The password must be at least 6 characters.');
                return response()->json([
                    'status' => false,
                    'message' => 'The password must be at least 6 characters.'
                ], 200);
            }
            if (strlen($request->password) > 20) {
                Log::info('postRegistrationAPI: The password may not be greater than 20 characters.');
                return response()->json([
                    'status' => false,
                    'message' => 'The password may not be greater than 20 characters.'
                ], 200);
            }
            if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $request->password)) {
                Log::info('postRegistrationAPI: The password must contain at least one letter, one number, and one special character.');
                return response()->json([
                    'status' => false,
                    'message' => 'The password must contain at least one letter, one number, and one special character.'
                ], 200);
            }
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'state' => 'required',
            'type' => 'required',
            'role' => 'required',
            'customer' => 'required'
        ]);

        if ($validator->fails()) {
            Log::info('postRegistrationAPI: before '.$validator->errors());
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        Log::info('postRegistrationAPI: before DB::beginTransaction');
        try {
            DB::beginTransaction();
            $newUser = new User();
            $newUser->email = $request->email;
            if (isset($request->password) && !empty($request->password)) {
                $newUser->password = $request->password;
            } else {
                $newUser->password = $this->generateRandomPassword();
            }
            $newUser->role = '5';
            $newUser->status = '0';
            $newUser->save();

            $company = new Company();
            $company->user_id = $newUser->id;
            $company->save();

            $userDetails = new UserDetails();
            $userDetails->company_id = $company->id;
            $userDetails->user_id = $newUser->id;
            $userDetails->state_id = $request->state;
            $userDetails->save();

            SendInvitationOnRegister::dispatch($newUser);
            DB::commit();

            DB::beginTransaction();
            $projectDetail = new ProjectDetail();
            $projectDetail->user_id = $newUser->id;
            $projectDetail->project_name = $request->email.' Project';
            $projectDetail->state_id = $request->state;
            $projectDetail->project_type_id = $request->type;
            $projectDetail->role_id = $request->role;
            $projectDetail->customer_id = $request->customer;
            $projectDetail->api = '1';
            $projectDetail->save();

            $projectContract = new ProjectContract();
            $projectContract->project_id = $projectDetail->id;
            $projectContract->base_amount = 0;
            $projectContract->extra_amount = 0;
            $projectContract->credits = 0;
            $projectContract->total_claim_amount = (($projectContract->base_amount + $projectContract->extra_amount) - $projectContract->credits);
            $projectContract->save();
            SendInvitationOnRegisterAPI::dispatch($newUser);
            DB::commit();

            Log::info('postRegistrationAPI: before GuzzleHttp');
            $client = new GuzzleClient(['headers' =>
                [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                ]
            ]);
            $data = [
                'inbox_lead_token' =>'e67b2e9a2bc612f65f53ad702e462ee6',
                'inbox_lead' => [
                    'from_email' => $request->email? $request->email : '',
                    'from_first' => $request->first_name? $request->first_name : '',
                    'from_last' => $request->last_name? $request->last_name : '',
                    'from_message' => $request->message? $request->message : '',
                    'from_phone' => $request->phone? $request->phone : '',
                    'referring_url' => 'https://lienmanager.trueproductions.com',
                    'from_source' => 'Website',
                ]
            ];
            $response = $client->post('https://grow.clio.com/inbox_leads', [
                'body' => json_encode($data)
            ]);

            $statusCode = $response->getStatusCode();
            if ($statusCode == 201) {
                Log::info('postRegistrationAPI: 201');
                $results = $response->getBody();
                $results = json_decode($results);

                if (isset($results->inbox_lead) && isset($results->inbox_lead->id)) {
                    Log::info('postRegistrationAPI: 201 - inbox_lead_id: '.$results->inbox_lead->id);
                    $newUser->inbox_lead_id = $results->inbox_lead->id;
                    $newUser->save();
                }
            } else {
                Log::info('postRegistrationAPI: '.$statusCode);
            }

            return response()->json([
                'status' => true,
                'message' => 'Registration and Project created successfully',
            ], 200);
        } catch (Exception $e) {
            DB::rollback();
            Log::info('postRegistrationAPI: '.$e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 409);
        } catch (ModelNotFoundException $exception) {
            DB::rollback();
            Log::info('postRegistrationAPI: '.$exception->getMessage());
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ], 406);
        }
    }

    /**
     * Member or admin logout method
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Session::forget('parents');
        $type = '';
        if (Auth::check() && Auth::user()->isMember()) {
            $type = 'member';
        } elseif (Auth::check() && Auth::user()->checkLienProvider()) {
            $type = 'lien';
        } else {
            $type = 'admin';
        }
        Auth::logout();
        if ($type == 'admin') {
            return redirect()->route('admin.login');
        } else {
            return redirect()->route('member.login');
        }
    }

    /**
     * Return Dashboard for Member
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function columnAdd(Request $request)
    {
        try {
            //$request->columns;
            $userid = $request->userid;
            User::where('id', $userid)->update(['custom' => $request->columns]);
            return response()->json([
                'status' => true,
                'success' => 'Project updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'success' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'success' => $exception->getMessage()
            ], 200);
        }
    }

    public function member(Request $request)
    {

        DB::enableQueryLog();
        // phpinfo();
        $filter = false;
        $state1 = $request->get('state');
        $state = explode(',', $state1);
        $ptype1 = $request->get('ptype');
        $ptype = explode(',', $ptype1);
        $prole1 = $request->get('prole');
        $prole = explode(',', $prole1);
        $customer1 = $request->get('customer');
        $customer = explode(',', $customer1);
        $userFilter = $request->get('user');
        $employees = array();

        $employees = DB::table('project_details')->select('id')

            ->where(function ($query) use ($state) {
                if (!empty($state)) {
                    $query->orWhereIn('state_id', $state);
                }
            })
            ->where(function ($query) use ($ptype) {
                if (!empty($ptype)) {
                    $query->orWhereIn('project_type_id', $ptype);
                }
            })
            ->where(function ($query) use ($prole) {
                if (!empty($prole)) {
                    $query->orWhereIn('role_id', $prole);
                }
            })
            ->where(function ($query) use ($customer) {
                if (!empty($customer)) {
                    $query->orWhereIn('customer_id', $customer);
                }
                return $query;
            })
            ->get();

        $myArray = json_decode(json_encode($employees), true);
        $array = array();
        for ($i = 0; $i < count($myArray); $i++) {
            $array[] = $myArray[$i]['id'];
        }

        $case = $request->get('case');
        $projectDetails = $request->get('projectDetails');
        $sortBy = $request->get('sortBy');
        $sortWith = $request->get('sortWith');
        $paginate = $request->get('paginate');

        $user = Auth::user();
        $user_id = $user->id;
        $userscustom = DB::table('users')->where('id', $user_id)->select('custom')->first();

        $states_list = State::all();
        $states = State::pluck('name', 'id')->toArray();

        $types = ProjectType::all();
        $roles = ProjectRole::all();
        $customerCodes = CustomerCode::get();
//        $allUsers = User::where('parent_id', Auth::user()->id)->pluck('id')->toArray();
        $arrayAllUsers = User::getAllChild(Auth::user()->id);
        foreach ($arrayAllUsers as $key => $value) {
            $allUsers[] = $value->id;
        }
        $allUsers[] = Auth::user()->id;

        foreach ($arrayAllUsers as $key => $value) {
            $subUsers[$value->id] = $value->name;
        }
        $subUsers[$user->id] = $user->name;

        if ($case == 'active') {
            //$projects = ProjectDetail::where('user_id', Auth::user()->id)->where('status', '1');
            //$projects = Auth::user()->projects()->where('status', '1')->join('project_contracts', 'project_contracts.project_id', '=', 'project_details.id')->select(DB::raw('project_details.*, project_contracts.total_claim_amount AS unpaid_balance'));
            $projects = ProjectDetail::whereIn('user_id', $allUsers)->where('status', '1')->join('project_contracts', 'project_contracts.project_id', '=', 'project_details.id')->select(DB::raw('project_details.*, project_contracts.total_claim_amount AS unpaid_balance'));
        } else {
            if ($filter) {
                //$projects = Auth::user()->projects()->join('project_contracts', 'project_contracts.project_id', '=', 'project_details.id')->select(DB::raw('project_details.*,  project_contracts.total_claim_amount AS unpaid_balance'))->WhereIn('project_details.id', $array);
                $projects = ProjectDetail::whereIn('user_id', $allUsers)->join('project_contracts', 'project_contracts.project_id', '=', 'project_details.id')->select(DB::raw('project_details.*,  project_contracts.total_claim_amount AS unpaid_balance'))->WhereIn('project_details.id', $array);
            } else {
                //$projects = Auth::user()->projects()->join('project_contracts', 'project_contracts.project_id', '=', 'project_details.id')->select(DB::raw('project_details.*,  project_contracts.total_claim_amount AS unpaid_balance'));
                $projects = ProjectDetail::whereIn('user_id', $allUsers)->join('project_contracts', 'project_contracts.project_id', '=', 'project_details.id')->select(DB::raw('project_details.*,  project_contracts.total_claim_amount AS unpaid_balance'));
            }
            //$projects = ProjectDetail::where('user_id', Auth::user()->id);
        }
        $test = Auth::user()->projects()->join('project_contracts', 'project_contracts.project_id', '=', 'project_details.id')->select(DB::raw('project_details.id,  project_contracts.total_claim_amount AS unpaid_balance'))->get();
        if (isset($projectDetails)) {
            if ($filter) {
                $projects->where(function ($query1) use ($projectDetails) {
                    $query1->Where('project_name', 'LIKE', '%' . $projectDetails . '%')
                        ->orWhere('project_details.created_at', 'LIKE', '%' . date("Y-m-d H:i:s", strtotime($projectDetails)) . '%')
                        ->orWhereHas('state', function ($query) use ($projectDetails) {
                            $query->where('name', 'LIKE', '%' . $projectDetails . '%');
                        })
                        ->orWhereHas('project_type', function ($query) use ($projectDetails) {
                            $query->where('project_type', 'LIKE', '%' . $projectDetails . '%');
                        })
                        ->orWhereHas('customer_contract.getContacts', function ($query) use ($projectDetails) {
                            $query->where('type', '0')->whereHas('mapContactCompany.company', function ($query) use ($projectDetails) {
                                $query->where('company', 'LIKE', '%' . $projectDetails . '%');
                            });
                        })->orWhereHas('customer_contract.getContacts', function ($query) use ($projectDetails) {
                            $query->where('first_name', 'LIKE', '%' . $projectDetails . '%')
                                ->orWhere('last_name', 'LIKE', '%' . $projectDetails . '%')
                                ->orWhere(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE', '%' . $projectDetails . '%');
                        });
                });

                $projects->where(function ($query2) use ($array) {
                    $query2->WhereIn('project_details.id', $array);
                });
            } else {
                $projects->where(function ($query1) use ($projectDetails) {
                    $query1->Where('project_name', 'LIKE', '%' . $projectDetails . '%')
                        ->orWhere('project_details.created_at', 'LIKE', '%' . date("Y-m-d H:i:s", strtotime($projectDetails)) . '%')
                        ->orWhereHas('state', function ($query) use ($projectDetails) {
                            $query->where('name', 'LIKE', '%' . $projectDetails . '%');
                        })
                        ->orWhereHas('project_type', function ($query) use ($projectDetails) {
                            $query->where('project_type', 'LIKE', '%' . $projectDetails . '%');
                        })
                        ->orWhereHas('customer_contract.getContacts', function ($query) use ($projectDetails) {
                            $query->where('type', '0')->whereHas('mapContactCompany.company', function ($query) use ($projectDetails) {
                                $query->where('company', 'LIKE', '%' . $projectDetails . '%');
                            });
                        })->orWhereHas('customer_contract.getContacts', function ($query) use ($projectDetails) {
                            $query->where('first_name', 'LIKE', '%' . $projectDetails . '%')
                                ->orWhere('last_name', 'LIKE', '%' . $projectDetails . '%')
                                ->orWhere(DB::raw("CONCAT(`first_name`, ' ', `last_name`)"), 'LIKE', '%' . $projectDetails . '%');
                        });
                });
            }
        }


        if (isset($sortBy) && isset($sortWith)) {
            switch ($sortWith) {
                case 'customer_company_name':
                    $projects->leftJoin('map_company_contacts', function ($join) {
                        $join->on('project_details.customer_contract_id', '=', 'map_company_contacts.id');
                    })->leftJoin('companies', function ($join) {
                        $join->on('map_company_contacts.company_id', '=', 'companies.id');
                    })->select(DB::raw('project_details.*,  project_contracts.total_claim_amount AS unpaid_balance, companies.company'))->orderBy('companies.company', $sortBy);

                    break;

                case 'customer_name':
                    $projects->leftJoin('map_company_contacts', function ($join) {
                        $join->on('project_details.customer_contract_id', '=', 'map_company_contacts.id');
                    })->leftJoin('company_contacts', function ($join) {
                        $join->on('map_company_contacts.company_contact_id', '=', 'company_contacts.id');
                    })->select(DB::raw('project_details.*,  project_contracts.total_claim_amount AS unpaid_balance, company_contacts.first_name'))->orderBy('company_contacts.first_name', $sortBy);

                    break;

                case 'customer_phone_number':
                    $projects->leftJoin('map_company_contacts', function ($join) {
                        $join->on('project_details.customer_contract_id', '=', 'map_company_contacts.id');
                    })->leftJoin('company_contacts', function ($join) {
                        $join->on('map_company_contacts.company_contact_id', '=', 'company_contacts.id');
                    })->select(DB::raw('project_details.*,  project_contracts.total_claim_amount AS unpaid_balance, company_contacts.phone'))->orderBy('company_contacts.phone', $sortBy);

                    break;


                default:
                    $projects->orderBy($sortWith, $sortBy);
                    break;
            }
        } else {
            $projects->orderBy('project_details.updated_at', 'DESC');
        }

        $filteredProjects = $this->searchProjectsThroughAdvancedFilter($request, $projects);
        $projects = !is_null($filteredProjects) ? $filteredProjects  : $projects;
        $queryParams = $this->getQueryParams($request);
        $projectNames = !is_null(Auth::user()->projects()) ? Auth::user()->projects()->pluck('project_name', 'id') : [];
        $customers = !is_null(Auth::user()->checkCustomer()) ? $this->createCustomerList(Auth::user()->checkCustomer()->get()) : [];


        if (isset($paginate)) {
            if ($paginate <= 100) {
                $projectssearch = $projects->paginate($paginate);
            } else {
                $projectssearch = $projects->paginate($projects->count());
            }
        } else {
            $projectssearch = $projects->paginate(10);
        }
//         dd($projectssearch);
//        dd(DB::getQueryLog());

        $lienProviders = !is_null(Auth::user()->lienProvider()) ? $this->createLienList(Auth::user()->lienProvider) : [];


        foreach($projectssearch as $key => $project) {
            $projectssearch[$key]['preliminaryDates'] = '';
            $projectssearch[$key]['preliminaryDatesName'] = '';
            $projectssearch[$key]['lienProviderByState'] = [];
            $lienProviderState = [];

            if(isset($userFilter)) {
                $findUserFilter = User::find($userFilter);
                if($findUserFilter) {
                    if($findUserFilter->id != $project->user_id) {
                        unset($projectssearch[$key]);
                        continue;
                    }
                }
            }

            foreach (Auth::user()->lienProvider as $lienProvider) {
                if(isset($lienProvider->findLien->stateId) && $lienProvider->findLien->stateId == $project->state_id) {
                    $lienProviderState[] = $lienProvider->findLien;
                }
            }
            if(isset($lienProviderState)) {
                $projectssearch[$key]['lienProviderByState'] = $lienProviderState;
            }

            $currentUser = User::where('id', $projectssearch[$key]['user_id'])->first();
            $projectssearch[$key]['username'] = $currentUser->name;

            $daysRemain = [];
            $flag = 0;
            $remedy = Remedy::where('state_id', $project->state_id)
                ->where('project_type_id', $project->project_type_id);
            $tiers = TierTable::where('role_id', $project->role_id)
                ->where('customer_id', $project->customer_id)->firstOrFail();
            $remedySteps = RemedyStep::whereIn('remedy_id', $remedy->pluck('id'));
            $tierRemedySteps = TierRemedyStep::where('tier_id', $tiers->id)
                ->whereIn('remedy_step_id', $remedySteps->pluck('id'))
                ->whereIn('answer1', [$project->answer1, ''])
                ->whereIn('answer2', [$project->answer2, '']);
            $remedyStepsNew = $remedySteps->whereIn('id', $tierRemedySteps->pluck('remedy_step_id'));
            $remedyDate = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy->pluck('id'))->whereIn('id', $remedyStepsNew->pluck('remedy_date_id'))->orderBy('date_order', 'ASC')->get();
            $role_id = ProjectDetail::where('id', $project->id);
            $answer = $role_id->first()->answer1;
            if ($answer == 'Yes' || $answer == 'Commercial') {
                $flag = 1;
            } elseif ($answer == 'No' || $answer == 'Residential') {
                $flag = 2;
            }
            if ($flag == 0) {
                $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                    ->whereIn('remedy_id', $remedy->pluck('id'));
                $deadline = $deadline1->whereIn('id', $tierRem->pluck('remedy_step_id'))->get();
            } elseif ($flag == 1) {
                $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                if ($answer == 'Yes') {
                    $tierRem1 = $tierRem->where(function ($query) {
                        $query->where('answer1', 'Yes')
                            ->orWhere('answer1', '');
                    });
                } else {
                    $tierRem1 = $tierRem->where(function ($query) {
                        $query->where('answer1', 'Commercial')
                            ->orWhere('answer1', '');
                    });
                }
                $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                    ->whereIn('remedy_id', $remedy->pluck('id'));
                $deadline = $deadline1->whereIn('id', $tierRem1->pluck('remedy_step_id'))->get();
            } elseif ($flag == 2) {
                $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                if ($answer == 'No') {
                    $tierRem1 = $tierRem->where(function ($query) {
                        $query->where('answer1', 'No')
                            ->orWhere('answer1', '');
                    });
                } else {
                    $tierRem1 = $tierRem->where(function ($query) {
                        $query->where('answer1', 'Residential')
                            ->orWhere('answer1', '');
                    });
                }
                $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                    ->whereIn('remedy_id', $remedy->pluck('id'));
                $deadline = $deadline1->whereIn('id', $tierRem1->pluck('remedy_step_id'))->get();
            }

            foreach ($deadline as $dkey => $value) {
                $years = $value->years;
                $months = $value->months;
                $days = $value->days;
                $remedyDateId = $value->remedy_date_id;
                $daysRemain[$dkey]['dates'] = ($years * 365) + ($months * 30) + ($days * 1);
                $preliminaryDeadline = ProjectDates::select('date_value')
                    ->where('project_id', $project->id)
                    ->where('date_id', $remedyDateId)->first();
                if ($preliminaryDeadline != null && $preliminaryDeadline->date_value != '') {
                    $dateNew = date_create($preliminaryDeadline->date_value);
                    date_add($dateNew, date_interval_create_from_date_string($years . " years " . $months . " months " . $days . " days"));
                    if ($value->day_of_month != 0) {
                        $prelim = date_format($dateNew, "m/{$value->day_of_month}/Y");
                    } else {
                        $prelim = date_format($dateNew, "m/d/Y");
                    }
                    $daysRemain[$dkey]['deadline'] = $preliminaryDeadline->date_value;
                    $daysRemain[$dkey]['preliminaryDates'] = date($prelim);
                    $daysRemain[$dkey]['name'] = $value->short_description;
                } else {
                    $daysRemain[$dkey]['deadline'] = '';
                    $daysRemain[$dkey]['preliminaryDates'] = '';
                    $daysRemain[$dkey]['name'] = '';
                }
                $remedyNames[$value->getRemedy->id] = $value->getRemedy->remedy;
            }

            if(isset($projectssearch[$key]) && isset($dkey) && isset($daysRemain[$dkey]) && count($daysRemain[$dkey]) > 0 && is_array($daysRemain[$dkey]) ) {
                $min = min(array_map(function($a) { return $a; }, $daysRemain));
                if(isset($min['preliminaryDates']) && isset($min['name'])) {
                    $projectssearch[$key]['preliminaryDates'] = $min['preliminaryDates'];
                    $projectssearch[$key]['preliminaryDatesName'] = $min['name'];
                }
                //echo $projectssearch[$key]['id'].' - '.$projectssearch[$key]['preliminaryDates'].'  - '.$projectssearch[$key]['preliminaryDatesName'].' <br/>';
            }

            $now = new DateTime();
            $task = ProjectTask::where('project_id', $project->id)->whereNull('complete_date')->where('due_date', '>=', $now->format('Y-m-d'))->orderBy('due_date')->first();
            if ( $task ) {
                $projectssearch[$key]['nextTaskAction'] = $task->task_name;
                $nextTaskDate = new DateTime($task->due_date);
                $projectssearch[$key]['nextTaskDate'] = $nextTaskDate->format('m/d/Y');
            }
        }
        $projectTypes = ProjectType::pluck('project_type', 'id')->toArray();
//         echo "<pre>"; print_r($projectssearch);
        if (isset($queryParams)) {
            return view('basicUser.dashboard.dashboard', [
                'projects' => $projectssearch,
                'lienProviders' => $lienProviders,
                'projectNames' => $projectNames,
                'customers' => $customers,
                'caseProject' => $case,
                'filterd' => $employees,
                'queryParams' => $queryParams,
                'unpaid' => $test,
                'projectTypes' => $projectTypes,
                'userscustom' => $userscustom,
                'states_list' => $states_list,
                'states' => $states,
                'types' => $types,
                'roles' => $roles,
                'customerCodes' => $customerCodes,
                'subUsers' => $subUsers
            ]);
        } else {
            return view('basicUser.dashboard.dashboard', [
                'projects' => $projectssearch,
                'lienProviders' => $lienProviders,
                'projectNames' => $projectNames,
                'customers' => $customers,
                'caseProject' => $case,
                'filterd' => $employees,
                'projectTypes' => $projectTypes,
                'unpaid' => $test,
                'userscustom' => $userscustom,
                'states_list' => $states_list,
                'states' => $states,
                'types' => $types,
                'roles' => $roles,
                'customerCodes' => $customerCodes,
                'subUsers' => $subUsers
            ]);
        }
    }
    protected function createLienList($lienProviders)
    {
        $lienProvidersArray = [];
        foreach ($lienProviders as $lienProvider) {
            $lienProvidersArray[$lienProvider->findLien->id.'@'.$lienProvider->findLien->stateId]   =  (!is_null($lienProvider->findLien) ? $lienProvider->findLien->company : 'N/A') . ' ( ' . $lienProvider->findLien->firstName . ' ' . $lienProvider->findLien->lastName . ' )';
        }
        return $lienProvidersArray;
    }

    protected function createCustomerList($customers)
    {
        $customerArray = [];
        $customers = MapCompanyContact::where('user_id', Auth::user()->id)
            ->whereHas('getContacts', function ($query) {
                $query->where('type', '0');
            })->with('company')->get();

        foreach ($customers as $customer) {
            $customerArray[$customer->contacts->id]   =   (!is_null($customer->company) ? $customer->company->company : 'N/A') . ' ( ' . $customer->contacts->first_name . ' ' . $customer->contacts->last_name . ' )';
        }

        return $customerArray;
    }

    protected function getQueryParams(Request $request)
    {
        $projectStatus = $request->input('project_status');
        $jobinfoStatus = $request->input('job_info_status');
        $projectType = $request->input('project_type');
        $projectState = $request->input('project_state');
        $receivableStatus = $request->input('receivable_status');
        $claimAmount = $request->input('claim_amount');
        $customer = $request->input('customers');
        $projectName = $request->input('project_name');
        $user = $request->input('user');
        $complianceManagement = $request->input('compliance_management');
        $dateType = $request->input('date_type');
        $from = $request->input('from');
        $to = $request->input('to');
        $complianceProvider = $request->input('compliance_provider');
        $deadlineCalc = $request->input('calculation_status');

        return [
            'projectStatus'    => (isset($projectStatus) && $projectStatus != '') ? $projectStatus : 'all',
            'jobinfoStatus'    => (isset($jobinfoStatus) && $jobinfoStatus != '') ? $jobinfoStatus : 'all',
            'projectType'   => (isset($projectType) && $projectType != '') ? $projectType : 'all',
            'projectState'  => (isset($projectState) && $projectState != '') ? $projectState : 'all',
            'receivableStatus' => (isset($receivableStatus) && $receivableStatus != '') ? $receivableStatus : 'all',
            'claimAmount'    => (isset($claimAmount) && $claimAmount != '') ? $claimAmount : 'all',
            'customer'     => (isset($customer) && $customer != '') ? $customer : 'all',
            'projectName' => (isset($projectName) && $projectName != '') ? $projectName : '',
            'user' => (isset($user) && $user != '') ? $user : 'all',
            'complianceManagement' => (isset($complianceManagement) && $complianceManagement != '') ? $complianceManagement : 'default',
            'dateType' => (isset($dateType) && $dateType != '') ? $dateType : 'all',
            'from' => (isset($from) && $from != '') ? $from : '',
            'to' => (isset($to) && $to != '') ? $to : '',
            'complianceProvider' => (isset($complianceProvider) && $complianceProvider != '') ? $complianceProvider : 'all',
            'calculation_status' => (isset($deadlineCalc) && !empty($deadlineCalc)) ? $deadlineCalc : 'all'
        ];
    }



    protected function searchProjectsThroughAdvancedFilter($request, $projects = null)
    {
        $projectStatus = $request->input('project_status');
        $jobinfoStatus = $request->input('job_info_status');
        $projectType = $request->input('project_type');
        $projectState = $request->input('project_state');
        $receivableStatus = $request->input('receivable_status');
        $claimAmount = $request->input('claim_amount');
        $customer = $request->input('customers');
        $projectName = $request->input('project_name');
        $user = $request->input('user');
        $complianceManagement = $request->input('compliance_management');
        $dateType = $request->input('date_type');
        $from = $request->input('from');
        $to = $request->input('to');
        $complianceProvider = $request->input('compliance_provider');
        $deadlineCalc = $request->input('calculation_status');

        if (isset($projectStatus) && $projectStatus != 'all') {
        }

        if (isset($jobinfoStatus) && $jobinfoStatus != 'all') {
            $projects->where('project_details.status', '=', $jobinfoStatus);
//            $projects->whereHas('jobInfo', function ($query) use ($jobinfoStatus) {
//                $query->where('status', '=', $jobinfoStatus);
//            });
        }

        if (isset($projectType) && $projectType != 'all') {
            $projects->whereHas('project_type', function ($query) use ($projectType) {
                $query->where('id', '=', $projectType);
            });
        }

        if (isset($projectState) && $projectState != 'all') {
            $projects->whereHas('state', function ($query) use ($projectState) {
                $query->where('id', '=', $projectState);
            });
        }

        if (isset($receivableStatus) && $receivableStatus != 'all') {
            $projects->whereHas('project_contract', function ($query) use ($receivableStatus) {
                $query->where('receivable_status', 'LIKE', '%' . $receivableStatus . '%');
            });
        }

        if (isset($claimAmount) && $claimAmount != 'all') {
            if ($claimAmount != '500000+') {
                $ranges = explode('-', $claimAmount);
                $lowerRange = $ranges[0];
                $upperRange = $ranges[1];

                $projects->whereHas('project_contract', function ($query) use ($lowerRange, $upperRange) {
                    $query->whereBetween('total_claim_amount', [$lowerRange, $upperRange]);
                });
            } else {
                $projects->whereHas('project_contract', function ($query) use ($claimAmount) {
                    $query->where('total_claim_amount', '>=', $claimAmount);
                });
            }
        }

        if (isset($customer) && $customer != 'all') {
            $projects->whereHas('customer_contract.contacts', function ($query) use ($customer) {
                $query->where('id', '=', $customer);
            });
        }

        if (isset($projectName) && $projectName != '') {
            $projects->where('project_details.id', '=', $projectName);
        }

        if (isset($user) && $user != 'all') {
        }

        if (isset($complianceManagement) && $complianceManagement != 'default') {
        }

        if (isset($dateType) && $dateType != 'all') {
            $projects->whereHas('project_date.remedyDate.remedy', function ($query) use ($dateType) {
                $query->where('remedy', 'LIKE', '%' . $dateType . '%');
            });
        }

        $fromDate = (isset($from) && $from != '') ? date('Y-m-d', strtotime($from)) : date('Y-m-d', strtotime(''));
        $toDate = (isset($to) && $to != '') ? date('Y-m-d', strtotime($to)) : date('Y-m-d');
        if (isset($from) && isset($to)) {
            // $projects->whereHas('project_detail', function ($query) use ($fromDate,$toDate) {
            //     $query->whereBetween('created_at', [$fromDate,$toDate]);
            // });
            $projects->whereBetween('project_details.created_at', [$fromDate, $toDate]);
        }

        if (isset($complianceProvider) && $complianceProvider != 'all') {
            $params = explode('@', $complianceProvider);
            $projects->where('project_details.state_id', '=', $params[1]);
        }
        return $projects;
    }

    /**
     * Return Dashboard for Lien
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lien()
    {
        $case = request()->get('case');
        $projectDetails = request()->get('projectDetails');
        $sortBy = request()->get('sortBy');
        $sortWith = request()->get('sortWith');
        $searchFlag = request()->get('search');
        $perPage = 7;

//        dd(Auth::user()->lienUser);
        $states = LienProviderStates::where('lien_id', Auth::user()->lienUser->id)->pluck('state_id');

        $membersAssociated = MemberLienMap::where('lien_id', Auth::user()->lienUser->id)->pluck('user_id')->toArray();
        $projectList = ProjectDetail::whereIn('user_id', $membersAssociated)->pluck('project_name', 'id');

        if ($case == 'active') {
            /*$projects = ProjectDetail::whereIn('user_id', $membersAssociated)->where('status', '1');*/
            $projects = ProjectDetail::where('status', '1');
        } else {
            /*$projects = ProjectDetail::whereIn('user_id', $membersAssociated);*/
            $projects = ProjectDetail::query();
        }

        if (isset($searchFlag) && $searchFlag == '1') {
            $perPage = 1000;
            $projectName = request()->get('project_name');
            $assignedDateFrom = request()->get('assigned_date_from');
            $assignedDateTo = request()->get('assigned_date_to');
            $dateCompletedFrom = request()->get('date_completed_from');
            $dateCompletedTo = request()->get('date_completed_to');
            $companyName = request()->get('company_name');
            $status = request()->get('status');
            $state = request()->get('state');

            if ($projectName != '') {
                $projects->where('project_name', 'LIKE', '%' . $projectName . '%');
            }

            if (($assignedDateFrom != '') && ($assignedDateTo != '')) {
                $dateFrom = ($assignedDateFrom != '') ? date('Y-m-d 00:00:00', strtotime($assignedDateFrom)) : date('Y-m-d', strtotime(''));
                $dateTo = ($assignedDateTo != '') ? date('Y-m-d 23:59:59', strtotime($assignedDateTo)) : date('Y-m-d');
//                $projects->orWhereBetween('project_details.created_at', [$dateFrom, $dateTo]);
//                $projects->orWhereBetween('project_details.updated_at', [$dateFrom, $dateTo]);

                $projects->leftJoin('project_tasks', function($join) {
                    $join->on('project_tasks.project_id', '=', 'project_details.id');
                })->select('project_details.*')
                ->groupBy('project_details.id');
//                $projects->where('project_tasks.due_date', '>=', date('Y-m-d'));
//                $projects->orWhereBetween('project_tasks.due_date', [$dateFrom, $dateTo]);
            }

//            if (($dateCompletedFrom != '') || ($dateCompletedTo != '')) {
//                $dateCompletedFrom = ($dateCompletedFrom != '') ? date('Y-m-d 00:00:00', strtotime($dateCompletedFrom)) : date('Y-m-d', strtotime(''));
//                $dateCompletedTo = ($dateCompletedTo != '') ? date('Y-m-d 23:59:59', strtotime($dateCompletedTo)) : date('Y-m-d', strtotime(''));
//                $projects->whereBetween('created_at', [$dateCompletedFrom, $dateCompletedTo]);
//            }

            if ($state != '') {
                $projects->where('state_id', $state);
            }

            if ($companyName != '') {
                $projects->whereHas('user.details', function ($query) use ($companyName) {
                    $query->where('company', 'LIKE', '%' . $companyName . '%');
                });
            }
        }

        if (isset($projectDetails)) {
            $projects->where(function ($query1) use ($projectDetails) {
                $query1->Where('project_name', 'LIKE', '%' . $projectDetails . '%')
                    ->orWhere('created_at', 'LIKE', '%' . date("Y-m-d H:i:s", strtotime($projectDetails)) . '%')
                    ->orWhereHas('state', function ($query) use ($projectDetails) {
                        $query->where('name', 'LIKE', '%' . $projectDetails . '%');
                    })
                    ->orWhereHas('project_type', function ($query) use ($projectDetails) {
                        $query->where('project_type', 'LIKE', '%' . $projectDetails . '%');
                    })
                    ->orWhereHas('customer_contract.getContacts', function ($query) use ($projectDetails) {
                        $query->where('type', '0')->whereHas('mapContactCompany.company', function ($query) use ($projectDetails) {
                            $query->where('company', 'LIKE', '%' . $projectDetails . '%');
                        });
                    });
            });
        }

        if (isset($sortBy) && isset($sortWith)) {
            if ($sortWith == 'customer_contact_name') {
                // dd('here');

                $projects->leftJoin('map_company_contacts', function ($join) {
                    $join->on('project_details.customer_contract_id', '=', 'map_company_contacts.id');
                })->leftJoin('companies', function ($join) {
                    $join->on('map_company_contacts.company_id', '=', 'companies.id');
                })->select('project_details.*', 'companies.company')->whereIn('project_details.user_id', $membersAssociated)->orderBy('companies.company', $sortBy);
            } elseif ($sortWith == 'customer_name') {
                $projects->leftJoin('map_company_contacts', function ($join) {
                    $join->on('project_details.customer_contract_id', '=', 'map_company_contacts.id');
                })->leftJoin('company_contacts', function ($join) {
                    $join->on('map_company_contacts.company_contact_id', '=', 'company_contacts.id');
                })->select('project_details.*', 'company_contacts.first_name')->whereIn('project_details.user_id', $membersAssociated)->orderBy('company_contacts.first_name', $sortBy);
            } else {
                // dd($projects);
                $projects->whereIn('user_id', $membersAssociated)->orderBy($sortWith, $sortBy);
            }
        } else {
            //dd($projects);
            $projects->whereIn('user_id', $membersAssociated)->orderBy('updated_at', 'DESC');
        }

//        dd($states);
        $projects->whereIn('state_id', $states);

        $allProjects = $projects->get();
        $total_contracts_amount = 0;
        $contracts_avg = 0;
        foreach($allProjects as $project) {
            $total_contracts_amount += $project->project_contract->total_claim_amount;
        }
        if(count($allProjects) > 0) {
            $contracts_avg = '$'.number_format(($total_contracts_amount / count($allProjects)), 2, '.', ',');
        }

        $projectssearch = $projects->paginate($perPage);

        $index = 0;
        foreach ($projectssearch as  $project)
        {
            $daysRemain = [];
            $flag = 0;
            $remedy = Remedy::where('state_id', $project->state_id)
                ->where('project_type_id', $project->project_type_id);
            $tiers = TierTable::where('role_id', $project->role_id)
                ->where('customer_id', $project->customer_id)->firstOrFail();
            $remedySteps = RemedyStep::whereIn('remedy_id', $remedy->pluck('id'));
            $tierRemedySteps = TierRemedyStep::where('tier_id', $tiers->id)
                ->whereIn('remedy_step_id', $remedySteps->pluck('id'))
                ->whereIn('answer1', [$project->answer1, ''])
                ->whereIn('answer2', [$project->answer2, '']);
            $remedyStepsNew = $remedySteps->whereIn('id', $tierRemedySteps->pluck('remedy_step_id'));
            $remedyDate = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy->pluck('id'))->whereIn('id', $remedyStepsNew->pluck('remedy_date_id'))->orderBy('date_order', 'ASC')->get();
            $role_id = ProjectDetail::where('id', $project->id);
            $answer = $role_id->first()->answer1;
            if ($answer == 'Yes' || $answer == 'Commercial') {
                $flag = 1;
            } elseif ($answer == 'No' || $answer == 'Residential') {
                $flag = 2;
            }
            if ($flag == 0) {
                $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                    ->whereIn('remedy_id', $remedy->pluck('id'));
                $deadline = $deadline1->whereIn('id', $tierRem->pluck('remedy_step_id'))->get();
            } elseif ($flag == 1) {
                $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                if ($answer == 'Yes') {
                    $tierRem1 = $tierRem->where(function ($query) {
                        $query->where('answer1', 'Yes')
                            ->orWhere('answer1', '');
                    });
                } else {
                    $tierRem1 = $tierRem->where(function ($query) {
                        $query->where('answer1', 'Commercial')
                            ->orWhere('answer1', '');
                    });
                }
                $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                    ->whereIn('remedy_id', $remedy->pluck('id'));
                $deadline = $deadline1->whereIn('id', $tierRem1->pluck('remedy_step_id'))->get();
            } elseif ($flag == 2) {
                $tier = TierTable::where('role_id', $role_id->pluck('role_id'))->where('customer_id', $role_id->pluck('customer_id'));
                $tierRem = TierRemedyStep::where('tier_id', $tier->pluck('id'));
                if ($answer == 'No') {
                    $tierRem1 = $tierRem->where(function ($query) {
                        $query->where('answer1', 'No')
                            ->orWhere('answer1', '');
                    });
                } else {
                    $tierRem1 = $tierRem->where(function ($query) {
                        $query->where('answer1', 'Residential')
                            ->orWhere('answer1', '');
                    });
                }
                $deadline1 = RemedyStep::where('status', '1')->whereIn('remedy_date_id', $remedyDate->pluck('id'))
                    ->whereIn('remedy_id', $remedy->pluck('id'));
                $deadline = $deadline1->whereIn('id', $tierRem1->pluck('remedy_step_id'))->get();
            }

            foreach ($deadline as $dkey => $value) {
                $years = $value->years;
                $months = $value->months;
                $days = $value->days;
                $remedyDateId = $value->remedy_date_id;
                $daysRemain[$dkey]['dates'] = ($years * 365) + ($months * 30) + ($days * 1);
                $preliminaryDeadline = ProjectDates::select('date_value')
                    ->where('project_id', $project->id)
                    ->where('date_id', $remedyDateId)->first();
                if ($preliminaryDeadline != null && $preliminaryDeadline->date_value != '') {
                    $dateNew = date_create($preliminaryDeadline->date_value);
                    date_add($dateNew, date_interval_create_from_date_string($years . " years " . $months . " months " . $days . " days"));
                    if ($value->day_of_month != 0) {
                        $prelim = date_format($dateNew, "m/{$value->day_of_month}/Y");
                    } else {
                        $prelim = date_format($dateNew, "m/d/Y");
                    }
                    $daysRemain[$dkey]['deadline'] = $preliminaryDeadline->date_value;
                    $daysRemain[$dkey]['preliminaryDates'] = date($prelim);
                    $daysRemain[$dkey]['name'] = $value->short_description;
                } else {
                    $daysRemain[$dkey]['preliminaryDates'] = 'N/A';
                    $daysRemain[$dkey]['name'] = '';
                    $daysRemain[$dkey]['deadline'] = '';
                }
                $remedyNames[$value->getRemedy->id] = $value->getRemedy->remedy;
            }

            if( isset($dkey) && isset($daysRemain[$dkey]) && count($daysRemain[$dkey]) > 0 && is_array($daysRemain[$dkey]) ) {
                $min = min(array_map(function($a) { return $a; }, $daysRemain));
                if(isset($min['preliminaryDates'])) {
                    $project['preliminaryDates'] = $min['preliminaryDates'];
                }
            }

            $tasks = ProjectTask::where('project_id', $project->id)
                ->where('due_date', '>=', date('Y-m-d'))
                ->orderBy('due_date')
                ->first();
            if( $tasks )
            {
                $project->next_task_date = date('m/d/Y', strtotime($tasks->due_date));

                $dueDiffFromNow = strtotime($tasks->due_date) - strtotime('now');
                $preliminaryDateDiffFromNow = strtotime($project->preliminaryDates) - strtotime('now');

                if( $preliminaryDateDiffFromNow < 0 && $dueDiffFromNow < 0 ) {
                    $project->next_task_date = 'N/A';
                } else {
                    if( $preliminaryDateDiffFromNow < 0 ) {
                        $project->next_action_date = date('m/d/Y', strtotime($tasks->due_date));
                    } else if( $dueDiffFromNow < 0 ) {
                        $project->next_action_date = $project->preliminaryDates;
                    } else {
                        if( $dueDiffFromNow < $preliminaryDateDiffFromNow ) {
                            $project->next_action_date = date('m/d/Y', strtotime($tasks->due_date));
                        } else {
                            $project->next_action_date = $project->preliminaryDates;
                        }
                    }
                }
            } else {
                $preliminaryDateDiffFromNow = strtotime($project->preliminaryDates) - strtotime('now');
                if($preliminaryDateDiffFromNow < 0 ) {
                    $project->next_action_date = 'N/A';
                } else {
                    $project->next_action_date = $project->preliminaryDates;
                }
                $project->next_task_date = 'N/A';
            }

            if( !empty($dateFrom) && !empty($dateTo) )
            {
                $remove = true;
                if ( $project->next_task_date != 'N/A' ) {
                    $next_task_date = strtotime($project->next_task_date);
                    if( strtotime($dateFrom) <= $next_task_date && $next_task_date <= strtotime($dateTo) ) {
                        $remove = false;
                    }
                }

                if ( $project->created_at != 'N/A' ) {
                    $created_at = strtotime($project->created_at);
                    if( strtotime($dateFrom) <= $created_at && $created_at <= strtotime($dateTo) ) {
                        $remove = false;
                    }
                }

                if ( $project->next_action_date != 'N/A' ) {
                    $next_action_date = strtotime($project->next_action_date);
                    if( strtotime($dateFrom) <= $next_action_date && $next_action_date <= strtotime($dateTo) ) {
                        $remove = false;
                    }
                }

                if ( $project->preliminaryDates != 'N/A' ) {
                    $preliminaryDates = strtotime($project->preliminaryDates);
                    if( strtotime($dateFrom) <= $preliminaryDates && $preliminaryDates <= strtotime($dateTo) ) {
                        $remove = false;
                    }
                }

                if ( $remove ) {
                    unset($projectssearch[$index]);
                }
            }

            $index++;
        }

        if(!empty($dateFrom) && !empty($dateTo)) {
            $allProjects = $projectssearch;
            $total_contracts_amount = 0;
            $contracts_avg = 0;
            foreach ($allProjects as $project) {
                $total_contracts_amount += $project->project_contract->total_claim_amount;
            }
            if (count($allProjects) > 0) {
                $contracts_avg = '$' . number_format(($total_contracts_amount / count($allProjects)), 2, '.', ',');
            }
        }

        $companies = [];
        foreach ($membersAssociated as $key => $member) {
            $memberUser = User::findOrFail($member);
            $memberCompany = $memberUser->details;
            $companies[$key]['id'] = $memberCompany->id;
            $companies[$key]['data'] = $memberCompany->company;
            $companies[$key]['value'] = $memberCompany->company;
        }

        $states = State::all();

        return view('lienProviders.dashboard.dashboard', [
            'projects' => $projectssearch,
            'number_of_contracts' => count($allProjects),
            'total_contracts_amount' => '$'.number_format($total_contracts_amount, 2, '.', ','),
            'contracts_avg' => $contracts_avg,
            'projectsList' => $projectList,
            'companies' => $companies,
            'states' => $states,
            'caseProject' => $case
        ]);
    }

    /**
     * Get Update Member Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateMemberProfile()
    {
        $companies = Company::pluck('company', 'id');

        $plan = $this->getUserSubscription();
        if ($plan == 'basic') {
            $state_id = $this->getUserState();
            $states = State::where('id', '=', $state_id)->pluck('name', 'id');
        } else {
            $states = State::pluck('name', 'id');
        }
        // print_r($states);
        // die();
        $user = Auth::user();
        $state_id = !is_null($user->details) && !is_null($user->details->getCompany) ? $user->details->getCompany->state_id : '';
        $state_name = State::where('id', $state_id)->first();
        $role_id = User::where('id', Auth::user()->id)->first()->role;
        $role_name = Role::where('id', $role_id)->first()->type;
        $subusers = !is_null(Auth::user()->subUsers) ? Auth::user()->subUsers : [];
        //dd($role_name);

        return view('basicUser.user.update_profile', [
            'states' => $states,
            'state_id' => $state_id,
            'state_name' => $state_name,
            'user' => $user,
            'role_name' => $role_name,
            'subusers' => $subusers,
            'companies' => $companies
        ]);
    }

    /**
     * Get Update Lien Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateLienProfile()
    {
        $companies = Company::pluck('company', 'id');
        $states = State::pluck('name', 'id');
        $user = Auth::user();
        $state_id = !is_null($user->details) && !is_null($user->details->getCompany) ? $user->details->getCompany->state_id : '';
        $state_name = State::where('id', $state_id)->first();
        $role_id = User::where('id', Auth::user()->id)->first()->role;
        $role_name = Role::where('id', $role_id)->first()->type;
        //$subusers = !is_null(Auth::user()->subUsers) ? Auth::user()->subUsers : [];
        //dd($role_name);

        return view('lienProviders.profile.profile', [
            'states' => $states,
            'state_id' => $state_id,
            'state_name' => $state_name,
            'user' => $user,
            'role_name' => $role_name,
            'companies' => $companies
        ]);
    }


    /**
     * Update Member Profile
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfileAction(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users,email,' . Auth::id(),
//            'zip' => 'required|numeric',
            'office_phone' => 'nullable|numeric',
            'phone' => 'nullable|numeric'
        ]);
        try {
            if ($request->hasFile('image')) {
                $extension = File::extension($request->image->getClientOriginalName());
                if (strtolower($extension) == "jpeg" || strtolower($extension) == "jpg" || strtolower($extension) == "png") {
                    $file = $request->image;
                    $fileName = time() . '.' . $extension;
                    $filePath = public_path() . "/image_logo";
                    $file->move($filePath, $fileName);
                }
            }

            /*  if($request->companyName == 'new_data') {
            $company = new Company();
            $company->company = $request->company_name;
            $company->user_id = Auth::id();
            $company->website = $request->website;
            $company->address = $request->address;
            $company->city = $request->city;
            $company->state_id = $request->state;
            $company->zip = $request->zip;
            $company->phone = $request->office_phone;
            $company->save();

        } else {
        $company = Company::findOrFail($request->companyName);
    }*/

            $user = Auth::user();
            $userDetails = $user->details()->firstOrFail();
            $userCompany = $user->details->getCompany;

            $user->email = $request->email;
            //$userDetails->company_id = $company->id;
            $userDetails->company = $request->company;
            $userDetails->first_name = $request->firstName;
            $userDetails->last_name = $request->lastName;
            $userDetails->address = $request->address;
            $userDetails->city = $request->city;
            $userDetails->state_id = $request->state;
            $userDetails->zip = $request->zip;
            $userDetails->website = $request->website;
            $userDetails->phone = $request->phone;
            $userDetails->office_phone = $request->office_phone;

            /* $subusers = $user->subUsers;
            foreach ($subusers as $subuser) {
            $map = $subuser->mapcompanyContacts;
            if(!is_null($map)) {
            $map->company_id = $company->id;
            $map->update();
}
}*/

            //$userCompany->company = $request->companyName;
            $userCompany->website = $request->website;
            $userCompany->address = $request->address;
            $userCompany->city = $request->city;
            $userCompany->state_id = $request->state;
            $userCompany->zip = $request->zip;
            $userCompany->phone = $request->office_phone;


            if (isset($fileName)) {
                $userDetails->image = $fileName;
            }
            $userDetails->update();
            $user->update();
            $userCompany->update();

            return redirect()->back()->with('success-update', 'Updated Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    public function updateNotificationSettingsAction(Request $request)
    {
        try {
            $insertDetails = [
                'user_id' => $request->user()->id,
                'days' => $request->notify,
            ];

            // $setting = NotificationSettings::first(array('user_id' => $request->user()->id));
            // $setting->update($insertDetails);

            $not_settings = NotificationSettings::where('user_id', $request->user()->id)->get();
            // var_dump($not_settings->count());
            if ($not_settings->count() > 0) {
                DB::table('notification_settings')->update($insertDetails);
            } else {
                DB::table('notification_settings')->insert($insertDetails);
            }
            return redirect()->back()->with('success-update', 'Updated Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        }
    }

    /**
     * Update Lien Profile
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateLienProfileAction(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'zip' => 'required|numeric',
            'office_phone' => 'nullable|numeric',
            'phone' => 'nullable|numeric'
        ]);
        try {
            if ($request->hasFile('image')) {
                $extension = File::extension($request->image->getClientOriginalName());
                if (strtolower($extension) == "jpeg" || strtolower($extension) == "jpg" || strtolower($extension) == "png") {
                    $file = $request->image;
                    $fileName = time() . '.' . $extension;
                    $filePath = public_path() . "/image_logo";
                    $file->move($filePath, $fileName);
                }
            }

            /* if($request->companyName == 'new_data') {
            $company = new Company();
            $company->company = $request->company_name;
            $company->user_id = Auth::id();
            $company->website = $request->website;
            $company->address = $request->address;
            $company->city = $request->city;
            $company->state_id = $request->state;
            $company->zip = $request->zip;
            $company->phone = $request->office_phone;
            $company->save();

    } else {
    $company = Company::findOrFail($request->companyName);
}*/

            $user = Auth::user();
            $userDetails = $user->details()->firstOrFail();
            $lienUser = $user->lienUser;
            $lienCompany = $user->lienUser->getCompany;

            $user->email = $request->email;
            /*$userDetails->company = $request->company_name;
            $userDetails->company_id = $company->id;*/
            $userDetails->first_name = $request->firstName;
            $userDetails->last_name = $request->lastName;
            $userDetails->address = $request->address;
            $userDetails->city = $request->city;
            $userDetails->state_id = $request->state;
            $userDetails->zip = $request->zip;
            $userDetails->website = $request->website;
            $userDetails->phone = $request->phone;
            $userDetails->office_phone = $request->office_phone;
            if (isset($fileName)) {
                $userDetails->image = $fileName;
            }

            //$lienUser->company_id = $company->id;
            $lienUser->firstName = $request->firstName;
            $lienUser->lastName = $request->lastName;
            $lienUser->address = $request->address;
            $lienUser->city = $request->city;
            $lienUser->stateId = $request->state;
            $lienUser->zip = $request->zip;
            $lienUser->companyPhone = $request->office_phone;
            $lienUser->phone = $request->phone;
            $lienUser->email = $request->email;

            $lienCompany->website = $request->website;

            $userDetails->update();
            $user->update();
            $lienCompany->update();
            $lienUser->update();

            return redirect()->back()->with('success-update', 'Updated Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }


    /**
     * Change Password form member section
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        //dd($request->all());
        try {
            $oldPassword = $request->oldPassword;
            $newPassword = $request->newPassword;
            $cNewPassword = $request->cNewPassword;
            $validator = Validator::make($request->all(), [
                'newPassword' => 'required|min:6',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'The password must be at least 6 characters.'
                ], 200);
            }
            $hashPassword = Hash::check($oldPassword, Auth::user()->password);
            if ($hashPassword) {
                if ($newPassword == $cNewPassword) {
                    $user = User::findOrFail(Auth::user()->id);
                    $user->password = $newPassword;
                    $user->update();
                    Auth::logout();
                    return response()->json([
                        'status' => true,
                        'message' => 'password Changed',
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Password and confirm password not match'
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Please check your current password'
                ], 200);
            }
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 200);
        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage()
            ], 200);
        }
    }
    public function getAllSubUserDetails(Request $request)
    {
        try {
            $user = User::findOrFail($request->user_id);
            $subUsers = User::find($request->user_id)->subUsers()->get();
            $data = [];
            $count = 0;
            foreach ($subUsers as $key => $subUser) {
                $data[$key]['id'] = $subUser->id;
                $data[$key]['email'] = $subUser->email;
                $data[$key]['first_name'] = $subUser->details->first_name;
                $data[$key]['last_name'] = $subUser->details->last_name;
                $data[$key]['company'] = !is_null($subUser->mapcompanyContacts) ? $subUser->mapcompanyContacts->company->company : 'N/A';
                $data[$key]['address'] = !is_null($subUser->mapcompanyContacts) ? $subUser->mapcompanyContacts->address : 'N/A';
                $data[$key]['city'] = !is_null($subUser->mapcompanyContacts) ? $subUser->mapcompanyContacts->city : 'N/A';
                $data[$key]['state'] = !is_null($subUser->mapcompanyContacts) ? $subUser->mapcompanyContacts->state_id : 'N/A';
                $data[$key]['zip'] = !is_null($subUser->mapcompanyContacts) ? $subUser->mapcompanyContacts->zip : 'N/A';
                $data[$key]['phone'] = $subUser->details->phone;
                $data[$key]['office_phone'] = !is_null($subUser->mapcompanyContacts) ? $subUser->mapcompanyContacts->phone : 'N/A';
                $count++;
            }
            $data[$count]['id'] = $user->id;
            $data[$count]['email'] = $user->email;
            $data[$count]['first_name'] = $user->details->first_name;
            $data[$count]['last_name'] = $user->details->last_name;
            $data[$count]['company'] = $user->details->getCompany->company;
            $data[$count]['address'] = $user->details->address;
            $data[$count]['city'] = $user->details->city;
            $data[$count]['state'] = $user->details->state->id;
            $data[$count]['zip'] = $user->details->zip;
            $data[$count]['phone'] = $user->details->phone;
            $data[$count]['office_phone'] = $user->details->office_phone;
            return response()->json([
                'status' => true,
                'data' => $data,
                'message' => 'All SUb User Details'
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 200);
        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage()
            ], 200);
        }
    }

    /**
     * Gets the maximum package price
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMaxPrice(Request $request)
    {
        try {
            $package = PackagePrice::findOrFail($request->id);
            $price = $package->price;

            return response()->json([
                'status' => true,
                'data' => $price
            ], 200);
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'data' => null
            ], 500);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'data' => null
            ], 500);
        }
    }
}
