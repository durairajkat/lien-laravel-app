<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso8859-1" />
    <title>Standard Credit Application/Payment Agreement</title>
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/document.css" type="text/css">
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/document_print.css" type="text/css" media="print">
    <!-- jQuery 3 -->
    <script src="{{ env('ASSET_URL') }}/admin_assets/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ env('ASSET_URL') }}/js/jquery-ui.min.js"></script>
    <script type="text/javascript">
        <!--
        function doOnLoad() {
            focusFirst();
            var oElement;
            for (i = 0; i < document.theForm.elements.length; i++) {
                oElement = document.theForm.elements[i];
                try {
                    if (oElement.type == "text") registerFocusField(oElement, "#FFFF99");
                } catch (e) {

                }
            }
        }
        -->
    </script>
</head>

<body onLoad="doOnLoad()">
    <form name="theForm" action="{{ route('post.creditApplicationSave', [$project_id]) }}" method="post">
        <input type="hidden" name="ID" value="">
        <input type="hidden" name="ProjectID" value="">
        <table width="640" class="data_sheet" align="center" style=" border:1px solid #000; border-collapse:collapse;">
            {{ csrf_field() }}
            <tr>
                <td width="100%">
                    <table width="670" border="0" cellpadding="0" cellspacing="0" style=" border-collapse: collapse;">
                        <tr>
                            <td width="100%">
                                <div align="center" class="NLBHeader">
                                    <p>STANDARD CREDIT APPLICATION/PAYMENT AGREEMENT </p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style=" border-bottom:1px solid #000; text-align:center"></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="border-bottom:2px solid #000;">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                        <tr>
                            <td width="50%" rowspan="5" style="border-left: 1px solid #000;" valign="top">
                                <table width="99%" border="0">
                                    <tr>
                                        <td><strong>&nbsp;&nbsp;To:</strong></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input name="Company" type="text"
                                                value="{{ isset($credit_application->Company) ? $credit_application->Company : '' }}"
                                                class="InputLine" maxlength="128" style="width:412px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input name="Contact" type="text"
                                                value="{{ isset($credit_application->Contact) ? $credit_application->Contact : '' }}"
                                                class="InputLine" style="width:443px" maxlength="64" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input name="Address" type="text"
                                                value="{{ isset($credit_application->Address) ? $credit_application->Address : '' }}"
                                                class="InputLine" maxlength="128" style="width:472px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">City
                                                <input name="City" type="text"
                                                    value="{{ isset($credit_application->City) ? $credit_application->City : '' }}"
                                                    maxlength="64" class="InputLine" style="width:180px" />
                                                State
                                                <input name="State" type="text"
                                                    value="{{ isset($credit_application->State) ? $credit_application->State : '' }}"
                                                    maxlength="64" class="InputLine" style="width:180px" />
                                                Zip
                                                <input name="Zip" type="text" class="InputLine"
                                                    value="{{ isset($credit_application->Zip) ? $credit_application->Zip : '' }}"
                                                    maxlength="10" style="width:120px" />
                                            </span></td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">Phone:</span>
                                            <input name="Phone" type="text"
                                                value="{{ isset($credit_application->Phone) ? $credit_application->Phone : '' }}"
                                                class="InputLine" maxlength="20" style="width:482px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">FAX:</span>
                                            <input name="Fax" type="text" class="InputLine"
                                                value="{{ isset($credit_application->Fax) ? $credit_application->Fax : '' }}"
                                                maxlength="20" style="width:492px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">Email:</span>
                                            <input name="Email" type="text" class="InputLine"
                                                value="{{ isset($credit_application->Email) ? $credit_application->Email : '' }}"
                                                maxlength="20" style="width:488px" />
                                        </td>
                                    </tr>
                                </table>
                                <table width="99%" border="0">
                                    <tr>
                                        <td>hereinafter referred to as the <span
                                                style="font-size:16px; ">&quot;Merchant&quot;</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p><strong>Customer Contact Information</strong></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">Business Name:</span>
                                            <input name="CustomerCompany" type="text"
                                                value="{{ isset($credit_application->CustomerCompany) ? $credit_application->CustomerCompany : '' }}"
                                                maxlength="128" class="InputLine" style="width:412px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">Contact Name: </span>
                                            <input name="CustomerContact" type="text"
                                                value="{{ isset($credit_application->CustomerContact) ? $credit_application->CustomerContact : '' }}"
                                                maxlength="64" class="InputLine" style="width:443px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">Address:</span>
                                            <input name="CustomerAddress"
                                                value="{{ isset($credit_application->CustomerAddress) ? $credit_application->CustomerAddress : '' }}"
                                                maxlength="128" type="text" class="InputLine" style="width:472px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">City
                                                <input name="CustomerCity" type="text"
                                                    value="{{ isset($credit_application->CustomerCity) ? $credit_application->CustomerCity : '' }}"
                                                    maxlength="64" class="InputLine" style="width:180px" />
                                                State
                                                <input name="CustomerState" type="text"
                                                    value="{{ isset($credit_application->CustomerState) ? $credit_application->CustomerState : '' }}"
                                                    maxlength="64" class="InputLine" style="width:180px" />
                                                Zip
                                                <input name="CustomerZip" type="text" class="InputLine"
                                                    value="{{ isset($credit_application->CustomerZip) ? $credit_application->CustomerZip : '' }}"
                                                    maxlength="10" style="width:120px" />
                                            </span></td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">Phone:</span>
                                            <input name="CustomerPhone" type="text" class="InputLine"
                                                value="{{ isset($credit_application->CustomerPhone) ? $credit_application->CustomerPhone : '' }}"
                                                maxlength="20" style="width:482px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">FAX:</span>
                                            <input name="CustomerFax" type="text" class="InputLine"
                                                value="{{ isset($credit_application->CustomerFax) ? $credit_application->CustomerFax : '' }}"
                                                maxlength="20" style="width:492px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">Email:</span>
                                            <input name="CustomerEmail" type="text" class="InputLine"
                                                value="{{ isset($credit_application->CustomerEmail) ? $credit_application->CustomerEmail : '' }}"
                                                maxlength="255" style="width:488px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>hereinafter referred to as the <span
                                                style="font-size:16px; ">&quot;Customer&quot;</span>
                                        </td>
                                    </tr>
                                </table>
                                <table width="99%" border="0">
                                    <tr>
                                        <td>
                                            <p>Please check Your Form of Doing Business </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>_ Corporation _ Partnership _ Sole Proprietorship _ Limited Liability
                                                Co.&nbsp;
                                                State of Origin
                                                <input name="StateOfOrigin" type="text"
                                                    value="{{ isset($credit_application->StateOfOrigin) ? $credit_application->StateOfOrigin : '' }}"
                                                    maxlength="64" class="InputLine" style="width:488px" />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Federal ID #
                                                <input name="FederalID"
                                                    value="{{ isset($credit_application->FederalID) ? $credit_application->FederalID : '' }}"
                                                    maxlength="64" type="text" class="InputLine" style="width:180px" />
                                                Sales Tax Exempt #
                                                <input name="SalesTaxID"
                                                    value="{{ isset($credit_application->SalesTaxID) ? $credit_application->SalesTaxID : '' }}"
                                                    maxlength="64" type="text" class="InputLine" style="width:180px" />
                                                (Attach copy&nbsp; of form) Person to contact regarding invoices &amp;
                                                payment
                                                <input name="PaymentContact" type="text"
                                                    value="{{ isset($credit_application->PaymentContact) ? $credit_application->PaymentContact : '' }}"
                                                    maxlength="128" class="InputLine" style="width:240px" />
                                                Phone Ext
                                                <input name="PaymentPhone"
                                                    value="{{ isset($credit_application->PaymentPhone) ? $credit_application->PaymentPhone : '' }}"
                                                    maxlength="20" type="text" class="InputLine" style="width:120px" />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">Email:</span>
                                            <input name="PaymentEmail"
                                                value="{{ isset($credit_application->PaymentEmail) ? $credit_application->PaymentEmail : '' }}"
                                                maxlength="255" type="text" class="InputLine" style="width:488px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">Address:</span>
                                            <input name="PaymentAddress"
                                                value="{{ isset($credit_application->PaymentAddress) ? $credit_application->PaymentAddress : '' }}"
                                                maxlength="255" type="text" class="InputLine" style="width:472px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Purchase Manager
                                            <input name="PurchaseManager"
                                                value="{{ isset($credit_application->PurchaseManager) ? $credit_application->PurchaseManager : '' }}"
                                                maxlength="128" type="text" class="InputLine" style="width:212px" />
                                            Phone Ext
                                            <input name="PurchaseManagerPhone"
                                                value="{{ isset($credit_application->PurchaseManagerPhone) ? $credit_application->PurchaseManagerPhone : '' }}"
                                                maxlength="30" type="text" class="InputLine" style="width:212px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Parent Company
                                                <input name="ParentCompany"
                                                    value="{{ isset($credit_application->ParentCompany) ? $credit_application->ParentCompany : '' }}"
                                                    maxlength="128" type="text" class="InputLine" style="width:170px" />
                                                Street Address
                                                <input name="ParentCompanyAddress"
                                                    value="{{ isset($credit_application->ParentCompanyAddress) ? $credit_application->ParentCompanyAddress : '' }}"
                                                    maxlength="128" type="text" class="InputLine" style="width:170px" />
                                                &nbsp;P.O. Box
                                                <input name="ParentCompanyPO"
                                                    value="{{ isset($credit_application->ParentCompanyPO) ? $credit_application->ParentCompanyPO : '' }}"
                                                    maxlength="32" type="text" class="InputLine" style="width:40px" />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">City
                                                <input name="ParentCompanyCity"
                                                    value="{{ isset($credit_application->ParentCompanyCity) ? $credit_application->ParentCompanyCity : '' }}"
                                                    maxlength="64" type="text" class="InputLine" style="width:180px" />
                                                State
                                                <input name="ParentCompanyState"
                                                    value="{{ isset($credit_application->ParentCompanyState) ? $credit_application->ParentCompanyState : '' }}"
                                                    maxlength="64" type="text" class="InputLine" style="width:180px" />
                                                Zip
                                                <input name="ParentCompanyZip"
                                                    value="{{ isset($credit_application->ParentCompanyZip) ? $credit_application->ParentCompanyZip : '' }}"
                                                    maxlength="10" type="text" class="InputLine" style="width:180px" />
                                            </span></td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">Phone:</span>
                                            <input name="ParentCompanyPhone"
                                                value="{{ isset($credit_application->ParentCompanyPhone) ? $credit_application->ParentCompanyPhone : '' }}"
                                                maxlength="30" type="text" class="InputLine" style="width:482px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="FieldLabel">FAX:</span>
                                            <input name="ParentCompanyFax"
                                                value="{{ isset($credit_application->ParentCompanyFax) ? $credit_application->ParentCompanyFax : '' }}"
                                                maxlength="30" type="text" class="InputLine" style="width:492px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                                <table width="99%" border="0">
                                    <tr>
                                        <td>
                                            <p> The following information must be completed in full, and will be held in
                                                the
                                                strictest confidence.</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p><strong><u>INFORMATION ON OWNER(S) / PRINCIPALS (S)</u></strong> defined
                                                as:
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>For Proprietorship or Partnership: List all Owners and/or Partners.Person
                                                to
                                                contact regarding invoices &amp; payment For Corporation or LLC: List
                                                all
                                                Officers, Directors, Members and Majority Stockholders</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <table border="1" cellspacing="0" cellpadding="0"
                                            style="font-size: 5px; font-family: Tahoma, Arial;border: 1px solid #666600; width:10px; ">
                                            <tr>
                                                <td>Name</td>
                                                <td>Home Address</td>
                                                <td>Phone</td>
                                                <td>Social Sec. #</td>
                                                <td>Position</td>
                                            </tr>

                                            <tr>
                                                <td><input name="name"
                                                        value="{{ isset($credit_application->name) ? $credit_application->name : '' }}"
                                                        maxlength="255" type="text" class="InputLine" /></td>

                                                <td> <input name="home"
                                                        value="{{ isset($credit_application->home) ? $credit_application->home : '' }}"
                                                        maxlength="255" type="text" class="InputLine" /></td>
                                                <td><input name="phone2"
                                                        value="{{ isset($credit_application->phone2) ? $credit_application->phone2 : '' }}"
                                                        maxlength="255" type="text" class="InputLine" /></td>
                                                <td><input name="social"
                                                        value="{{ isset($credit_application->social) ? $credit_application->social : '' }}"
                                                        maxlength="255" type="text" class="InputLine" /></td>
                                                <td><input name="position"
                                                        value="{{ isset($credit_application->position) ? $credit_application->position : '' }}"
                                                        maxlength="255" type="text" class="InputLine" /></td>

                                            </tr>
                                            <tr>
                                                <td><input name="name1"
                                                        value="{{ isset($credit_application->name1) ? $credit_application->name1 : '' }}"
                                                        maxlength="255" type="text" class="InputLine" /></td>

                                                <td> <input name="home1"
                                                        value="{{ isset($credit_application->home1) ? $credit_application->home1 : '' }}"
                                                        maxlength="255" type="text" class="InputLine" /></td>
                                                <td><input name="phone1"
                                                        value="{{ isset($credit_application->phone1) ? $credit_application->phone1 : '' }}"
                                                        maxlength="255" type="text" class="InputLine" /></td>
                                                <td><input name="social1"
                                                        value="{{ isset($credit_application->social1) ? $credit_application->social1 : '' }}"
                                                        maxlength="255" type="text" class="InputLine" /></td>
                                                <td><input name="position1"
                                                        value="{{ isset($credit_application->position1) ? $credit_application->position1 : '' }}"
                                                        maxlength="255" type="text" class="InputLine" /></td>

                                            </tr>

                                        </table>
                                    <tr>
                                        <td>Have any of the companies or principals listed above ever been a debtor in a
                                            bankruptcy proceeding?
                                            <input name="Bankruptcy"
                                                value="{{ isset($credit_application->Bankruptcy) ? $credit_application->Bankruptcy : '' }}"
                                                maxlength="32" type="text" class="InputLine" style="width:80px" />
                                            If yes, list year and state:
                                            <input name="BankruptcyYearState"
                                                value="{{ isset($credit_application->BankruptcyYearState) ? $credit_application->BankruptcyYearState : '' }}"
                                                maxlength="64" type="text" class="InputLine" style="width:180px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Has any judgment been entered against any of the companies or principals
                                            listed
                                            above?
                                            <input name="Judgement"
                                                value="{{ isset($credit_application->Judgement) ? $credit_application->Judgement : '' }}"
                                                maxlength="32" type="text" class="InputLine" style="width:212px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Are there any legal actions or arbitration pending against any of the
                                                companies or principals listed above?
                                                <input name="PendingLegal"
                                                    value="{{ isset($credit_application->PendingLegal) ? $credit_application->PendingLegal : '' }}"
                                                    maxlength="32" type="text" class="InputLine" style="width:120px" />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>If yes to either of the last two questions, please attach separate sheet
                                                detailing. </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                                <table width="99%" border="0">
                                    <tr>
                                        <td>
                                            <p align="center"><strong>*** Please attach most current year-end company
                                                    Financial Statement***</strong></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p><strong>CREDIT REFERENCES</strong> (Attach separate schedule if
                                                necessary)
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p><em>Primary Bank</em>: </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Name
                                            <input name="BankName"
                                                value="{{ isset($credit_application->BankName) ? $credit_application->BankName : '' }}"
                                                maxlength="64" type="text" class="InputLine" style="width:188px" />
                                            &nbsp; Account #
                                            <input name="BankAccount"
                                                value="{{ isset($credit_application->BankAccount) ? $credit_application->BankAccount : '' }}"
                                                maxlength="64" type="text" class="InputLine" style="width:188px" />
                                            <br>
                                            Phone
                                            <input name="BankPhone"
                                                value="{{ isset($credit_application->BankPhone) ? $credit_application->BankPhone : '' }}"
                                                maxlength="32" type="text" class="InputLine" style="width:170px" />
                                            &nbsp; Fax
                                            <input name="BankFax"
                                                value="{{ isset($credit_application->BankFax) ? $credit_application->BankFax : '' }}"
                                                maxlength="32" type="text" class="InputLine" style="width:170px" />
                                            &nbsp; Contact Name
                                            <input name="BankContact"
                                                value="{{ isset($credit_application->BankContact) ? $credit_application->BankContact : '' }}"
                                                maxlength="64" type="text" class="InputLine" style="width:118px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p><strong>TRADE REFERENCES</strong></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Name
                                            <input name="Reference1Name"
                                                value="{{ isset($credit_application->Reference1Name) ? $credit_application->Reference1Name : '' }}"
                                                maxlength="64" type="text" class="InputLine" style="width:188px" />
                                            &nbsp; Account #
                                            <input name="Reference1Account"
                                                value="{{ isset($credit_application->Reference1Account) ? $credit_application->Reference1Account : '' }}"
                                                maxlength="64" type="text" class="InputLine" style="width:188px" />
                                            <br>
                                            Phone
                                            <input name="Reference1Phone"
                                                value="{{ isset($credit_application->Reference1Phone) ? $credit_application->Reference1Phone : '' }}"
                                                maxlength="32" type="text" class="InputLine" style="width:170px" />
                                            &nbsp; Fax
                                            <input name="Reference1Fax"
                                                value="{{ isset($credit_application->Reference1Fax) ? $credit_application->Reference1Fax : '' }}"
                                                maxlength="32" type="text" class="InputLine" style="width:170px" />
                                            &nbsp; Contact Name
                                            <input name="Reference1Contact"
                                                value="{{ isset($credit_application->Reference1Contact) ? $credit_application->Reference1Contact : '' }}"
                                                maxlength="64" type="text" class="InputLine" style="width:118px" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Name
                                                <input name="Reference2Name"
                                                    value="{{ isset($credit_application->Reference2Name) ? $credit_application->Reference2Name : '' }}"
                                                    maxlength="64" type="text" class="InputLine" style="width:188px" />
                                                &nbsp; Account #
                                                <input name="Reference2Account"
                                                    value="{{ isset($credit_application->Reference2Account) ? $credit_application->Reference2Account : '' }}"
                                                    maxlength="64" type="text" class="InputLine" style="width:188px" />
                                                <br>
                                                Phone
                                                <input name="Reference2Phone"
                                                    value="{{ isset($credit_application->Reference2Phone) ? $credit_application->Reference2Phone : '' }}"
                                                    maxlength="32" type="text" class="InputLine" style="width:170px" />
                                                &nbsp; Fax
                                                <input name="Reference2Fax"
                                                    value="{{ isset($credit_application->Reference2Fax) ? $credit_application->Reference2Fax : '' }}"
                                                    maxlength="32" type="text" class="InputLine" style="width:170px" />
                                                &nbsp; Contact Name
                                                <input name="Reference2Contact"
                                                    value="{{ isset($credit_application->Reference2Contact) ? $credit_application->Reference2Contact : '' }}"
                                                    maxlength="64" type="text" class="InputLine" style="width:118px" />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Name
                                                <input name="Reference3Name"
                                                    value="{{ isset($credit_application->Reference3Name) ? $credit_application->Reference3Name : '' }}"
                                                    maxlength="64" type="text" class="InputLine" style="width:188px" />
                                                &nbsp; Account #
                                                <input name="Reference3Account"
                                                    value="{{ isset($credit_application->Reference3Account) ? $credit_application->Reference3Account : '' }}"
                                                    maxlength="64" type="text" class="InputLine" style="width:188px" />
                                                <br>
                                                Phone
                                                <input name="Reference3Phone"
                                                    value="{{ isset($credit_application->Reference3Phone) ? $credit_application->Reference3Phone : '' }}"
                                                    maxlength="32" type="text" class="InputLine" style="width:170px" />
                                                &nbsp; Fax
                                                <input name="Reference3Fax"
                                                    value="{{ isset($credit_application->Reference3Fax) ? $credit_application->Reference3Fax : '' }}"
                                                    maxlength="32" type="text" class="InputLine" style="width:170px" />
                                                &nbsp; Contact Name
                                                <input name="Reference3Contact"
                                                    value="{{ isset($credit_application->Reference3Contact) ? $credit_application->Reference3Contact : '' }}"
                                                    maxlength="64" type="text" class="InputLine" style="width:118px" />
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>The &ldquo;Credit Terms and Conditions&rdquo; below are incorporated herein
                                            in
                                            their entity and shall be binding upon the Customer.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>I (We) certify that all the information provided by Customer for the
                                                credit
                                                application is true and correct.&nbsp; Customer acknowledges that
                                                Merchant
                                                will rely upon the information provided herein.&nbsp; Customer agrees
                                                that
                                                all Merchant&rsquo;s invoice(s) shall be timely paid within the standard
                                                payment terms of the Merchant.&nbsp; I (We) further understand that
                                                Merchant
                                                is authorized from time to time to obtain Business and Consumer Credit
                                                Reports on Customer or any principals listed above or the right to
                                                request
                                                and secure, at any time, a Personal Guaranty from any or all Owners or
                                                Principals of the organization as additional security of obligations due
                                                to
                                                Merchant.&nbsp; Customer represents and warrants that the goods and
                                                supplies
                                                purchased from the Merchant are being brought for business purposes and
                                                not
                                                for personal consumption.&nbsp;&nbsp; I (We) certify that there is no
                                                other
                                                credit information relevant to the above inquiries and that the Customer
                                                fully understands and agrees to the following terms:</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>(1)&nbsp;&nbsp;&nbsp;&nbsp; The Customer promises to (a) provide its
                                                customers and the paying bank with a sworn materialmen's or contractor's
                                                statement which list the Merchant as the supplier of the materials or
                                                services purchased herein from time to time and will list the correct
                                                value
                                                of said materials and (b) cause said customer to give original sworn
                                                statements to the owner of the premises and the paying bank if said
                                                premises
                                                are improved by Merchant's materials.</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>(2)&nbsp;&nbsp;&nbsp;&nbsp; The Customer will provide Merchant upon
                                                request,
                                                with the address and location of the job where the materials are being
                                                delivered and the name of the party purchasing said materials from the
                                                Customer and any other information including the names and addresses of
                                                all
                                                owners, general contractors, architects, lenders, subcontractors, and
                                                title
                                                companies.</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>(3)&nbsp;&nbsp;&nbsp;&nbsp; The Customer hereby gives you permission to
                                            obtain
                                            information from any source available to you in order to investigate the
                                            undersigned's credit worthiness.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>(4)&nbsp;&nbsp;&nbsp;&nbsp; The Customer agrees to compensate the Merchant
                                            for
                                            all expenses relating to Merchant's credit check of any given project not to
                                            exceed $175 per project.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>(5)&nbsp;&nbsp;&nbsp;&nbsp; The Customer represents and warrants that the
                                            goods
                                            and supplies purchased from the Merchant are being bought for business
                                            purposes
                                            and not for personal consumption.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>Except as stated herein nothing in this agreement shall modify any prior
                                                agreement between the parties hereto, including any prior credit
                                                agreement
                                                or other agreement for purchase and sale of products or extrusions of
                                                products.</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>(6)&nbsp;&nbsp;&nbsp;&nbsp; The Customer hereby agrees that within 48
                                                hours
                                                after the Customer receives payments from any source for the materials
                                                the
                                                Customer purchased from Merchant, the Customer shall immediately without
                                                delay, pay in full for all materials for which the Customer has been
                                                paid.&nbsp;
                                                Until payment is made to Merchant the Customer shall act as trustee for
                                                Merchant and said trustee agrees to hold said funds paid to Customer on
                                                account of Merchant's materials for sole benefit of Merchant.</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>(7)&nbsp;&nbsp;&nbsp;&nbsp; All waivers executed by the Merchant shall be
                                                effective only to the total dollar amount of payments actually
                                                received.&nbsp;
                                                Customer agrees that Merchant retains it&rsquo;s mechanic lien, payment
                                                bond
                                                or other legal rights for unpaid invoices, regardless of what other
                                                documents have been presented to Merchant for signature that may imply
                                                otherwise.&nbsp; Customer further agrees that Merchant has the right to
                                                determine, in its sole discretion, how to apply payments, and which
                                                invoices
                                                to pay with all payments, received on this account, despite any advice
                                                to
                                                the contrary. Merchant may change credit limits or other credit terms at
                                                any
                                                time, in its sole discretion.</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>(8)&nbsp;&nbsp;&nbsp;&nbsp; Customer further agrees that Merchant shall
                                                not,
                                                in any event, be responsible for any damage due to delay in supply of
                                                any
                                                labor or materials.&nbsp; Customer expressly agrees for itself, its
                                                customers, and their customers, contractors and suppliers not to make,
                                                and
                                                hereby waives, any claim for damages on account of any delay,
                                                obstruction,
                                                or hindrance.&nbsp; Customer&rsquo;s sole remedy for any delay,
                                                obstruction,
                                                or hindrance shall be an extension of the time in which to complete the
                                                Work.&nbsp; Customer agrees to pay a reasonable storage fee if materials
                                                are
                                                stored on Merchant&rsquo;s yard for more than 60 days.</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>(9)&nbsp;&nbsp;&nbsp;&nbsp; In the event a waiver of lien document is
                                                required before the Customer is able to fully pay for materials
                                                purchased
                                                herein, the Customer hereby authorizes you to instruct all of its
                                                customers,
                                                the lender, or title company to issue a direct or joint check made
                                                payable
                                                if joint, to Merchant and the Customer in an amount equal to my total
                                                unpaid
                                                balance outstanding with Merchant on any given project.</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>(10)&nbsp;&nbsp; In the event of the Undersigned's default in timely payment
                                            to
                                            the Merchant for material hereinafter purchased, the Customer agrees to pay,
                                            indemnify and compensate Merchant for all costs of collection including
                                            reasonable attorney's fees, Court costs and late charges.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>(11)&nbsp;&nbsp; As security for payment of all past, present and future
                                            extensions of credit and all other types of indebtedness and to secure
                                            payment
                                            of other satisfaction of all other liabilities between the Customer and
                                            Merchant, the Customer hereby conveys, assigns and grants to Merchant a
                                            present,
                                            future and continuing security interest, for collateral purposes only, in
                                            the
                                            following property of the Undersigned: all accounts of any kind, inventory,
                                            cash, equipment, tangibles, intangibles, goods sold by Merchant to the
                                            Customer
                                            and other property and collateral contained in invoices and delivery tickets
                                            given by the Merchant to the Customer in the normal course of business
                                            hereinafter, and all proceeds of any and all such goods.&nbsp; Said property
                                            is
                                            hereinafter referred to as the &quot;Collateral.&quot;&nbsp; The parties
                                            herein
                                            declare that the security interest in the &quot;Collateral&quot; granted to
                                            Merchant hereunder is granted for the purpose of securing past, present and
                                            future extensions of credit agreed to herein by the undersigned, and that
                                            the
                                            Collateral is not, in fact, sold to Merchant.&nbsp; Simultaneous with the
                                            execution of this document, the parties will cooperate in executing the form
                                            entitled UCC-2 or UCC-1.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>(12)&nbsp;&nbsp; To further secure past, present and future extensions of
                                            credit
                                            from Merchant to Customer to the Customer, the Customer hereby assigns for
                                            collateral purposes only, any and all mechanics' lien rights and contract
                                            rights
                                            which the Customer possesses in the projects and real estate which the
                                            Customer
                                            has permanently improved by the resale and/or incorporation of Merchant's
                                            materials and goods and as additional security this includes all mechanics'
                                            lien
                                            rights and contract rights in any and all parcels of land improved by the
                                            Customer from time to time.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>(13)&nbsp;&nbsp; Customer further agrees to pay all amounts due under
                                                this
                                                Agreement until Merchant has received written notice closing this
                                                account,
                                                mailed U.S. Mail Certified Return Receipt Requested, no matter what
                                                person
                                                or entity ordered or used the labor and material supplied on this
                                                account
                                                and regardless of any change in the legal structure of Customer or the
                                                existence of entities or individuals legally distinct from Customer
                                                using or
                                                benefiting from the labor and materials supplied.&nbsp; In the event
                                                other
                                                entities or individuals order or use the labor or materials pursuant to
                                                this
                                                Agreement, it is agreed that both the Customer and such other legal
                                                entities
                                                or individuals shall be obligated for all amounts due under this
                                                Agreement.</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>Customer
                            <input name="Customer"
                                value="{{ isset($credit_application->Customer) ? $credit_application->Customer : '' }}"
                                maxlength="64" type="text" class="InputLine" style="width:180px" />
                        </strong></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>By
                            <input name="PreparedBy"
                                value="{{ isset($credit_application->PreparedBy) ? $credit_application->PreparedBy : '' }}"
                                maxlength="64" type="text" class="InputLine" style="width:180px" />
                        </strong></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div align="right"><strong>Dated</strong><strong>
                            <input name="Dated" id="DateSign"
                                value="{{ isset($credit_application->Dated) ? $credit_application->Dated : '' }}"
                                maxlength="64" type="text" class="InputLine" style="width:118px" />
                            <input type="button" name="button_DataSheetDate223" value="..."
                                onClick="$('#DateSign').datepicker();$('#DateSign').datepicker('show');"
                                class="NoPrint">
                        </strong> (Title) <strong>
                            <input name="Title"
                                value="{{ isset($credit_application->Title) ? $credit_application->Title : '' }}"
                                maxlength="64" type="text" class="InputLine" style="width:118px" />
                        </strong></div>
                </td>
            </tr>
        </table>
        @if ($flag != 1)
            <div style="text-align:center;margin-top:15px; ">
                <input type="button" name="printButton" value="Print" onClick="window.print()"
                    class="noprint">&nbsp;&nbsp;&nbsp;
                <input type="submit" name="submitButton" value="Save" class="noprint">
            </div>
        @endif

    </form>
</body>

</html>
