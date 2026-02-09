<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Validator;
use App\Models\State;
use App\Models\Company;
use App\Models\Contact;
use App\Models\ContactUs;
use App\Models\ProjectRole;
use App\Models\UserDetails;
use App\Models\ContactPhone;
use Illuminate\Http\Request;
use App\Models\ProjectDetail;
use App\Models\CompanyContact;
use App\Models\MapCompanyContact;
use App\Models\ContactInformation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\QueryException;
use App\Models\ProjectIndustryContactMap;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ContactController for handel all contact system
 * @package App\Http\Controllers
 */
class ContactController extends Controller
{
    /**
     * Get Customer Contact page form member area
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCustomerContacts(Request $request)
    {
        try {
            $paginate = $request->input('paginate');
            $sortWith = $request->input('sortWith');
            $sortBy = $request->input('sortBy');
            $search = $request->input('search');
            $states = State::all();
            //$companies = Company::pluck('company','id');
            $companies = Company::whereHas('mapContactCompany.getContacts', function ($query) {
                $query->where('type', '0');
            })->pluck('company', 'id');
            $firstNames = UserDetails::pluck('first_name', 'id');
            $customerContacts = Auth::user()->company_contacts()->where('type', '=', '0');
            if (isset($search)) {
                $query = $customerContacts
                    ->join('map_company_contacts', 'map_company_contacts.company_contact_id', '=', 'company_contacts.id')
                    ->join('companies', 'companies.id', '=', 'map_company_contacts.company_id')
                    ->select('company_contacts.*', 'companies.company', 'map_company_contacts.company_id', 'map_company_contacts.company_contact_id')
                    ->where([['company_contacts.type', '=', '0'], ['company_contacts.contact_type', 'LIKE', '%' . $search . '%']])
                    ->orWhere([['company_contacts.type', '=', '0'], ['company_contacts.title', 'LIKE', '%' . $search . '%']])
                    ->orWhere([['company_contacts.type', '=', '0'], ['company_contacts.first_name', 'LIKE', '%' . $search . '%']])
                    ->orWhere([['company_contacts.type', '=', '0'], ['company_contacts.last_name', 'LIKE', '%' . $search . '%']])
                    ->orWhere([['company_contacts.type', '=', '0'], [DB::raw("concat(company_contacts.first_name, ' ', company_contacts.last_name)"), 'LIKE', '%' . $search . '%']])
                    ->orWhere([['company_contacts.type', '=', '0'], ['company_contacts.phone', 'LIKE', '%' . $search . '%']])
                    ->orWhere([['company_contacts.type', '=', '0'], ['company_contacts.cell', 'LIKE', '%' . $search . '%']])
                    ->orWhere([['company_contacts.type', '=', '0'], ['company_contacts.email', 'LIKE', '%' . $search . '%']])
                    ->orWhere([['company_contacts.type', '=', '0'], ['companies.company', 'LIKE', '%' . $search . '%']])
                    ->orWhere([['company_contacts.type', '=', '0'], ['company_contacts.contact_type', 'LIKE', '%' . $search . '%']]);
                $customerContacts = $query;
            }
            if (isset($sortWith) && isset($sortBy)) {
                switch ($sortWith) {
                    case 'customer_name':
                        $customerContacts->leftJoin('map_company_contacts', function ($join) {
                            $join->on('map_company_contacts.company_contact_id', '=', 'company_contacts.id');
                        })->leftJoin('companies', function ($join) {
                            $join->on('map_company_contacts.company_id', '=', 'companies.id');
                        })->select('company_contacts.*', 'companies.company')->orderBy('companies.company', $sortBy);

                        break;

                    default:
                        $customerContacts = $customerContacts->orderBy($sortWith, $sortBy);
                        break;
                }
                //$customerContacts = $customerContacts->orderBy($sortWith,$sortBy);
            } else {
                $customerContacts = $customerContacts->orderBy('created_at', 'desc');
            }
            /*$customerContacts = Contact::where('type', '0')
                ->where('user_id', Auth::user()->id)
                ->orderBy('created_at', 'desc')->paginate(5);*/
            /*$customerContacts = Auth::user()->companies()->whereHas('mapContactCompany.contacts',function ($query) {
                $query->where('type','=','0');
            })->orderBy('created_at', 'desc')->paginate(5);*/

