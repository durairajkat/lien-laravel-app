<?php

namespace App\Http\Controllers;

use App\Models\LienProvider;
use DB;
use Log;
use App\User;
use Exception;
use App\Models\State;
use App\Models\Remedy;
use App\Models\Company;
use App\Models\Contact;
use App\Models\JobInfo;
use App\Models\TierTable;
use App\Models\RemedyDate;
use App\Models\RemedyStep;
use App\Models\ProjectType;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use App\Models\ClaimDataForm;
use App\Models\ProjectDetail;
use App\Models\CompanyContact;
use App\Models\TierRemedyStep;
use App\Models\ProjectContract;
use App\Models\LienBoundSummary;
use App\Models\CreditApplication;
use App\Models\MapCompanyContact;
use App\Models\ContactInformation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\ClaimFormProjectDataSheet;
use App\Models\JointPaymentAuthorization;
use App\Models\ProjectIndustryContactMap;
use App\Models\UnconditionalWaiverProgress;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class DocumentController for Management of Project Documents
 * @package App\Http\Controllers
 */
class DocumentController extends Controller
{
    /**
     * Get Line Bound Summery Form
     * @param $state
     * @param $projectType
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getLineBoundSummery($state, $projectType)
    {
        try {
            $lineBoundSummery = LienBoundSummary::where('state_id', $state)
                ->where('project_type_id', $projectType)->get();
            $state = State::findOrFail($state);
            $project = ProjectType::findOrFail($projectType);
            return view('basicUser.document.line_bound_summery', [
                'data' => $lineBoundSummery,
                'state' => $state,
                'projectType' => $project
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * Get Claim Data and Project Data Sheet -> part-1
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getDocumentClaimData($project_id)
    {
        try {
            //dd($project_id);
            $claimForm = ClaimFormProjectDataSheet::where('project_id', $project_id)->first();
            // dd($claimForm);
            $claimForm2 = ClaimDataForm::where('project_id', $project_id)->first();
            if ($claimForm && $claimForm2) {
                return redirect()->back()->with('doc-success', 'Document Allready Created');
            } else {
                $project = ProjectDetail::findOrFail($project_id);
                $company_details = UserDetails::where('user_id', $project->user_id)->first();
                $contract_info = ProjectDetail::with('project_contract')->findOrFail($project_id);
                return view('basicUser.document.document_claim_data', [
                    'ProjectState' => $project->state->short_code,
                    'project_id' => $project->id,
                    'company_details' => $company_details,
                    'contract_info' => $contract_info,
                    'claimForm' => $claimForm,
                    'claimForm2' => $claimForm2
                ]);
            }
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }
    /**
     * Get Claim Data and Project Data Sheet -> part-1
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getDocumentClaimData2($project_id, $flag)
    {
        try {
            //dd($flag);
            $project = ProjectDetail::findOrFail($project_id);
            $claimForm = ClaimFormProjectDataSheet::where('project_id', $project_id)->first();
            return view('basicUser.document.document_claim_data_2', [
                'project_id' => $project_id,
                '$claimDataForm' => $claimForm,
                'flag' => $flag
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }


    /**
     * Get Claim Data and Project Data view
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */

