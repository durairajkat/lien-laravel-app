<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>CONDITIONAL WAIVER AND RELEASE UPON PROGRESS PAYMENT</title>
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
                    PROGRESS PAYMENT</strong></p>


            Upon receipt by the undersigned of a check from
            <input type="text" name="Payer" class="InputLine" value="" maxlength="32" size="18" />
            in the sum of $
            <input type="text" name="CheckAmount" class="InputLine" value="" maxlength="10" size="7" />
            payable to
            <input type="text" name="Payee" class="InputLine" value="" maxlength="32" size="25" />
            and when the check has been properly endorsed and has been paid by the bank upon which it is drawn, this
            document shall become effective to release
            pro tanto any mechanics&rsquo; lien the undersigned has on the job of
            <input type="text" name="Owner" class="InputLine" value="" maxlength="32" />
            for the
            <input type="text" name="ProjectName" class="InputLine" value="" maxlength="32" />
            located at
            <input type="text" name="ProjectAddress" class="InputLine" value="" maxlength="32" size="40" />
            to the following extent. This release covers a progress payment for all labor, services, equipment or
            material
            furnished to
            <input type="text" name="Customer" class="InputLine" value="" maxlength="32" />
            through
            <input type="text" name="ThroughDate" class="InputLine" value="" maxlength="32" />
            only and does not cover any retentions retained before or after the release date; extras furnished before
            the
            release date for which payment has not been
            received; extras or items furnished after the release date. Rights based upon work performed or items
            furnished
            under a written change order which has been
            fully executed by the parties prior to the release date are covered by this release unless specifically
            reserved
            by the claimant in this release. This
            release of any mechanic's lien, stop notice, or bond right shall not otherwise affect the contract rights,
            including rights between parties to the contract
            based upon a rescission, abandonment, or breach of the contract, or the right of the undersigned to recover
            compensation for furnished labor, services,
            equipment, or material covered by this release if that furnished labor, services, equipment, or material was
            not
            compensated by the progress payment.

            <p style="margin:10px 0px 5px 0px; ">Before any recipient of this document relies on it, said party should
                verify evidence of payment to the undersigned.</p>


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
