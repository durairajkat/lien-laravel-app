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
    <form action="#" method="post" enctype="multipart/form-data">

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
                                            <strong>Fax: / Phone: </strong></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-bottom:2px solid #000;">
                                <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                    style="border-collapse: collapse;">
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
                                                            value="{{ isset($company_details) ? $company_details->company : '' }}"
                                                            maxlength="64" style="width:180px" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="FieldLabel">Contact Name: </span>
                                                        <input name="Contact" type="text" class="InputLine"
                                                            value="{{ isset($company_details) ? $company_details->contact : '' }}"
                                                            maxlength="32" style="width:215px" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="FieldLabel">Address:</span>
                                                        <input name="Address" type="text" class="InputLine"
                                                            value="{{ isset($company_details) ? $company_details->address : '' }}"
                                                            maxlength="64" style="width:260px" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="FieldLabel">City
                                                            <input name="City" type="text" class="InputLine"
                                                                value="{{ isset($company_details->city) ? $company_details->city : '' }}"
                                                                maxlength="32" style="width:80px" />
                                                            State
                                                            <input name="State" type="text" class="InputLine"
                                                                value="{{ isset($company_details) ? $company_details->state->name : '' }}"
                                                                maxlength="32" style="width:50px" />
                                                            Zip
                                                            <input name="Zip" type="text" class="InputLine"
                                                                value="{{ $company_details->zip or '' }}"
                                                                maxlength="32" style="width:80px" />
                                                        </span></td>
                                                </tr>
                                                <tr>
                                                    <td><span class="FieldLabel">Phone:</span>
                                                        <input name="Phone" type="text" class="InputLine"
                                                            value="{{ $company_details->phone or '' }}" maxlength="16"
                                                            style="width:260px" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="FieldLabel">FAX:</span>
                                                        <input name="Fax" type="text" class="InputLine" value=""
                                                            maxlength="16" style="width:292px" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="FieldLabel">Email:</span>
                                                        <input name="Email" type="text" class="InputLine" value=""
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
                                                <input type="radio" name="Purpose" value="HMB" id="purpose_HMB">
                                            </span>&nbsp;&nbsp;&nbsp;
                                            <label for="purpose_HAE">&nbsp;&nbsp;HAE </label>
                                            <span class="BorderBottom" style="line-height: 30px;">
                                                <input type="radio" name="Purpose" value="HAE" id="purpose_HAE">
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="54"><strong>&nbsp;TODAY'S DATE:</strong>&nbsp;
                                            <input name="ClaimDate" id="ClaimDate" type="text"
                                                class="InputLine DateField"
                                                value="{{ Carbon\Carbon::today()->format('Y-m-d') }}"
                                                style="width:80px" />
                                            <input type="button" name="button_DataSheetDate223" value="..."
                                                onClick="$('#ClaimDate').datepicker();$('#ClaimDate').datepicker('show');"
                                                class="NoPrint">
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
                            <td class="BorderBottom"><span class="FieldLabel"><strong>PROJECT
                                        INFORMATION</strong></span>
                                <input name="ProjectName" type="text" value="" maxlength="64" style="width:220px">
                                <br>
                                <span class="FieldLabel">Address:</span>
                                <input name="ProjectAddress" type="text" value="" maxlength="32" style="width:269px;">
                                <br>
                                <span class="FieldLabel">City
                                    <input name="ProjectCity" type="text" value="" maxlength="32" style="width:80px">
                                    State
                                    <input name="ProjectState" type="text" value="" maxlength="32" style="width:80px">
                                    Zip
                                    <input name="ProjectZip" type="text" value="" maxlength="16"
                                        style="width:70px"></span>
                                <span class="FieldLabel">Phone &amp; Fax:</span>
                                <input name="ProjectPhone" type="text" value="" maxlength="32" style="width:246px">
                                <br>
                                <span class="FieldLabel">County of Property:</span>
                                <input name="ProjectCounty" type="text" value="" style="width:216px">
                            </td>
                        </tr>
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>OWNER/AWARDING AUTHORITY
                                    </strong></span>
                                <input name="ProjectOwnerCompany" type="text" value="" maxlength="32"
                                    style="width:125px">
                                <!-- <input type="button" value="Add Contact" onClick="addContact()"> -->
                                <br>
                                <span class="FieldLabel">Contact Name:</span>
                                <input type="text" name="ProjectOwnerContact" value="" maxlength="32"
                                    style="width:236px">
                                <br>
                                <span class="FieldLabel">Address:</span>
                                <input type="text" name="ProjectOwnerAddress" value="" maxlength="32"
                                    style="width:269px;">
                                <br>
                                <span class="FieldLabel">City
                                    <input type="text" name="ProjectOwnerCity" value="" maxlength="32"
                                        style="width:80px">
                                    State
                                    <input type="text" name="ProjectOwnerState" value="" maxlength="32"
                                        style="width:80px">
                                    Zip
                                    <input type="text" name="ProjectOwnerZip" value="" maxlength="32"
                                        style="width:70px">
                                </span><br>
                                <span class="FieldLabel">Phone &amp; Fax:</span>
                                <input type="text" name="ProjectOwnerPhone" value="" maxlength="32" style="width:246px">
                            </td>
                        </tr>
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>LENDER-(CA & AZ Project Only)
                                    </strong></span>
                                <input type="text" name="OriginalContractorCompany" value="" maxlength="32"
                                    style="width:190px">
                                <br>
                                <span class="FieldLabel">Contact Name:</span>
                                <input type="text" name="OriginalContractorContact" value="" maxlength="32"
                                    style="width:236px">
                                <br>
                                <span class="FieldLabel">Address:</span>
                                <input type="text" name="OriginalContractorAddress" value="" maxlength="32"
                                    style="width:269px">
                                <br>
                                <span class="FieldLabel">City
                                    <input type="text" name="OriginalContractorCity" value="" maxlength="32"
                                        style="width:80px">
                                    State
                                    <input type="text" name="OriginalContractorState" value="" maxlength="32"
                                        style="width:70px">
                                    Zip
                                    <input type="text" name="OriginalContractorZip" value="" maxlength="16"
                                        style="width:75px"></span>
                                <br>
                                <span class="FieldLabel">Phone &amp; Fax:</span>
                                <input type="text" name="OriginalContractorPhone" value="" maxlength="32"
                                    style="width:246px">
                            </td>
                        </tr>
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>ARCHITECT </strong></span>
                                <input type="text" name="SubContractorCompany" value="" maxlength="32"
                                    style="width:228px">
                                <span class="FieldLabel">Contact Name:</span>
                                <input type="text" name="SubContractorContact" value="" maxlength="32"
                                    style="width:236px">
                                <br>
                                <span class="FieldLabel">Address:</span>
                                <input type="text" name="SubContractorAddress" value="" maxlength="32"
                                    style="width:269px">
                                <br>
                                <span class="FieldLabel">City
                                    <input type="text" name="SubContractorCity" value="" maxlength="32"
                                        style="width:80px">
                                    State
                                    <input type="text" name="SubContractorState" value="" maxlength="32"
                                        style="width:80px">
                                    Zip
                                    <input type="text" name="SubContractorZip" value="" maxlength="16"
                                        style="width:70px">
                                    <br></span>
                                <span class="FieldLabel">Phone &amp; Fax:</span>
                                <input type="text" name="SubContractorPhone" value="" maxlength="32"
                                    style="width:246px">
                            </td>
                        </tr>



                    </table>
                </td>
                <td valign="top" class="BorderBottom">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>PRIME CONTRACTOR</strong></span>
                                <input type="text" name="CustomerCompany" value="" maxlength="32" style="width:216px">
                                <br>
                                <span class="FieldLabel">Contact Name:</span>
                                <input type="text" name="CustomerContact" value="" maxlength="32" style="width:232px">
                                <br>
                                <span class="FieldLabel">Address:</span>
                                <input type="text" name="CustomerAddress" value="" maxlength="32" style="width:265px">
                                <br>
                                <span class="FieldLabel">City
                                    <input type="text" name="CustomerCity" value="" maxlength="32" style="width:80px">
                                    State
                                    <input type="text" name="CustomerState" value="" maxlength="32" style="width:80px">
                                    Zip
                                    <input type="text" name="CustomerZip" value="" maxlength="32"
                                        style="width:70px"></span>
                                <br>
                                <span class="FieldLabel">Phone:</span>
                                <input type="text" name="CustomerPhone" value="" maxlength="32" style="width:242px">
                                <br>
                                <span class="FieldLabel">No.:</span>
                                <input type="text" name="CustomerNumber" value="" maxlength="32" style="width:292px">
                                <br>
                                <span class="FieldLabel">Account #.:</span>
                                <input type="text" name="CustomerAccount" value="" maxlength="32" style="width:255px">
                            </td>
                        </tr>
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>PRIME'S BONDING COMPANY
                                    </strong></span><br>
                                <span class="FieldLabel">Value of order: $</span>
                                <input type="text" name="OrderValue" value="" maxlength="10" style="width:228px">
                                <br>
                                <span class="FieldLabel">Job No.: </span>
                                <input type="text" name="JobNumber" value="" maxlength="32" style="width:276px">
                                <br>
                                <span class="FieldLabel">P.O.No.:</span>
                                <input type="text" name="PONumber" value="" maxlength="32" style="width:272px">
                                <br>
                                <span class="FieldLabel">Contract No.:</span>
                                <input type="text" name="OrderContractNumber" value="" maxlength="32"
                                    style="width:246px">
                                <br>
                                <span class="FieldLabel">Date Products needed.:</span>
                                <input type="text" name="DateNeeded" value="" style="width:188px">
                                <br>
                                <span class="FieldLabel">Approximate start work date:</span>
                                <input type="text" name="StartDate" value="" style="width:164px">
                            </td>
                        </tr>
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>SUB CONTRACTOR</strong></span><br>
                                <span class="FieldLabel">Payment Terms :</span>
                                <input type="text" name="PaymentTerms" value="" maxlength="32" style="width:224px">
                                <br>
                                <span class="FieldLabel">Billing Cycle:</span>
                                <input type="text" name="BillingCycle" value="" maxlength="32" style="width:248px">
                                <br>
                                <span class="FieldLabel">
                                    <input name="PaymentType" type="radio" value="Joint Check">
                                    Joint Check
                                    <input name="PaymentType" type="radio" value="Direct Payment">
                                    Direct Payment</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>SUB'S BONDING COMPANY or LESSEE(if
                                        applicable)</strong></span><br>
                                <label class="FieldLabel" style="clear:left; ">
                                    <input name="YourStatus" type="Radio" value="General Contractor">
                                    General Contractor</label> <br>
                                <label class="FieldLabel" style="clear:left; ">
                                    <input type="Radio" name="YourStatus" value="Subcontractor">
                                    Subcontractor </label><br>
                                <label class="FieldLabel" style="clear:left; ">
                                    <input type="Radio" name="YourStatus" value="Supplier To Subcontractor">
                                    Supplier to Subcontractor </label><br>
                                <label class="FieldLabel" style="clear:left; ">
                                    <input type="Radio" name="YourStatus" value="Supplier Supplier">
                                    Supplier to Supplier(ie. Representative/Wholesaler/Distributor)</label> <br>
                                <label class="FieldLabel" style="clear:left; ">
                                    <input type="Radio" name="YourStatus" value="Other">
                                    Other</label>
                                <input type="text" name="YourStatusOther" value="" maxlength="32" style="width:268px">
                            </td>
                        </tr>

                        <tr>
                            <td><span class="FieldLabel"><strong>14. Miscellaneous:</strong></span>
                                <input type="text" name="Miscellaneous" value="" maxlength="64"
                                    style="margin-left:5px;width:326px">
                            </td>
                        </tr>
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
                                        <td width="10%"><input type="checkbox" name="HeardByWeb" value="1"></td>
                                        <td width="19%">Google</td>
                                        <td width="14%"><input type="checkbox" name="HeardByGoogle" value="1"></td>
                                        <td width="14%">AOL</td>
                                        <td width="13%"><input type="checkbox" name="HeardByAOL" value="1"></td>
                                    </tr>
                                    <tr>
                                        <td>Referral</td>
                                        <td><input type="checkbox" name="HeardByReferral" value="1">
                                        </td>
                                        <td>MSN</td>
                                        <td><input type="checkbox" name="HeardByMSN" value="1">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Other</td>
                                        <td colspan="5"><input name="HeardByOther" type="text" style="width:64px"
                                                maxlength="32" value="">
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
            <button type="button">Continue</button>
        </div>

    </form>
</body>

</html>
