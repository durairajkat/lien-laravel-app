<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>NLB Claim Form: Project Data Sheet</title>
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/document.css" type="text/css">
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/document_print.css" type="text/css" media="print">
    <!-- jQuery 3 -->
    <script src="{{ env('ASSET_URL') }}/admin_assets/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ env('ASSET_URL') }}/js/jquery-ui.min.js"></script>
</head>

<body onLoad="doOnLoad()">
    <form action="{{ route('get.documentClaimDataComplete', [$project_id]) }}" method="post"
        enctype="multipart/form-data">

        <input type="hidden" name="ID" value="">
        <input type="hidden" name="ProjectID" value="">
        <table width="670" border="1" align="center" style="border:1px solid #000; border-collapse:collapse;">
            {{ csrf_field() }}
            <tr>
                <td colspan="2" align="center" class="BorderBottom">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="670">
                                <table width="670" border="0" cellpadding="0" cellspacing="0"
                                    style=" border-collapse: collapse;">
                                    <tr>
                                        <td width="174">
                                            <div align="center"><img src="{{ env('ASSET_URL') }}/images/nlb.jpg" />
                                            </div>
                                        </td>
                                        <td width="492">
                                            <div align="center" class="NLBHeader">National Lien Platform Claim Form
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style=" border-bottom:1px solid #000; text-align:center">
                                            <strong>Fax: / Phone: </strong>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="BorderBottom">
                <td style="border-right:1px solid #000;" class="BorderBottom" valign="top" width="50%">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>1.Project Name:</strong></span>
                                <input name="ProjectName" type="text"
                                    value="{{ isset($claimForm->p_name) ? $claimForm->p_name : '' }}" maxlength="64"
                                    style="width:220px">
                                <br>
                                <span class="FieldLabel">Address:</span>
                                <input name="ProjectAddress" type="text"
                                    value=" {{ isset($claimForm->p_address) ? $claimForm->p_address : '' }} "
                                    maxlength="32" style="width:269px;">
                                <br>
                                <span class="FieldLabel">City
                                    <input name="ProjectCity" type="text"
                                        value="{{ isset($claimForm->p_city) ? $claimForm->p_city : '' }}"
                                        maxlength="32" style="width:80px">
                                    State
                                    <input name="ProjectState" type="text"
                                        value="{{ isset($claimForm->p_state) ? $claimForm->p_state : '' }} "
                                        maxlength="32" style="width:80px">
                                    Zip</span>
                                <input name="ProjectZip" type="text"
                                    value="{{ isset($claimForm->p_zip) ? $claimForm->p_zip : '' }} " maxlength="16"
                                    style="width:80px">
                                <span class="FieldLabel">Phone &amp; Fax:</span>
                                <input name="ProjectPhone" type="text"
                                    value="{{ isset($claimForm->p_phone) ? $claimForm->p_phone : '' }}"
                                    maxlength="32" style="width:246px">
                                <br>
                                <span class="FieldLabel">County of Property:</span>
                                <input name="ProjectCounty" type="text"
                                    value="{{ isset($claimForm->p_country_of_property) ? $claimForm->p_country_of_property : '' }}"
                                    style="width:216px">
                            </td>
                        </tr>
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>2.Project Owner or Public Agency:
                                    </strong></span>
                                <input name="ProjectOwnerCompany" type="text"
                                    value="{{ isset($claimForm->p_owner_company) ? $claimForm->p_owner_company : '' }}"
                                    maxlength="32" style="width:125px">
                                <!-- <input type="button" value="Add Contact" onClick="addContact()"> -->
                                <br>
                                <span class="FieldLabel">Contact Name:</span>
                                <input type="text" name="ProjectOwnerContact"
                                    value="{{ isset($claimForm->p_owner_contact) ? $claimForm->p_owner_contact : '' }}"
                                    maxlength="32" style="width:236px">
                                <br>
                                <span class="FieldLabel">Address:</span>
                                <input type="text" name="ProjectOwnerAddress"
                                    value="{{ isset($claimForm->p_owner_address) ? $claimForm->p_owner_address : '' }}"
                                    maxlength="32" style="width:269px;">
                                <br>
                                <span class="FieldLabel">City
                                    <input type="text" name="ProjectOwnerCity"
                                        value="{{ isset($claimForm->p_owner_city) ? $claimForm->p_owner_city : '' }}"
                                        maxlength="32" style="width:80px">
                                    State
                                    <input type="text" name="ProjectOwnerState"
                                        value="{{ isset($claimForm->p_owner_state) ? $claimForm->p_owner_state : '' }}"
                                        maxlength="32" style="width:80px">
                                    Zip
                                    <input type="text" name="ProjectOwnerZip"
                                        value="{{ isset($claimForm->p_owner_zip) ? $claimForm->p_owner_zip : '' }}"
                                        maxlength="32" style="width:80px">
                                </span><br>
                                <span class="FieldLabel">Phone &amp; Fax:</span>
                                <input type="text" name="ProjectOwnerPhone"
                                    value="{{ isset($claimForm->p_owner_phone) ? $claimForm->p_owner_phone : '' }}"
                                    maxlength="32" style="width:246px">
                            </td>
                        </tr>
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>3.Original Contractor:
                                    </strong></span>
                                <input type="text" name="OriginalContractorCompany"
                                    value="{{ isset($claimForm->o_contractor_company) ? $claimForm->o_contractor_company : '' }}"
                                    maxlength="32" style="width:190px">
                                <br>
                                <span class="FieldLabel">Contact Name:</span>
                                <input type="text" name="OriginalContractorContact"
                                    value="{{ isset($claimForm->o_contractor_contact) ? $claimForm->o_contractor_contact : '' }}"
                                    maxlength="32" style="width:236px">
                                <br>
                                <span class="FieldLabel">Address:</span>
                                <input type="text" name="OriginalContractorAddress"
                                    value="{{ isset($claimForm->o_contractor_address) ? $claimForm->o_contractor_address : '' }}"
                                    maxlength="32" style="width:269px">
                                <br>
                                <span class="FieldLabel">City
                                    <input type="text" name="OriginalContractorCity"
                                        value="{{ isset($claimForm->o_contractor_city) ? $claimForm->o_contractor_city : '' }}"
                                        maxlength="32" style="width:80px">
                                    State
                                    <input type="text" name="OriginalContractorState"
                                        value="{{ isset($claimForm->o_contractor_state) ? $claimForm->o_contractor_state : '' }}"
                                        maxlength="32" style="width:80px">
                                    Zip</span>
                                <input type="text" name="OriginalContractorZip"
                                    value="{{ isset($claimForm->o_contractor_zip) ? $claimForm->o_contractor_zip : '' }}"
                                    maxlength="16" style="width:80px">
                                <br>
                                <span class="FieldLabel">Phone &amp; Fax:</span>
                                <input type="text" name="OriginalContractorPhone"
                                    value="{{ isset($claimForm->o_contractor_phone) ? $claimForm->o_contractor_phone : '' }}"
                                    maxlength="32" style="width:246px">
                            </td>
                        </tr>
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>4.Subcontractor: </strong></span>
                                <input type="text" name="SubContractorCompany"
                                    value="{{ isset($claimForm->s_contractor_company) ? $claimForm->s_contractor_company : '' }}"
                                    maxlength="32" style="width:228px">
                                <span class="FieldLabel">Contact Name:</span>
                                <input type="text" name="SubContractorContact"
                                    value="{{ isset($claimForm->s_contractor_contact) ? $claimForm->s_contractor_contact : '' }}"
                                    maxlength="32" style="width:236px">
                                <br>
                                <span class="FieldLabel">Address:</span>
                                <input type="text" name="SubContractorAddress"
                                    value="{{ isset($claimForm->s_contractor_address) ? $claimForm->s_contractor_address : '' }}"
                                    maxlength="32" style="width:269px">
                                <br>
                                <span class="FieldLabel">City
                                    <input type="text" name="SubContractorCity"
                                        value="{{ isset($claimForm->s_contractor_city) ? $claimForm->s_contractor_city : '' }}"
                                        maxlength="32" style="width:80px">
                                    State
                                    <input type="text" name="SubContractorState"
                                        value="{{ isset($claimForm->s_contractor_state) ? $claimForm->o_contractor_state : '' }}"
                                        maxlength="32" style="width:80px">
                                    Zip</span>
                                <input type="text" name="SubContractorZip"
                                    value="{{ isset($claimForm->s_contractor_zip) ? $claimForm->s_contractor_zip : '' }}"
                                    maxlength="16" style="width:80px">
                                <br>
                                <span class="FieldLabel">Phone &amp; Fax:</span>
                                <input type="text" name="SubContractorPhone"
                                    value="{{ isset($claimForm->s_contractor_phone) ? $claimForm->s_contractor_phone : '' }}"
                                    maxlength="32" style="width:246px">
                            </td>
                        </tr>
                        <tr>
                            <td class="BorderBottom">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="29%" valign="top"><span class="FieldLabel"
                                                style="display:block; float:left;"><strong>5. Is the
                                                    Project:</strong></span> </td>
                                        <td width="71%"><span class="FieldLabel">
                                                <label><input name="ProjectType" type="radio" value="Privately Owned"
                                                        style="border-bottom:none"
                                                        {{ $claimForm->project_type == 'Privately Owned' ? 'checked' : '' }}>
                                                    Privately Owned</label>
                                                <label><input type="radio" name="ProjectType" value="Public Works"
                                                        style="border-bottom:none"
                                                        {{ $claimForm->project_type == 'Public Works' ? 'checked' : '' }}>
                                                    Public Works </label><br>
                                                <label><input type="radio" name="ProjectType" value="Local Owned"
                                                        style="border-bottom:none"
                                                        {{ $claimForm->project_type == 'Local Owned' ? 'checked' : '' }}>
                                                    Local Owned</label>
                                                <label><input type="radio" name="ProjectType" value="Federal"
                                                        style="border-bottom:none"
                                                        {{ $claimForm->project_type == 'Federal' ? 'checked' : '' }}>
                                                    Federal</label></span></td>
                                    </tr>
                                </table>
                                <span class="FieldLabel">Contract #</span>
                                <input type="text" name="ContractNumber"
                                    value="{{ isset($claimForm->contact_number) ? $claimForm->contact_number : '' }}"
                                    style="width:262px">
                                <br>
                                <span class="FieldLabel">Project#</span>
                                <input type="text" name="ProjectNumber"
                                    value="{{ isset($claimForm->project_number) ? $claimForm->project_number : '' }}"
                                    style="width:273px">
                            </td>
                        </tr>
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>6. Project Notice:</strong> Has
                                    the GC or Owner filed a notice that the Project has commenced?</span><br>
                                <span class="FieldLabel">
                                    <input name="ProjectNotice" type="radio" value="Yes"
                                        {{ $claimForm->project_notice == 'Yes' ? 'checked' : '' }}>
                                    Yes (Fax copies to NLB)
                                    <input type="radio" name="ProjectNotice" value="No"
                                        {{ $claimForm->project_notice == 'No' ? 'checked' : '' }}>
                                    No</span>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="FieldLabel"><strong>7. All Jobs:</strong> Is there a payment bond?
                                    <input name="PaymentBond" type="radio" value="Yes">
                                    Yes
                                    <input type="radio" name="PaymentBond" value="No">
                                    No</span><br>
                                <span class="FieldLabel">If Yes, give payment bond #:</span>
                                <input name="PaymentBondNumber" type="text"
                                    value="{{ isset($claimForm->payment_bond_number) ? $claimForm->payment_bond_number : '' }}"
                                    maxlength="32" style="width:166px">
                                <br>
                                <span class="FieldLabel">Name of bonding company:</span>
                                <input name="BondCompany" type="text"
                                    value="{{ isset($claimForm->bond_comapny) ? $claimForm->bond_company : '' }}"
                                    maxlength="32" style="width:167px">
                                <br>
                                <span class="FieldLabel">Contact Name:</span>
                                <input type="text" name="BondContact"
                                    value="{{ isset($claimForm->bond_contact) ? $claimForm->bond_contact : '' }}"
                                    maxlength="32" style="width:236px">
                                <br>
                                <span class="FieldLabel">Address:</span>
                                <input type="text" name="BondAddress"
                                    value="{{ isset($claimForm->bond_address) ? $claimForm->bond_address : '' }}"
                                    maxlength="32" style="width:269px">
                                <br>
                                <span class="FieldLabel">City/State/Zip:</span>
                                <input type="text" name="BondCity"
                                    value="{{ isset($claimForm->bond_city) ? $claimForm->bond_city : '' }}"
                                    maxlength="32" style="width:80px">
                                /
                                <input type="text" name="BondState"
                                    value="{{ isset($claimForm->bond_state) ? $claimForm->bond_state : '' }}"
                                    maxlength="32" style="width:80px">
                                /
                                <input type="text" name="BondZip"
                                    value="{{ isset($claimForm->bond_zip) ? $claimForm->bond_zip : '' }}"
                                    maxlength="16" style="width:80px">
                                <br>
                                <span class="FieldLabel">Phone:</span>
                                <input type="text" name="BondPhone"
                                    value="{{ isset($claimForm->bond_phone) ? $claimForm->bond_phone : '' }}"
                                    maxlength="16" style="width:246px">
                            </td>
                        </tr>
                    </table>
                </td>
                <td valign="top" class="BorderBottom">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>9.Your Customer:</strong></span>
                                <input type="text" name="CustomerCompany"
                                    value="{{ isset($claimForm->c_company) ? $claimForm->c_company : '' }}"
                                    maxlength="32" style="width:216px">
                                <br>
                                <span class="FieldLabel">Contact Name:</span>
                                <input type="text" name="CustomerContact"
                                    value="{{ isset($claimForm->c_contact) ? $claimForm->c_contact : '' }}"
                                    maxlength="32" style="width:232px">
                                <br>
                                <span class="FieldLabel">Address:</span>
                                <input type="text" name="CustomerAddress"
                                    value="{{ isset($claimForm->c_address) ? $claimForm->c_address : '' }}"
                                    maxlength="32" style="width:265px">
                                <br>
                                <span class="FieldLabel">City
                                    <input type="text" name="CustomerCity"
                                        value="{{ isset($claimForm->c_city) ? $claimForm->c_city : '' }}"
                                        maxlength="32" style="width:80px">
                                    State
                                    <input type="text" name="CustomerState"
                                        value="{{ isset($claimForm->c_state) ? $claimForm->c_state : '' }}"
                                        maxlength="32" style="width:80px">
                                    Zip</span>
                                <input type="text" name="CustomerZip"
                                    value="{{ isset($claimForm->c_zip) ? $claimForm->c_zip : '' }}" maxlength="32"
                                    style="width:80px">
                                <br>
                                <span class="FieldLabel">Phone:</span>
                                <input type="text" name="CustomerPhone"
                                    value="{{ isset($claimForm->c_phone) ? $claimForm->c_phone : '' }}"
                                    maxlength="32" style="width:242px">
                                <br>
                                <span class="FieldLabel">No.:</span>
                                <input type="text" name="CustomerNumber"
                                    value="{{ isset($claimForm->c_number) ? $claimForm->c_number : '' }}"
                                    maxlength="32" style="width:292px">
                                <br>
                                <span class="FieldLabel">Account #.:</span>
                                <input type="text" name="CustomerAccount"
                                    value="{{ isset($claimForm->c_account) ? $claimForm->c_account : '' }}"
                                    maxlength="32" style="width:255px">
                            </td>
                        </tr>
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>10.Your Order :
                                    </strong></span><br>
                                <span class="FieldLabel">Value of order: $</span>
                                <input type="text" name="OrderValue"
                                    value="{{ isset($claimForm->order_value) ? $claimForm->order_value : '' }}"
                                    maxlength="10" style="width:228px">
                                <br>
                                <span class="FieldLabel">Job No.: </span>
                                <input type="text" name="JobNumber"
                                    value="{{ isset($claimForm->job_number) ? $claimForm->job_number : '' }}"
                                    maxlength="32" style="width:276px">
                                <br>
                                <span class="FieldLabel">P.O.No.:</span>
                                <input type="text" name="PONumber"
                                    value="{{ isset($claimForm->pon_number) ? $claimForm->pon_number : '' }}"
                                    maxlength="32" style="width:272px">
                                <br>
                                <span class="FieldLabel">Contract No.:</span>
                                <input type="text" name="OrderContractNumber"
                                    value="{{ isset($claimForm->order_contract_number) ? $claimForm->order_contract_number : '' }}"
                                    maxlength="32" style="width:246px">
                                <br>
                                <span class="FieldLabel">Date Products needed.:</span>
                                <input type="text" name="DateNeeded"
                                    value="{{ isset($claimForm->date_product_needed) ? $claimForm->date_product_needed : '' }}"
                                    style="width:188px">
                                <br>
                                <span class="FieldLabel">Approximate start work date:</span>
                                <input type="text" name="StartDate"
                                    value="{{ isset($claimForm->start_date) ? $claimForm->start_date : '' }}"
                                    style="width:164px">
                            </td>
                        </tr>
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>11. Credit
                                        Information:</strong></span><br>
                                <span class="FieldLabel">Payment Terms :</span>
                                <input type="text" name="PaymentTerms"
                                    value="{{ isset($claimForm->payment_terms) ? $claimForm->payment_terms : '' }}"
                                    maxlength="32" style="width:224px">
                                <br>
                                <span class="FieldLabel">Billing Cycle:</span>
                                <input type="text" name="BillingCycle"
                                    value="{{ isset($claimForm->billing_cycle) ? $claimForm->billing_cycle : '' }}"
                                    maxlength="32" style="width:248px">
                                <br>
                                <span class="FieldLabel">
                                    <input name="PaymentType" type="radio" value="Joint Check"
                                        {{ $claimForm->payment_type == 'Joint Check' ? 'checked' : '' }}>
                                    Joint Check
                                    <input name="PaymentType" type="radio" value="Direct Payment"
                                        {{ $claimForm->payment_type == 'Direct Payment' ? 'checked' : '' }}>
                                    Direct Payment</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>12.Your Status in Project:
                                    </strong></span><br>
                                <label class="FieldLabel" style="clear:left; ">
                                    <input name="YourStatus" type="Radio" value="General Contractor"
                                        {{ $claimForm->status == 'General Contractor' ? 'checked' : '' }}>
                                    General Contractor</label> <br>
                                <label class="FieldLabel" style="clear:left; ">
                                    <input type="Radio" name="YourStatus" value="Subcontractor"
                                        {{ $claimForm->status == 'Subcontractor' ? 'checked' : '' }}>
                                    Subcontractor </label><br>
                                <label class="FieldLabel" style="clear:left; ">
                                    <input type="Radio" name="YourStatus" value="Supplier To Subcontractor"
                                        {{ $claimForm->status == 'Supplier To Subcontractor' ? 'checked' : '' }}>
                                    Supplier to Subcontractor </label><br>
                                <label class="FieldLabel" style="clear:left; ">
                                    <input type="Radio" name="YourStatus" value="Supplier Supplier"
                                        {{ $claimForm->status == 'Supplier Supplier' ? 'checked' : '' }}>
                                    Supplier to Supplier(ie. Representative/Wholesaler/Distributor)</label> <br>
                                <label class="FieldLabel" style="clear:left; ">
                                    <input type="Radio" name="YourStatus" value="Other"
                                        {{ $claimForm->status == 'Other' ? 'checked' : '' }}>
                                    Other</label>
                                <input type="text" name="YourStatusOther" value="" maxlength="32" style="width:268px">
                            </td>
                        </tr>
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>13. Your Document Check
                                        List:</strong></span><br>
                                <label class="FieldLabel" style="float:none; ">
                                    <input type="checkbox" name="DocumentsPurchaseOrder" value="1"
                                        {{ $claimForm->documents_purchase_order == '1' ? 'checked' : '' }}>
                                    Purchase Order / Contract </label>
                                <label class="FieldLabel" style="float:none; ">
                                    <input type="checkbox" name="DocumentsDeliveryTickets" value="1"
                                        {{ $claimForm->documents_delivary_tickets == '1' ? 'checked' : '' }}>
                                    Invoices &amp; Delivery Tickets </label>
                                <label class="FieldLabel" style="float:none; ">
                                    <input type="checkbox" name="DocumentsWaiver" value="1"
                                        {{ $claimForm->documents_Waiver == '1' ? 'checked' : '' }}>
                                    Copies of Waiver of Lien </label>
                                <label class="FieldLabel" style="float:none; ">
                                    <input type="checkbox" name="DocumentsLegalDescription" value="1"
                                        {{ $claimForm->documents_legal_description == '1' ? 'checked' : '' }}>
                                    Legal Description</label>
                                <label class="FieldLabel" style="float:none; ">
                                    <input type="checkbox" name="DocumentsPaymentBond" value="1"
                                        {{ $claimForm->documents_payment_bond == '1' ? 'checked' : '' }}>
                                    Payment Bond</label>
                                <label class="FieldLabel" style="float:none; ">
                                    <input type="checkbox" name="DocumentsJointCheckAgreement" value="1"
                                        {{ $claimForm->documents_joint_check_agreement == '1' ? 'checked' : '' }}>
                                    Joint Check Agreement</label>
                                <label class="FieldLabel" style="float:none; ">
                                    <input type="checkbox" name="DocumentsPreliminaryNotice" value="1"
                                        {{ $claimForm->documents_preliminary_notice == '1' ? 'checked' : '' }}>
                                    Preliminary Notice</label>
                                <label class="FieldLabel" style="float:none; ">
                                    <input type="checkbox" name="DocumentsOther" value="1"
                                        {{ $claimForm->documents_other == '1' ? 'checked' : '' }}>
                                    Other</label>
                                <input type="text" name="DocumentsOtherDocument" value="" style="width:270px">
                            </td>
                        </tr>
                        <tr>
                            <td><span class="FieldLabel"><strong>14. Miscellaneous:</strong></span>
                                <input type="text" name="Miscellaneous"
                                    value="{{ isset($claimForm->miscellaneous) ? $claimForm->miscellaneous : '' }}"
                                    maxlength="64" style="margin-left:5px;width:326px">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="BorderBottom"><span class="FieldLabel"><strong>8. Misc. Project
                            Information:</strong> List other people involved in Project - Architects, Engineers, Title
                        Co., Lenders and any others:</span>

                    <table border="0" class="text" style="text-align: left" cellpadding="2" cellspacing="0">
                        <thead>
                            <tr>
                                <td> Contact Name </td>
                                <td> Project Relationship </td>
                                <td> Business Name </td>
                                <td> Phone </td>
                                <td> Email </td>
                                <td></td>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <td style="border-top:solid 1px #666666; "><input type="text" name="AddContactFirstName"
                                        id="AddContactFirstName" style="width:55px;"
                                        value="{{ isset($claimForm->add_contact_first_name) ? $claimForm->add_contact_first_name : '' }}">

                                    <input type="text" name="AddContactLastName" id="AddContactLastName"
                                        style="width:55px;"
                                        value="{{ isset($claimForm->add_contact_last_name) ? $claimForm->add_contact_last_name : '' }}">
                                </td>
                                <td style="border-top:solid 1px #666666; "><select name="AddContactType"
                                        id="AddContactType"
                                        style="border: 1px solid #FFFFFF;border-bottom: 1px solid #000;height: 15px;font-size: 12px;color: #000; ">
                                        <option value="Architect"
                                            {{ $claimForm->add_contact_type == 'Architect' ? 'selected' : '' }}>
                                            Architect </option>
                                        <option value="Building Department Inspector"
                                            {{ $claimForm->add_contact_type == 'Building Department Inspector' ? 'selected' : '' }}>
                                            Building Department Inspector </option>
                                        <option value="Civil Engineer"
                                            {{ $claimForm->add_contact_type == 'Civil Engineer' ? 'selected' : '' }}>
                                            Civil Engineer </option>
                                        <option value="Construction Escrowee"
                                            {{ $claimForm->add_contact_type == 'Construction Escrowee' ? 'selected' : '' }}>
                                            Construction Escrowee </option>
                                        <option value="County Agency"
                                            {{ $claimForm->add_contact_type == 'County Agency' ? 'selected' : '' }}>
                                            County Agency </option>
                                        <option value="Designer"
                                            {{ $claimForm->add_contact_type == 'Designer' ? 'selected' : '' }}>
                                            Designer </option>
                                        <option value="Engineer"
                                            {{ $claimForm->add_contact_type == 'Engineer' ? 'selected' : '' }}>
                                            Engineer </option>
                                        <option value="Equipment Lessor"
                                            {{ $claimForm->add_contact_type == 'Equipment Lessor' ? 'selected' : '' }}>
                                            Equipment Lessor </option>
                                        <option value="Fabricator"
                                            {{ $claimForm->add_contact_type == 'Fabricator' ? 'selected' : '' }}>
                                            Fabricator </option>
                                        <option value="Federal Agency"
                                            {{ $claimForm->add_contact_type == 'Federal Agency' ? 'selected' : '' }}>
                                            Federal Agency </option>
                                        <option value="General Contractor"
                                            {{ $claimForm->add_contact_type == 'General Contractor' ? 'selected' : '' }}>
                                            General Contractor </option>
                                        <option value="Insurance Carrier"
                                            {{ $claimForm->add_contact_type == 'Insurance Carrier' ? 'selected' : '' }}>
                                            Insurance Carrier </option>
                                        <option value="Labor"
                                            {{ $claimForm->add_contact_type == 'Labor' ? 'selected' : '' }}> Labor
                                        </option>
                                        <option value="Lender"
                                            {{ $claimForm->add_contact_type == 'Lender' ? 'selected' : '' }}> Lender
                                        </option>
                                        <option value="Lessee"
                                            {{ $claimForm->add_contact_type == 'Lessee' ? 'selected' : '' }}> Lessee
                                        </option>
                                        <option value="Lessor"
                                            {{ $claimForm->add_contact_type == 'Lessor' ? 'selected' : '' }}> Lessor
                                        </option>
                                        <option value="Management"
                                            {{ $claimForm->add_contact_type == 'Manufacturer' ? 'selected' : '' }}>
                                            Management </option>
                                        <option value="Manufacturer"
                                            {{ $claimForm->add_contact_type == 'Manufacturer' ? 'selected' : '' }}>
                                            Manufacturer </option>
                                        <option value="Municipality"
                                            {{ $claimForm->add_contact_type == 'Municipality' ? 'selected' : '' }}>
                                            Municipality </option>
                                        <option value="Misc"
                                            {{ $claimForm->add_contact_type == 'Misc' ? 'selected' : '' }}> Misc.
                                            Related Party </option>
                                        <option value="Owner"
                                            {{ $claimForm->add_contact_type == 'Owner' ? 'selected' : '' }}> Project
                                            Owner </option>
                                        <option value="Project Manager"
                                            {{ $claimForm->add_contact_type == 'Project Manager' ? 'selected' : '' }}>
                                            Project Manager </option>
                                        <option value="State"
                                            {{ $claimForm->add_contact_type == 'State' ? 'selected' : '' }}> State
                                        </option>
                                        <option value="Structural Engineer"
                                            {{ $claimForm->add_contact_type == 'Structural Engineer' ? 'selected' : '' }}>
                                            Structural Engineer </option>
                                        <option value="Sub-Contractor"
                                            {{ $claimForm->add_contact_type == 'Sub-Contractor' ? 'selected' : '' }}>
                                            Sub-Contractor </option>
                                        <option value="Supplier"
                                            {{ $claimForm->add_contact_type == 'Supplier' ? 'selected' : '' }}>
                                            Supplier </option>
                                        <option value="Surety"
                                            {{ $claimForm->add_contact_type == 'Surety' ? 'selected' : '' }}> Surety
                                        </option>
                                        <option value="Temporary Laborer"
                                            {{ $claimForm->add_contact_type == 'Temporary Laborer' ? 'selected' : '' }}>
                                            Temporary Laborer </option>
                                        <option value="Title Company"
                                            {{ $claimForm->add_contact_type == 'Title Company' ? 'selected' : '' }}>
                                            Title Company </option>
                                    </select>
                                </td>
                                <td style="border-top:solid 1px #666666; "><input type="text" name="AddContactCompany"
                                        id="Company" style="width:110px;"
                                        value="{{ isset($claimForm->add_contact_company) ? $claimForm->add_contact_company : '' }}">
                                </td>
                                <td style="border-top:solid 1px #666666; "><input type="text" name="AddContactPhone"
                                        id="Phone"
                                        value="{{ isset($claimForm->add_contact_phone) ? $claimForm->add_contact_phone : '' }}"
                                        style="width:80px;">
                                </td>
                                <td style="border-top:solid 1px #666666; "><input type="text" name="AddContactEmail"
                                        id="Email" style="width:120px;"
                                        value="{{ isset($claimForm->add_contact_email) ? $claimForm->add_contact_email : '' }}">
                                </td>
                                <!-- <td style="border-top:solid 1px #666666; ">
                            <input type="button" name="add" value="Add" style="border: 1px solid #ccc; width:40px; height: 18px;" onclick="addList()">
                        </td> -->
                            </tr>
                            <tr>
                                <td colspan="6">

                                </td>
                            </tr>
                        </tfoot>
                        <tbody id="tbContactList">
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%" border="0" cellspacing="0">
                        <tr>
                            <td width="310" height="124"><span class="FaxInstructions">Please Fax your contracts,
                                    invoices, purchase orders, and all other related documents to us at:</span></td>
                            <td width="130"><span
                                    style="font-size: 14px;font-weight: bold;display: block;width: auto;padding: 5px 10px; ">Please
                                    let us know how you heard about NLB!
                                    Thanks for your business! </span></td>
                            <td width="230" valign="top">
                                <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                    style="color:#000; font:12px Arial, Helvetica, sans-serif;margin-top:10px;">
                                    <tr>
                                        <td width="30%">Web search</td>
                                        <td width="10%"><input type="checkbox" name="HeardByWeb" value="1"
                                                {{ $claimForm->heard_by_web == '1' ? 'checked' : '' }}></td>
                                        <td width="19%">Google</td>
                                        <td width="14%"><input type="checkbox" name="HeardByGoogle" value="1"
                                                {{ $claimForm->heard_by_google == '1' ? 'checked' : '' }}></td>
                                        <td width="14%">AOL</td>
                                        <td width="13%"><input type="checkbox" name="HeardByAOL" value="1"
                                                {{ $claimForm->heard_by_aol == '1' ? 'checked' : '' }}></td>
                                    </tr>
                                    <tr>
                                        <td>Referral</td>
                                        <td><input type="checkbox" name="HeardByReferral" value="1"
                                                {{ $claimForm->heard_by_referral == '1' ? 'checked' : '' }}>
                                        </td>
                                        <td>MSN</td>
                                        <td><input type="checkbox" name="HeardByMSN" value="1"
                                                {{ $claimForm->heard_by_msn == '1' ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Other</td>
                                        <td colspan="5"><input name="HeardByOther" type="text" style="width:64px"
                                                maxlength="32"
                                                value="{{ isset($claimForm->heard_by_other) ? $claimForm->heard_by_other : '' }}">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div align="center" style=" margin-top: 5px;">
            <button type="submit">Continue</button>
        </div>
    </form>
</body>

</html>
