<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Joint Payment Authrorization Document</title>
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
    <form name="theForm" action="{{ route('post.jointPaymentAuthorizationSave', [$project_id]) }}" method="post">
        <input type="hidden" name="ID" value="">
        <input type="hidden" name="ProjectID" value="">
        {{ csrf_field() }}

        <table width="640" border="1" align="center">
            <tr>
                <td>
                    <div>
                        <p>
                        <div align="center" class="NLBHeader"><strong><u>JOINT PAYMENT
                                    AUTHORIZATION</u></strong><strong> </strong></div>
                        </p>
                        <p>The undersigned agree as follows:</p>
                        <p>&nbsp;</p>
                        <p> In consideration of
                            <input type="text" name="Company" class="InputLine"
                                value="{{ isset($joint_payment->Company) ? $joint_payment->Company : '' }}"
                                maxlength="32" />

                            <input type="text" name="Supplier" class="InputLine"
                                value="{{ isset($joint_payment->Supplier) ? $joint_payment->Supplier : '' }}"
                                maxlength="32" />
                            , of
                            <input type="text" name="Address" class="InputLine"
                                value="{{ isset($joint_payment->Address) ? $joint_payment->Address : '' }}"
                                maxlength="32" size="30" />
                            , supplying
                            <input type="text" name="BusinessDescription" class="InputLine"
                                value="{{ isset($joint_payment->BusinessDescription) ? $joint_payment->BusinessDescription : '' }}"
                                maxlength="64" />
                            to
                            <input type="text" name="CustomerName" class="InputLine"
                                value="{{ $joint_payment->CustomerName or '' }}" maxlength="32" />
                            ,
                            <input type="text" name="Subcontractor" class="InputLine"
                                value="{{ isset($joint_payment->Subcontractor) ? $joint_payment->Subcontractor : '' }}"
                                maxlength="32" size="24" />
                            of
                            <input type="text" name="CustomerAddress" class="InputLine"
                                value="{{ isset($joint_payment->CustomerAddress) ? $joint_payment->CustomerAddress : '' }}"
                                maxlength="32" />
                            ,
                            <input type="text" name="CustomerCity" class="InputLine"
                                value="{{ isset($joint_payment->CustomerCity) ? $joint_payment->CustomerCity : '' }}"
                                maxlength="32" />
                            ,
                            <input type="text" name="CustomerState" class="InputLine"
                                value="{{ isset($joint_payment->CustomerState) ? $joint_payment->CustomerState : '' }}"
                                maxlength="32" size="10" />
                            , who installed said materials for
                            <input type="text" name="ContractorName" class="InputLine"
                                value="{{ isset($joint_payment->ContractorName) ? $joint_payment->ContractorName : '' }}"
                                maxlength="32" />

                            <input type="text" name="GeneralContractor" class="InputLine"
                                value="{{ isset($joint_payment->GeneralContractor) ? $joint_payment->GeneralContractor : '' }}"
                                maxlength="32" size="28" />

                            <input type="text" name="ContractorAddress" class="InputLine"
                                value="{{ isset($joint_payment->ContractorAddress) ? $joint_payment->ContractorAddress : '' }}"
                                maxlength="32" />

                            <input type="text" name="ContractorCity" class="InputLine"
                                value="{{ isset($joint_payment->ContractorCity) ? $joint_payment->ContractorCity : '' }}"
                                maxlength="32" />

                            <input type="text" name="ContractorState" class="InputLine"
                                value="{{ isset($joint_payment->ContractorState) ? $joint_payment->ContractorState : '' }}"
                                maxlength="32" size="10" />
                            , to the Project known as
                            <input type="text" name="ProjectName" class="InputLine"
                                value="{{ isset($joint_payment->ProjectName) ? $joint_payment->ProjectName : '' }}"
                                maxlength="32" />

                            <input type="text" name="Project" class="InputLine"
                                value="{{ isset($joint_payment->Project) ? $joint_payment->Project : '' }}"
                                maxlength="32" />
                            ,
                            <input type="text" name="ContractorName2" class="InputLine"
                                value="{{ isset($joint_payment->ContractorName2) ? $joint_payment->ContractorName2 : '' }}"
                                maxlength="32" />
                            agrees to pay the full amount of Supplier's invoices thereon for a total of $
                            <input type="text" name="ContractAmount" class="InputLine"
                                value="{{ isset($joint_payment->ContractAmount) ? $joint_payment->ContractAmount : '' }}"
                                maxlength="16" size="10" />
                            , by check payable to Supplier and Subcontractor, for said materials, Net 30 Days. It is
                            expressly understood that Supplier does not waive any rights accorded to it by law as a
                            material
                            supplier on this Project, or otherwise. Check is to be sent directly to Supplier
                            <strong> </strong><br />
                            <strong> </strong>A photocopy of this document shall be of the same force and effect as the
                            original.
                        </p>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div>
                        <p style="margin:0; ">&nbsp;</p>
                        <p align="center"><strong>Confirmed and Accepted:</strong></p>
                        <p>
                            <input type="text" name="ContractorSigned" class="InputLine"
                                value="{{ isset($joint_payment->ContractorSigned) ? $joint_payment->ContractorSigned : '' }}"
                                size="30" maxlength="32">
                        </p>
                        <p>By:
                            <input type="text" class="InputLine" name="ContractorBy"
                                value="{{ isset($joint_payment->ContractorBy) ? $joint_payment->ContractorBy : '' }}"
                                maxlength="32" />
                        </p>
                        <p>Its: <u>
                                <input type="text" class="InputLine" name="ContractorITS"
                                    value="{{ isset($joint_payment->ContractorITS) ? $joint_payment->ContractorITS : '' }}"
                                    maxlength="32" />
                            </u></p>
                        <p>&nbsp;</p>
                        <p>
                            <input type="text" name="CompanySigned" class="InputLine"
                                value="{{ isset($joint_payment->CompanySigned) ? $joint_payment->CompanySigned : '' }}"
                                maxlength="32" />
                            <br />
                        </p>
                        <p>By:
                            <input type="text" class="InputLine" name="CompanyBy"
                                value="{{ isset($joint_payment->CompanyBy) ? $joint_payment->CompanyBy : '' }}"
                                maxlength="32" />
                        </p>
                        <p>Its: <u>
                                <input type="text" class="InputLine" name="CompanyITS"
                                    value="{{ isset($joint_payment->CompanyITS) ? $joint_payment->CompanyITS : '' }}"
                                    maxlength="32" />
                            </u></p>
                        <p>&nbsp;</p>
                        <p><br />
                            <input type="text" name="UserSigned" class="InputLine"
                                value="{{ isset($joint_payment->UserSigned) ? $joint_payment->UserSigned : '' }}"
                                maxlength="32" />
                            <br />
                        </p>
                        <p> By:
                            <input type="text" class="InputLine" name="UserBy"
                                value="{{ isset($joint_payment->UserBy) ? $joint_payment->UserBy : '' }}"
                                maxlength="32" />
                        </p>
                        <p>Its: <u>
                                <input type="text" class="InputLine" name="UserITS"
                                    value="{{ isset($joint_payment->UserITS) ? $joint_payment->UserITS : '' }}"
                                    maxlength="32" />
                            </u></p>
                    </div>
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
