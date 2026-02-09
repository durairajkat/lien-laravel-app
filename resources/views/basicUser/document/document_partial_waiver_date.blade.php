<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>PARTIAL WAIVER OF LIEN</title>
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/document.css" type="text/css">
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/document_print.css" type="text/css" media="print">

    <script type="text/javascript" src="{{ env('ASSET_URL') }}/admin_assets/bower_components/jquery/dist/jquery.min.js">
    </script>
    <script src="{{ env('ASSET_URL') }}/js/common_form_fields.js"></script>
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
            registerNumberField(document.theForm.CheckAmount, true);
            registerNumberField(document.theForm.ContractorAmount, true);
            registerNumberField(document.theForm.ContractorPaid, true);
            registerNumberField(document.theForm.ContractPrice1, true);
            registerNumberField(document.theForm.AmountPaid1, true);
            registerNumberField(document.theForm.ThisPayment1, true);
            registerNumberField(document.theForm.BalDue1, true);
            registerNumberField(document.theForm.ContractPrice2, true);
            registerNumberField(document.theForm.AmountPaid2, true);
            registerNumberField(document.theForm.ThisPayment2, true);
            registerNumberField(document.theForm.BalDue2, true);
            registerNumberField(document.theForm.ContractPrice3, true);
            registerNumberField(document.theForm.AmountPaid3, true);
            registerNumberField(document.theForm.ThisPayment3, true);
            registerNumberField(document.theForm.BalDue3, true);
            registerNumberField(document.theForm.ContractPrice4, true);
            registerNumberField(document.theForm.AmountPaid4, true);
            registerNumberField(document.theForm.ThisPayment4, true);
            registerNumberField(document.theForm.BalDue4, true);
            registerNumberField(document.theForm.ContractPrice5, true);
            registerNumberField(document.theForm.AmountPaid5, true);
            registerNumberField(document.theForm.ThisPayment5, true);
            registerNumberField(document.theForm.BalDue5, true);
        }
    </script>
</head>

<body onLoad="doOnLoad()">
    <form name="theForm" action="" method="post">
        <input type="hidden" name="ID" value="">
        <input type="hidden" name="ProjectID" value="">
        <div class="MainDocument" style="margin: 0 auto;">
            <p align="center" style="margin:0px 0px 5px 0px;padding:0px; "><strong>PARTIAL WAIVER OF LIEN TO
                    DATE</strong></p>

            <p style="margin:0px 0px 2px 0px;padding:0px; ">STATE OF <input type="text" name="State" class="InputLine"
                    value="" maxlength="32" /></p>
            <p style="margin:0px 0px 5px 0px;padding:0px; ">COUNTY OF <input type="text" name="County" class="InputLine"
                    value="" maxlength="32" /></p>

            <p style="margin:0px 0px 5px 0px;padding:0px; ">TO WHOM IT MAY CONCERN:</p>

            <p style="margin:0px 0px 5px 0px;padding:0px; ">WHEREAS the undersigned has been employed by
                <input type="text" name="Customer" class="InputLine" value="" maxlength="32" />
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
                and upon the clearance of said funds accordingly the Undersigned shall waive and release any and all
                lien or claim of, or right to, lien, under the statutes of the State of
                <input type="text" name="WaiverState" class="InputLine" value="" maxlength="64" size="12" />
                ,
                relating to mechanic&rsquo;s liens, with respect to and on said above described premises, and the
                improvements thereon, and on the material, fixtures,
                apparatus or machinery furnished, and on the moneys, funds or other due or to become due from the owner,
                on account of labor, services, material, fixtures,
                apparatus or machinery furnished up to the
                <span class="noprint" style="font-weight:bold;color:red; ">ENTER THE CORRECT DATE HERE</span>
                <input type="text" name="WaiverDate" class="InputLine" value="" maxlength="32" size="20" />
                <span class="noprint" style="font-weight:bold;color:red; ">(NOTE THE DATE ENTERED HERE SHOULD ONLY BE
                    FOR UP TO THE TIME PERIOD FOR THE VALUE OF ONLY THE EXACT WORK YOUR COMPANY IS BEING PAID FOR UNDER
                    AND BY THIS WAIVER
                    - DO NOT OVERSTATE THE TIME PERIOD OR RISK WAIVING YOUR LIEN RIGHTS FOR AMOUNTS YOU DO NOT INTEND TO
                    WAIVE)</span> stated herein and nothing in this partial waiver shall
                prejudice the balance of funds owing for the unpaid.
            </p>



            @include('basicUser.document.include.standard_document_signature')
        </div>
        <div class="Affadavit" style="margin: 0 auto;">
            @include('basicUser.document.include.standard_document_affadavit')
        </div>
        <div style="width:640px;text-align:center;margin: 15px auto;" class="noprint">
            <input type="button" name="printButton" value="Print" onClick="window.print()"
                class="noprint">&nbsp;&nbsp;&nbsp;
            <input type="submit" name="submitButton" value="Save" class="noprint">
        </div>
    </form>
    @include('basicUser.document.include.standard_document_tracking_code')
</body>

</html>
