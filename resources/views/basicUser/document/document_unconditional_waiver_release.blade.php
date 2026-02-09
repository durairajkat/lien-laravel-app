<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>UNCONDITIONAL WAIVER AND RELEASE UPON PROGRESS PAYMENT</title>
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
    <form name="theForm" action="{{ route('post.documentUnconditionalWaiverRelease', [$project_id]) }}" method="post">
        <input type="hidden" name="ID" value="">
        <input type="hidden" name="ProjectID" value="">
        <div class="MainDocument" style="margin: 0 auto;">
            {{ csrf_field() }}

            <p align="center" style="margin:0px;padding:0px; "><strong>UNCONDITIONAL WAIVER AND RELEASE UPON PROGRESS
                    PAYMENT</strong></p>


            <p style="margin-top:5px;margin-bottom:0px;padding:0px; ">The undersigned has been paid and has received a
                progress payment in the sum of
                $<input type="text" name="CheckAmount" size="6" maxlength="10"
                    value="{{ $waver_view->CheckAmount or '' }}" class="InputLine">
                for labor, services, equipment or material furnished to
                <input name="CustomerName" type="text" class="InputLine" size="15"
                    value="{{ $waver_view->CustomerName or '' }}" maxlength="64" />
                on the job of
                <input name="Owner" type="text" class="InputLine" size="15" value="{{ $waver_view->Owner or '' }}"
                    maxlength="64" />
                for the
                <input name="ProjectName" type="text" class="InputLine" size="15"
                    value="{{ $waver_view->ProjectName or '' }}" maxlength="64" />
                located at
                <input name="ProjectAddress" type="text" class="InputLine" size="15"
                    value="{{ $waver_view->ProjectAddress or '' }}" maxlength="64" />
                and does hereby release any mechanic&rsquo;s lien, stop notice or bond right that the undersigned has on
                the
                above referenced job to the following extent.
            </p>

            <p style="margin-top:5px;margin-bottom:0px;padding:0px; ">This release covers a progress payment for labor,
                services, equipment, or material furnished to
                <input name="CustomerName2" type="text" class="InputLine" size="16"
                    value="{{ $waver_view->CustomerName2 or '' }}" maxlength="64" />
                through
                <input name="ThroughDate" type="text" class="InputLine" value="{{ $waver_view->ThroughDate or '' }}"
                    maxlength="32" size="15" />
                only and does not cover any retentions retained before or after the release date; extras furnished
                before
                the release date for which payment has not been received;
                extras or items furnished after the release date. Rights based upon work performed or items furnished
                under
                a written change order which has been fully executed by
                the parties prior to the release date are covered by this release unless specifically reserved by the
                claimant in this release. This release of any mechanic&rsquo;s
                lien, stop notice, or bond right shall not otherwise affect the contract rights, including rights
                between
                parties to the contract based upon a rescission,
                abandonment, or breach of the contract, or the right of the undersigned to recover compensation for
                furnished labor, services, equipment, or material covered by
                this release if that furnished labor, services, equipment, or material was not compensated by the
                progress
                payment.
            </p>


            @include('basicUser.document.include.standard_document_signature')

            <p style="margin-bottom:0px;margin-top:5px;padding:0px; ">NOTICE: THIS DOCUMENT WAIVES RIGHTS
                UNCONDITIONALLY
                AND STATES THAT YOU HAVE BEEN PAID FOR GIVING UP THOSE RIGHTS. THIS DOCUMENT IS ENFORCEABLE AGAINST
                YOU IF YOU SIGN IT, EVEN IF YOU HAVE NOT BEEN PAID. IF YOU HAVE NOT BEEN PAID, USE A CONDITIONAL RELEASE
                FORM.</p>


        </div>
        <div class="Affadavit" style="margin: 0 auto;">
            @include('basicUser.document.include.standard_document_affadavit')

        </div>
        @if ($flag != 1)
            <div style="text-align:center;margin-top:15px; " class="noprint">
                <input type="button" name="printButton" value="Print" onClick="window.print()"
                    class="noprint">&nbsp;&nbsp;&nbsp;
                <input type="submit" name="submitButton" value="Save" class="noprint">
            </div>
        @endif
    </form>
</body>

</html>
