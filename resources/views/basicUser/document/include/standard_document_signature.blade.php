<table width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td width="50%">DATE: <input name="SignedDate" type="text" class="InputLine" style="width:250px "
                value="{{ $waver_view->SignedDate or '' }}" maxlength="32" /></td>
        <td width="50%">COMPANY NAME: <input name="SignedCompany" type="text" class="InputLine"
                value="{{ $waver_view->SignedCompany or '' }}" maxlength="64" size="30" /></td>
    </tr>
    <tr>
        <td></td>
        <td>ADDRESS: <input name="SignedAddress" type="text" class="InputLine"
                value="{{ $waver_view->SignedAddress or '' }}" maxlength="64" size="30" /></td>
    </tr>
</table>

<p style="margin:0px;padding:0px; ">SIGNATURE AND TITLE: <img src="{{ env('ASSET_URL') }}/images/spacer.gif"
        height="18" width="250" style="border-bottom:solid 1px black; "></p>