    public function getDocumentClaimView($project_id, $flag)
    {
        try {
            $claimForm = ClaimFormProjectDataSheet::where('project_id', $project_id)->first();
            $claimForm2 = ClaimDataForm::where('project_id', $project_id)->first();
            $project = ProjectDetail::findOrFail($project_id);
            $company_details = UserDetails::where('user_id', $project->user_id)->first();
            $contract_info = ProjectDetail::with('project_contract')->findOrFail($project_id);
            $revised_total = $contract_info->project_contract->base_amount + $contract_info->project_contract->extra_amount;
            $claim_amount = $revised_total - $contract_info->project_contract->credits;
            return view('basicUser.document.document_claim_data_view', [
                'ProjectState' => $project->state->short_code,
                'project_id' => $project->id,
                'company_details' => $company_details,
                'contract_info' => $contract_info,
                'revised_total' => $revised_total,
                'claim_amount' => $claim_amount,
                'claimForm' => $claimForm,
                'claimForm2' => $claimForm2,
                'flag' => $flag
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }
    /**
     * Get Credit application view
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */

    public function getDocumentCreditView($project_id, $flag)
    {
        try {
            //dd($flag);
            $creditAppllication = CreditApplication::where('project_id', $project_id)->first();
            $project = ProjectDetail::findOrFail($project_id);
            $company_details = UserDetails::where('user_id', $project->user_id)->first();
            $contract_info = ProjectDetail::with('project_contract')->findOrFail($project_id);
            return view('basicUser.document.credit_application', [
                'ProjectState' => $project->state->short_code,
                'project_id' => $project->id,
                'company_details' => $company_details,
                'contract_info' => $contract_info,
                'credit_application' => $creditAppllication,
                'flag' => $flag

            ]);
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }
    /**
     * Get Joint payment application view
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */

    public function getDocumentJointView($project_id, $flag)
    {
        try {
            //dd($flag);
            $jointPayment = JointPaymentAuthorization::where('project_id', $project_id)->first();
            $project = ProjectDetail::findOrFail($project_id);
            $company_details = UserDetails::where('user_id', $project->user_id)->first();
            $contract_info = ProjectDetail::with('project_contract')->findOrFail($project_id);
            return view('basicUser.document.joint_payment_authorization', [
                'ProjectState' => $project->state->short_code,
                'project_id' => $project->id,
                'company_details' => $company_details,
                'contract_info' => $contract_info,
                'joint_payment' => $jointPayment,
                'flag' => $flag

            ]);
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }
    /**
     * Get unconditional waver view
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */

    public function getDocumentWaverView($project_id, $flag)
    {
        try {
            //dd($flag);
            $waverView = UnconditionalWaiverProgress::where('project_id', $project_id)->first();
            $project = ProjectDetail::findOrFail($project_id);
            $company_details = UserDetails::where('user_id', $project->user_id)->first();
            $contract_info = ProjectDetail::with('project_contract')->findOrFail($project_id);
            return view('basicUser.document.document_unconditional_waiver_release', [
                'ProjectState' => $project->state->short_code,
                'project_id' => $project->id,
                'company_details' => $company_details,
                'contract_info' => $contract_info,
                'waver_view' => $waverView,
                'flag' => $flag
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * Get Claim Data and Project Data first form submit
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getDocumentClaimDataTwo(Request $request, $project_id)
    {
        $this->validate($request, [
            'Agree' => 'required'
        ]);
        try {
            $flag = false;
            $claimForm = ClaimFormProjectDataSheet::where('project_id', $project_id)->first();
            if ($claimForm == '') {
                $claimForm = new ClaimFormProjectDataSheet();
                $claimForm->project_id = $project_id;
                $flag = true;
            }
            $claimForm->project_id = $project_id;
            $claimForm->company = $request->Company;
            $claimForm->contact = $request->Contact;
            $claimForm->address = $request->Address;
            $claimForm->city = $request->City;
            $claimForm->state = $request->State;
            $claimForm->zip = $request->Zip;
            $claimForm->phone = $request->Phone;
            $claimForm->fax = $request->Fax;
            $claimForm->email = $request->Email;
            $claimForm->purpose = $request->Purpose;
            $claimForm->claim_date = $request->ClaimDate;
            $claimForm->claim_type = $request->ClaimType;
            $claimForm->claim_type_other = $request->ClaimTypeOther;
            $claimForm->contract_date = $request->ContractDate;
            $claimForm->contract_type = $request->ContractType;
            $claimForm->base_amount = $request->BaseAmount;
            $claimForm->extra_amount = $request->ExtraAmount;
            $claimForm->subtotal = $request->Subtotal;
            $claimForm->payments = $request->Payments;
            $claimForm->total = $request->Total;
            $claimForm->extra_work_related = $request->ExtraWorkRelated;
            $claimForm->provided = $request->Provided;
            $claimForm->custom_manufacture_date = $request->CustomManufacture;
            $claimForm->custom_manufacture = $request->CustomManufactureDate;
            $claimForm->preliminary_notice = $request->PreliminaryNotice;
            $claimForm->lienwaiver = $request->LienWaiver;
            $claimForm->construction_type = $request->ConstructionType;
            $claimForm->notice_of_completion = $request->NoticeOfCompletion;
            $claimForm->project_complete = $request->ProjectComplete;
            $claimForm->complete_date = $request->CompleteDate;
            $claimForm->agree = $request->Agree;
            $claimForm->signature = $request->Signature;
            $claimForm->signature_date = $request->SignatureDate;
            $claimForm->project_state = $request->ProjectState;
            $claimForm->project_state_other = $request->ProjectStateOther;
            if ($flag) {
                $claimForm->save();
                $claimForm = ClaimDataForm::where('project_id', $project_id)->first();
            } else {
                $claimForm->update();
            }
            $var = 0;

            return redirect()->route('get.documentClaimData2', ['project' => $project_id, 'flag' => $var]);
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * Get Claim Data and Project Data second form submit
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getDocumentClaimDataComplete(Request $request, $project_id)
    { // dd($request->all());
        try {
            $flag = false;
            $project = ProjectDetail::findOrFail($project_id);
            $company_details = UserDetails::where('user_id', $project->user_id)->first();
            $claimForm = ClaimDataForm::where('project_id', $project_id)->first();
            if ($claimForm == '') {
                $claimForm = new ClaimDataForm();
                $claimForm->project_id = $project_id;
                $flag = true;
            }
            $claimForm->p_name = $request->ProjectName;
            $claimForm->p_address = $request->ProjectAddress;
            $claimForm->p_city = $request->ProjectCity;
            $claimForm->p_state = $request->ProjectState;
            $claimForm->p_zip = $request->ProjectZip;
            $claimForm->p_phone = $request->ProjectPhone;
            $claimForm->p_country_of_property = $request->ProjectCounty;
            $claimForm->c_name = $request->CustomerCompany;
            $claimForm->c_contact_name = $request->CustomerContact;
            $claimForm->c_address = $request->CustomerAddress;
            $claimForm->c_city = $request->CustomerCity;
            $claimForm->c_state = $request->CustomerState;
            $claimForm->c_zip = $request->CustomerZip;
            $claimForm->c_phone = $request->CustomerPhone;
            $claimForm->c_no = $request->CustomerNumber;
            $claimForm->c_account = $request->CustomerAccount;
            $claimForm->p_owner_company = $request->ProjectOwnerCompany;
            $claimForm->p_owner_contact = $request->ProjectOwnerContact;
            $claimForm->p_owner_address = $request->ProjectOwnerAddress;
            $claimForm->p_owner_city = $request->ProjectOwnerCity;
            $claimForm->p_owner_state = $request->ProjectOwnerState;
            $claimForm->p_owner_zip = $request->ProjectOwnerZip;
            $claimForm->p_owner_phone = $request->ProjectOwnerPhone;
            $claimForm->o_contractor_company = $request->OriginalContractorCompany;
            $claimForm->o_contractor_contact = $request->OriginalContractorContact;
            $claimForm->o_contractor_address = $request->OriginalContractorAddress;
            $claimForm->o_contractor_city = $request->OriginalContractorCity;
            $claimForm->o_contractor_state = $request->OriginalContractorState;
            $claimForm->o_contractor_zip = $request->OriginalContractorZip;
            $claimForm->o_contractor_phone = $request->OriginalContractorPhone;
            $claimForm->s_contractor_company = $request->SubContractorCompany;
            $claimForm->s_contractor_contact = $request->SubContractorContact;
            $claimForm->s_contractor_address = $request->SubContractorAddress;
            $claimForm->s_contractor_city = $request->SubContractorCity;
            $claimForm->s_contractor_state = $request->SubContractorState;
            $claimForm->s_contractor_zip = $request->SubContractorZip;
            $claimForm->s_contractor_phone = $request->SubContractorPhone;
            $claimForm->project_type = $request->ProjectType;
            $claimForm->contact_number = $request->ContractNumber;
            $claimForm->project_number = $request->ProjectNumber;
            $claimForm->project_notice = $request->ProjectNotice;
            $claimForm->payment_bond_number = $request->PaymentBondNumber;
            $claimForm->bond_company = $request->BondCompany;
            $claimForm->bond_contract = $request->BondContact;
            $claimForm->bond_address = $request->BondAddress;
            $claimForm->bond_city = $request->BondCity;
            $claimForm->bond_state = $request->BondState;
            $claimForm->bond_zip = $request->BondZip;
            $claimForm->bond_phone = $request->BondPhone;
            $claimForm->order_value = $request->OrderValue;
            $claimForm->job_number = $request->JobNumber;
            $claimForm->pon_number = $request->PONumber;
            $claimForm->order_contract_number = $request->OrderContractNumber;
            $claimForm->start_date = $request->DateNeeded;
            $claimForm->payment_terms = $request->PaymentTerms;
            $claimForm->billing_cycle = $request->BillingCycle;
            $claimForm->payment_type = $request->PaymentType;
            $claimForm->status = $request->YourStatus;
            $claimForm->status_other = $request->YourStatusOther;
            $claimForm->documents_other_document = $request->DocumentsOtherDocument;
            $claimForm->documents_purchase_order = $request->DocumentsPurchaseOrder;
            $claimForm->documents_delivery_tickets = $request->DocumentsDeliveryTickets;
            $claimForm->documents_waiver = $request->DocumentsWaiver;
            $claimForm->documents_legal_description = $request->DocumentsLegalDescription;
            $claimForm->documents_payment_bond = $request->DocumentsPaymentBond;
            $claimForm->documents_joint_check_agreement = $request->DocumentsJointCheckAgreement;
            $claimForm->documents_preliminary_notice = $request->DocumentsPreliminaryNotice;
            $claimForm->documents_other = $request->DocumentsOther;
            $claimForm->miscellaneous = $request->Miscellaneous;
            $claimForm->add_contact_first_name = $request->AddContactFirstName;
            $claimForm->add_contact_last_name = $request->AddContactLastName;
            $claimForm->add_contact_type = $request->AddContactType;
            $claimForm->add_contact_company = $request->AddContactCompany;
            $claimForm->add_contact_phone = $request->AddContactPhone;
            $claimForm->add_contact_email = $request->AddContactEmail;
            $claimForm->heard_by_web = $request->HeardByWeb;
            $claimForm->heard_by_google = $request->HeardByGoogle;
            $claimForm->heard_by_aol = $request->HeardByAOL;
            $claimForm->heard_by_referral = $request->HeardByReferral;
            $claimForm->heard_by_msn = $request->HeardByMSN;
            $claimForm->heard_by_other = $request->HeardByOther;
            if (!$flag) {
                $claimForm->update();
            } else {
                $claimForm->save();
            }
            $attorny['FirstName'] = $company_details->first_name;
            $attorny['LastName'] = $company_details->last_name;
            $attorny['Fax'] = $company_details->phone;
            $attorny['Phone'] = $company_details->phone;
            $attorny['Company'] = $company_details->company;
            return view('basicUser.document.ducument_claim_data_complete', [
                'project_id' => $project->id,
                'attorney' => $attorny
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * Get Credit application form
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getCreditApplication($project_id)
    {
        try {
            $flag = 0;
            $project = ProjectDetail::findOrFail($project_id);
            return view('basicUser.document.credit_application', [
                'project_id' => $project->id,
                'flag' => $flag
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * Credit application form submit
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */

    public function saveCreditApplication(Request $request, $project_id)
    {
        try {
            //dd($request->all());
            $flag = false;
            $project = ProjectDetail::findOrFail($project_id);
            $creditDetails = CreditApplication::where('project_id', $project_id)->first();
            if ($creditDetails == '') {
                $creditDetails = new CreditApplication();
                $creditDetails->project_id = $project_id;
                $flag = true;
            }
            $creditDetails->project_id = $project_id;
            $creditDetails->Company = $request->Company;
            $creditDetails->Address = $request->Address;
            $creditDetails->City = $request->City;
            $creditDetails->State = $request->State;
            $creditDetails->Zip = $request->Zip;
            $creditDetails->Phone = $request->Phone;
            $creditDetails->Fax = $request->Fax;
            $creditDetails->Email = $request->Email;
            $creditDetails->CustomerCompany = $request->CustomerCompany;
            $creditDetails->CustomerContact = $request->CustomerContact;
            $creditDetails->CustomerAddress = $request->CustomerAddress;
            $creditDetails->CustomerCity = $request->CustomerCity;
            $creditDetails->CustomerState = $request->CustomerState;
            $creditDetails->CustomerZip = $request->CustomerZip;
            $creditDetails->CustomerPhone = $request->CustomerPhone;
            $creditDetails->CustomerFax = $request->CustomerFax;
            $creditDetails->CustomerEmail = $request->CustomerEmail;
            $creditDetails->StateOfOrigin = $request->StateOfOrigin;
            $creditDetails->FederalID = $request->FederalID;
            $creditDetails->SalesTaxID = $request->SalesTaxID;
            $creditDetails->PaymentContact = $request->PaymentContact;
            $creditDetails->PaymentEmail = $request->PaymentEmail;
            $creditDetails->PaymentAddress = $request->PaymentAddress;
            $creditDetails->PurchaseManager = $request->PurchaseManager;
            $creditDetails->PurchaseManagerPhone = $request->PurchaseManagerPhone;
            $creditDetails->ParentCompany = $request->ParentCompany;
            $creditDetails->ParentCompanyAddress = $request->ParentCompanyAddress;
            $creditDetails->ParentCompanyPO = $request->ParentCompanyPO;
            $creditDetails->ParentCompanyCity = $request->ParentCompanyCity;
            $creditDetails->ParentCompanyState = $request->ParentCompanyState;
            $creditDetails->ParentCompanyZip = $request->ParentCompanyZip;
            $creditDetails->ParentCompanyPhone = $request->ParentCompanyPhone;
            $creditDetails->ParentCompanyFax = $request->ParentCompanyFax;
            $creditDetails->OwnersLine1 = $request->OwnersLine1;
            $creditDetails->Bankruptcy = $request->Bankruptcy;
            $creditDetails->BankruptcyYearState = $request->BankruptcyYearState;
            $creditDetails->Judgement = $request->Judgement;
            $creditDetails->PendingLegal = $request->PendingLegal;
            $creditDetails->BankName = $request->BankName;
            $creditDetails->BankAccount = $request->BankAccount;
            $creditDetails->BankPhone = $request->BankPhone;
            $creditDetails->BankFax = $request->BankFax;
            $creditDetails->BankContact = $request->BankContact;
            $creditDetails->Reference1Name = $request->Reference1Name;
            $creditDetails->Reference1Account = $request->Reference1Account;
            $creditDetails->Reference1Phone = $request->Reference1Phone;
            $creditDetails->Reference1Fax = $request->Reference1Fax;
            $creditDetails->Reference1Contact = $request->Reference1Contact;
            $creditDetails->Reference2Name = $request->Reference2Name;
            $creditDetails->Reference2Account = $request->Reference2Account;
            $creditDetails->Reference2Phone = $request->Reference2Phone;
            $creditDetails->Reference2Fax = $request->Reference2Fax;
            $creditDetails->Reference2Contact = $request->Reference2Contact;
            $creditDetails->Reference3Name = $request->Reference3Name;
            $creditDetails->Reference3Account = $request->Reference3Account;
            $creditDetails->Reference3Phone = $request->Reference3Phone;
            $creditDetails->Reference3Fax = $request->Reference3Fax;
            $creditDetails->Reference3Contact = $request->Reference3Contact;
            $creditDetails->Customer = $request->Customer;
            $creditDetails->PreparedBy = $request->PreparedBy;
            $creditDetails->Dated = $request->Dated;
            $creditDetails->Title = $request->Title;
            $creditDetails->name = $request->name;
            $creditDetails->home = $request->home;
            $creditDetails->phone2 = $request->phone2;
            $creditDetails->social = $request->social;
            $creditDetails->position = $request->position;
            $creditDetails->name1 = $request->name1;
            $creditDetails->home1 = $request->home1;
            $creditDetails->phone1 = $request->phone1;
            $creditDetails->social1 = $request->social1;
            $creditDetails->position1 = $request->position1;


            if (!$flag) {
                $creditDetails->update();
            } else {
                $creditDetails->save();
            }
            return redirect()->route('project.document.view');
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * Get Joint Payment Authorization form
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getJointPaymentAuthorization($project_id)
    {
        try {
            $flag = 0;
            $project = ProjectDetail::findOrFail($project_id);
            $join_payment = JointPaymentAuthorization::where('project_id', $project_id)->first();
            if ($join_payment) {
                return redirect()->back()->with('doc-success', 'Document Allready Created');
            } else {
                return view('basicUser.document.joint_payment_authorization', [
                    'project_id' => $project->id,
                    'flag' => $flag
                ]);
            }
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }
    /**
     * Post waiver form
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function saveJointPaymentAuthorization(Request $request, $project_id)
    {
        try {
            $flag = false;
            $jointPaymentAuthorization = JointPaymentAuthorization::where('project_id', $project_id)->first();
            $project = ProjectDetail::findOrFail($project_id);
            if ($jointPaymentAuthorization == '') {
                $jointPaymentAuthorization = new JointPaymentAuthorization();
                $jointPaymentAuthorization->project_id = $project_id;
                $flag = true;
            }
            $jointPaymentAuthorization->project_id = $project_id;
            $jointPaymentAuthorization->Company = $request->Company;
            $jointPaymentAuthorization->Supplier = $request->Supplier;
            $jointPaymentAuthorization->Address = $request->Address;
            $jointPaymentAuthorization->BusinessDescription = $request->BusinessDescription;
            $jointPaymentAuthorization->CustomerName = $request->CustomerName;
            $jointPaymentAuthorization->Subcontractor = $request->Subcontractor;
            $jointPaymentAuthorization->CustomerAddress = $request->CustomerAddress;
            $jointPaymentAuthorization->CustomerCity = $request->CustomerCity;
            $jointPaymentAuthorization->CustomerState = $request->CustomerState;
            $jointPaymentAuthorization->GeneralContractor = $request->GeneralContractor;
            $jointPaymentAuthorization->ContractorAddress = $request->ContractorAddress;
            $jointPaymentAuthorization->ContractorCity = $request->ContractorCity;
            $jointPaymentAuthorization->ContractorState = $request->ContractorState;
            $jointPaymentAuthorization->ProjectName = $request->ProjectName;
            $jointPaymentAuthorization->Project = $request->Project;
            $jointPaymentAuthorization->ContractorName2 = $request->ContractorName2;
            $jointPaymentAuthorization->ContractAmount = $request->ContractAmount;
            $jointPaymentAuthorization->ContractorSigned = $request->ContractorSigned;
            $jointPaymentAuthorization->ContractorBy = $request->ContractorBy;
            $jointPaymentAuthorization->ContractorITS = $request->ContractorITS;
            $jointPaymentAuthorization->CompanySigned = $request->CompanySigned;
            $jointPaymentAuthorization->CompanyBy = $request->CompanyBy;
            $jointPaymentAuthorization->CompanyITS = $request->CompanyITS;
            $jointPaymentAuthorization->UserSigned = $request->UserSigned;
            $jointPaymentAuthorization->UserBy = $request->UserBy;
            $jointPaymentAuthorization->UserITS = $request->UserITS;
            if (!$flag) {
                $jointPaymentAuthorization->update();
            } else {
                $jointPaymentAuthorization->save();
            }
            //            return view('basicUser.document.joint_payment_authorization', [
            //                'project_id' => $project->id,
            //                'joint_payment_authorization' => $jointPaymentAuthorization
            //            ]);
            $url = route('project.document.view') . '?project_id=' . $project_id;
            return Redirect::to($url);
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * Post Waver progress form
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function saveDocumentUnconditionalWaiverRelease(Request $request, $project_id)
    {
        try {
            // dd($request->all());
            $flag = false;
            $unconditionalWaiver = UnconditionalWaiverProgress::where('project_id', $project_id)->first();
            $project = ProjectDetail::findOrFail($project_id);
            if ($unconditionalWaiver == '') {
                $unconditionalWaiver = new UnconditionalWaiverProgress();
                $unconditionalWaiver->project_id = $project_id;
                $flag = true;
            }

            $unconditionalWaiver->project_id = $project_id;
            $unconditionalWaiver->CheckAmount = $request->CheckAmount;
            $unconditionalWaiver->CustomerName = $request->CustomerName;
            $unconditionalWaiver->Owner = $request->Owner;
            $unconditionalWaiver->ProjectName = $request->ProjectName;
            $unconditionalWaiver->ProjectAddress = $request->ProjectAddress;
            $unconditionalWaiver->CustomerName2 = $request->CustomerName2;
            $unconditionalWaiver->ThroughDate = $request->ThroughDate;
            $unconditionalWaiver->SignedDate = $request->SignedDate;
            $unconditionalWaiver->SignedCompany = $request->SignedCompany;
            $unconditionalWaiver->SignedAddress = $request->SignedAddress;
            $unconditionalWaiver->ContractorState = $request->ContractorState;
            $unconditionalWaiver->ContractorCounty = $request->ContractorCounty;
            $unconditionalWaiver->Undersigned = $request->Undersigned;
            $unconditionalWaiver->ContractorTitle = $request->ContractorTitle;
            $unconditionalWaiver->ContractorCompanyName = $request->ContractorCompanyName;
            $unconditionalWaiver->ContractorWorkType = $request->ContractorWorkType;
            $unconditionalWaiver->ContractorProjectAddress = $request->ContractorProjectAddress;
            $unconditionalWaiver->ContractorAmount = $request->ContractorAmount;
            $unconditionalWaiver->ContractorPaid = $request->ContractorPaid;
            $unconditionalWaiver->ItemName1 = $request->ItemName1;
            $unconditionalWaiver->WhatFor1 = $request->WhatFor1;
            $unconditionalWaiver->ContractPrice1 = $request->ContractPrice1;
            $unconditionalWaiver->AmountPaid1 = $request->AmountPaid1;
            $unconditionalWaiver->ThisPayment1 = $request->ThisPayment1;
            $unconditionalWaiver->BalDue1 = $request->BalDue1;
            $unconditionalWaiver->ItemName2 = $request->ItemName2;
            $unconditionalWaiver->WhatFor2 = $request->WhatFor2;
            $unconditionalWaiver->ContractPrice2 = $request->ContractPrice2;
            $unconditionalWaiver->AmountPaid2 = $request->AmountPaid2;
            $unconditionalWaiver->BalDue2 = $request->BalDue2;
            $unconditionalWaiver->ItemName3 = $request->ItemName3;
            $unconditionalWaiver->WhatFor3 = $request->WhatFor3;
            $unconditionalWaiver->ContractPrice3 = $request->ContractPrice3;
            $unconditionalWaiver->AmountPaid3 = $request->AmountPaid3;
            $unconditionalWaiver->ThisPayment3 = $request->ThisPayment3;
            $unconditionalWaiver->BalDue3 = $request->BalDue3;
            $unconditionalWaiver->ItemName4 = $request->ItemName4;
            $unconditionalWaiver->WhatFor4 = $request->WhatFor4;
            $unconditionalWaiver->ContractPrice4 = $request->ContractPrice4;
            $unconditionalWaiver->AmountPaid4 = $request->AmountPaid4;
            $unconditionalWaiver->ThisPayment4 = $request->ThisPayment4;
            $unconditionalWaiver->BalDue4 = $request->BalDue4;
            $unconditionalWaiver->WhatFor5 = $request->WhatFor5;
            $unconditionalWaiver->ContractPrice5 = $request->ContractPrice5;
            $unconditionalWaiver->AmountPaid5 = $request->AmountPaid5;
            $unconditionalWaiver->ThisPayment5 = $request->ThisPayment5;
            $unconditionalWaiver->BalDue5 = $request->BalDue5;
            $unconditionalWaiver->ContractorDate = $request->ContractorDate;
            $unconditionalWaiver->NotaryDay = $request->NotaryDay;
            $unconditionalWaiver->NotaryMonth = $request->NotaryMonth;
            $unconditionalWaiver->NotaryYear = $request->NotaryYear;
            $unconditionalWaiver->NotarySigned = $request->NotarySigned;


            if (!$flag) {
                $unconditionalWaiver->update();
            } else {
                $unconditionalWaiver->save();
            }

            return redirect()->route('project.document.view');
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * Get Document Unconditional Waiver Release form
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getDocumentUnconditionalWaiverRelease($project_id)
    {
        try {
            $flag = 0;
            $project = ProjectDetail::findOrFail($project_id);
            $waiver = UnconditionalWaiverProgress::where('project_id', $project_id)->first();
            if ($waiver) {
                return redirect()->back()->with('doc-success', 'Document Allready Created');
            } else {
                return view('basicUser.document.document_unconditional_waiver_release', [
                    'project_id' => $project->id,
                    'flag' => $flag
                ]);
            }
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * Get Document Conditional Waiver form
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getDocumentConditionalWaiver($project_id)
    {
        try {
            $project = ProjectDetail::findOrFail($project_id);
            return view('basicUser.document.document_conditional_waiver', [
                'project_id' => $project->id,
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * Get Document Conditional Waiver Final form
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getDocumentConditionalWaiverFinal($project_id)
    {
        try {
            $project = ProjectDetail::findOrFail($project_id);
            return view('basicUser.document.document_conditional_waiver_final', [
                'project_id' => $project->id,
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * Get Document Unconditional Waiver Final form
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getDocumentUnconditionalWaiverFinal($project_id)
    {
        try {
            $project = ProjectDetail::findOrFail($project_id);
            return view('basicUser.document.document_unconditional_waiver_final', [
                'project_id' => $project->id,
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * Get Document Partial Waiver form
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getDocumentPartialWaiver($project_id)
    {
        try {
            $project = ProjectDetail::findOrFail($project_id);
            return view('basicUser.document.document_partial_waiver', [
                'project_id' => $project->id,
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * Get Document Partial Waiver Date form
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getDocumentPartialWaiverDate($project_id)
    {
        try {
            $project = ProjectDetail::findOrFail($project_id);
            return view('basicUser.document.document_partial_waiver_date', [
                'project_id' => $project->id,
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * Get Document Standard Waiver Final form
     * @param $project_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getDocumentStandardWaiverFinal($project_id)
    {
        try {
            $project = ProjectDetail::findOrFail($project_id);
            return view('basicUser.document.document_standard_waiver_final', [
                'project_id' => $project->id,
            ]);
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * getJobInfoSheet
     * @param $projectId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getJobInfoSheet($projectId)
    {
        try {
            $project = ProjectDetail::find($projectId);
            $remedy = Remedy::where('state_id', $project->state_id)
                ->where('project_type_id', $project->project_type_id);
            $remedySteps = RemedyStep::whereIn('remedy_id', $remedy->pluck('id'));
            $tiers = TierTable::where('role_id', $project->role_id)
                ->where('customer_id', $project->customer_id)->firstOrFail();
            $tierRemedySteps = TierRemedyStep::where('tier_id', $tiers->id)
                ->whereIn('remedy_step_id', $remedySteps->pluck('id'))
                ->whereIn('answer1', [$project->answer1, ''])
                ->whereIn('answer2', [$project->answer2, '']);
            $remedyStepsNew = $remedySteps->whereIn('id', $tierRemedySteps->pluck('remedy_step_id'));
            $remedyDate = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy->pluck('id'))->whereIn('id', $remedyStepsNew->pluck('remedy_date_id'))->orderBy('date_order', 'ASC')->get();
            $dateFields = [];
            $datesEntered = [];
            foreach ($project->project_date as $date) {
                if ($date->date_value != '') {
                    $dateFormat = \DateTime::createFromFormat('Y-m-d', $date->date_value);
                    $formattedDate = $dateFormat->format('m/d/Y');
                } else {
                    $formattedDate = $date->date_value;
                }
                $datesEntered[$date->id] = ['id' => $date->id, 'remedy' => $date->date_id, 'value' => $formattedDate];
            }
            foreach ($remedyDate as $date) {
                $dateFields[$date->id] = ['id' => $date->id, 'name' => $date->date_name, 'recurring' => $date->recurring, 'dates' => []];
                foreach ($datesEntered as $value) {
                    if ($value['remedy'] == $date->id) {
                        $dateFields[$date->id]['dates'] += [$value['id'] => ['id' => $value['id'], 'remedy' => $value['remedy'], 'value' => $value['value'], 'recurring' => $date->recurring]];
                    }
                }
            }
            $companyData = [];
            $companies = Company::pluck('company', 'id');
            $com = Company::get();
            $companyCont = CompanyContact::get();
            foreach ($com as $company) {
                foreach ($companyCont as $con) {
                    if ($con->id === $company->id) {
                        $companyData += [$company->id => ['id' => $company->id, 'company' => $company->company, 'type' => $con->contact_type]];
                    }
                }
            }
            $firstNames = UserDetails::pluck('first_name', 'id');
            $project = ProjectDetail::find($projectId);
            if (!is_null($project) && $project->user_id == Auth::id()) {
                $states = State::all();
                $projectContactsCompany = ProjectIndustryContactMap::where('projectId', $projectId)->pluck('contactId');
                $companyContacts = MapCompanyContact::whereIn('id', $projectContactsCompany)->get();
                $projectContacts = [];
                $count = 0;
                $contactTypes = [];
                $contactArray = [];
                foreach ($companyContacts as $key => $companyContact) {
                    if (in_array($companyContact->contacts->contact_type, $contactTypes) && in_array($companyContact->company_id, $contactArray)) {
                        foreach ($projectContacts as $key1 => $contact) {
                            if ($contact['company_id'] == $companyContact->company_id && $contact['type'] == $companyContact->contacts->contact_type) {
                                $projectContacts[$key1]['customers'][] = $companyContact->contacts;
                                $projectContacts[$key1]['customer_id'] = $projectContacts[$key1]['customer_id'] . ',' . $companyContact->contacts->id;
                            }
                        }
                    } else {
                        $contactTypes[] = $companyContact->contacts->contact_type;
                        $contactArray[] = $companyContact->company_id;
                        $projectContacts[$count]['company_id'] = $companyContact->company_id;
                        $projectContacts[$count]['type'] = $companyContact->contacts->contact_type;
                        $projectContacts[$count]['company'] = $companyContact->company;
                        $projectContacts[$count]['contactAd'] = $companyContact->address;
                        $projectContacts[$count]['customers'][] = $companyContact;
                        $projectContacts[$count]['customer'][] = $companyContact->contacts;
                        $projectContacts[$count]['customer_id'] = $companyContact->contacts->id;
                        $count++;
                    }
                }
                // Log::info($project->id);
                $jobInfoSheet = JobInfo::where('project_id', $projectId)->get()->first();
                if ($jobInfoSheet != '') {
                    //$jobInfoSheet = new \stdClass();
                    if(isset($jobInfoSheet->customer_company_id) && !empty($jobInfoSheet->customer_company_id)) {
                        $user = User::findOrFail($jobInfoSheet->customer_company_id);
                    } else {
                        return view('errors.403');
                    }
                } else {
                    $user = User::findOrFail($project->user_id);
                }

                $contract = ProjectContract::where('project_id', $projectId)->get()->first();
                if (!empty($contract)) {
                    $contractTotal = $contract->total_claim_amount;
                } else {
                    $contractTotal = '0';
                }
                $companyMap = MapCompanyContact::find($project->customer_contract_id);
                $companyExists = '';
                if($companyMap) {
                    $companyExists = Company::find($companyMap->company_id);
                }

                return view('basicUser.document.job_info_sheet', [
                    'project_id' => $project->id,
                    'project' => $project,
                    'user' => $user,
                    'states' => $states,
                    'projectContacts' => $projectContacts,
                    'jobInfoSheet' => $jobInfoSheet,
                    'companies' => $companies,
                    'first_names' => $firstNames,
                    'contactInfo' => $companyData,
                    'contract' => $contract,
                    'projectDates' => $dateFields,
					'companyExists' =>$companyExists
                ]);
            } elseif (is_null($project)) {
                return view('errors.custom_error', ['title' => '404', 'message' => 'No Projects Found']);
            } else {
                return view('errors.403');
            }
        } catch (\Exception $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
            // return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return view('errors.exceptions', ['exception' => $e]);
            //return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }
    public function getExpressJobInfoSheet($projectId)
    {
        try {
            $project = ProjectDetail::find($projectId);
            $remedy = Remedy::where('state_id', $project->state_id)
                ->where('project_type_id', $project->project_type_id);
            $remedySteps = RemedyStep::whereIn('remedy_id', $remedy->pluck('id'));
            $tiers = TierTable::where('role_id', $project->role_id)
                ->where('customer_id', $project->customer_id)->firstOrFail();
            $tierRemedySteps = TierRemedyStep::where('tier_id', $tiers->id)
                ->whereIn('remedy_step_id', $remedySteps->pluck('id'))
                ->whereIn('answer1', [$project->answer1, ''])
                ->whereIn('answer2', [$project->answer2, '']);
            $remedyStepsNew = $remedySteps->whereIn('id', $tierRemedySteps->pluck('remedy_step_id'));
            $remedyDate = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy->pluck('id'))->whereIn('id', $remedyStepsNew->pluck('remedy_date_id'))->orderBy('date_order', 'ASC')->get();
            $dateFields = [];
            $datesEntered = [];
            foreach ($project->project_date as $date) {
                if ($date->date_value != '') {
                    $dateFormat = \DateTime::createFromFormat('Y-m-d', $date->date_value);
                    $formattedDate = $dateFormat->format('m/d/Y');
                } else {
                    $formattedDate = $date->date_value;
                }
                $datesEntered[$date->id] = ['id' => $date->id, 'remedy' => $date->date_id, 'value' => $formattedDate];
            }
            foreach ($remedyDate as $date) {
                $dateFields[$date->id] = ['id' => $date->id, 'name' => $date->date_name, 'recurring' => $date->recurring, 'dates' => []];
                foreach ($datesEntered as $value) {
                    if ($value['remedy'] == $date->id) {
                        $dateFields[$date->id]['dates'] += [$value['id'] => ['id' => $value['id'], 'remedy' => $value['remedy'], 'value' => $value['value'], 'recurring' => $date->recurring]];
                    }
                }
            }
            $companyData = [];
            $companies = Company::pluck('company', 'id');
            $com = Company::get();
            $companyCont = CompanyContact::get();
            foreach ($com as $company) {
                foreach ($companyCont as $con) {
                    if ($con->id === $company->id) {
                        $companyData += [$company->id => ['id' => $company->id, 'company' => $company->company, 'type' => $con->contact_type]];
                    }
                }
            }
            $firstNames = UserDetails::pluck('first_name', 'id');
            $project = ProjectDetail::find($projectId);
            if (!is_null($project) && $project->user_id == Auth::id()) {
                $states = State::all();
                $projectContactsCompany = ProjectIndustryContactMap::where('projectId', $projectId)->pluck('contactId');
                $companyContacts = MapCompanyContact::whereIn('id', $projectContactsCompany)->get();
                $projectContacts = [];
                $count = 0;
                $contactTypes = [];
                $contactArray = [];
                foreach ($companyContacts as $key => $companyContact) {
                    if (in_array($companyContact->contacts->contact_type, $contactTypes) && in_array($companyContact->company_id, $contactArray)) {
                        foreach ($projectContacts as $key1 => $contact) {
                            if ($contact['company_id'] == $companyContact->company_id && $contact['type'] == $companyContact->contacts->contact_type) {
                                $projectContacts[$key1]['customers'][] = $companyContact->contacts;
                                $projectContacts[$key1]['customer_id'] = $projectContacts[$key1]['customer_id'] . ',' . $companyContact->contacts->id;
                            }
                        }
                    } else {
                        $contactTypes[] = $companyContact->contacts->contact_type;
                        $contactArray[] = $companyContact->company_id;
                        $projectContacts[$count]['company_id'] = $companyContact->company_id;
                        $projectContacts[$count]['type'] = $companyContact->contacts->contact_type;
                        $projectContacts[$count]['company'] = $companyContact->company;
                        $projectContacts[$count]['contactAd'] = $companyContact->address;
                        $projectContacts[$count]['customers'][] = $companyContact;
                        $projectContacts[$count]['customer'][] = $companyContact->contacts;
                        $projectContacts[$count]['customer_id'] = $companyContact->contacts->id;
                        $count++;
                    }
                }

                $jobInfoSheet = JobInfo::where('project_id', $projectId)->first();
                if ($jobInfoSheet != '') {
                    $jobInfoSheet = new \stdClass();
                    $user = User::findOrFail($jobInfoSheet->customer_company_id);
                } else {
                    $user = User::findOrFail($project->user_id);
                }
                $contract = ProjectContract::where('project_id', $projectId)->first();
                if (!empty($contract)) {
                    $contractTotal = $contract->total_claim_amount;
                } else {
                    $contractTotal = '0';
                }
                return view('basicUser.document.express_job_info_sheet', [
                    'project_id' => $project->id,
                    'project' => $project,
                    'user' => $user,
                    'states' => $states,
                    'projectContacts' => $projectContacts,
                    'jobInfoSheet' => $jobInfoSheet,
                    'companies' => $companies,
                    'first_names' => $firstNames,
                    'contactInfo' => $companyData,
                    'contract' => $contract,
                    'projectDates' => $dateFields
                ]);
            } elseif (is_null($project)) {
                return view('errors.custom_error', ['title' => '404', 'message' => 'No Projects Found']);
            } else {
                return view('errors.403');
            }
        } catch (\Exception $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
            // return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return view('errors.exceptions', ['exception' => $e]);
            //return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * getOwnerNoticeSheet
     * @param $projectId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getOwnerNoticeSheet($projectId)
    {
        try {
            $project = ProjectDetail::find($projectId);
//            if (isset($project->state) && $project->state->name != 'Florida') {
//                return redirect()->back()->with('try-error', 'You are not allowed to visit this page!!!');
//            }
            $remedy = Remedy::where('state_id', $project->state_id)
                ->where('project_type_id', $project->project_type_id);
            $remedySteps = RemedyStep::whereIn('remedy_id', $remedy->pluck('id'));
            $tiers = TierTable::where('role_id', $project->role_id)
                ->where('customer_id', $project->customer_id)->firstOrFail();
            $tierRemedySteps = TierRemedyStep::where('tier_id', $tiers->id)
                ->whereIn('remedy_step_id', $remedySteps->pluck('id'))
                ->whereIn('answer1', [$project->answer1, ''])
                ->whereIn('answer2', [$project->answer2, '']);
            $remedyStepsNew = $remedySteps->whereIn('id', $tierRemedySteps->pluck('remedy_step_id'));
            $remedyDate = RemedyDate::where('status', '1')->whereIn('remedy_id', $remedy->pluck('id'))->whereIn('id', $remedyStepsNew->pluck('remedy_date_id'))->orderBy('date_order', 'ASC')->get();
            $dateFields = [];
            $datesEntered = [];
            foreach ($project->project_date as $date) {
                if ($date->date_value != '') {
                    $dateFormat = \DateTime::createFromFormat('Y-m-d', $date->date_value);
                    $formattedDate = $dateFormat->format('m/d/Y');
                } else {
                    $formattedDate = $date->date_value;
                }
                $datesEntered[$date->id] = ['id' => $date->id, 'remedy' => $date->date_id, 'value' => $formattedDate];
            }
            foreach ($remedyDate as $date) {
                $dateFields[$date->id] = ['id' => $date->id, 'name' => $date->date_name, 'recurring' => $date->recurring, 'dates' => []];
                foreach ($datesEntered as $value) {
                    if ($value['remedy'] == $date->id) {
                        $dateFields[$date->id]['dates'] += [$value['id'] => ['id' => $value['id'], 'remedy' => $value['remedy'], 'value' => $value['value'], 'recurring' => $date->recurring]];
                    }
                }
            }
            $companyData = [];
            $companies = Company::pluck('company', 'id');
            $com = Company::get();
            $companyCont = CompanyContact::get();
            foreach ($com as $company) {
                foreach ($companyCont as $con) {
                    if ($con->id === $company->id) {
                        $companyData += [$company->id => ['id' => $company->id, 'company' => $company->company, 'type' => $con->contact_type]];
                    }
                }
            }
            $firstNames = UserDetails::pluck('first_name', 'id');
            $project = ProjectDetail::find($projectId);
            if (!is_null($project) && $project->user_id == Auth::id()) {
                $states = State::all();
                $projectContactsCompany = ProjectIndustryContactMap::where('projectId', $projectId)->pluck('contactId');
                $companyContacts = MapCompanyContact::whereIn('id', $projectContactsCompany)->get();
                $projectContacts = [];
                $count = 0;
                $contactTypes = [];
                $contactArray = [];
                foreach ($companyContacts as $key => $companyContact) {
                    if (in_array($companyContact->contacts->contact_type, $contactTypes) && in_array($companyContact->company_id, $contactArray)) {
                        foreach ($projectContacts as $key1 => $contact) {
                            if ($contact['company_id'] == $companyContact->company_id && $contact['type'] == $companyContact->contacts->contact_type) {
                                $projectContacts[$key1]['customers'][] = $companyContact->contacts;
                                $projectContacts[$key1]['customer_id'] = $projectContacts[$key1]['customer_id'] . ',' . $companyContact->contacts->id;
                            }
                        }
                    } else {
                        $contactTypes[] = $companyContact->contacts->contact_type;
                        $contactArray[] = $companyContact->company_id;
                        $projectContacts[$count]['company_id'] = $companyContact->company_id;
                        $projectContacts[$count]['type'] = $companyContact->contacts->contact_type;
                        $projectContacts[$count]['company'] = $companyContact->company;
                        $projectContacts[$count]['contactAd'] = $companyContact->address;
                        $projectContacts[$count]['customers'][] = $companyContact;
                        $projectContacts[$count]['customer'][] = $companyContact->contacts;
                        $projectContacts[$count]['customer_id'] = $companyContact->contacts->id;
                        $count++;
                    }
                }
                $jobInfoSheet = JobInfo::where('project_id', $projectId)->first();
                if ($jobInfoSheet != '') {
                    $user = User::findOrFail($jobInfoSheet->customer_company_id);
                } else {
                    $user = User::findOrFail($project->user_id);
                }
                $contract = ProjectContract::where('project_id', $projectId)->first();
                if (!empty($contract)) {
                    $contractTotal = $contract->total_claim_amount;
                } else {
                    $contractTotal = '0';
                }

                $lienProviders = LienProvider::join('lien_provider_states', function ($join) {
                    $join->on('lien_provider_states.lien_id', '=', 'lien_providers.id');
                })
                    ->where('state_id', $project->state_id)
                    ->where('role_name', 'notice_service')->first();

                return view('basicUser.document.owner_notice_sheet', [
                    'project_id' => $project->id,
                    'project' => $project,
                    'user' => $user,
                    'lienProvider' => $lienProviders,
                    'states' => $states,
                    'projectContacts' => $projectContacts,
                    'jobInfoSheet' => $jobInfoSheet,
                    'companies' => $companies,
                    'first_names' => $firstNames,
                    'contactInfo' => $companyData,
                    'contract' => $contract,
                    'projectDates' => $dateFields
                ]);
            } elseif (is_null($project)) {
                return view('errors.custom_error', ['title' => '404', 'message' => 'No Projects Found']);
            } else {
                return view('errors.403');
            }
        } catch (\Exception $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
            // return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return view('errors.exceptions', ['exception' => $e]);
            //return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }


    /**
     * getJobDocuments
     * @param $projectId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getJobDocuments($projectId)
    {
        try {
            $project = ProjectDetail::find($projectId);
            $project = ProjectDetail::find($projectId);
            if (!is_null($project) && $project->user_id == Auth::id()) {
                $jobInfoSheet = JobInfo::where('project_id', $projectId)->first();
                return view('basicUser.document.job_document_sheet', [
                    'project_id' => $project->id,
                    'project' => $project,
                    'jobInfoSheet' => $jobInfoSheet
                ]);
            } elseif (is_null($project)) {
                return view('errors.custom_error', ['title' => '404', 'message' => 'No Projects Found']);
            } else {
                return view('errors.403');
            }
        } catch (\Exception $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
            // return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return view('errors.exceptions', ['exception' => $e]);
        }
    }


    /**
     * getProjectManagementDocuments
     * @param $projectId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getProjectDocuments($projectId)
    {
        try {
//            $project = ProjectDetail::find($projectId);
            $project = ProjectDetail::find($projectId);
            if (!is_null($project) && $project->user_id == Auth::id()) {
                $jobInfoSheet = JobInfo::where('project_id', $projectId)->first();
                return view('basicUser.projects.viewdocuments', [
                    'project_id' => $project->id,
                    'project' => $project,
                    'selected_project' => $project,
                    'jobInfoSheet' => $jobInfoSheet
                ]);
            } elseif (is_null($project)) {
                return view('errors.custom_error', ['title' => '404', 'message' => 'No Projects Found']);
            } else {
                return view('errors.403');
            }
        } catch (\Exception $exception) {
            return view('errors.exceptions', ['exception' => $exception]);
            // return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return view('errors.exceptions', ['exception' => $e]);
        }
    }

    /**
     * getJObInfo
     * @param $projectId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getJobInfo($projectId)
    {
        try {
            $project = ProjectDetail::find($projectId);
            if (!is_null($project) && $project->user_id == Auth::id()) {
                $jobInfo = JobInfo::where('project_id', $projectId)->firstOrFail();
                $states = State::all();
                return view('basicUser.document.job_info_sheet_edit', [
                    'project_id' => $project->id,
                    'project' => $project,
                    'job_info' => $jobInfo,
                    'states' => $states
                ]);
            } elseif (is_null($project)) {
                return view('errors.custom_error', ['title' => '404', 'message' => 'No Projects Found']);
            } else {
                return view('errors.403');
            }
        } catch (\Exception $exception) {
            return redirect()->route('member.dashboard')->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->route('member.dashboard')->with('try-error', $e->getMessage());
        }
    }

    /**
     * @param $projectId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function claimForm($projectId)
    {
        try {
            $project = ProjectDetail::findOrFail($projectId);
            $state = State::all();
            return view('basicUser.claim.new_clam1', [
                'states' => $state
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        }
    }

    /**
     * @param $file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getView($file)
    {
        try {
            $ext = \File::extension($file);

            if ($ext == 'pdf') {
                $content_types = 'application/pdf';
            } elseif ($ext == 'doc') {
                $content_types = 'application/msword';
            } elseif ($ext == 'docx') {
                $content_types = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
            } elseif ($ext == 'xls') {
                $content_types = 'application/vnd.ms-excel';
            } elseif ($ext == 'xlsx') {
                $content_types = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            } elseif ($ext == 'txt') {
                $content_types = 'application/octet-stream';
            } else {
                $content_types = 'image/jpeg';
            }
            return response()->file(env('ASSET_URL') . '/upload/' . $file, [
                'Content-Type' => $content_types
            ]);
        } catch (\Exception $exception) {
            return redirect()->back()->with('try-error', $exception->getMessage());
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autoComplete(Request $request)
    {
        $data = Contact::query();
        $data->where('user_id', Auth::user()->id);
        if (isset($request->type) && $request->type != '') {
            if ($request->type == 'customer') {
                $data->Where('type', '0');
            } else {
                $data->Where('type', '1');
            }
        }
        if (isset($request->key) && $request->key != '') {
            $data->Where('company', 'Like', '%' . $request->key . '%');
        }
        $datas = $data->with('contactInformation')->get();
        $resalt = [];
        foreach ($datas as $key => $data) {
            $resalt[$key]['id'] = $data->id;
            $resalt[$key]['company'] = $data->company;
            $resalt[$key]['data'] = $data;
        }
        return response()->json([
            'data' => $resalt
        ], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autoCompleteContract(Request $request)
    {
        $data = ContactInformation::query();
        $data->where('contact_id', $request->id);
        if (isset($request->key) && $request->key != '') {
            $data->where('first_name', 'LIKE', '%' . $request->key . '%');
        }
        $datas = $data->get();
        return response()->json([
            'data' => $datas
        ], 200);
    }


    public function projectDocument(Request $request)
    {
        $projects = ProjectDetail::all();
        return view('basicUser.document.new_project_document', [
            'projects' => $projects,

        ]);
        die("qqqqqqqqqqqqqqqqqqqqq");
    }

    public function saveProjectDocument(Request $request)
    {
        $projects = ProjectDetail::all();
        try {
            $filename = '';
            foreach ($request->input('document', []) as $file) {
                $filename = $file;
                // $project->addMedia(storage_path('tmp/uploads/' . $file))->toMediaCollection('document');
            }
            $ldate = date('Y-m-d');
            $insertDetails = [
                'title' => $request->project_name,
                'project_id' => $request->project_id,
                'date' => $ldate,
                'notes' => '',
                'filename' => $filename
            ];

            DB::table('project_documents')->insert($insertDetails);
            // return view('basicUser.document.new_project_document', [
            //     'projects' => $projects,

            // ]);

            // return redirect()->route('member.document');
            return redirect()->back()->with('doc-success', 'Document Created');
        } catch (\Exception $e) {
            return redirect()->back()->with('try-error', $e->getMessage());
        }
    }


    /**
     * Save Job Files
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveJobFile(Request $request)
    {
        ini_set('memory_limit', '-1');
        $code = $message = $status = '';
        // dd($request);
        try {
            $file = $request->file('file');
            $extension = \File::extension($file->getClientOriginalName());
            if (strtolower($extension) != "sql") {
                // $file = $request->lien;
                $fileName = time() . '.' . $extension;
                $path = base_path();
                $filePath = $path . "/public/upload";
                $file->move($filePath, $fileName);

                return response()->json([
                    'status' => true,
                    'message' => 'Upload successful',
                    'name' => $fileName,
                    'time' => str_replace('.', '_', $fileName),
                ], 200);
            } else {
                $status = false;
                $message = 'Upload a valid file111111111';
                $code = 200;
            }
        } catch (Exception $e) {
            $status = false;
            $message = $e->getMessage();
            $code = 200;
        } catch (ModelNotFoundException $e) {
            $status = false;
            $message = $e->getMessage();
            $code = 200;
        }
        return response()->json([
            'status' => $status,
            'message' => $message,
        ], $code);
    }
}
