<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>UNCONDITIONAL WAIVER AND RELEASE UPON FINAL PAYMENT</title>
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


            <p align="center"><strong>UNCONDITIONAL WAIVER AND RELEASE UPON FINAL PAYMENT</strong></p>


            <p>The undersigned has been paid in full for all labor, services, equipment or material furnished to on the
                job
                of
                <input name="CustomerName" type="text" class="InputLine" style="width:100px" value="" maxlength="64" />
                for the
                <input name="ProjectName" type="text" class="InputLine" style="width:100px" value="" maxlength="64" />
                located at
                <input name="ProjectAddress" type="text" class="InputLine" style="width:180px" value=""
                    maxlength="64" />
                and does hereby waive and release any right to a mechanic&rsquo;s lien, stop notice, or any right
                against a
                labor and material bond
                on the job, except for disputed claims for extra work in the amount of $
                <input type="text" class="InputLine" name="ExtraAmount" value="" maxlength="10" size="10" />.
            </p>

            @include('basicUser.document.include.standard_document_signature')

            <p>NOTICE: THIS DOCUMENT WAIVES RIGHTS UNCONDITIONALLY AND STATES THAT YOU HAVE BEEN PAID FOR GIVING UP
                THOSE
                RIGHTS. THIS DOCUMENT IS ENFORCEABLE AGAINST YOU IF
                YOU SIGN IT, EVEN IF YOU HAVE NOT BEEN PAID. IF YOU HAVE NOT BEEN PAID, USE A CONDITIONAL RELEASE FORM.
            </p>
            <p>7/04</p>
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
