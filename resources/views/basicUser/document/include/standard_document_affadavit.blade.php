<p
    style="text-align:center;font-weight:bold;font-size:12px;text-decoration:underline;margin-top:2px;margin-bottom:0px;padding:0px;page-break-before:always; ">
    CONTRACTOR'S AFFIDAVIT</p>
<p style="margin-top:0px;padding:0px;margin-bottom:0px; ">STATE OF
    <input type="text" name="ContractorState" class="AffadavitInputLine"
        value="{{ $waver_view->ContractorState or '' }}" maxlength="32" style="width:150px; " />
    <br />
    COUNTY OF
    <input type="text" name="ContractorCounty" class="AffadavitInputLine"
        value="{{ $waver_view->ContractorCounty or '' }}" maxlength="32" style="width:150px; " />
</p>
<p style="margin-bottom:0px;margin-top:5px;padding:0px; ">TO WHOM IT MAY CONCERN:<br />
    <span style="text-transform:uppercase; ">
        THE undersigned,
        <input type="text" name="Undersigned" class="AffadavitInputLine" value="{{ $waver_view->Undersigned or '' }}"
            maxlength="64" size="60" />
        being duly sworn, deposes and says that he or she is
        <input type="text" name="ContractorTitle" class="AffadavitInputLine"
            value="{{ $waver_view->ContractorTitle or '' }}" maxlength="32" size="30" />
        of
        <input type="text" name="ContractorCompanyName" class="AffadavitInputLine"
            value="{{ $waver_view->ContractorCompanyName or '' }}" maxlength="32" size="40" />
        who is the contractor furnishing
        <input type="text" name="ContractorWorkType" class="AffadavitInputLine"
            value="{{ $waver_view->ContractorWorkType or '' }}" maxlength="32" size="35" />
        work on the building located at
        <input type="text" name="ContractorProjectAddress" class="AffadavitInputLine"
            value="{{ $waver_view->ContractorProjectAddress or '' }}" maxlength="64" size="70" />
        .
    </span>

    That the total amount of the contract including extras* is $
    <input type="text" class="AffadavitInputLine" name="ContractorAmount"
        value="{{ $waver_view->ContractorAmount or '' }}" maxlength="10" size="10" />
    on which he or she has received payment of $
    <input type="text" class="AffadavitInputLine" name="ContractorPaid" value="{{ $waver_view->ContractorPaid or '' }}"
        maxlength="10" size="10" />
    prior to this payment. That all waivers are true, correct and genuine and delivered unconditionally and that there
    is no claim either legal or equitable to defect
    the validity of said waivers. That the following are the names and addresses of all parties having contracts or sub
    contracts for specific portions of said work
    or for material entering into construction thereof and the amount due or to become due to each, and that the items
    mention include all labor and material required to complete
    said work according to plans and specifications:
