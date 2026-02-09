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
                            <td class="BorderBottom"><span class="FieldLabel"><strong>2.Project Owner or Public Agency:
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
                            <td class="BorderBottom"><span class="FieldLabel"><strong>3.Original Contractor:
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
                            <td class="BorderBottom"><span class="FieldLabel"><strong>4.Subcontractor: </strong></span>
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
                        <tr>
                            <td class="BorderBottom">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="29%" valign="top"><span class="FieldLabel"
                                                style="display:block; float:left;"><strong>5. Is the
                                                    Project:</strong></span> </td>
                                        <td width="71%"><span class="FieldLabel">
                                                <label><input name="ProjectType" type="radio" value="Privately Owned"
                                                        style="border-bottom:none">
                                                    Privately Owned</label>
                                                <label><input type="radio" name="ProjectType" value="Public Works"
                                                        style="border-bottom:none">
                                                    Public Works </label><br>
                                                <label><input type="radio" name="ProjectType" value="Local Owned"
                                                        style="border-bottom:none">
                                                    Local Owned</label>
                                                <label><input type="radio" name="ProjectType" value="Federal"
                                                        style="border-bottom:none">
                                                    Federal</label></span></td>
                                    </tr>
                                </table>
                                <span class="FieldLabel">Contract #</span>
                                <input type="text" name="ContractNumber" value="" style="width:262px">
                                <br>
                                <span class="FieldLabel">Project#</span>
                                <input type="text" name="ProjectNumber" value="" style="width:273px">
                            </td>
                        </tr>
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>6. Project Notice:</strong> Has
                                    the GC or Owner filed a notice that the Project has commenced?</span><br>
                                <span class="FieldLabel">
                                    <input name="ProjectNotice" type="radio" value="Yes">
                                    Yes (Fax copies to NLB)
                                    <input type="radio" name="ProjectNotice" value="No">
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
                                <input name="PaymentBondNumber" type="text" value="" maxlength="32" style="width:166px">
                                <br>
                                <span class="FieldLabel">Name of bonding company:</span>
                                <input name="BondCompany" type="text" value="" maxlength="32" style="width:167px">
                                <br>
                                <span class="FieldLabel">Contact Name:</span>
                                <input type="text" name="BondContact" value="" maxlength="32" style="width:236px">
                                <br>
                                <span class="FieldLabel">Address:</span>
                                <input type="text" name="BondAddress" value="" maxlength="32" style="width:269px">
                                <br>
                                <span class="FieldLabel">City/State/Zip:</span>
                                <input type="text" name="BondCity" value="" maxlength="32" style="width:80px">
                                /
                                <input type="text" name="BondState" value="" maxlength="32" style="width:80px">
                                /
                                <input type="text" name="BondZip" value="" maxlength="16" style="width:80px">
                                <br>
                                <span class="FieldLabel">Phone:</span>
                                <input type="text" name="BondPhone" value="" maxlength="16" style="width:246px">
                            </td>
                        </tr>
                    </table>
                </td>
                <td valign="top" class="BorderBottom">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="BorderBottom"><span class="FieldLabel"><strong>9.Your Customer:</strong></span>
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
                            <td class="BorderBottom"><span class="FieldLabel"><strong>10.Your Order :
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
                            <td class="BorderBottom"><span class="FieldLabel"><strong>11. Credit
                                        Information:</strong></span><br>
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
                            <td class="BorderBottom"><span class="FieldLabel"><strong>12.Your Status in Project:
                                    </strong></span><br>
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
                            <td class="BorderBottom"><span class="FieldLabel"><strong>13. Your Document Check
                                        List:</strong></span><br>
                                <label class="FieldLabel" style="float:none; ">
                                    <input type="checkbox" name="DocumentsPurchaseOrder" value="1">
                                    Purchase Order / Contract </label>
                                <label class="FieldLabel" style="float:none; ">
                                    <input type="checkbox" name="DocumentsDeliveryTickets" value="1">
                                    Invoices &amp; Delivery Tickets </label>
                                <label class="FieldLabel" style="float:none; ">
                                    <input type="checkbox" name="DocumentsWaiver" value="1">
                                    Copies of Waiver of Lien </label>
                                <label class="FieldLabel" style="float:none; ">
                                    <input type="checkbox" name="DocumentsLegalDescription" value="1">
                                    Legal Description</label>
                                <label class="FieldLabel" style="float:none; ">
                                    <input type="checkbox" name="DocumentsPaymentBond" value="1">
                                    Payment Bond</label>
                                <label class="FieldLabel" style="float:none; ">
                                    <input type="checkbox" name="DocumentsJointCheckAgreement" value="1">
                                    Joint Check Agreement</label>
                                <label class="FieldLabel" style="float:none; ">
                                    <input type="checkbox" name="DocumentsPreliminaryNotice" value="1">
                                    Preliminary Notice</label>
                                <label class="FieldLabel" style="float:none; ">
                                    <input type="checkbox" name="DocumentsOther" value="1">
                                    Other</label>
                                <input type="text" name="DocumentsOtherDocument" value="" style="width:270px">
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
                                        id="AddContactFirstName" style="width:55px;" value="">

                                    <input type="text" name="AddContactLastName" id="AddContactLastName"
                                        style="width:55px;" value="">
                                </td>
                                <td style="border-top:solid 1px #666666; "><select name="AddContactType"
                                        id="AddContactType"
                                        style="border: 1px solid #FFFFFF;border-bottom: 1px solid #000;height: 15px;font-size: 12px;color: #000; ">
                                        <option value="Architect"> Architect </option>
                                        <option value="Building Department Inspector"> Building Department Inspector
                                        </option>
                                        <option value="Civil Engineer"> Civil Engineer </option>
                                        <option value="Construction Escrowee"> Construction Escrowee </option>
                                        <option value="County Agency"> County Agency </option>
                                        <option value="Designer"> Designer </option>
                                        <option value="Engineer"> Engineer </option>
                                        <option value="Equipment Lessor"> Equipment Lessor </option>
                                        <option value="Fabricator"> Fabricator </option>
                                        <option value="Federal Agency"> Federal Agency </option>
                                        <option value="General Contractor"> General Contractor </option>
                                        <option value="Insurance Carrier"> Insurance Carrier </option>
                                        <option value="Labor"> Labor </option>
                                        <option value="Lender"> Lender </option>
                                        <option value="Lessee"> Lessee </option>
                                        <option value="Lessor"> Lessor </option>
                                        <option value="Management"> Management </option>
                                        <option value="Manufacturer"> Manufacturer </option>
                                        <option value="Municipality"> Municipality </option>
                                        <option value="Misc"> Misc. Related Party </option>
                                        <option value="Owner"> Project Owner </option>
                                        <option value="Project Manager"> Project Manager </option>
                                        <option value="State"> State </option>
                                        <option value="Structural Engineer"> Structural Engineer </option>
                                        <option value="Sub-Contractor"> Sub-Contractor </option>
                                        <option value="Supplier"> Supplier </option>
                                        <option value="Surety"> Surety </option>
                                        <option value="Temporary Laborer"> Temporary Laborer </option>
                                        <option value="Title Company"> Title Company </option>
                                    </select>
                                </td>
                                <td style="border-top:solid 1px #666666; "><input type="text" name="AddContactCompany"
                                        id="Company" style="width:110px;" value="">
                                </td>
                                <td style="border-top:solid 1px #666666; "><input type="text" name="AddContactPhone"
                                        id="Phone" value="" style="width:80px;">
                                </td>
                                <td style="border-top:solid 1px #666666; "><input type="text" name="AddContactEmail"
                                        id="Email" style="width:120px;" value="">
                                </td>
                                <!--  <td style="border-top:solid 1px #666666; ">
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
        @if ($flag != 1)
            <div align="center" style=" margin-top: 5px;">
                <button type="submit">Continue</button>
            </div>
        @endif
    </form>
</body>

</html>
