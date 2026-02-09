<?php

namespace App\Http\Controllers;

use DB;
use View;
use App\User;
use Exception;
use Validator;
use Stripe\Token;
use Stripe\Stripe;
use App\Models\Role;
use App\Models\State;
use App\Models\Company;
use App\Models\Package;
use App\Models\UserDetails;
use App\Models\LienProvider;
use App\Models\PackagePrice;
use Illuminate\Http\Request;
use App\Models\MemberLienMap;
use App\Models\MemberPackage;
use App\Models\MapCompanyContact;
use App\Models\MemberBillingAddress;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class MemberController for member system
 * @package App\Http\Controllers
 */
class MemberController extends Controller
{
    /**
     * Show All agency Controller
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function agencyUser()
    {
        try {
            $agency = Role::where('type', 'Agency user')->firstOrFail();
            $users = User::where('role', $agency->id)->paginate(10);
            //dd($users);
            return view('member.agency', [
                'users' => $users
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Show all business user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function businessUser()
    {
        try {
            $agency = Role::where('type', 'Business user')->firstOrFail();
            $users = User::where('role', $agency->id)->paginate(10);
            return view('member.business', [
                'users' => $users
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Show all Sub user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function subUser()
    {
        try {
            $search = request()->get('search');
            $agency = Role::where('type', 'Sub-Member')->firstorFail();
            if ( $search != '' ) {
                $users = User::where('name', 'LIKE', '%' . $search . '%')
                                ->where('role', $agency->id)
                                ->orWhere('user_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('email', 'LIKE', '%' . $search . '%')
                                ->orderBy('id', 'DESC')->paginate(10);
            } else {
                $users = User::where('role', $agency->id)->paginate(10);
            }

            $states = State::all();
            $members = User::where('role', '5')->where('status', '0')->get();
            return view('admin.subUser.list', [
                'users' => $users,
                'states' => $states,
                'members' => $members
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Return member add edit delete section form admin panel
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function memberUser()
    {
        try {
            $member = Role::where('type', 'Member')->firstorFail();
            $search = request()->get('search');
            if ( $search != '' ) {
                $users = User::where('name', 'LIKE', '%' . $search . '%')
                                ->where('role', $member->id)
                                ->orWhere('user_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('email', 'LIKE', '%' . $search . '%')
                                ->orderBy('id', 'DESC')->paginate(10);
            } else {
                $users = User::where('role', $member->id)->orderBy('id', 'DESC')->paginate(10);
            }
            $packages = Package::all();
            $states = State::all();
            $lienProviders = LienProvider::where('is_deletable', '1')->orderBy('created_at', 'desc')->get();
            $plans = config('app.plans');
            return view('member.member', [
                'users' => $users,
                'states' => $states,
                'lienProviders' => $lienProviders,
                'packages' => $packages,
                'plans' => $plans
            ]);
        } catch (Exception $e) {
            return view('errors.exceptions', ['exception' => $e]);
            //return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
            //return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function packagestateAutopopulate(Request $request)
    {
        try {

            $stateCount = count($request->ids);
            if ($stateCount != 0) {
                $packagePrices = PackagePrice::all();
                foreach ($packagePrices as $packagePrice) {
                    if ($stateCount >= $packagePrice->state_lower_range && $stateCount <= $packagePrice->state_upper_range) {
                        $price = $packagePrice->price;
                    }
                }
            }
            return response()->json([
                'status' => true,
                'price' => isset($price) ? $price : ''
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        } catch (ModelNotFoundException $exception) {

            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 500);
        } catch (QueryException $exception) {
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }
    /**
     * Get Update Member Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateMemberProfile($id)
    {
        $states = State::all();
        $userEmail = User::where('id', $id)->first()->email;
        $user = UserDetails::where('user_id', $id)->firstorFail();
        $state_id = $user->state_id;
        $state_name = State::where('id', $state_id)->first();
        $role_id = User::where('id', $id)->first()->role;
        $role_name = Role::where('id', $role_id)->first()->type;
        $lienProviders = LienProvider::orderBy('created_at', 'desc')->get();
        $selectedLienPros = MemberLienMap::where('user_id', $id)->pluck('lien_id')->toArray();

        //dd($member);
        return view('member.edit', [
            'states' => $states,
            'state_id' => $state_id,
            'state_name' => $state_name,
            'user' => $user,
            'email' => $userEmail,
            'id' => $id,
            'role_name' => $role_name,
            'lienProviders' => $lienProviders,
            'selectedLienPros' => $selectedLienPros
        ]);
    }

    /**
     * Get Update Member Modal Response
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateMemberProfileModal()
    {
        try {
            $id = $_GET['memberID'];
            $member = User::findOrFail($id);
            $states = State::all();
            $userEmail = User::where('id', $id)->first()->email;
            $username = User::where('id', $id)->first()->user_name;
            $user = UserDetails::with('getCompany')->where('user_id', $id)->firstorFail();
            $state_id = $user->state_id;
            $state_name = State::where('id', $state_id)->first();
            $role_id = User::where('id', $id)->first()->role;
            $role_name = Role::where('id', $role_id)->first()->type;
            $lienProviders = LienProvider::orderBy('created_at', 'desc')->get();
            $selectedLienPros = MemberLienMap::where('user_id', $id)->pluck('lien_id')->toArray();
            $package = !is_null($member->getPaymentDetails) ? $member->getPaymentDetails->package_id : '';
            $packageState = !is_null($member->getPaymentDetails) ? explode(',', $member->getPaymentDetails->package_state) : [];
            $packageCost = !is_null($member->getPaymentDetails) ? $member->getPaymentDetails->package_cost : '';
            $billingInfo = !is_null($member->getPaymentDetails) ? $member->getPaymentDetails->billing_info_same : '0';
            $period = !is_null($member->getPaymentDetails) ? $member->getPaymentDetails->period : '';
            $memberType = !is_null($member->getPaymentDetails) ? $member->getPaymentDetails->membership : '';
            $original_price = !is_null(PackagePrice::find($package)) ? PackagePrice::find($package)->price : '';

            return response()->json([
                'states' => $states,
                'state_id' => $state_id,
                'state_name' => $state_name,
                'user' => $user,
                'email' => $userEmail,
                'id' => $id,
                'role_name' => $role_name,
                'lienProviders' => $lienProviders,
                'selectedLienPros' => $selectedLienPros,
                'username' => $username,
                'package_id'  =>  $package,
                'package_state' => $packageState,
                'package_cost'  => $packageCost,
                'billing_info' => $billingInfo,
                'period' => $period,
                'membership' => $memberType,
                'original_price' => $original_price,
                'edit' => 1
            ]);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get Update Sub User Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function updateSubUserProfile($id)
    {
        $states = State::all();
        $userEmail = User::where('id', $id)->first()->email;
        $user = UserDetails::where('user_id', $id)->firstorFail();
        $state_id = $user->state_id;
        $parent_id = User::where('id', $id)->first()->parent_id;
        $state_name = State::where('id', $state_id)->first();
        $role_id = User::where('id', $id)->first()->role;
        $role_name = Role::where('id', $role_id)->first()->type;
        $members = User::where('role', '5')->get();

        return view('admin.subUser.edit', [
            'states' => $states,
            'state_id' => $state_id,
            'parent_id' => $parent_id,
            'state_name' => $state_name,
            'user' => $user,
            'email' => $userEmail,
            'id' => $id,
            'role_name' => $role_name,
            'members' => $members
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
            'companyName' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'phone' => 'required',
            'provider' => 'required',
            'lienProviders' => 'required'
        ]);
        try {
            $userDetails = UserDetails::where('user_id', $request->user_id)->firstOrFail();
            $userCompany = $userDetails->getCompany;
            $userDetails->company = $request->companyName;
            $userDetails->first_name = $request->firstName;
            $userDetails->last_name = $request->lastName;
            $userDetails->address = $request->address;
            $userDetails->city = $request->city;
            $userDetails->state_id = $request->state;
            $userDetails->zip = $request->zip;
            $userDetails->phone = $request->phone;
            $userDetails->website = $request->website;
            $userDetails->lien_status = $request->provider;
            $userDetails->update();

            $user = User::where('id', $request->user_id)->firstOrFail();
            $user->name = $request->firstName . " " . $request->lastName;
            $user->update();

            $userCompany->company = $request->companyName;
            $userCompany->address = $request->address;
            $userCompany->city = $request->city;
            $userCompany->state_id = $request->state;
            $userCompany->zip = $request->zip;
            $userCompany->phone = $request->phone;
            $userCompany->website = $request->website;
            $userCompany->update();

            if ($request->provider == '1' && is_array($request->lienProviders)) {

                $this->validate(
                    $request,
                    ['lienProviders' => 'required']
                );

                MemberLienMap::where('user_id', $request->user_id)->delete();

                foreach ($request->lienProviders as $key => $lienProviders) {
                    $memberLienMap = new MemberLienMap;
                    $memberLienMap->user_id = $request->user_id;
                    $memberLienMap->lien_id = $lienProviders;
                    $memberLienMap->save();
                }
            } else {

                MemberLienMap::where('user_id', $request->user_id)->delete();
            }

            return redirect()->back()->with('success-update', 'Updated Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }


    /**
     * Update Member Profile
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfileModalAction(Request $request)
    {
        // return $request->all();
        DB::beginTransaction();
        try {
            $userDetails = UserDetails::where('user_id', $request->user_id)->firstOrFail();
            $userCompany = $userDetails->getCompany;
            $userDetails->company = $request->company;
            $userDetails->first_name = $request->fname;
            $userDetails->last_name = $request->lname;
            $userDetails->address = $request->address;
            $userDetails->city = $request->city;
            $userDetails->state_id = $request->state;
            $userDetails->zip = $request->zip;
            $userDetails->phone = $request->phone;
            $userDetails->lien_status = $request->providers;
            $userDetails->update();

            $user = User::where('id', $request->user_id)->firstOrFail();
            $user->name = $request->fname . " " . $request->lname;
            if (($request->has('password')) && $request->password != '') {
                $user->password = $request->password;
            }
            $user->update();

            $userCompany->company = $request->company;
            $userCompany->address = $request->address;
            $userCompany->city = $request->city;
            $userCompany->state_id = $request->state;
            $userCompany->zip = $request->zip;
            // $userCompany->phone = $request->phone;
            // $userCompany->website = $request->website;
            $userCompany->update();

            if ($request->triggerPayment == 'true') {
                $memberPackageDetails = $user->getPaymentDetails;
                if (is_null($memberPackageDetails)) {
                    $memberPackageDetails = new MemberPackage();
                    $memberPackageDetails->user_id = $user->id;
                }
                $memberPackageDetails->package_id = $request->packageType;
                $memberPackageDetails->period = $request->period;
                $memberPackageDetails->membership = $request->memberType;
                $memberPackageDetails->package_state = $request->allStates == '0' ? (isset($request->packageState) ? $request->packageState : implode(',', $request->packageStateMultiple)) : count(State::all());
                $memberPackageDetails->package_cost = $request->packageCost;
                $memberPackageDetails->billing_info_same = isset($request->billing_info) ? ($request->billing_info == 'true' ? '1' : '0') : '0';

                $memberBilling = $user->getBillingAddress;
                if (is_null($memberBilling)) {
                    $memberBilling = new MemberBillingAddress();
                    $memberBilling->user_id = $user->id;
                }
                $memberBilling->first_name = $request->fname;
                $memberBilling->last_name = $request->lname;
                $memberBilling->address = $request->address;
                $memberBilling->city = $request->city;
                $memberBilling->state_id = $request->state;
                $memberBilling->zip = $request->zip;
                $memberBilling->phone = $request->phone;
                $memberBilling->save();

                //if($request->triggerPayment == 'true') {
                $memberSubscription = $user->subscriptions()->whereNull('ends_at')->first();
                if (!is_null($memberSubscription)) {
                    if ($request->allStates == '0') {
                        if (isset($request->packageState)) {
                            /* $user->newSubscription('One State',$request->plan)->create($token->id, [
                        'metadata' => [
                        'id' => $user->id
                    ]
                ]);*/
                            $user->subscription($memberSubscription->name)->swap($request->plan, [
                                'metadata' => [
                                    'id' => $user->id
                                ]
                            ]);
                        } else if (isset($request->packageStateMultiple)) {
                            /*$user->newSubscription('Two-Five States',$request->plan.'2')->create($token->id, [
                'metadata' => [
                'id' => $user->id
            ]
        ]);*/
                            $user->subscription($memberSubscription->name)->swap($request->plan . '2', [
                                'metadata' => [
                                    'id' => $user->id
                                ]
                            ]);
                        }
                    } else {
                        /* $user->newSubscription('All States',$request->plan.'3')->create($token->id, [
    'metadata' => [
    'id' => $user->id
]
]);*/
                        $user->subscription($memberSubscription->name)->swap($request->plan . '3', [
                            'metadata' => [
                                'id' => $user->id
                            ]
                        ]);
                    }

                    $memberPackageDetails->stripe_id = $user->subscriptions()->whereNull('ends_at')->first()->stripe_id;
                    $memberPackageDetails->save();
                } else if (is_null($memberSubscription)) {
                    Stripe::setApiKey(config('services.stripe.secret'));
                    $token = Token::create([
                        "card" => [
                            "number" => $request->cardNumber,
                            "exp_month" => $request->expMonth,
                            "exp_year" => $request->expYear,
                            "cvc" => $request->cvvNumber
                        ]
                    ]);

                    if ($request->allStates == '0') {
                        if (isset($request->packageState)) {
                            $user->newSubscription('One State', $request->plan)->create($token->id, [
                                'metadata' => [
                                    'id' => $user->id
                                ]
                            ]);
                        } else if (isset($request->packageStateMultiple)) {
                            $user->newSubscription('Two-Five States', $request->plan . '2')->create($token->id, [
                                'metadata' => [
                                    'id' => $user->id
                                ]
                            ]);
                        }
                    } else {
                        $user->newSubscription('All States', $request->plan . '3')->create($token->id, [
                            'metadata' => [
                                'id' => $user->id
                            ]
                        ]);
                    }

                    $memberPackageDetails->stripe_id = $user->subscriptions()->whereNull('ends_at')->first()->stripe_id;
                    $memberPackageDetails->save();
                }
                //}

                $memberPackageDetails->update();
            }

            if ($request->providers == '1' && is_array($request->lienProviders)) {

                $this->validate(
                    $request,
                    ['lienProviders' => 'required']
                );

                MemberLienMap::where('user_id', $request->user_id)->delete();

                foreach ($request->lienProviders as $key => $lienProviders) {
                    $memberLienMap = new MemberLienMap;
                    $memberLienMap->user_id = $request->user_id;
                    $memberLienMap->lien_id = $lienProviders;
                    $memberLienMap->save();
                }
            } else {

                MemberLienMap::where('user_id', $request->user_id)->delete();
                $defaultLien = LienProvider::where('is_deletable', '0')->first();
                $memberLienMap = new MemberLienMap;
                $memberLienMap->user_id = $user->id;
                $memberLienMap->lien_id = $defaultLien->id;
                $memberLienMap->save();
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Profile Updated'
            ], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        } catch (ModelNotFoundException $exception) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function cancelSubscription(Request $request)
    {
        try {

            $user = User::findOrFail($request->user_id);
            $memberSubscription = $user->subscriptions()->whereNull('ends_at')->first();
            $user->subscription($memberSubscription->name)->cancel();
            $user->getPaymentDetails()->delete();
            $user->getBillingAddress()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Subscription cancelled successfully!'
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        } catch (ModelNotFoundException $exception) {

            return response()->json([
                'status' => false,
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * Update Sub User Profile
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSubUserProfileAction(Request $request)
    {
        $this->validate($request, [
            'companyName' => 'required',
            'firstName' => 'required',
            'lastName' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'member' => 'required',
            'zip' => 'required',
            'phone' => 'required',
        ]);
        try {
            $userDetails = UserDetails::where('user_id', $request->user_id)->firstOrFail();
            $userDetails->company = $request->companyName;
            $userDetails->first_name = $request->firstName;
            $userDetails->last_name = $request->lastName;
            $userDetails->address = $request->address;
            $userDetails->city = $request->city;
            $userDetails->state_id = $request->state;
            $userDetails->zip = $request->zip;
            $userDetails->phone = $request->phone;
            $userDetails->website = $request->website;

            $userCompany = $userDetails->user->mapcompanyContacts;
            $userCompany->address = $request->address;
            $userCompany->city = $request->city;
            $userCompany->state_id = $request->state;
            $userCompany->zip = $request->zip;
            $userCompany->phone = $request->phone;
            //$userCompany->website = $request->website;

            $userDetails->update();
            $userCompany->update();

            $user = User::where('id', $request->user_id)->firstOrFail();
            $user->name = $request->firstName . " " . $request->lastName;
            $user->parent_id = $request->member;
            $user->update();

            return redirect()->back()->with('success-update', 'Updated Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Delete member
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMember(Request $request)
    {
        try {

            $user = User::findOrFail($request->id);
            $user->delete();

            $userDetails = UserDetails::where('user_id', $request->id);
            $userDetails->delete();

            return response()->json([
                'status' => true,
                'message' => 'Deleted successfully'
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
     * Add a Agency user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addAgency(Request $request)
    {
        try {
            $email = User::where('email', $request->email)->count();
            if ($email == '0') {
                $userName = User::where('user_name', $request->user_name)->count();
                if ($userName == '0') {
                    $user = new User();
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->user_name = $request->user_name;
                    $user->password = $request->password;
                    $user->role = Role::where('type', 'Agency user')->firstOrFail()->id;
                    $user->save();

                    return response()->json([
                        'status' => true,
                        'type' => 'success',
                        'message' => 'Agency created'
                    ], 201);
                } else {
                    return response()->json([
                        'status' => false,
                        'type' => 'user_name',
                        'message' => 'User name already exists'
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'type' => 'email',
                    'message' => 'Email already exists'
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => $exception->getMessage()
            ], 200);
        }
    }

    /**
     * Add member from admin
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addMember(Request $request)
    {
        DB::beginTransaction();
        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $email = User::where('email', $request->email)->count();
            if ($email == '0') {
                $userName = User::where('user_name', $request->user_name)->count();
                if ($userName == '0') {
                    $user = new User();
                    $user->name = $request->fname . " " . $request->lname;
                    $user->email = $request->email;
//                    $user->user_name = $request->user_name;
                    $user->password = $request->password;
                    $user->role = Role::where('type', 'Member')->firstOrFail()->id;
                    $user->save();

                    if (isset($user->id)) {

                        $company = new Company();
                        $company->user_id = $user->id;
                        $company->company = $request->company;
                        $company->address = $request->address;
                        $company->city = $request->city;
                        $company->state_id = $request->state;
                        $company->zip = $request->zip;
                        $company->save();

                        $userDetails = new UserDetails();
                        $userDetails->user_id = $user->id;
                        $userDetails->company = $request->company;
                        $userDetails->company_id = $company->id;
                        $userDetails->first_name = $request->fname;
                        $userDetails->last_name = $request->lname;
                        $userDetails->address = $request->address;
                        $userDetails->city = $request->city;
                        $userDetails->state_id = $request->state;
                        $userDetails->zip = $request->zip;
                        $userDetails->phone = $request->phone;
                        $userDetails->lien_status = $request->providers;
                        $userDetails->save();


                        $memberPackageDetails = new MemberPackage();
                        $memberPackageDetails->user_id = $user->id;
                        $memberPackageDetails->package_id = $request->packageType;
                        $memberPackageDetails->period = $request->period;
                        $memberPackageDetails->membership = $request->memberType;
                        $memberPackageDetails->package_state = $request->allStates == '0' ? (isset($request->packageState) && !is_null($request->packageState) ? $request->packageState : implode(',', $request->packageStateMultiple)) : (!is_null($request->packageStateMultiple) ? implode(',', $request->packageStateMultiple) : count(State::all()));
                        $memberPackageDetails->package_cost = $request->packageCost;
                        $memberPackageDetails->billing_info_same = isset($request->billing_info) ? ($request->billing_info == 'true' ? '1' : '0') : '0';

                        $memeberBilling = new MemberBillingAddress();
                        $memeberBilling->user_id = $user->id;
                        $memeberBilling->first_name = $request->fname;
                        $memeberBilling->last_name = $request->lname;
                        $memeberBilling->address = $request->address;
                        $memeberBilling->city = $request->city;
                        $memeberBilling->state_id = $request->state;
                        $memeberBilling->zip = $request->zip;
                        $memeberBilling->phone = $request->phone;
                        $memeberBilling->save();

                        $token = Token::create([
                            "card" => [
                                "number" => $request->cardNumber,
                                "exp_month" => $request->expMonth,
                                "exp_year" => $request->expYear,
                                "cvc" => $request->cvvNumber
                            ]
                        ]);

                        if ($request->allStates == '0') {
                            if (isset($request->packageState)) {
                                $user->newSubscription('One State', $request->plan)->create($token->id, [
                                    'metadata' => [
                                        'id' => $user->id
                                    ]
                                ]);
                            } else if (isset($request->packageStateMultiple)) {
                                $user->newSubscription('Two-Five States', $request->plan . '2')->create($token->id, [
                                    'metadata' => [
                                        'id' => $user->id
                                    ]
                                ]);
                            }
                        } else {
                            $user->newSubscription('All States', $request->plan . '3')->create($token->id, [
                                'metadata' => [
                                    'id' => $user->id
                                ]
                            ]);
                        }

                        $memberPackageDetails->stripe_id = $user->subscriptions()->whereNull('ends_at')->first()->stripe_id;
                        $memberPackageDetails->save();

                        if ($request->providers == '1' && is_array($request->lienProviders)) {

                            foreach ($request->lienProviders as $key => $lienProviders) {

                                $memberLienMap = new MemberLienMap;
                                $memberLienMap->user_id = $user->id;
                                $memberLienMap->lien_id = $lienProviders;
                                $memberLienMap->save();
                            }
                        } else {

                            $defaultLien = LienProvider::where('is_deletable', '0')->first();
                            $memberLienMap = new MemberLienMap;
                            $memberLienMap->user_id = $user->id;
                            $memberLienMap->lien_id = $defaultLien->id;
                            $memberLienMap->save();
                        }
                    }

                    DB::commit();
                    return response()->json([
                        'status' => true,
                        'type' => 'success',
                        'message' => 'Member created'
                    ], 201);
                } else {
                    DB::rollback();
                    return response()->json([
                        'status' => false,
                        'type' => 'user_name',
                        'message' => 'User name already exists'
                    ], 200);
                }
            } else {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'type' => 'email',
                    'message' => 'Email already exists'
                ], 200);
            }
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => $exception->getMessage()
            ], 200);
        }
    }

    /**
     * Add member from admin
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addSubUser(Request $request)
    {
        try {

            $email = User::where('email', $request->email)->count();
            if ($email == '0') {
                $userName = User::where('user_name', $request->user_name)->count();
                if ($userName == '0') {
                    $user = new User();
                    $user->name = $request->fname . " " . $request->lname;
                    $user->email = $request->email;
                    $user->user_name = $request->user_name;
                    $user->password = $request->password;
                    $user->role = Role::where('type', 'Sub-Member')->firstOrFail()->id;
                    $user->parent_id = $request->member;
                    $user->save();

                    if (isset($user->id)) {
                        $userDetails = new UserDetails();
                        $userDetails->user_id = $user->id;
                        $userDetails->company = $request->company;
                        $userDetails->company_id = $request->company_id;
                        $userDetails->first_name = $request->fname;
                        $userDetails->last_name = $request->lname;
                        $userDetails->address = $request->address;
                        $userDetails->city = $request->city;
                        $userDetails->state_id = $request->state;
                        $userDetails->zip = $request->zip;
                        $userDetails->phone = $request->phone;
                        $userDetails->save();

                        $mapSubUser = new MapCompanyContact();
                        $mapSubUser->company_id = $request->company_id;
                        $mapSubUser->user_id = $user->id;
                        $mapSubUser->is_user = '1';
                        $mapSubUser->address = $request->address;
                        $mapSubUser->city = $request->city;
                        $mapSubUser->state_id = $request->state;
                        $mapSubUser->zip = $request->zip;
                        $mapSubUser->phone = $request->phone;
                        $mapSubUser->save();
                    }

                    return response()->json([
                        'status' => true,
                        'type' => 'success',
                        'message' => 'Sub user created'
                    ], 201);
                } else {
                    return response()->json([
                        'status' => false,
                        'type' => 'user_name',
                        'message' => 'User name already exists'
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'type' => 'email',
                    'message' => 'Email already exists'
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => $exception->getMessage()
            ], 200);
        }
    }

    /**
     * Update User Status from Admin
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userStatus(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);
            if ($request->status == '0') {
                $user->status = '1';
            } else {
                $user->status = '0';
            }
            $user->update();
            return response()->json([
                'status' => true,
                'type' => 'success',
                'message' => 'Status changed successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'type' => 'error',
                'message' => $exception->getMessage()
            ], 200);
        }
    }

    /**
     * Autocomplete company details for the member section.
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * Autocomplete company name
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autocompleteCompany(Request $request)
    {
        try {

            $company = Company::find($request->key);
            return response()->json([
                'success'   => true,
                'data' => $company
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $e->getMessage()
            ], 500);
        } catch (ModelNotFoundException $exception) {

            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $exception->getMessage()
            ], 500);
        } catch (QueryException $exception) {

            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanyDetails(Request $request)
    {
        try {

            $member = User::findOrFail($request->member_id);
            $companyDetails = $member->details->getCompany;
            return response()->json([
                'success'   => true,
                'data' => $companyDetails
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $e->getMessage()
            ], 200);
        } catch (ModelNotFoundException $exception) {

            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $exception->getMessage()
            ], 200);
        } catch (QueryException $exception) {

            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $exception->getMessage()
            ], 200);
        }
    }

    /**
     * Returns the member plan page.
     * @return View
     */
    public function getPlans()
    {
        try {
            $packages = PackagePrice::all();
            return view('admin.plan.members', [
                'packages' => $packages
            ]);
        } catch (Exception $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
        } catch (ModelNotFoundException $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
        } catch (QueryException $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
        }
    }

    /**
     * Updates the member plan
     * @param Request $request
     * @return View
     */
    public function updatePlan(Request $request)
    {
        try {
            $plans = $request->price;
            foreach ($plans as $id => $plan) {
                $price = PackagePrice::find($id);
                $price->price = $plan['monthly'];
                $price->update();
            }

            return redirect()->route('admin.member.plans')->with('success', 'Price updated successfully.');
        } catch (Exception $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
        } catch (ModelNotFoundException $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
        } catch (QueryException $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
        }
    }
}
