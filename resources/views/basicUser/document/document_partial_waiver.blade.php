<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>PARTIAL WAIVER OF LIEN</title>
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/document.css" type="text/css">
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/document_print.css" type="text/css" media="print">
    <!-- jQuery 3 -->
    <script src="{{ env('ASSET_URL') }}/admin_assets/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ env('ASSET_URL') }}/js/jquery-ui.min.js"></script>
    <script language="javascript">
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
    </script>
</head>

<body onLoad="doOnLoad()">
    <form name="theForm" action="" method="post">
        <input type="hidden" name="ID" value="">
        <input type="hidden" name="ProjectID" value="">
        <div class="MainDocument" style="margin: 0 auto;">
            <p align="center" style="margin:0px 0px 2px 0px;padding:0px; ">
                <strong>
                    PARTIAL WAIVER OF LIEN FOR AN AMOUNT ONLY
                </strong>
            </p>
            <table width="100%" cellpadding="1" cellspacing="0">
                <tr>
                    <td width="50%">STATE OF <input type="text" name="State" class="InputLine" value=""
                            maxlength="32" /></td>
                    <td width="50%"></td>
                </tr>
                <tr>
                    <td>COUNTY OF <input type="text" name="County" class="InputLine" value="" maxlength="32" /></td>
                    <td></td>
                </tr>
            </table>


            <p style="margin:2px 0px 0px 0px;padding:0px; ">TO WHOM IT MAY CONCERN:</p>

            <p style="margin:0px 0px 5px 0px;padding:0px; ">
                WHEREAS the undersigned has been employed by
                <input type="text" name="Customer" class="InputLine" value="" maxlength="32" size="30" />
                to furnish
                <input type="text" name="WorkType" class="InputLine" value="" maxlength="32" />
                for the premises known as
                <input type="text" name="ProjectName" class="InputLine" value="" maxlength="64" />
                ,
                <input type="text" name="ProjectAddress" class="InputLine" value="" maxlength="64" />
                ,
                <input type="text" name="ProjectCity" class="InputLine" value="" maxlength="64" />
                ,
                <input type="text" name="ProjectCounty" class="InputLine" value="" maxlength="64" />
                ,
                <input type="text" name="ProjectState" class="InputLine" value="" maxlength="64" size="12" />
                of which
                <input type="text" name="Owner" class="InputLine" value="" maxlength="32" size="30" />
                is the owner.
            </p>

            <p style="margin:0px 0px 5px 0px;padding:0px; ">The undersigned, for and in consideration of
                $<input type="text" name="CheckAmount" class="InputLine" value="" maxlength="10" size="10" />
                U.S. Dollars, and other good and valuable consideration, and upon clearance of a check from
                <input type="text" name="WaiverCustomer" class="InputLine" value="" maxlength="32" />
                and the clearance of said funds accordingly the Undersigned shall waive and release any and all lien or
                claim of, or right to, lien,
                under the statutes of the State of
                <input type="text" name="WaiverState" class="InputLine" value="" maxlength="64" size="12" />,
                relating to mechanics liens, with respect to and on said above described premises, and the improvements
                thereon, and on the material,
                fixtures, apparatus or machinery furnished, and on the moneys, funds or other due or to become due from
                the
                owner, on account of labor,
                services, material, fixtures, apparatus or machinery furnished only for the amount of this waiver as
                stated
                herein and nothing in this
                partial waiver shall prejudice the balance of funds owing for the value of the unpaid portion of the
                work
                furnished by the undersigned
                for the above described premises.
            </p>

            @include('basicUser.document.include.standard_document_signature')
        </div>
        <div class="Affadavit" style="margin: 0 auto;">
            @include('basicUser.document.include.standard_document_affadavit')
        </div>
        <div style="text-align:center;margin: 0 auto; " class="noprint">
            <input type="button" name="printButton" value="Print" onClick="window.print()"
                class="noprint">&nbsp;&nbsp;&nbsp;
            <input type="submit" name="submitButton" value="Save" class="noprint">
        </div>
    </form>
</body>

</html>