            if (isset($paginate) && !empty($paginate)) {
                $customerContacts = $customerContacts->paginate($paginate);
                //$customerContacts = Auth::user()->company_contacts()->where('type','=','0')->orderBy('created_at', 'desc')->paginate($paginate);
            } else {
                $customerContacts = $customerContacts->paginate(5);
                //$customerContacts = Auth::user()->company_contacts()->where('type','=','0')->orderBy('created_at', 'desc')->paginate(5);
            }
            return view('basicUser.contacts.customer', [
                'states' => $states,
                'contacts' => $customerContacts,
                'companies' => $companies,
                'first_names' => $firstNames
            ]);
        } catch (Exception $e) {
            return view('errors.exceptions', [
                'exception' => $e
            ]);
            //return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            return view('errors.exceptions', [
                'exception' => $e
            ]);
            //return redirect()->back()->with('try-error', $e->getMessage());
        }
    }

    /**
     * Get Customer Contact page form member area
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getUserContacts()
    {
        $states = State::pluck('name', 'id');
        $sub_user = User::where('parent_id', Auth::user()->id)->paginate(5);
        $user = Auth::user();

        return view('basicUser.contacts.users', [
            'sub_users' => $sub_user,
            'states' => $states,
            'user'  => $user
        ]);
    }

    /**
     * Get Industry Contact Page from member area
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getIndustryContacts()
    {
        try {
            $paginate = request()->get('paginate');
            $sortWith = request()->get('sortWith');
            $sortBy = request()->get('sortBy');
            $search = request()->get('search');
            $states = State::all();
            // $companies = Company::pluck('company','id');
            $firstNames = UserDetails::pluck('first_name', 'id');
            $companies = Company::whereHas('mapContactCompany.getContacts', function ($query) {
                $query->where('type', '1');
            })->pluck('company', 'id');

            /*$contacts = Contact::where('type', '1')
            ->where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')->paginate(5);*/
            /*$contacts  = Auth::user()->companies()->whereHas('mapContactCompany.contacts',function ($query) {
            $query->where('type','=','1');
        })->orderBy('created_at', 'desc')->paginate(5);*/

            $industryContacts = Auth::user()->company_contacts()->where('company_contacts.type', '1');
            $industryContacts = $industryContacts->join('map_company_contacts', 'map_company_contacts.company_contact_id', '=', 'company_contacts.id')
                ->join('companies', 'companies.id', '=', 'map_company_contacts.company_id')
                ->select('company_contacts.*', 'companies.company', 'map_company_contacts.company_id', 'map_company_contacts.company_contact_id');
            if (isset($search)) {
                $query = $industryContacts
                    ->join('map_company_contacts', 'map_company_contacts.company_contact_id', '=', 'company_contacts.id')
                    ->join('companies', 'companies.id', '=', 'map_company_contacts.company_id')
                    ->select('company_contacts.*', 'companies.company', 'map_company_contacts.company_id', 'map_company_contacts.company_contact_id')
                    ->where([['company_contacts.type', '=', '1'], ['company_contacts.contact_type', 'LIKE', '%' . $search . '%']])
                    ->orWhere([['company_contacts.type', '=', '1'], ['company_contacts.title', 'LIKE', '%' . $search . '%']])
                    ->orWhere([['company_contacts.type', '=', '1'], ['company_contacts.first_name', 'LIKE', '%' . $search . '%']])
                    ->orWhere([['company_contacts.type', '=', '1'], ['company_contacts.last_name', 'LIKE', '%' . $search . '%']])
                    ->orWhere([['company_contacts.type', '=', '1'], [DB::raw("concat(company_contacts.first_name, ' ', company_contacts.last_name)"), 'LIKE', '%' . $search . '%']])
                    ->orWhere([['company_contacts.type', '=', '1'], ['company_contacts.phone', 'LIKE', '%' . $search . '%']])
                    ->orWhere([['company_contacts.type', '=', '1'], ['company_contacts.cell', 'LIKE', '%' . $search . '%']])
                    ->orWhere([['company_contacts.type', '=', '1'], ['company_contacts.email', 'LIKE', '%' . $search . '%']])
                    ->orWhere([['company_contacts.type', '=', '1'], ['companies.company', 'LIKE', '%' . $search . '%']])
                    ->orWhere([['company_contacts.type', '=', '1'], ['company_contacts.contact_type', 'LIKE', '%' . $search . '%']]);
                $industryContacts = $query;
            }
            if (isset($sortWith) && isset($sortBy)) {
                switch ($sortWith) {
                    case 'industry_name':
                        $industryContacts->leftJoin('map_company_contacts', function ($join) {
                            $join->on('map_company_contacts.company_contact_id', '=', 'company_contacts.id');
                        })->leftJoin('companies', function ($join) {
                            $join->on('map_company_contacts.company_id', '=', 'companies.id');
                        })->select('company_contacts.*', 'companies.company')->orderBy('companies.company', $sortBy);

                        break;

                    default:
                        $industryContacts = $industryContacts->orderBy($sortWith, $sortBy);
                        break;
                }
                //$industryContacts = $industryContacts->orderBy($sortWith,$sortBy);
            } else {
                $industryContacts = $industryContacts->orderBy('created_at', 'desc');
            }

            if (isset($paginate) && !empty($paginate)) {
                $industryContacts = $industryContacts->paginate($paginate);
                //$customerContacts = Auth::user()->company_contacts()->where('type','=','0')->orderBy('created_at', 'desc')->paginate($paginate);
            } else {
                $industryContacts = $industryContacts->paginate(5);
                //$customerContacts = Auth::user()->company_contacts()->where('type','=','0')->orderBy('created_at', 'desc')->paginate(5);
            }

            // $industryContacts = Auth::user()->company_contacts()->where('type','=','1')->orderBy('created_at', 'desc')->paginate(5);

            return view('basicUser.contacts.industry', [
                'states' => $states,
                'contacts' => $industryContacts,
                'companies' => $companies,
                'first_names' => $firstNames
            ]);
        } catch (Exception $e) {
            return view('errors.exceptions', [
                'exception' => $e
            ]);
            //return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $e) {
            return view('errors.exceptions', [
                'exception' => $e
            ]);
            //return redirect()->back()->with('try-error', $e->getMessage());
        }
    }

    /**
     * Member view Contact us form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getContactUs()
    {
        $queryRole = ProjectRole::orderBy('project_roles', 'ASC')->get();
        return view('basicUser.user.contact_us', [
            'role' => $queryRole
        ]);
    }

    /**
     * Post Contact us form
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postContactUs(Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
            'department' => 'required'
        ]);
        try {
            $contactUs = new ContactUs();
            $contactUs->user_id = $request->user_id;
            $contactUs->department = $request->department;
            $contactUs->message = $request->message;
            $contactUs->save();

            $tmp_msg = "User ID: " . $request->user_id  .
                "\r\n Department : " . $request->department  .
                "\r\n Message : " . $request->message;

            //            \mail('aalogic@gmail.com', 'Get help', $tmp_msg);


            // mailtrap authentication required
            Mail::raw($tmp_msg, function ($message) {
                $message->from('info@nlb.org')->subject('Get Help')->to('casey@slynerds.com');
            });

            return redirect()->route('member.contact.us')->with('success', 'Message Send');
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }
    
    public function postContact(Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
            'email' => 'required',
            'name' => 'required'
        ]);
        
        try {
            //$contactUs = new ContactUs();
            //$contactUs->user_id = $request->user_id;
            //$contactUs->department = $request->department;
            //$contactUs->message = $request->message;
            //$contactUs->save();

            $tmp_msg = "Name " . $request->name  .
                "\r\n Email : " . $request->email  .
                "\r\n Message : " . $request->message;

            //            \mail('aalogic@gmail.com', 'Get help', $tmp_msg);


            // mailtrap authentication required
            Mail::raw($tmp_msg, function ($message) {
                $message->subject('Get Help - Lien Manager')
                        ->to('info@mechanicslien.com');
            });


            return redirect()->route('contact')->with('success', 'Message Send');
        } catch (Exception $e) {
           var_dump($e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Submit Add & Edit contact form
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitContact(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                // 'company' => 'required',
                // 'email' => 'required|email',
                // 'phone' => 'numeric',
                // 'fax' => 'numeric'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid data given'
                ], 200);
            }
            if ($request->type == 'edit') {
                $contact = Contact::findOrFail($request->id);
                $contactPhone = ContactInformation::where('contact_id', $contact->id)->delete();
            } else {
                $contact = new Contact();
                $contact->user_id = $request->user_id;
            }
            if ($request->contact == 'customer') {
                $contact->type = '0';
            } else {
                $contact->type = '1';
                $contact->contact_type = $request->contactType;
            }
            $contact->company = $request->company;
            $contact->website = $request->website;
            //$contact->first_name = $request->firstName;
            //$contact->last_name = $request->lastName;
            $contact->address = $request->address;
            $contact->city = $request->city;
            $contact->state_id = $request->state;
            $contact->zip = $request->zip;
            $contact->phone = $request->phone;
            $contact->fax = $request->fax;
            //$contact->email = $request->email;

            if ($request->type == 'edit') {
                $contact->update();
                $message = "Contact updated successfully";
            } else {
                $contact->save();
                $message = "Contact saved successfully";
                if (isset($request->formContactType) && $request->contact == 'industry') {
                    $map = new ProjectIndustryContactMap();
                    $map->projectId = $request->project_id;
                    $map->contactId = $contact->id;
                    $map->save();
                }
            }
            if (isset($request->title) && count($request->title) > 0) {
                foreach ($request->title as $key => $title) {
                    $newInformation = new ContactInformation();
                    $newInformation->contact_id = $contact->id;
                    $newInformation->title = $request->title[$key];
                    $newInformation->title_other = $request->titleOther[$key];
                    $newInformation->first_name = $request->firstName[$key];
                    $newInformation->last_name = $request->lastName[$key];
                    $newInformation->email = $request->email[$key];
                    $newInformation->direct_phone = $request->directPhone[$key];
                    $newInformation->cell = $request->cell[$key];
                    $newInformation->save();
                }
            }
            return response()->json([
                'status' => true,
                'message' => $message,
                'company' => $contact->company,
                'id' => $contact->id
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

    public function createContact(Request $request)
    {
        try {
            if ($request->type == 'edit') {
                $company = Company::findOrFail($request->id);
            } else {
                $company = new Company();
            }

            $company->user_id = $request->user_id;
            $company->company = $request->company;
            $company->website = $request->website;
            $company->address = $request->address;
            $company->city = $request->city;
            $company->state_id = $request->state;
            $company->zip = $request->zip;
            $company->phone = $request->phone;
            $company->fax = $request->fax;

            if ($request->type == 'edit') {
                $company->update();
                $message = "Contact updated successfully";
            } else {
                $company->save();
                $message = "Contact saved successfully";
            }

            if (isset($request->title) && count($request->title) > 0) {
                foreach ($request->title as $key => $title) {
                    $addUserFlagWhenEdited = false;
                    if ($request->type == 'edit') {
                        $companyContact = CompanyContact::find($request->contactId[$key]);
                        if (is_null($companyContact)) {
                            $companyContact = new CompanyContact();
                            $companyContact->user_id = $request->user_id;
                            $addUserFlagWhenEdited = true;
                        }
                    } else {
                        $companyContact = new CompanyContact();
                        $companyContact->user_id = $request->user_id;
                    }
                    $companyContact->contact_type = (isset($request->contactType) && !is_null($request->contactType)) ? $request->contactType : null;
                    $companyContact->type = $request->contact;
                    $companyContact->title = $request->title[$key];
                    $companyContact->title_other = $request->titleOther[$key];
                    $companyContact->first_name = $request->firstName[$key];
                    $companyContact->last_name = $request->lastName[$key];
                    $companyContact->email = $request->email[$key];
                    $companyContact->phone = $request->directPhone[$key];
                    $companyContact->cell = $request->cell[$key];
                    $companyContact->save();

                    if ($request->type != 'edit' || $addUserFlagWhenEdited) {
                        $map = new MapCompanyContact();
                        $map->company_id = $company->id;
                        $map->company_contact_id = $companyContact->id;
                        $map->save();
                    }
                }
            }


            return response()->json([
                'status' => true,
                'message' => $message,
                'company' => $company->company,
                'id' => $company->id
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
     * Delete a contact
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteContacts(Request $request)
    {
        try {
            $this->validate($request, [
                'id' => 'required'
            ]);

            $contacts = Contact::findOrFail($request->id);
            /*$map = $contacts->mapContactCompany;
            if(!is_null($map)){
            $project = $map->getProject;
            $projecIndustryMap = ProjectIndustryContactMap::where('contactId',$map->id)->get();
            if(!is_null($project)) {
            $project->customer_contract_id = null;
            $project->update();
        }

        if(!is_null($projecIndustryMap)) {
        $projecIndustryMap->delete();
    }
    }
    $map->delete();*/
            $contacts->delete();

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
     * Delete a contact
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteContact(Request $request)
    {
        try {
            $this->validate($request, [
                'id' => 'required'
            ]);

            $contact = CompanyContact::findOrFail($request->id);
            $map = $contact->mapContactCompany;
            if (!is_null($map)) {
                $project = $map->getProject;
                $projecIndustryMap = ProjectIndustryContactMap::where('contactId', $map->id)->get();
                if (!is_null($project)) {
                    $project->customer_contract_id = null;
                    $project->update();
                }

                if (!is_null($projecIndustryMap)) {
                    foreach ($projecIndustryMap as $industrymap) {
                        $industrymap->delete();
                    }
                }
            }
            $map->delete();
            $contact->delete();

            /* $company = Company::findOrFail($request->id);
            $maps = $company->mapContactCompany;
            if(!is_null($maps)){
            foreach ($maps as $map) {
            $contact = $map->contacts;
            $contact->delete();
        }
    } else {

    return response()->json([
    'status' => false,
    'message' => 'Not Found.'
    ], 200);
    }*/

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
     * Get Contact Request Form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function contactRequest()
    {
        try {
            $contacts = ContactUs::orderBy('created_at', 'DESC')->paginate(15);
            return view('admin.contact.contact', [
                'contacts' => $contacts
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Delete Contact Request
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function contactRequestDelete(Request $request)
    {
        try {
            $this->validate($request, [
                'id' => 'required'
            ]);

            $contact = ContactUs::findOrFail($request->id);
            $contact->delete();

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
     * Get Reply Form For Send Mail
     * @param $contact_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function contactRequestReply($contact_id)
    {
        try {
            $contact = ContactUs::findOrFail($contact_id);
            return view('admin.contact.reply', [
                'contact' => $contact
            ]);
        } catch (Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        } catch (ModelNotFoundException $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        }
    }

    /**
     * Send Mail Action
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function contactRequestReplySend(Request $request)
    {
        try {
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $destinationPath = public_path() . '/upload/';
                $filename = time(32) . '.' . $file->getClientOriginalExtension();
                $upload_success = $file->move($destinationPath, $filename);
                if ($upload_success) {
                    $fileName = $filename;
                } else {
                    $fileName = '';
                }
            } else {
                $fileName = '';
            }
            $contact = ContactUs::findOrFail($request->contact_id);
            $contact->status = 1;
            $contact->replyMessage = $request->message;
            $contact->subject = $request->subject;
            $contact->fileName = $fileName;
            $email = $contact->user->email;
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
     * Autocomplete company name (My contacts)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autoCompleteCompanyDetails(Request $request)
    {
        try {

            //$data = Auth::user()->companies();
            $data = Company::query();
            if (isset($request->key) && $request->key != '') {
                $data->where('company', 'Like', '%' . $request->key . '%');
            }
            $datas = $data->with('user')->get();
            $result = [];
            foreach ($datas as $key => $data) {
                $result[$key]['id'] = $data->id;
                $result[$key]['company'] = $data->company;
                $result[$key]['data'] = $data;
            }
            return response()->json([
                'success'   => true,
                'data' => $result
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $e->getMessage()
            ], $e->getCode());
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $exception->getMessage()
            ], $exception->getCode());
        } catch (QueryException $exception) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $exception->getMessage()
            ], $exception->getCode());
        }
    }


    /**
     * Autocomplete company name (Admin)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autoCompleteAdminCompany(Request $request)
    {
        try {

            //$data = Auth::user()->companies();
            $data = Company::query();
            $result = null;
            if (isset($request->key) && $request->key != '') {
                $result = $data->findOrFail($request->key);
            }
            /*  $datas = $data->with('user')->get();
            $result = [];
            foreach ($datas as $key => $data) {
            $result[$key]['id'] = $data->id;
            $result[$key]['company'] = $data->company;
            $result[$key]['data'] = $data;
        }*/
            return response()->json([
                'success'   => true,
                'data' => $result
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $e->getMessage()
            ], $e->getCode());
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $exception->getMessage()
            ], $exception->getCode());
        } catch (QueryException $exception) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $exception->getMessage()
            ], $exception->getCode());
        }
    }

    /**
     * Autocomplete company name (My contacts)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autoCompleteCompanyDetailsOnRoleChange(Request $request)
    {
        try {

            //$data = Auth::user()->companies();
            $data = Company::query();
            if (isset($request->key) && $request->key != '' && !is_null($request->key)) {
                $role = $request->key;
                $data->whereHas('mapContactCompany.getContacts', function ($query) use ($role) {
                    $query->where('type', '1')->where('contact_type', $role);
                });
            } elseif (!isset($request->key)) {
                $data->whereHas('mapContactCompany.getContacts', function ($query) {
                    $query->where('type', '1');
                });
            }


            $datas = count($data->with('user')->get()) > 0 ? $data->with('user')->get() : $data->with('user')->whereHas('mapContactCompany.getContacts', function ($query) {
                $query->where('type', '1');
            })->get();
            $result = [];
            foreach ($datas as $key => $data) {
                $result[$key]['id'] = $data->id;
                $result[$key]['company'] = $data->company;
                $result[$key]['data'] = $data;
            }

            return response()->json([
                'success'   => true,
                'data' => $result
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $e->getMessage()
            ], $e->getCode());
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $exception->getMessage()
            ], $exception->getCode());
        } catch (QueryException $exception) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $exception->getMessage()
            ], $exception->getCode());
        }
    }

    /**
     * Fetches companies on the basis of industry contacts or customer contacts.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function fetchCompanies(Request $request)
    {
        try {
            $companyData = [];
            $com = Company::get();
            $companyCont = CompanyContact::get();
            $data = Company::query();
            $mapCompanyContact = MapCompanyContact::get();
            if (isset($request->type) && $request->type == 'customer') {
                $data->whereHas('mapContactCompany.getContacts', function ($query) {
                    $query->where('type', '0');
                });
            } else {
                $data->whereHas('mapContactCompany.getContacts', function ($query) {
                    $query->where('type', '1');
                });
            }

            $datas = $data->with('user')->get();
            $result = [];
            foreach ($datas as $key => $data) {
                $result[$key]['id'] = $data->id;
                $result[$key]['company'] = $data->company;
                $result[$key]['data'] = $data;
                // foreach in companies foreach in map company if company id = id foreach in company contacts where contact id = id add type
                foreach ($com as $company) {
                    if ($company->id === $data->id) {
                        foreach ($mapCompanyContact as $map) {
                            if ($map->company_id === $company->id) {
                                foreach ($companyCont as $contact) {
                                    if ($contact->id === $map->company_contact_id) {
                                        $result[$key]['type'] = $contact->contact_type;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return response()->json([
                'success'   => true,
                'data' => $result
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $e->getMessage()
            ], $e->getCode());
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $exception->getMessage()
            ], $exception->getCode());
        } catch (QueryException $exception) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $exception->getMessage()
            ], $exception->getCode());
        }
    }

    /**
     * Autocomplete company name (My contacts)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autoCompleteCompany(Request $request)
    {
        try {
            $company = Company::findOrFail($request->key);
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
     * Autocomplete first name (My contacts)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autoCompleteContactFirstName(Request $request)
    {
        try {
            if (isset($request->company_id) && $request->company_id == "new_data") {
                $contacts = CompanyContact::query();
            } elseif (isset($request->company_id)) {
                $contacts = CompanyContact::whereHas('mapContactCompany.company', function ($query) use ($request) {
                    $query->where('id', '=', $request->company_id);
                });
            } else {
                $contacts = CompanyContact::query();
            }

            if (isset($request->type)) {
                $contact_type =  $request->type == 'customer' ? '0' : '1';
                $contacts = $contacts->where('type', '=', $contact_type);
            }

            if (isset($request->contact_type)) {
                $contacts = $contacts->where('contact_type', '=', $request->contact_type);
            }

            //$data = Auth::user()->company_contacts();
            if (isset($request->key) && $request->key != '') {
                $contacts->where('first_name', 'LIKE', '%' . $request->key . '%');
            }

            if (isset($request->ids) && count($request->ids) > 0) {
                $contacts->whereNotIn('id', $request->ids);
            }
            $contacts = $contacts->get();
            return response()->json([
                'success'   => true,
                'data' => $contacts
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $e->getMessage()
            ], $e->getCode());
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $exception->getMessage()
            ], $exception->getCode());
        } catch (QueryException $exception) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $exception->getMessage()
            ], $exception->getCode());
        }
    }



    /**
     * Autocomplete first name (My contacts)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getContactDetails(Request $request)
    {
        try {

            if (isset($request->id)) {
                $contacts = CompanyContact::find($request->id);
                $company = $contacts->mapContactCompany;
                // dd($contacts->mapContactCompany);
            }
            return response()->json([
                'success'   => true,
                'data' => $contacts,
                'company' => $company
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $e->getMessage()
            ], $e->getCode());
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $exception->getMessage()
            ], $exception->getCode());
        } catch (QueryException $exception) {
            return response()->json([
                'success'   => false,
                'data' => null,
                'message'   => $exception->getMessage()
            ], $exception->getCode());
        }
    }
    /*public function createNewContact(Request $request)
        {
        try{
        dd($request->all());
        if ($request->company == '0') {
        $company = new Company();
        $company->user_id = $request->user_id;
    } else {
    $company = Company::find($request->company_id);
    }
    $data = [];
    $company->company = $request->company;
    $company->website = $request->website;
    $company->address = $request->address;
    $company->city = $request->city;
    $company->state_id = $request->state;
    $company->zip = $request->zip;
    $company->phone = $request->phone;
    $company->fax = $request->fax;
    $company->save();
    $data['company'] = $request->company;
    $data['company_id'] = $company->id;
    $data['contact'] = $request->contactType;
    $dataCustomers = [];
    foreach ($request->customer_id as $key => $customer) {
    if ($customer > 0) {
    $customerDetails = CompanyContact::find($customer);
    } else {
    $customerDetails = new CompanyContact();
    $customerDetails->user_id = $request->user_id;
    }
    $customerDetails->contact_type = $request->contactType;
    $customerDetails->type = $request->contact == 'customer' ? '0' : '1';
    $customerDetails->title = $request->title[$key];
    $customerDetails->title_other = $request->titleOther[$key];
    $customerDetails->first_name = $request->firstName[$key];
    $customerDetails->last_name = $request->lastName[$key];
    $customerDetails->email = $request->email[$key];
    $customerDetails->phone = $request->directPhone[$key];
    $customerDetails->cell = $request->cell[$key];
    $customerDetails->save();
    $dataCustomers['first_name'] = $request->firstName[$key];
    $dataCustomers['last_name'] = $request->lastName[$key];;
    if ($customer == '0') {
    $map = new MapCompanyContact();
    $map->company_contact_id = $customerDetails->id;
    } else {
    $map = MapCompanyContact::where('company_contact_id',$customerDetails->id)->first();
    if ($map == '') {
    $map = new MapCompanyContact();
    $map->company_contact_id = $customerDetails->id;
    }
    }
    $map->company_id = $company->id;
    $map->save();
    $dataCustomers['map_id'] = $map->id;
    $data['customers'][] = $dataCustomers;
    if (isset($request->project_id) && $request->project_id != 0 ) {
    if (isset($request->contact)) {
    if ($request->contact == 'customer') {
    if ($key == 0) {
    $project = ProjectDetail::findOrFail($request->project_id);
    $project->customer_contract_id = $map->id;
    $project->save();
    }
    } elseif ($request->contact == 'industry') {
    $projectContact = ProjectIndustryContactMap::where('projectId',$request->project_id)
    ->where('contactId',$map->company_contact_id)->first();
    if ($projectContact == '') {
    $projectContact = new ProjectIndustryContactMap();
    $projectContact->projectId = $request->project_id;
    }
    $projectContact->contactId = $map->company_contact_id;
    $projectContact->save();
    }
    }
    }
    }
    return response()->json([
    'success'   => true,
    'message' => 'Contact Created Successfully',
    'data' => $data
    ],200);

    } catch (Exception $e) {
    return response()->json([
    'success'   => false,
    'data' => null,
    'message'   => $e->getMessage()
    ],500);

    } catch (ModelNotFoundException $exception) {

    return response()->json([
    'success'   => false,
    'data' => null,
    'message'   => $exception->getMessage()
    ],500);
    } catch (QueryException $exception) {

    return response()->json([
    'success'   => false,
    'data' => null,
    'message'   => $exception->getMessage()
    ],500);
    }

    }*/


    public function createNewContact(Request $request)
    {
        try {
            $flag = false;
            //$company = $request->company == 'new_data' ? new Company() : Company::findOrFail($request->company);
            $company = Company::where('company',$request->company_name)->first();
            $company = $request->company == 'new_data' ? new Company() : Company::findOrFail($company->id);
            if ($request->company == 'new_data') {
                $company->user_id = $request->user_id;
                $company->company = isset($request->company_name) ? $request->company_name : $request->company;
                $company->website = $request->website;
                $company->address = $request->address;
                $company->city = $request->city;
                $company->state_id = $request->state;
                $company->zip = $request->zip;
                $company->phone = $request->phone;
                $company->fax = $request->fax;
                $company->save();

                $data['company'] = $company->company;
                $data['company_id'] = $company->id;

                foreach ($request->contacts as $key => $contact) {
                    if (array_key_exists('first_name', $contact) && array_key_exists('email', $contact) && !is_null($contact['first_name']) && !is_null($contact['email'])) {
                        $customerDetails = array_key_exists('customer_id', $contact) ? CompanyContact::find($contact['customer_id']) : null;
                        if (!is_null($customerDetails)) {
                            $customerDetails->user_id = $request->user_id;
                            $customerDetails->contact_type = $request->contactType;
                            $customerDetails->type = $request->contact == 'customer' ? '0' : '1';
                            $customerDetails->title = $contact['title'];
                            $customerDetails->title_other = $contact['title_other'];
                            $customerDetails->first_name = $contact['first_name'];
                            $customerDetails->last_name = $contact['last_name'];
                            $customerDetails->email = $contact['email'];
                            $customerDetails->phone = $contact['phone'];
                            $customerDetails->cell = $contact['cell'];
                            $customerDetails->update();

                            $map = $customerDetails->mapContactCompany;
                            if (!is_null($map)) {
                                $map->company_contact_id = $customerDetails->id;
                                $map->company_id = $company->id;
                                $map->address = $company->address;
                                $map->city = $company->city;
                                $map->state_id = $company->state_id;
                                $map->zip = $company->zip;
                                $map->phone = $company->phone;
                                $map->fax = $company->fax;
                                $map->update();
                            } else {
                                $map = new MapCompanyContact();
                                $map->user_id = Auth::user()->id;
                                $map->company_contact_id = $customerDetails->id;
                                $map->company_id = $company->id;
                                $map->address = $company->address;
                                $map->city = $company->city;
                                $map->state_id = $company->state_id;
                                $map->zip = $company->zip;
                                $map->phone = $company->phone;
                                $map->fax = $company->fax;
                                $map->website = $company->website;
                                $map->save();
                            }
                        } else {
                            $customerDetails = new CompanyContact();
                            $customerDetails->user_id = $request->user_id;
                            $customerDetails->contact_type = $request->contactType;
                            $customerDetails->type = $request->contact == 'customer' ? '0' : '1';
                            $customerDetails->title = $contact['title'];
                            $customerDetails->title_other = $contact['title_other'];
                            $customerDetails->first_name = $contact['first_name'];
                            $customerDetails->last_name = $contact['last_name'];
                            $customerDetails->email = $contact['email'];
                            $customerDetails->phone = $contact['phone'];
                            $customerDetails->cell = $contact['cell'];
                            $customerDetails->save();

                            $map = new MapCompanyContact();
                            $map->company_contact_id = $customerDetails->id;
                            $map->user_id = Auth::user()->id;
                            $map->company_id = $company->id;
                            $map->address = $company->address;
                            $map->city = $company->city;
                            $map->state_id = $company->state_id;
                            $map->zip = $company->zip;
                            $map->phone = $company->phone;
                            $map->fax = $company->fax;
                            $map->website = $company->website;
                            $map->save();
                        }

                        $dataCustomers['first_name'] = $contact['first_name'];
                        $dataCustomers['last_name'] = $contact['last_name'];
                        $dataCustomers['contact_type'] = $customerDetails->contact_type;
                        $dataCustomers['email'] = $customerDetails->email;
                        $dataCustomers['phone'] = $customerDetails->phone;
                        $dataCustomers['id'] = $customerDetails->id;
                        $dataCustomers['company'] = $company->company;
                        $dataCustomers['map_id'] = $map->id;
                        $data['new_customers'][] = $dataCustomers;

                        if (isset($request->project_id) && $request->project_id != 0) {
                            if (isset($request->contact)) {
                                if ($request->contact == 'customer') {
                                    if ($key == 0) {
                                        $project = ProjectDetail::findOrFail($request->project_id);
                                        $project->customer_contract_id = $map->id;
                                        $project->save();
                                    }
                                } elseif ($request->contact == 'industry') {
                                    /*  $projectContact = ProjectIndustryContactMap::where('projectId',$request->project_id)
                        ->where('contactId',$map->id)->first();
                        if (is_null($projectContact)) {*/
                                    $projectContact = ProjectIndustryContactMap::where('projectId', $request->project_id)
                                        ->where('contactId', $map->id)->first();

                                    if (is_null($projectContact)) {
                                        $projectContact = new ProjectIndustryContactMap();
                                        $projectContact->projectId = $request->project_id;
                                        $projectContact->contactId = $map->id;
                                        $projectContact->save();
                                    } else {
                                        $projectContact->contactId = $map->id;
                                        $projectContact->update();
                                    }
                                    // }
                                }
                            }
                        }
                        $flag = true;
                    }
                }
            } else {
                if (!empty($request->website)) {
                    $company->website = $request->website;
                    $company->address = $request->address;
                    $company->city = $request->city;
                    $company->state_id = $request->state;
                    $company->zip = $request->zip;
                    $company->phone = $request->phone;
                    $company->fax = $request->fax;

                    $company->update();
                }

                $data['company'] = $company->company;
                $data['company_id'] = $company->id;

                foreach ($request->contacts as $key => $contact) {
                    if (array_key_exists('first_name', $contact) && array_key_exists('email', $contact) && !is_null($contact['first_name']) && !is_null($contact['email'])) {
                        $customerDetails = array_key_exists('customer_id', $contact) ? CompanyContact::find($contact['customer_id']) : null;
                        if (!is_null($customerDetails)) {
                            $customerDetails->user_id = $request->user_id;
                            $customerDetails->contact_type = $request->contactType;
                            $customerDetails->type = $request->contact == 'customer' ? '0' : '1';
                            $customerDetails->title = $contact['title'];
                            $customerDetails->title_other = $contact['title_other'];
                            $customerDetails->first_name = $contact['first_name'];
                            $customerDetails->last_name = $contact['last_name'];
                            $customerDetails->email = $contact['email'];
                            $customerDetails->phone = $contact['phone'];
                            $customerDetails->cell = $contact['cell'];
                            $customerDetails->update();

                            $map = $customerDetails->mapContactCompany;

                            if (!is_null($map)) {
                                $map->company_contact_id = $customerDetails->id;
                                $map->company_id = $company->id;
                                $map->user_id = Auth::user()->id;
                                $map->address = $request->address;
                                $map->city = $request->city;
                                $map->state_id = $request->state;
                                $map->zip = $request->zip;
                                $map->phone = $request->phone;
                                $map->fax = $request->fax;
                                $map->website = $company->website;
                                $map->update();
                            } else {
                                $map = new MapCompanyContact();
                                $map->company_contact_id = $customerDetails->id;
                                $map->user_id = Auth::user()->id;
                                $map->company_id = $company->id;
                                $map->address = $company->address;
                                $map->city = $company->city;
                                $map->state_id = $company->state_id;
                                $map->zip = $company->zip;
                                $map->phone = $company->phone;
                                $map->fax = $company->fax;
                                $map->website = $company->website;
                                $map->save();
                            }
                        } else {
                            $customerDetails = new CompanyContact();
                            $customerDetails->user_id = $request->user_id;
                            $customerDetails->contact_type = $request->contactType;
                            $customerDetails->type = $request->contact == 'customer' ? '0' : '1';
                            $customerDetails->title = $contact['title'];
                            $customerDetails->title_other = $contact['title_other'];
                            $customerDetails->first_name = $contact['first_name'];
                            $customerDetails->last_name = $contact['last_name'];
                            $customerDetails->email = $contact['email'];
                            $customerDetails->phone = $contact['phone'];
                            $customerDetails->cell = $contact['cell'];
                            $customerDetails->save();

                            $map = new MapCompanyContact();
                            $map->company_contact_id = $customerDetails->id;
                            $map->user_id = Auth::user()->id;
                            $map->company_id = $company->id;
                            $map->address = $request->address;
                            $map->city = $request->city;
                            $map->state_id = $request->state;
                            $map->zip = $request->zip;
                            $map->phone = $request->phone;
                            $map->fax = $request->fax;
                            $map->website = $company->website;
                            $map->save();
                        }

                        $dataCustomers['first_name'] = $contact['first_name'];
                        $dataCustomers['last_name'] = $contact['last_name'];
                        $dataCustomers['contact_type'] = $customerDetails->contact_type;
                        $dataCustomers['email'] = $customerDetails->email;
                        $dataCustomers['phone'] = $customerDetails->phone;
                        $dataCustomers['id'] = $customerDetails->id;
                        $dataCustomers['company'] = $company->company;
                        $dataCustomers['map_id'] = $map->id;
                        $data['new_customers'][] = $dataCustomers;

                        if (isset($request->project_id) && $request->project_id != 0) {
                            if (isset($request->contact)) {
                                if ($request->contact == 'customer') {
                                    if ($key == 0) {
                                        $project = ProjectDetail::findOrFail($request->project_id);
                                        $project->customer_contract_id = $map->id;
                                        $project->save();
                                    }
                                } elseif ($request->contact == 'industry') {
                                    $projectContact = ProjectIndustryContactMap::where('projectId', $request->project_id)
                                        ->where('contactId', $map->id)->first();
                                    if (is_null($projectContact)) {
                                        $projectContact = new ProjectIndustryContactMap();
                                        $projectContact->projectId = $request->project_id;
                                        $projectContact->contactId = $map->id;
                                        $projectContact->save();
                                    } else {
                                        $projectContact->contactId = $map->id;
                                        $projectContact->update();
                                    }
                                }
                            }
                        }
                        $flag = true;
                    }
                }
            }

            $all_contacts = Company::pluck('id');
            $customer_contacts = MapCompanyContact::where('user_id', Auth::user()->id)
                ->whereHas('getContacts', function ($query) use ($request) {
                    $query->where('type', $request->contact == 'customer' ? '0' : '1');
                })->with('company')->get();

            foreach ($customer_contacts as $customer_contact) {
                $dataCustomers['first_name'] = !is_null($customer_contact->contacts) ? $customer_contact->contacts->first_name : null;
                $dataCustomers['last_name'] = !is_null($customer_contact->contacts) ? $customer_contact->contacts->last_name : null;
                $dataCustomers['contact_type'] = !is_null($customer_contact->contacts) ? $customer_contact->contacts->contact_type : null;
                $dataCustomers['map_id'] = $customer_contact->id;
                $dataCustomers['company'] = !is_null($customer_contact->company) ? $customer_contact->company->company : null;
                $data['customers'][] = $dataCustomers;
            }

            $data['selected_customer'] = (array_key_exists('new_customers', $data)) ? $data['new_customers'][0] : '';

            return response()->json([
                'success'   => true,
                'empty' =>   isset($flag) && $flag ? false : true,
                'message' => 'Contact Created Successfully',
                'data' => $data
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

    public function getContactData(Request $request)
    {
        try {
            $contacts = CompanyContact::WhereIn('id', explode(',', $request->contact_id))->get();
            $company = Company::findOrFail($request->company_id);
            $mapCompanyContact = isset($request->map_id) ? MapCompanyContact::findOrFail($request->map_id) : null;

            return response()->json([
                'success'   => true,
                'company' => $company,
                'customers' => $contacts,
                'maping'   => $mapCompanyContact
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
}
