<?php

if (Vine_Registry::getSetting('test-mode')) {
    $url = 'http://domain.com/public/emails';
} else {
    $url = 'http://domain.com/public/emails';
}

?>

<!DOCTYPE html>

<html lang="en">
<head>
    <title>Email</title>
    <meta charset="utf-8" />
    <style type="text/css">
        body{ width: 100% !important; }
        .ReadMsgBody{ width: 100%; }
        .ExternalClass{ width: 100%; }
        .msg-body { width: 100% !important; }

        body {
            -webkit-text-size-adjust: none;
            margin: 0;
            padding: 0;
        }

        body, #email-bg {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" bgcolor="#f1f1f1" style="-webkit-text-size-adjust:none;margin:0;padding:0;background-color:#f1f1f1;width:100% !important;">

<style type="text/css">
    img {
        border: 0;
        height: auto;
        outline: none;
        text-decoration: none;
        border: 0;
    }

    table td {
        border-collapse: collapse;
    }

    #email-bg {
        height: 100% !important;
        width: 100% !important;
        margin: 0;
        padding: 0;
    }

    #email-start {
        color: #333333;
        font-family: Arial;
        font-size: 12px;
        text-align: left;
    }

    #email-top {
        background-color: #3082d4;
        color: #ffffff;
        font-family: Arial;
        font-size: 12px;
        text-align: left;
    }

    #email-header {
        color: #555555;
        font-family: Arial;
        font-size: 20px;
        font-weight: bold;
        text-align: left;
    }

    #email-content {
        color: #555555;
        font-family: Arial;
        font-size: 12px;
        text-align: left;
    }

    #email-footer {
        color: #333333;
        font-family: Arial;
        font-size: 12px;
        text-align: left;
    }

    #email-end {
        color: #333333;
        font-family: Arial;
        font-size: 12px;
        text-align: left;
    }

    #email-top a:link,
    #email-top a:visited,
    #email-top a {
        color: #ffffff;
        font-weight: normal;
        text-decoration: underline;
    }
</style>
<center>
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="email-bg" bgcolor="#f1f1f1" style="table-layout:fixed;margin:0;padding:0;background-color:#f1f1f1;height:100% !important;width:100% !important;">
<tr>
<td align="center" valign="top" style="border-collapse:collapse;">

<table border="0" cellpadding="5" cellspacing="0" width="600" id="email-start">
    <tr>
        <td valign="top" align="center" style="border-collapse:collapse;padding:5px;">
            &nbsp;
        </td>
    </tr>
</table>

<table border="0" cellpadding="20" cellspacing="0" width="600" id="email-header" style="background-color:#ffffff;">
    <tr>
        <td valign="top" align="left" style="border-collapse:collapse;padding:20px;border-bottom:1px solid #ddd;color:#555555;font-family:Arial;font-size:20px;font-weight:bold;text-align:left;">
        {Company Name}
        </td>
    </tr>
</table>

<table border="0" cellpadding="20" cellspacing="0" width="600" id="email-content" style="background-color:#ffffff;">
    <tr>
        <td valign="top" style="border-collapse:collapse;padding:20px;">