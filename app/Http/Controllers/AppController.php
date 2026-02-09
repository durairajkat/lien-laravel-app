<?php

namespace App\Http\Controllers;

use Image;
use App\User;
use App\Models\State;
use App\Models\Company;
use App\Models\JobInfo;
use App\Models\UserDetails;
use App\Models\LienProvider;
use Illuminate\Http\Request;
use App\Models\ProjectDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Http\Requests\AdminProfileRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class AppController for Root Use
 * @package App\Http\Controllers
 */
class AppController extends Controller
{
    /**
     * Return Admin dashboard
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $projects = ProjectDetail::count();
        $members = User::where('role', 5)->count();
        $subMembers = User::where('role', 6)->count();
        $jobInfos = JobInfo::count();
        $lienProviders = LienProvider::count();
        return view('dashboard.dashboard', [
            'projects' => $projects,
            'members' => $members,
            'subMembers' => $subMembers,
            'jobInfos' => $jobInfos,
            'lienProviders' => $lienProviders
        ]);
    }

    /**
     * Return Privacy Policy view for member
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function privacy()
    {
        return view('basicUser.layout.privacy_policy');
    }

    /**
     * Return Terms of Use view for member
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function terms()
    {
        return view('basicUser.layout.terms_of_use');
    }

    /**
     * Admin profile view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function adminProfile()
    {

        try {

            $adminUser = Auth::user();
            $adminDetails = $adminUser->details;
            $states =  State::pluck('name', 'id')->toArray();
            $companies = Company::pluck('company', 'id')->toArray();

            return view('admin.profile.index', [
                'admin' => $adminUser,
                'adminDetails' => $adminDetails,
                'states' => $states,
                'companies' => $companies
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        } catch (QueryException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Update admin profile details
     * @param AdminProfileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adminProfileUpdate(AdminProfileRequest $request)
    {

        $admin = User::findOrFail($request->admin_id);
        $admin->email = $request->email;
        $admin->update();

        $adminDetails = $admin->details;
        if (is_null($adminDetails)) {

            $this->createDetails($request);
        } else {

            $this->updateDetails($request, $adminDetails);
        }

        return redirect()->route('admin.profile')->with('success-update', 'User updated successfully!');
    }

    /**
     * Create Admin Details
     * @param Request $request
     */
    protected function createDetails(Request $request)
    {

        $details = new UserDetails();
        $details->user_id = $request->admin_id;
        $details->company = $request->user_company;
        $details->first_name = $request->first_name;
        $details->last_name = $request->last_name;
        $details->address = $request->user_address;
        $details->city = $request->user_city;
        $details->state_id = $request->user_state;
        $details->zip = $request->zip;
        $details->phone = $request->phone;
        $details->website = $request->website;
        $details->office_phone = $request->office_phone;
        $details->image = $this->createAvatarThumbnail($request);
        $details->save();
    }

    /**
     * Update admin details
     * @param Request $request
     * @param $adminDetails
     */
    protected function updateDetails(Request $request, $adminDetails)
    {

        $adminDetails->company = $request->company_name;
        $adminDetails->company_id = $request->user_company;
        $adminDetails->first_name = $request->first_name;
        $adminDetails->last_name = $request->last_name;
        $adminDetails->address = $request->user_address;
        $adminDetails->city = $request->user_city;
        $adminDetails->state_id = $request->user_state;
        $adminDetails->zip = $request->zip;
        $adminDetails->phone = $request->phone;
        $adminDetails->website = $request->website;
        $adminDetails->office_phone = $request->office_phone;
        $image = $this->createAvatarThumbnail($request, true);
        if (gettype($image) !== "boolean") {
            $adminDetails->image = $image;
        }
        $adminDetails->update();
    }

    /**
     * Creates the image thumbnail of the user
     * @param $request
     */
    protected function createAvatarThumbnail($request, $edit = false)
    {
        $image = (isset($request->avatar) && !empty($request->avatar && $request->hasFile('avatar'))) ? $request->avatar : null;
        $imagefileName = null;

        if (!is_null($image)) {

            if (!file_exists(config('app.image_storage_path.avatar'))) {
                mkdir(config('app.image_storage_path.avatar'), 0777, true);
            }

            if (!file_exists(config('app.image_storage_path.avatar_thumbnails'))) {
                mkdir(config('app.image_storage_path.avatar_thumbnails'), 0777, true);
            }

            if ($image->isValid()) {
                $extension = $image->getClientOriginalExtension();
                $imagefileName = uniqid() . time() . '.' . $extension;
                $thumbnailImg = Image::make($image)->resize(150, 150)->save(config('app.image_storage_path.avatar_thumbnails') . $imagefileName);
                $image->move(config('app.image_storage_path.avatar'), $imagefileName);
                $newFile = config('app.image_storage_path.avatar') . $imagefileName;
                $thumbFile = config('app.image_storage_path.avatar_thumbnails') . $imagefileName;
                chmod($newFile, 0777);
                chmod($thumbFile, 0777);
                return $imagefileName;
            }
        }
        return ($edit) ? false : null;
    }
}
