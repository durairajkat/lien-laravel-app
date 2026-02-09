<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>NLB Claim Form: Complete</title>
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/document.css" type="text/css">
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/document_print.css" type="text/css" media="print">
    <!-- jQuery 3 -->
    <script src="{{ env('ASSET_URL') }}/admin_assets/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ env('ASSET_URL') }}/js/jquery-ui.min.js"></script>
    <script type="text/javascript">
        function doClose() {
            window.opener.location.reload();
            window.close();
        }
    </script>
</head>

<body>
    <table width="670" class="data_sheet" align="center" style=" border:1px solid #000; border-collapse:collapse;">
        <?php
    if($attorney)
    {
    ?>
        <tr>
            <td width="670">
                <table width="670" border="0" cellpadding="0" cellspacing="0" style=" border-collapse: collapse;">
                    <tr>
                        <td width="266" style="padding:2px; ">
                            <span style="font-size:11px;color:#0066FF;">Serviced by Your National Lien Plan
                                Provider</span><br>
                            <?= htmlspecialchars($attorney['FirstName'] . ' ' . $attorney['LastName']) ?><br>
                            <?= htmlspecialchars($attorney['Company']) ?><br>
                            <span style="font-size:11px;color:#0066FF;">Powered by National Lien &amp; Bond Claim
                                Systems</span>

                        </td>
                        <td width="404">
                            <div align="center" class="NLBHeader" style="font-size:20px; ">National Lien Platform Claim
                                Form</div>
                            <div style="font-weight:bold;text-align:center;margin-top:15px; ">Fax:
                                <?= $attorney['Fax'] ?> / Phone: <?= $attorney['Phone'] ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style=" border-bottom:1px solid #000; text-align:center">&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php
    }
    else
    {
    ?>
        <tr>
            <td width="670">
                <table width="670" border="0" cellpadding="0" cellspacing="0" style=" border-collapse: collapse;">
                    <tr>
                        <td width="151">
                            <div align="center"><img src="{{ env('ASSET_URL') }}/images/nlb.jpg" /></div>
                        </td>
                        <td width="506">
                            <div align="center" class="NLBHeader">National Lien Platform Claim Form</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style=" border-bottom:1px solid #000; text-align:center"><strong>Fax:
                                <?= $attorney['Fax'] ? $attorney['Fax'] : '847-432-8950' ?> / Phone:
                                <?= $attorney['Phone'] ? $attorney['Phone'] : '800-432-7799' ?></strong></td>
                    </tr>
                </table>
            </td>
        </tr>
        <?php
    }
    ?>
        <tr>
            <td style="padding:4px;font-weight:bold; ">
                <p>Your claim has been submitted for an initial review of your compliance deadlines. To assure prompt
                    and accurate review of your compliance steps it is your
                    sole responsibility to immediately fax to
                    <?= $attorney['Fax'] ? $attorney['Fax'] : '847-432-8950' ?>all the back up documents supporting your
                    claim including you&rsquo;re a/r reconciliation showing
                    all billings, payments and credits and balances currently due to you&rsquo;re your initial quotes or
                    proposals; your current purchase order or contract;
                    all progress billings/invoices/delivery tickets and bills of lading; all waivers of liens supporting
                    your pay out requests or affidavits. If you are a
                    subcontractor or general contractor performing labor and materials on a project please furnish your
                    schedule of values such as AIA G702 or G703 schedules
                    and any similar payout documentation along with an accounting of all payments credits and balances
                    currently due. </p>

                <p>Please also furnish any and all other documentation related to the balance of monies due you, any
                    defenses alleged offsetting said balance due by your customer;
                    contract performance, extras, contract modification, project notices or any correspondence relating
                    to your termination or suspension of your work or the project
                    progress, if applicable. You are solely responsible to call
                    <?= $attorney['Phone'] ? $attorney['Phone'] : '800-432-7799' ?> to get additional written
                    verification that your claim form was in fact received and
                    is being acted upon. There is no obligation to commence work until your package is complete, we
                    review the package and you received written confirmation that we
                    accepted the claim form and are proceeding on your behalf per your direction. Until you receive
                    written confirmation that your claim form is received Lien Store, LLC
                    nor anybody else on its behalf has any obligation to act on your behalf. We can not guarantee that
                    any claim form or other documents you sent electronically will
                    always get received or opened by the recipient timely so please comply with this requirement to
                    follow up immediately upon your submitting your claim</p>

                <p>All claim forms, supporting documents and request for verification that the claim form was received
                    and is being acted upon is required. Your follow up may be by
                    manual fax number <?= $attorney['Fax'] ? $attorney['Fax'] : '847-432-8950' ?>, the general the Claim
                    FAX. To confirm your claim status and obtain written verification by phone you can call Customer
                    Service
                    <?= $attorney['Phone'] ? $attorney['Phone'] : '800-432-7799' ?>.</p>
                <p align="center"><a href="{{ route('project.document.view') . '?project_id=' . $project_id }}"
                        onClick="doClose();return false">Close This Window</a></p>
            </td>
        </tr>
    </table>
</body>

</html>
