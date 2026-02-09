<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso8859-1" />
    <title>NLB Claim Form: Claim Data Sheet</title>

    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/document.css" type="text/css">
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/document_print.css" type="text/css" media="print">
    <!-- jQuery 3 -->
    <script src="{{ env('ASSET_URL') }}/admin_assets/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ env('ASSET_URL') }}/js/jquery-ui.min.js"></script>
</head>

<body>
    <form name="theForm" action="{{ route('get.documentClaimDataTwo', [$project_id]) }}" method="post">
        <input type="hidden" name="ID" value="">
        <input type="hidden" name="ProjectID" value="{{ $project_id }}">
        <table width="670" class="data_sheet" align="center" style=" border:1px solid #000; border-collapse:collapse;"
            cellpadding="0" cellspacing="0">
            {{ csrf_field() }}
            <tr>
                <td width="670">
                    <table width="670" border="0" cellpadding="0" cellspacing="0" style=" border-collapse: collapse;">
                        <tr>
                            <td width="174">
                                <div align="center"><img src="{{ env('ASSET_URL') }}/images/nlb.jpg" /></div>
                            </td>
                            <td width="492">
                                <div align="center" class="NLBHeader">National Lien Platform Claim Form</div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style=" border-bottom:1px solid #000; text-align:center"><strong>Fax: /
                                    Phone: </strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="border-bottom:2px solid #000;">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                        <tr>
                            <td width="50%" class="BorderBottom" style="padding: 5px 0px;">
                                <strong>&nbsp;TO:</strong><br />
                                &nbsp;National Lien and Bond Claim Systems<br />
                                &nbsp;440 Central Ave. <br />
                                &nbsp;Hightland Park,IL 60035
                            </td>
                            <td width="50%" rowspan="5" style="border-left: 1px solid #000;" valign="top">
                                <table width="100%" border="0">
                                    <tr>
                                        <td width="339"><strong>&nbsp;&nbsp;FROM:</strong></td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">Your Company Name:</span>
                                            <input name="Company" type="text" class="InputLine"
                                                value="{{ isset($claimForm->company) ? $claimForm->company : $company_details->company }}"
                                                maxlength="64" style="width:180px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">Contact Name: </span>
                                            <input name="Contact" type="text" class="InputLine"
                                                value="{{ isset($claimForm->contact) ? $claimForm->contact : $company_details->contact }}"
                                                maxlength="32" style="width:215px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">Address:</span>
                                            <input name="Address" type="text" class="InputLine"
                                                value="{{ isset($claimForm->address) ? $claimForm->address : $company_details->address }}"
                                                maxlength="64" style="width:260px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">City
                                                <input name="City" type="text" class="InputLine"
                                                    value="{{ isset($claimForm->city) ? $claimForm->city : $company_details->city }}"
                                                    maxlength="32" style="width:80px" />
                                                State
                                                <input name="State" type="text" class="InputLine"
                                                    value="{{ isset($claimForm->state) ? $claimForm->state : $company_details->city }}"
                                                    maxlength="32" style="width:50px" />
                                                Zip
                                                <input name="Zip" type="text" class="InputLine"
                                                    value="{{ isset($claimForm->zip) ? $claimForm->zip : $company_details->state }}"
                                                    maxlength="32" style="width:80px" />
                                            </span></td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">Phone:</span>
                                            <input name="Phone" type="text" class="InputLine"
                                                value="{{ isset($claimForm->phone) ? $claimForm->phone : $company_details->phone }}"
                                                maxlength="16" style="width:260px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">FAX:</span>
                                            <input name="Fax" type="text" class="InputLine"
                                                value="{{ isset($claimForm->fax) ? $claimForm->fax : '' }}"
                                                maxlength="16" style="width:292px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">Email:</span>
                                            <input name="Email" type="text" class="InputLine"
                                                value="{{ isset($claimForm->email) ? $claimForm->email : '' }}"
                                                maxlength="255" style="width:260px" />
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="BorderBottom" style="line-height: 30px;"><label
                                    for="purpose_HMB">&nbsp;&nbsp;&nbsp;
                                    HMB &nbsp;</label>
                                <span class="BorderBottom" style="line-height: 30px;">
                                    <input type="radio" name="Purpose" value="HMB" id="purpose_HMB"
                                        {{ isset($claimForm->purpose) ? ($claimForm->purpose == 'HMB' ? 'checked' : '') : '' }}>
                                </span>&nbsp;&nbsp;&nbsp;
                                <label for="purpose_HAE">&nbsp;&nbsp;HAE </label>
                                <span class="BorderBottom" style="line-height: 30px;">
                                    <input type="radio" name="Purpose" value="HAE" id="purpose_HAE"
                                        {{ isset($claimForm->purpose) ? ($claimForm->purpose == 'HAE' ? 'checked' : '') : '' }}>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td height="54"><strong>&nbsp;TODAY'S DATE:</strong>&nbsp;
                                <input name="ClaimDate" id="ClaimDate" type="text" class="InputLine DateField"
                                    value="{{ isset($claimForm->claim_date) ? $claimForm->claim_date : '' }}"
                                    style="width:80px" />
                                <input type="button" name="button_DataSheetDate223" value="..."
                                    onClick="$('#ClaimDate').datepicker();$('#ClaimDate').datepicker('show');"
                                    class="NoPrint">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse">
                        <tr>
                            <td width="100%">
                                <table width="100%" border="0" class="BorderBottom">
                                    <tr>
                                        <td width="12">
                                            <div align="center"><strong>1.</strong></div>
                                        </td>
                                        <td width="507"><strong>CHECK TYPE OF CLAIM:</strong></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div align="center"></div>
                                        </td>
                                        <td>
                                            <table width="507" border="0">
                                                <tr>
                                                    <td width="162"><input type="radio" name="ClaimType"
                                                            value="Preliminary Notice" id="ClaimType1"
                                                            {{ $claimForm->claim_type == 'Preliminary Notice' ? 'checked' : '' }} />
                                                        <label for="ClaimType1"> Preliminary Notice &nbsp;</label>
                                                    </td>
                                                    <td width="154"><input type="radio" name="ClaimType" id="ClaimType2"
                                                            value="Collection"
                                                            {{ $claimForm->claim_type == 'Collection' ? 'checked' : '' }} />
                                                        <label for="ClaimType2"> Collection &nbsp;&nbsp;&nbsp;</label>
                                                    </td>
                                                    <td width="177"><input type="radio" name="ClaimType" id="ClaimType3"
                                                            value="Bankruptcy"
                                                            {{ $claimForm->claim_type == 'Bankruptcy' ? 'checked' : '' }} />
                                                        <label for="ClaimType3"> Bankruptcy </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><input type="radio" name="ClaimType" value="Lien Claim"
                                                            id="ClaimType4"
                                                            {{ $claimForm->claim_type == 'Lien' ? 'checked' : '' }} />
                                                        <label for="ClaimType4"> Lien Claim </label>
                                                    </td>
                                                    <td><input type="radio" name="ClaimType" value="Prelitigation"
                                                            id="ClaimType5"
                                                            {{ $claimForm->claim_type == 'Prelitigation' ? 'checked' : '' }} />
                                                        <label for="ClaimType5"> Prelitigation</label>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td><input type="radio" name="ClaimType" value="Bond Claim"
                                                            id="ClaimType6"
                                                            {{ $claimForm->claim_type == 'Bond' ? 'checked' : '' }} />
                                                        <label for="ClaimType6"> Bond Claim</label>
                                                    </td>
                                                    <td><input type="radio" name="ClaimType" id="ClaimType7"
                                                            value="Litigation"
                                                            {{ $claimForm->claim_type == 'Litigation' ? 'checked' : '' }} />
                                                        <label for="ClaimType7"> Litigation</label>
                                                    </td>
                                                    <td><input type="radio" name="ClaimType" value="Other"
                                                            id="ClaimType8"
                                                            {{ $claimForm->claim_type == '' ? 'checked' : '' }} />
                                                        <label for="ClaimType8"> Other</label>
                                                        <input name="ClaimTypeOther" type="text" class="InputLine"
                                                            value="" maxlength="32" style="width:50px" />
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <table width="100%" border="0" class="BorderBottom">
                                    <tr>
                                        <td width="12">
                                            <div align="center"><strong>2.</strong></div>
                                        </td>
                                        <td width="508"><strong>YOUR CONTRACT INFORMATION:</strong><br />
                                            &nbsp;Contract Date
                                            <input id="ContractDate" name="ContractDate" readonly type="text"
                                                class="InputLine DateField"
                                                value="{{ isset($claimForm->contract_date) ? $claimForm->contract_date : '' }}"
                                                maxlength="10" style="width:80px" />
                                            <input type="button" name="button_DataSheetDate223" value="..."
                                                onClick="$('#ContractDate').datepicker();$('#ContractDate').datepicker('show');"
                                                class="NoPrint">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div align="center"></div>
                                        </td>
                                        <td>
                                            <table width="100%" border="0">
                                                <tr>
                                                    <td width="213">Do you have a <br />
                                                        <input type="radio" name="ContractType"
                                                            id="ContractType_Written" value="Written"
                                                            {{ $claimForm->contract_type == 'Written' ? 'checked' : '' }} />
                                                        <label for="ContractType_Written"> written (Fax copies to NLB)
                                                            or </label>
                                                        <br />
                                                        <input type="radio" name="ContractType" id="ContractType_Verbal"
                                                            value="Verbal"
                                                            {{ $claimForm->contract_type == 'Verbal' ? 'checked' : '' }} />
                                                        <label for="ContractType_Verbal"> verbal</label>
                                                        contract?<br />
                                                        (Fax copies of P.O.'s, contracts, invoices, delivery tickets,
                                                        change
                                                        orders, and billing statements,etc. to NLB)
                                                    </td>
                                                    <td width="311">
                                                        <table cellpadding="1" cellspacing="0">
                                                            <tr>
                                                                <td width="214" style="font-size:14px; ">Base contract
                                                                    amount
                                                                </td>
                                                                <td width="87">$
                                                                    <input name="BaseAmount" type="text"
                                                                        class="InputLine"
                                                                        value="{{ isset($claimForm->base_amount) ? $claimForm->base_amount : '' }}"
                                                                        style="width:80px;font-size:14px;text-align:right;"
                                                                        id="base_amount" maxlength="12" />
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size:14px; ">+ Value of extras or
                                                                    changes
                                                                </td>
                                                                <td>$
                                                                    <input name="ExtraAmount" type="text"
                                                                        class="InputLine"
                                                                        value="{{ isset($claimForm->extra_amount) ? $claimForm->base_amount : '' }}"
                                                                        style="width:80px;font-size:14px;text-align:right;"
                                                                        id="extra_amount" maxlength="12" />
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size:14px; ">= Revised Contract subtotal
                                                                </td>
                                                                <td>$
                                                                    <input name="Subtotal" type="text" class="InputLine"
                                                                        readonly="true" id="contact_total"
                                                                        style="width:80px;font-size:14px;text-align:right;"
                                                                        maxlength="12" />
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size:14px; ">- Less Amount Paid to date
                                                                    &amp; credits
                                                                </td>
                                                                <td>$
                                                                    <input name="Payments" type="text" class="InputLine"
                                                                        value="{{ isset($claimForm->payments) ? $claimForm->payments : '' }}"
                                                                        style="width:80px;font-size:14px;text-align:right;"
                                                                        id="payment" maxlength="12" />
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="font-size:14px; ">= Claim Amount Total</td>
                                                                <td>$
                                                                    <input name="Total" type="text" class="InputLine"
                                                                        readonly="true"
                                                                        style="width:80px;font-size:14px;text-align:right;"
                                                                        id="claim_amount" maxlength="12" />
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">Does all extra work relate to original contract?
                                                        <input type="radio" name="ExtraWorkRelated"
                                                            id="ExtraWorkRelated1" value="Yes"
                                                            {{ $claimForm->extra_work_related == 'Yes' ? 'checked' : '' }} />
                                                        <label for="ExtraWorkRelated1"> Yes </label>
                                                        &nbsp;
                                                        <input type="radio" name="ExtraWorkRelated"
                                                            id="ExtraWorkRelated0" value="No"
                                                            {{ $claimForm->extra_work_related == 'No' ? 'checked' : '' }} />
                                                        <label for="ExtraWorkRelated0"> No</label>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <table width="100%" border="0" class="BorderBottom">
                                    <tr>
                                        <td width="13"><strong>3.</strong></td>
                                        <td width="504"><strong>DESCRIPTION OF YOUR PRODUCT/SERVICES PROVIDED</strong> (
                                            fax
                                            to NLB your product literature)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <p>Did you provide?&nbsp;
                                                <input type="radio" name="Provided" id="Provided0"
                                                    value="Materials Only"
                                                    {{ $claimForm->provided == 'Materials Only' ? 'checked' : '' }} />
                                                <label for="Provided0"> Materials only</label>
                                                &nbsp;
                                                <input type="radio" name="Provided" id="Provided1" value="Labor Only"
                                                    {{ $claimForm->provided == 'Labor Only' ? 'checked' : '' }} />
                                                <label for="Provided1">Labor only</label>
                                                &nbsp;
                                                <input type="radio" name="Provided" id="Provided2"
                                                    value="Materials and Labor"
                                                    {{ $claimForm->provided == 'Materials and Labor' ? 'checked' : '' }} />
                                                <label for="Provided2">Materials &amp; Labor</label>
                                                <br />
                                                Is your product custom manufactured for the project? <br />
                                                <input type="radio" name="CustomManufacture" id="CustomManufacture1"
                                                    value="Yes"
                                                    {{ $claimForm->custom_manufacture == 'Yes' ? 'checked' : '' }} />
                                                <label for="CustomManufacture1">Yes, first date of fabrication (includes
                                                    shop drawings)</label>
                                                &nbsp;
                                                <input id="CustomManufactureDate" name="CustomManufactureDate" readonly
                                                    type="text" class="InputLine DateField"
                                                    value="{{ $claimForm->custom_manufacture_date == 'Materials and Labor' ? 'checked' : '' }}"
                                                    style="width:80px" />
                                                <input type="button" name="button_DataSheetDate22" value="..."
                                                    onClick="$('#CustomManufactureDate').datepicker();$('#CustomManufactureDate').datepicker('show');"
                                                    class="NoPrint">
                                                <input type="radio" name="CustomManufacture" id="CustomManufacture0"
                                                    value="No"
                                                    {{ $claimForm->custom_manufacture == 'No' ? 'checked' : '' }} />
                                                <label for="CustomManufacture0">No</label>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                                <table width="100%" border="0" class="BorderBottom">
                                    <tr>
                                        <td width="12"><strong>4.</strong></td>
                                        <td width="504"><strong>CONTRACT/PAYMENT: </strong></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <table width="100%" border="0">
                                                <tr>
                                                    <td width="256">
                                                        <p>Have you issued a preliminary notice? &nbsp;&nbsp;
                                                            &nbsp;<br />
                                                            Have you issued lien waiver?&nbsp;</p>
                                                    </td>
                                                    <td width="238"><input type="radio" name="PreliminaryNotice"
                                                            id="PreliminaryNotice1" value="Yes"
                                                            {{ $claimForm->preliminary_notice == 'Yes' ? 'checked' : '' }} />
                                                        <label for="PreliminaryNotice1">Yes ( please fax to NLB
                                                            )</label>
                                                        &nbsp;&nbsp;
                                                        <input type="radio" name="PreliminaryNotice"
                                                            id="PreliminaryNotice0" value="No"
                                                            {{ $claimForm->preliminary_notice == 'No' ? 'checked' : '' }} />
                                                        <label for="PreliminaryNotice0">No</label>
                                                        <br />
                                                        <input type="radio" name="LienWaiver" id="LienWaiver1"
                                                            value="Yes"
                                                            {{ $claimForm->preliminary_notice == 'No' ? 'checked' : '' }} />
                                                        <label for="LienWaiver1">Yes ( please fax to NLB )</label>
                                                        &nbsp;&nbsp;
                                                        <input type="radio" name="LienWaiver" id="LienWaiver0"
                                                            value="No"
                                                            {{ $claimForm->leinwaiver == 'No' ? 'checked' : '' }} />
                                                        <label for="LienWaiver0">No</label>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <table width="100%" border="0" class="BorderBottom">
                                    <tr>
                                        <td width="13"><strong>5.</strong></td>
                                        <td width="100%" rowspan="2">
                                            <table width="100%" border="0">
                                                <tr>
                                                    <td><strong>CONSTRUCTION TYPE:</strong>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                    <td><input type="radio" name="ConstructionAge" id="ConstructionAge0"
                                                            value="New"
                                                            {{ $claimForm->construction_age == 'New' ? 'checked' : '' }} />
                                                        <label for="ConstructionAge0">New</label>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                    </td>
                                                    <td><input type="radio" name="ConstructionAge" id="ConstructionAge1"
                                                            value="Rehab"
                                                            {{ $claimForm->construction_age == 'Rehab' ? 'checked' : '' }} />
                                                        <label for="ConstructionAge1">Rehab</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="187"><input type="radio" name="  ConstructionType"
                                                            id="ConstructionType0" value="Multi-Unit Residential"
                                                            {{ $claimForm->construction_type == 'Multi-Unit Residential' ? 'checked' : '' }} />
                                                        <label for="ConstructionType0">Multi-Unit Residential</label>
                                                        <br />
                                                        <input type="radio" name="ConstructionType"
                                                            id="ConstructionType1" value="Subdivision"
                                                            {{ $claimForm->construction_type == 'Subdivision' ? 'checked' : '' }} />
                                                        <label for="ConstructionType1">Subdivision</label>
                                                    </td>
                                                    <td width="162"><input type="radio" name="ConstructionType"
                                                            id="ConstructionType2" value="Personal Residence"
                                                            {{ $claimForm->construction_type == 'Personal Residence' ? 'checked' : '' }} />
                                                        <label for="ConstructionType2">Personal Residence</label>
                                                        <br />
                                                        <input type="radio" name="ConstructionType"
                                                            id="ConstructionType3" value="Commercial"
                                                            {{ $claimForm->construction_type == 'Commercial' ? 'checked' : '' }} />
                                                        <label for="ConstructionType3">Commercial</label>
                                                    </td>
                                                    <td width="175"><input type="radio" name="ConstructionType"
                                                            id="ConstructionType4" value="Municipal Government"
                                                            {{ $claimForm->construction_type == 'Municipal Government' ? 'checked' : '' }} />
                                                        <label for="ConstructionType4">Municipal/Government</label>
                                                        <br />
                                                        <input type="radio" name="ConstructionType"
                                                            id="ConstructionType5" value="Other"
                                                            {{ $claimForm->construction_type == 'Other' ? 'checked' : '' }} />
                                                        <label for="ConstructionType5">Other</label>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                                <table width="100%" border="0" class="BorderBottom">
                                    <tr>
                                        <td width="11"><strong>6.</strong></td>
                                        <td width="508"><strong>DATES OF WORK/FURNISHING/SHIPPING</strong>:(fax to NLB
                                            your
                                            delivery tickets)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <table width="100%" border="0">
                                                <tr>
                                                    <td width="502">

                                                        <?php /*foreach($dates as $adate): */
                                                        ?>
                                                        <!--
                                                    <?php /*$adate_str = ( $adate["Value"] && $adate["Value"]!="0000-00-00" ) ? date("m/d/Y",strtotime($adate["Value"])) : ''; */
                                                    ?>
                                                        <div style="display:inline-block;width:300px;"><?/*= $adate["DateName"]; */?>:</div><div style="display:inline-block;"><?/*= $adate_str; */?></div><br/>
                                                    --><?php /*endforeach; */
                                                    ?>

                                                        <br />
                                                        Is there a project certificate or notice of completion or
                                                        acceptance
                                                        filed? <br />
                                                        <input type="radio" name="NoticeOfCompletion"
                                                            id="NoticeOfCompletion1" value="Yes" />
                                                        <label for="NoticeOfCompletion1">Yes</label>
                                                        <input type="radio" name="NoticeOfCompletion"
                                                            id="NoticeOfCompletion0" value="No"
                                                            {{ $claimForm->notice_of_complition == 'No' ? 'checked' : '' }} />
                                                        <label for="NoticeOfCompletion">No</label>
                                                        <br />
                                                        Is the project as a whole completed?&nbsp;
                                                        <input type="radio" name="ProjectComplete" id="ProjectComplete1"
                                                            value="Yes"
                                                            {{ $claimForm->project_complete == 'Yes' ? 'checked' : '' }} />
                                                        <label for="ProjectComplete1">Yes</label>
                                                        &nbsp;
                                                        <input type="radio" name="ProjectComplete" id="ProjectComplete0"
                                                            value="No"
                                                            {{ $claimForm->project_complete == 'No' ? 'checked' : '' }} />
                                                        <label for="ProjectComplete0">No</label>
                                                        <div class="completed">
                                                            <br />&nbsp; &nbsp; If Yes, Date of completion <u></u>
                                                            <input name="CompleteDate" id="CompleteDate" readonly
                                                                type="text" class="InputLine DateField"
                                                                value="{{ isset($claimForm->complete_date) ? $claimForm->contract_date : '' }}"
                                                                style="width:80px" />
                                                            <input type="button" name="button_DataSheetDate" value="..."
                                                                onClick="$('#CompleteDate').datepicker();$('#CompleteDate').datepicker('show');"
                                                                class="NoPrint">
                                                            <br />
                                                    </td>
                                                    </div>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                                <table width="100%" border="0">
                                    <tr>
                                        <td colspan="4">
                                            <p>Please remember to fax to NLB all documentation related to this project.
                                                This includes contracts, invoices, notices, purchase orders, etc. <span
                                                    style="font-weight:bold;font-size:13px; ">FAX: </span></p>
                                            <div style="text-align:center; "><img
                                                    src="{{ env('ASSET_URL') }}/images/nlb_logo.jpg" alt="NLB"
                                                    align="middle"></div>
                                            <p class="Font7pt">
                                                <span class="Font10">Liability Limitations:</span> National Lien and
                                                Bond
                                                Claim Systems, a division of Network*50, Inc (NLB)
                                                does not guarantee or in any way represent or warrant the information
                                                transmitted or received by customer or third parties.
                                                Customer acknowledges and agrees that the service provided by NLB
                                                consists
                                                solely of providing access to a filing network
                                                which may in appropriate cases involve attorneys. NLB is not in any way
                                                responsible or liable for errors, omissions,
                                                inadequacy or interruptions. In the event any errors is attributable to
                                                NLB
                                                or to the equipment, customer should be
                                                entitled only to a refund of the cost for preparation of any notices.
                                                The
                                                refund shall be exclusively in lieu of any other
                                                damages or remedies against NLB.
                                            </p>
                                            <p><label><input type="checkbox" name="Agree" id="agree" value="1" checked>
                                                    &nbsp;By submitting this
                                                    form, you agree to the Liability Limitation terms stated
                                                    herein.</label>
                                                @if ($errors->has('Agree'))
                                                    <div style="color:red;">{{ $errors->first('Agree') }}</div>
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="30%"><strong>7.Customer Signature:</strong>&nbsp;</td>
                                        <td width="32%"><label>
                                                <input name="Signature" type="text" class="InputLine"
                                                    value="{{ isset($claimForm->signature) ? $claimForm->signature : '' }}" />
                                            </label></td>
                                        <td width="8%"><strong>Date: </strong></td>
                                        <td width="30%"><input id="SignatureDate" name="SignatureDate" type="text"
                                                class="InputLine DateField"
                                                value="{{ isset($claimForm->signature_date) ? $claimForm->signature_date : '' }}"
                                                style="width:100px" readonly />
                                            <input type="button" name="button_DataSheetDate" value="..."
                                                onClick="$('#SignatureDate').datepicker();$('#SignatureDate').datepicker('show');"
                                                class="NoPrint">
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width="114" style="border-left:1px solid #000;" valign="top">
                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td width="100%" class="BorderBottom">
                                            <table width="108" border="0">
                                                <tr>
                                                    <td valign="top"><strong>8.</strong></td>
                                                    <td colspan="3"><strong><span class="Font10">CIRCLE &nbsp;PROJECT
                                                                &nbsp;STATE:</span></strong>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="23">
                                                        <div align="center"> AL</div>
                                                    </td>
                                                    <td width="20"><input type="radio" name="ProjectState" value="AL"
                                                            <?php if ($ProjectState == 'AL') {
                                                                echo ' checked="checked"';
                                                            } ?> />
                                                    </td>
                                                    <td width="27">
                                                        <div align="center"> MT</div>
                                                    </td>
                                                    <td width="27"><input type="radio" name="ProjectState" value="MT"
                                                            <?php if ($ProjectState == 'MT') {
                                                                echo ' checked="checked"';
                                                            } ?> />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> AK</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="AK"
                                                            <?php if ($ProjectState == 'AK') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> NE</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="NB"
                                                            <?php if ($ProjectState == 'NE') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> AZ</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="AZ"
                                                            <?php if ($ProjectState == 'AZ') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> NC</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="NC"
                                                            <?php if ($ProjectState == 'NC') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center">
                                                            <div align="center"> AR</div>
                                                        </div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="AR"
                                                            <?php if ($ProjectState == 'AR') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> ND</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="ND"
                                                            <?php if ($ProjectState == 'ND') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> CA</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="CA"
                                                            <?php if ($ProjectState == 'CA') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> NH</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="NH"
                                                            <?php if ($ProjectState == 'NH') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> CO</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="CO"
                                                            <?php if ($ProjectState == 'CO') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> NL</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="NL"
                                                            <?php if ($ProjectState == 'NL') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> CT</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="CT"
                                                            <?php if ($ProjectState == 'CT') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> NM</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="NM"
                                                            <?php if ($ProjectState == 'NM') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> DE</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="DE"
                                                            <?php if ($ProjectState == 'DE') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> NV</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="NV"
                                                            <?php if ($ProjectState == 'NV') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> FL</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="FL"
                                                            <?php if ($ProjectState == 'FL') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> NY</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="NY"
                                                            <?php if ($ProjectState == 'NY') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> GA</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="GA"
                                                            <?php if ($ProjectState == 'GA') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> OH</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="OH"
                                                            <?php if ($ProjectState == 'OH') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> HI</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="HI"
                                                            <?php if ($ProjectState == 'HI') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> OK</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="OK"
                                                            <?php if ($ProjectState == 'OK') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> IA</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="IA"
                                                            <?php if ($ProjectState == 'IA') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> OR</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="OR"
                                                            <?php if ($ProjectState == 'OR') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> ID</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="ID"
                                                            <?php if ($ProjectState == 'ID') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> PA</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="PA"
                                                            <?php if ($ProjectState == 'PA') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> IL</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="IL"
                                                            <?php if ($ProjectState == 'IL') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> RI</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="RI"
                                                            <?php if ($ProjectState == 'RI') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> IN</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="IN"
                                                            <?php if ($ProjectState == 'IN') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> SC</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="SC"
                                                            <?php if ($ProjectState == 'SC') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> KS</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="KS"
                                                            <?php if ($ProjectState == 'KS') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> SD</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="SD"
                                                            <?php if ($ProjectState == 'SD') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> KY</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="KY"
                                                            <?php if ($ProjectState == 'KY') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> TN</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="TN"
                                                            <?php if ($ProjectState == 'TN') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> LA</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="LA"
                                                            <?php if ($ProjectState == 'LA') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> TX</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="TX"
                                                            <?php if ($ProjectState == 'TX') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> MA</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="MA"
                                                            <?php if ($ProjectState == 'MA') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> UT</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="UT"
                                                            <?php if ($ProjectState == 'UT') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> MD</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="MD"
                                                            <?php if ($ProjectState == 'MD') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> VA</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="VA"
                                                            <?php if ($ProjectState == 'VA') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> ME</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="ME"
                                                            <?php if ($ProjectState == 'ME') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> VT</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="VT"
                                                            <?php if ($ProjectState == 'VT') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> MI</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="MI"
                                                            <?php if ($ProjectState == 'MI') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> WA</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="WA"
                                                            <?php if ($ProjectState == 'WA') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center">MN</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="MN"
                                                            <?php if ($ProjectState == 'MN') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> WI</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="WI"
                                                            <?php if ($ProjectState == 'WI') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> MO</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="MO"
                                                            <?php if ($ProjectState == 'MO') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> WV</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="WV"
                                                            <?php if ($ProjectState == 'WV') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div align="center"> MS</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="MS"
                                                            <?php if ($ProjectState == 'MS') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                    <td>
                                                        <div align="center"> WY</div>
                                                    </td>
                                                    <td><input type="radio" name="ProjectState" value="WY"
                                                            <?php if ($ProjectState == 'WY') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                            </table>
                                            WASH D.C
                                            <input type="radio" name="ProjectState" value="DC" <?php if ($ProjectState == 'DC') {
    echo ' checked="checked"';
} ?>>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="66">
                                            <table width="100%" border="0">
                                                <tr>
                                                    <td width="62"><span class="Font10">CANADA</span></td>
                                                    <td width="26"><input type="radio" name="ProjectState"
                                                            value="CANADA" <?php if ($ProjectState == 'CANADA') {
    echo ' checked="checked"';
} ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="Font10">MEXICO</span></td>
                                                    <td><input type="radio" name="ProjectState" value="MEXICO"
                                                            <?php if ($ProjectState == 'MEXICO') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="Font10">PEURTO RICO</span></td>
                                                    <td><input type="radio" name="ProjectState" value="PEURTORICO"
                                                            <?php if ($ProjectState == 'PEURTORICO') {
                                                                echo ' checked="checked"';
                                                            } ?>>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td height="18"><span class="Font10"> OTHER:
                                                            <input type="radio" name="ProjectState" value="OTHER"
                                                                <?php if ($ProjectState == 'OTHER') {
                                                                    echo ' checked="checked"';
                                                                } ?>>
                                                        </span>
                                                    </td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="17"><input name="ProjectStateOther" type="text" class="InputLine"
                                                value="" maxlength="32" style="width:50px" /></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        @if ($flag != 1)
            <div align="center" style="padding-top: 20px">
                <div align="center"> Input the claim information that relates to your company and project. If you have
                    any
                    questions, please call us at .
                </div>

                <div align="center" style="text-align:center;margin-top:15px; ">
                    <!-- <a href="{{ route('get.documentClaimDataTwo', [$project_id]) }}"> -->
                    <button type="submit" id="continue">Continue</button>
                </div>

            </div>
        @else
            <div align="center" style="padding-top: 20px">

                <div align="center" style="text-align:center;margin-top:15px; ">
                    <!-- <a href="{{ route('get.documentClaimDataTwo', [$project_id]) }}"> -->
                    <!--  <button type="submit">Next</button>  -->
                    <a href="{{ route('get.documentClaimData2', ['project' => $project_id, 'flag' => $flag]) }}"
                        class="button">Next</a>
                </div>

            </div>
        @endif
    </form>
</body>

</html>
<script type="text/javascript">
    $(document).ready(function() {
        var base_amount = 0;
        var extra_amount = 0;
        var payment = 0;
        var total = 0;
        var claim_total = 0;
        totalAmount();

        $('#base_amount,#extra_amount,#payment').on('change', function() {
            totalAmount();
        });

        function totalAmount() {
            base_amount = $('#base_amount').val();
            extra_amount = $('#extra_amount').val();
            payment = $('#payment').val();
            total = parseFloat(base_amount) + parseFloat(extra_amount);
            claim_total = parseFloat(total) - parseFloat(payment);
            $('#contact_total').val(parseFloat(total));
            $('#claim_amount').val(parseFloat(claim_total));
        }

        $('#ProjectComplete1').on('click', function() {

            $('.completed').css("display", "block");

        });
        $('#ProjectComplete0').on('click', function() {

            $('.completed').css("display", "none");

        });
    });
</script>
