<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Job Info sheet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td width="100%" valign="top">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td>
                        Hello {{ isset($adminDetails) ? $adminDetails['name'] : '' }},
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>A job information sheet uploaded.please find the attachment for more details.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</html>
