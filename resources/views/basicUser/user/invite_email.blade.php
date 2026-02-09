<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Demystifying Email Design</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<table border="1" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td width="100%" valign="top">
            <table border="1" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td>
                        Hello {{ $name }},
                    </td>
                </tr>
                <tr>
                    <td style="padding: 25px 0 0 0;">
                        <p>You have been invited for register as a Member of National NLB.Please click on the link
                            bellow to register.</p>
                        <p>{{ $mes }}</p>
                        <p><a target="_blank" href="{{ $url }}">{{ $url }}</a></p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</html>
