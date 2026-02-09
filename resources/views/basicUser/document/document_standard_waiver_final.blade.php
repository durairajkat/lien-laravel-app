<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Final Waiver of Lien</title>
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/document.css" type="text/css">
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/document_print.css" type="text/css" media="print">

    <script type="text/javascript" src="{{ env('ASSET_URL') }}/admin_assets/bower_components/jquery/dist/jquery.min.js">
    </script>
    <script src="{{ env('ASSET_URL') }}/js/common_form_fields.js"></script>
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
            registerNumberField(document.theForm.ClaimAmount, true);
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
        -->
    </script>
</head>

<body onLoad="doOnLoad()">
    <form name="theForm" action="" method="post">
        <input type="hidden" name="ID" value="">
        <input type="hidden" name="ProjectID" value="">
        <div class="MainDocument" style="margin: 0 auto;">
            <p style="text-align:center; "><strong>FINAL WAIVER OF LIEN</strong></p>
            <p style="margin-top:0px; margin-bottom:5px;padding:0px; ">STATE OF <input name="State" type="text"
                    class="InputLine" style="width:250px" value="" maxlength="32" /></p>
            <p style="margin-top:0px; margin-bottom:5px;padding:0px; ">COUNTY OF: <input name="County" type="text"
                    class="InputLine" style="width:250px" value="" maxlength="32" /> </p>

            <p style="margin-bottom:0px;padding:0px;margin-top:0px; ">WHEREAS, the undersigned has been employed by
                <input name="CustomerName" type="text" class="InputLine" value="" maxlength="64"
                    style="width:220px; " />
                for the premises known as
                <input name="ProjectName" type="text" class="InputLine" style="width:100px" value="" maxlength="64" />
                located at
                <input name="ProjectAddress" type="text" class="InputLine" value="" maxlength="64"
                    style="width:230px; " />
                of which
                <input name="OwnerName" type="text" class="InputLine" style="width:130px" value="" maxlength="64" />
                is the owner.
            </p>

            <p style="margin-top:0px;padding:0px; ">
                The undersigned, for and in consideration of $
                <input name="ClaimAmount" type="text" class="InputLine" style="width:80px" value="" maxlength="10" />
                and other good and valuable consideration, the receipt whereof is hereby acknowledged, do(es) hereby
                waive and release any and all lien or claim of, or
                right to, lien, under the statutes of
                <input name="ProjectState" type="text" class="InputLine" style="width:50px" value="" />,
                relating to mechanics&rsquo; liens, with respect to moneys, funds or other considerations due or to
                become due from the owner, on account of labor, services,
                material, fixtures, apparatus or machinery heretofore furnished, or which may be furnished at any time
                hereafter, by the undersigned for the above-described premises.
            </p>

            @include('basicUser.document.include.standard_document_signature')

            <p>
                Note: All waivers must be for the full amount paid. If waiver is for a corporation, corporate name
                should be used, corporate seal affixed and title of
                officer signing waiver should be set forth; if waiver is for a partnership, the partnership name should
                be used, partner should sign and designate himself as partner.
            </p>

        </div>
        <div class="Affadavit" style="margin: 0 auto;">
            @include('basicUser.document.include.standard_document_affadavit')
        </div>
        <div style="width:640px;text-align:center;margin :15px auto; " class="noprint">
            <input type="button" name="printButton" value="Print" onClick="window.print()" class="noprint">
            &nbsp;&nbsp;&nbsp;
            <input type="submit" name="submitButton" value="Save" class="noprint">
        </div>
    </form>
    @include('basicUser.document.include.standard_document_tracking_code')
</body>

</html>
