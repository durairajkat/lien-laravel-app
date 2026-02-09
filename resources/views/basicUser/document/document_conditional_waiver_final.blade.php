<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>CONDITIONAL WAIVER AND RELEASE UPON FINAL PAYMENT</title>
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
            <p align="center" style="margin:0px 0px 5px 0px;padding:0px; "><strong>CONDITIONAL WAIVER AND RELEASE UPON
                    FINAL
                    PAYMENT</strong></p>
            <p style="margin:0px 0px 5px 0px;padding:0px; ">Upon receipt by the undersigned of a check from
                <input type="text" name="Payer" class="InputLine" value="" maxlength="32" style="width:120px " />
                in the sum of $
                <input type="text" name="CheckAmount" class="InputLine" value="" maxlength="10" size="11" />
                payable to
                <input type="text" name="Payee" class="InputLine" value="" maxlength="32" size="25" />
                and when the check has been properly endorsed and has been paid by the bank upon which it is drawn, this
                document shall become effective to
                release pro tanto any mechanics&rsquo; lien the undersigned has on the job of
                <input type="text" name="Owner" class="InputLine" value="" maxlength="32" />
                for the
                <input type="text" name="ProjectName" class="InputLine" value="" maxlength="32" />
                located at
                <input type="text" name="ProjectAddress" class="InputLine" value="" maxlength="32" size="30" />.
                This release covers the final payment to the undersigned for all labor, services, equipment, or material
                furnished on the job, except for
                disputed claims for additional work in the amount of
                $<input type="text" name="DisputeAmount" class="InputLine" value="" maxlength="10" size="12" />.
                Before any recipient of this document relies on it, the party should verify evidence of payment to the
                undersigned.
            </p>

            @include('basicUser.document.include.standard_document_signature')
        </div>
        <div class="Affadavit" style="margin: 0 auto;">
            @include('basicUser.document.include.standard_document_affadavit')
        </div>
        <div style="text-align:center;margin-top:15px; " class="noprint">
            <input type="button" name="printButton" value="Print" onClick="window.print()"
                class="noprint">&nbsp;&nbsp;&nbsp;
            <input type="submit" name="submitButton" value="Save" class="noprint">
        </div>
    </form>
</body>

</html>