</p>
<table border="1" cellspacing="0" cellpadding="0"
    style="font-size: 11px; font-family: Tahoma, Arial;border: 1px solid #666600; ">
    <tr>
        <td valign="top" style="font-size:10px;text-align:center;vertical-align:bottom; ">NAMES AND ADDRESSES</td>
        <td valign="top" style="font-size:10px;text-align:center;vertical-align:bottom; ">WHAT FOR</td>
        <td valign="top" style="font-size:10px;text-align:center;vertical-align:bottom; ">CONTRACT PRICE<br>INCLUDING
            EXTRAS*
        </td>
        <td valign="top" style="font-size:10px;text-align:center;vertical-align:bottom; ">AMOUNT PAID</td>
        <td valign="top" style="font-size:10px;text-align:center;vertical-align:bottom; ">THIS PAYMENT</td>
        <td valign="top" style="font-size:10px;text-align:center;vertical-align:bottom; ">BALANCE DUE</td>
    </tr>
    <tr>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:216px; " name="ItemName1"
                value="{{ $waver_view->ItemName1 or '' }}" maxlength="32"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:64px; " name="WhatFor1"
                value="{{ $waver_view->WhatFor1 or '' }}" maxlength="16"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:93px; " name="ContractPrice1"
                value="{{ $waver_view->ContractPrice1 or '' }}" maxlength="10"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:74px; " name="AmountPaid1"
                value="{{ $waver_view->AmountPaid1 or '' }}" maxlength="10"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:86px; " name="ThisPayment1"
                value="{{ $waver_view->ThisPayment1 or '' }}" maxlength="10"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:89px; " name="BalDue1"
                value="{{ $waver_view->BalDue1 or '' }}" maxlength="10"></td>
    </tr>
    <tr>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:216px; " name="ItemName2"
                value="{{ $waver_view->ItemName2 or '' }}" maxlength="32"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:64px; " name="WhatFor2"
                value="{{ $waver_view->WhatFor2 or '' }}" maxlength="16"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:93px; " name="ContractPrice2"
                value="{{ $waver_view->ContractPrice2 or '' }}" maxlength="10"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:74px; " name="AmountPaid2"
                value="{{ $waver_view->AmountPaid2 or '' }}" maxlength="10"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:86px; " name="ThisPayment2"
                value="{{ $waver_view->ThisPayment2 or '' }}" maxlength="10"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:89px; " name="BalDue2"
                value="{{ $waver_view->BalDue2 or '' }}" maxlength="10"></td>
    </tr>
    <tr>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:216px; " name="ItemName3"
                value="{{ $waver_view->ItemName3 or '' }}" maxlength="32"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:64px; " name="WhatFor3"
                value="{{ $waver_view->WhatFor3 or '' }}" maxlength="16"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:93px; " name="ContractPrice3"
                value="{{ $waver_view->ContractPrice3 or '' }}" maxlength="10"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:74px; " name="AmountPaid3"
                value="{{ $waver_view->AmountPaid3 or '' }}" maxlength="10"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:86px; " name="ThisPayment3"
                value="{{ $waver_view->ThisPayment3 or '' }}" maxlength="10"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:89px; " name="BalDue3"
                value="{{ $waver_view->BalDue3 or '' }}" maxlength="10"></td>
    </tr>
    <tr>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:216px; " name="ItemName4"
                value="{{ $waver_view->ItemName4 or '' }}" maxlength="32"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:64px; " name="WhatFor4"
                value="{{ $waver_view->WhatFor4 or '' }}" maxlength="16"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:93px; " name="ContractPrice4"
                value="{{ $waver_view->ContractPrice4 or '' }}" maxlength="10"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:74px; " name="AmountPaid4"
                value="{{ $waver_view->AmountPaid4 or '' }}" maxlength="10"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:86px; " name="ThisPayment4"
                value="{{ $waver_view->ThisPayment4 or '' }}" maxlength="10"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:89px; " name="BalDue4"
                value="{{ $waver_view->BalDue4 or '' }}" maxlength="10"></td>
    </tr>
    <tr>
        <td valign="top" style="font-size:10px; ">TOTAL LABOR AND MATERIAL TO COMPLETE</td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:64px; " name="WhatFor5"
                value="{{ $waver_view->WhatFor5 or '' }}" maxlength="16"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:93px; " name="ContractPrice5"
                value="{{ $waver_view->ContractPrice5 or '' }}" maxlength="10"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:74px; " name="AmountPaid5"
                value="{{ $waver_view->AmountPaid5 or '' }}" maxlength="10"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:86px; " name="ThisPayment5"
                value="{{ $waver_view->ThisPayment5 or '' }}" maxlength="10"></td>
        <td valign="top"><input type="text" class="AffadavitInputLine" style="width:89px; " name="BalDue5"
                value="{{ $waver_view->BalDue5 or '' }}" maxlength="10"></td>
    </tr>
</table>
<p style="margin-top:0px;margin-bottom:0px;padding:0px; ">That there are no other contracts for said work outstanding,
    and that there is nothing due or to become due to any person for material, labor or other work of any kind done or
    to be done upon or in connection with said work other than above stated.</p>
<span style="text-transform:uppercase; ">
    <p style="margin-top:5px;padding:0px;margin-bottom:0px; ">Date:
        <input type="text" class="AffadavitInputLine" name="ContractorDate"
            value="{{ $waver_view->ContractorDate or '' }}" maxlength="32" size="40" />
        &nbsp;&nbsp;&nbsp;
        Signature: <img src="{{ asset('images/spacer.gif') }}" height="18" width="250" style="border-bottom:solid 1px black; ">

    </p>
    <p style="margin-top:5px;padding:0px;margin-bottom:0px; ">Subscribed and sworn to before me this
        <input type="text" class="AffadavitInputLine" name="NotaryDay" value="{{ $waver_view->NotaryDay or '' }}"
            maxlength="25" size="8" />
        day of
        <input type="text" class="AffadavitInputLine" name="NotaryMonth" value="{{ $waver_view->NotaryMonth or '' }}"
            size="24" maxlength="16" />
        ,
        <input type="text" class="AffadavitInputLine" name="NotaryYear" value="{{ $waver_view->NotaryYear or '' }}"
            size="8" maxlength="4" />
        .
    </p>
</span>
<p style="text-align:right;margin-top:0px; ">
    <input type="text" class="AffadavitInputLine" name="NotarySigned" value="{{ $waver_view->NotarySigned or '' }}"
        size="64" maxlength="64" />
</p>
<p style="text-transform:uppercase; ">* Extras include but are not limited to change orders, both oral and written, to
    the contract.</p>
