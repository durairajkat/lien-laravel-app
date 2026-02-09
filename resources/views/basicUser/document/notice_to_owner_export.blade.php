<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $project ? $project->project_name : '' }}</title>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="center-part">
                    <h2>
                        <div style="text-align: center;">
                            @if($lienProvider && !empty($lienProvider->logo))
                                <img src="liens/{{$lienProvider->logo}}" alt="Logo" width="200px">
                            @else
                                <img src="{{ env('ASSET_URL') }}/images/bnc_logo.png" alt="Logo" width="200px">
                            @endif
                        </div>
                    </h2>
                    <h3 style="text-align: center; padding-bottom: 20px;">NOTICE TO OWNER</h3>
                </div>

                <table style="width:700px;">
                    <tr>
                        <th style="text-align:center;" colspan="2">Company Information</th>
                    </tr>
                    <tr>
                        <td style="width:350px;">
                            <table>
                                <tr>
                                    <td colspan="2" style="font-size:12px;font-weight:bold;">Company:</td>
                                    <td colspan="4" style="font-size:12px;">
                                        {{ $user->details ? $user->details->company : '' }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="font-size:12px;font-weight:bold;">Address:</td>
                                    <td colspan="4" style="font-size:12px;">
                                        {{ $user->details ? $user->details->address : '' }}</td>
                                </tr>
                                <tr>
                                    <td style="font-size:12px;font-weight:bold;">City:</td>
                                    <td style="padding-right:10px; font-size:12px">
                                        {{ $user->details ? $user->details->city : '' }}</td>
                                    <td style="font-size:12px;font-weight:bold;">State:</td>
                                    <td style="padding-right: 20px;font-size:12px">
                                        {{ $user->details ? $user->details->state->name : '' }}</td>
                                    <td style="font-size:12px;font-weight:bold;">Zip:</td>
                                    <td style="font-size:12px;">{{ $user->details ? $user->details->zip : '' }}</td>

                                </tr>
                                <tr>
                                    <td colspan="2" style="font-size:12px;font-weight:bold;">Office Number:</td>
                                    <td colspan="4" style="font-size:12px;">
                                        {{ $user->details ? $user->details->office_phone : '' }}</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width:350px;">
                            <table valign="top">
                                <tr>
                                    <td style="font-size:12px;font-weight:bold;">First:</td>
                                    <td style="font-size:12px;">{{ $user->details ? $user->details->first_name : '' }}
                                    </td>
                                    <td style="font-size:12px;font-weight:bold; padding-left:25px;">Last:</td>
                                    <td style="font-size:12px;">{{ $user->details ? $user->details->last_name : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="" style="font-size:12px;font-weight:bold;">Email:</td>
                                    <td colspan="3" style="font-size:12px;">{{ $user->details ? $user->email : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="" style="font-size:12px;font-weight:bold;">Phone:</td>
                                    <td colspan="3" style="font-size:12px;">
                                        {{ $user->details ? $user->details->phone : '' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table style="width:700px; padding-top: 35px;">
                    <tr>
                        <th style="text-align:center;">JOB DESCRIPTION</th>
                    </tr>
                    <tr>
                        <td style="width:350px;">
                            <table valign="top">
                                <tr>
                                    <td colspan="" style="font-size:12px;font-weight:bold;">JOB NAME:</td>
                                    <td colspan="3" style="font-size:12px;">
                                        {{ $project ? $project->project_name : '' }}</td>
                                </tr>
                                <tr>
                                    <td colspan="" style="font-size:12px;font-weight:bold;">JOB ADDRESS:</td>
                                    <td colspan="3" style="font-size:12px;">{{ $project->address }}</td>
                                </tr>
                                <tr>
                                    <td colspan="" style="font-size:12px;font-weight:bold;">JOB CITY:</td>
                                    <td colspan="3" style="font-size:12px;">{{ $project->city }}</td>
                                </tr>
                                <tr>
                                    <td style="font-size:12px;font-weight:bold;">STATE:</td>
                                    <td style="font-size:12px;">{{ $project->state->name }}</td>
                                    <td style="font-size:12px;font-weight:bold; padding-left:25px;">ZIP:</td>
                                    <td style="font-size:12px;">{{ $project->zip }}</td>
                                </tr>
                                <tr>
                                    <td colspan="" style="font-size:12px;font-weight:bold;">County: </td>
                                    <td colspan="3" style="font-size:12px;">{{ $project->county }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                @php
                    // Using PHP to check for contract amounts or set to 0
                    $baseAmount = isset($contract->base_amount) && !empty($contract->base_amount) ? $contract->base_amount : 0;
                    $extraCharges = isset($contract->extra_amount) && !empty($contract->extra_amount) ? $contract->extra_amount : 0;
                    $credits = isset($contract->credits) && !empty($contract->credits) ? $contract->credits : 0;
                    $revised = $baseAmount + $extraCharges;
                    $totalClaimAmount = $revised - $credits;
                    // contractCreated is used to set a value on data-contract, this watches for contracts not already created
                    $contractCreated = isset($contract) && !empty($contract) ? 'contract-exists' : 'no-contract';
                @endphp
                <table style="width:700px; padding-top: 35px;">
                    <tr>
                        <th style="text-align:center;">Contract Information</th>
                    </tr>
                    <tr>
                        <td style="width:350px;">
                            <table valign="top">
                                <tr>
                                    <td colspan="" style="font-size:12px;font-weight:bold;">Base Contract Amount:</td>
                                    <td colspan="3" style="font-size:12px;">
                                        {{ number_format(isset($baseAmount) && !empty($baseAmount) ? $baseAmount : '0', 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="" style="font-size:12px;font-weight:bold;">+ Value of Extras:</td>
                                    <td colspan="3" style="font-size:12px;">
                                        {{ number_format(isset($extraCharges) && !empty($extraCharges) ? $extraCharges : '0', 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="" style="font-size:12px;font-weight:bold;">Revised Contract Total:</td>
                                    <td colspan="3" style="font-size:12px;">
                                        {{ number_format(isset($revised) && !empty($revised) ? $revised : '0', 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="" style="font-size:12px;font-weight:bold;">- Credits to Date:</td>
                                    <td colspan="3" style="font-size:12px;">
                                        {{ number_format(isset($credits) && !empty($credits) ? $credits : '0', 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="" style="font-size:12px;font-weight:bold;">Total Claim Amount:</td>
                                    <td colspan="3" style="font-size:12px;">
                                        {{ number_format(isset($totalClaimAmount) && !empty($totalClaimAmount) ? $totalClaimAmount : '0', 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="" style="font-size:12px;font-weight:bold;">Job No.:</td>
                                    <td colspan="3" style="font-size:12px;">
                                        {{ isset($contract->job_no) && !empty($contract->job_no) ? $contract->job_no : '0' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="" style="font-size:12px;font-weight:bold;">General Description:</td>
                                    <td colspan="3" style="font-size:12px;">
                                        {{ isset($contract->general_description) && !empty($contract->general_description) ? $contract->general_description : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table style="width:700px; padding-top: 35px;">
                    <tr>
                        <th colspan="2" style="text-align:center;">
                            Your Customer <span class="gc_show"
                                style="display: {{ isset($jobInfoSheet) && $jobInfoSheet != '' ? ($jobInfoSheet->is_gc == '0' ? 'inline' : 'none') : ($project->originalCustomer->name == 'Original Contractor' ? 'inline' : 'none') }};">/
                                General Contractor</span>
                        </th>
                    </tr>
                    <tr>
                        <td style="width:350px;">
                            <table valign="top">
                                <tr>
                                    <td colspan="2" style="font-size:12px;font-weight:bold;">Company:</td>
                                    <td colspan="4" style="font-size:12px;">
                                        {{ $project->customer_contract ? $project->customer_contract->company->company : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="font-size:12px;font-weight:bold;">Address:</td>
                                    <td colspan="4" style="font-size:12px;">
                                        {{ $project->customer_contract ? $project->customer_contract->company->address : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size:12px;font-weight:bold;">City:</td>
                                    <td style="font-size:12px;">
                                        {{ $project->customer_contract ? $project->customer_contract->company->city : '' }}
                                    </td>
                                    <td style="font-size:12px;font-weight:bold;">State:</td>
                                    <td style="font-size:12px;">
                                        {{ $project->customer_contract ? $project->customer_contract->company->state->name : '' }}
                                    </td>
                                    <td style="font-size:12px;font-weight:bold; padding-left:25px;">Zip:</td>
                                    <td style="font-size:12px;">
                                        {{ $project->customer_contract ? $project->customer_contract->company->zip : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="font-size:12px;font-weight:bold;">Telephone:</td>
                                    <td colspan="4" style="font-size:12px;">
                                        {{ $project->customer_contract ? $project->customer_contract->company->phone : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="font-size:12px;font-weight:bold;">Fax:</td>
                                    <td colspan="4" style="font-size:12px;">
                                        {{ $project->customer_contract ? $project->customer_contract->company->fax : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="font-size:12px;font-weight:bold;">Web:</td>
                                    <td colspan="4" style="font-size:12px;">
                                        {{ $project->customer_contract ? $project->customer_contract->company->website : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width:350px;">
                            <table valign="top">
                                <tr>
                                    <td colspan="2">
                                        <table>
                                            <tr>
                                                <td style="font-size:12px;font-weight:bold;">First: </td>
                                                <td>{{ $project->customer_contract != '' ? $project->customer_contract->contacts->first_name : 'N/A' }}
                                                </td>
                                                <td style="font-size:12px;font-weight:bold;">Last: </td>
                                                <td>{{ $project->customer_contract != '' ? $project->customer_contract->contacts->first_name : 'N/A' }}
                                                </td>
                                                <td style="font-size:12px;font-weight:bold;">Title: </td>
                                                <td>{{ $project->customer_contract != '' ? ($project->customer_contract->contacts->title == 'Other' ? $project->customer_contract->contacts->title_other : $project->customer_contract->contacts->title) : 'N/A' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" style="font-size:12px;font-weight:bold;">
                                                    Direct Phone
                                                </td>
                                                <td colspan="3">
                                                    {{ $project->customer_contract != '' ? $project->customer_contract->contacts->phone : 'N/A' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" style="font-size:12px;font-weight:bold;">
                                                    Cell Phone
                                                </td>
                                                <td colspan="3">
                                                    {{ $project->customer_contract != '' ? $project->customer_contract->contacts->cell : 'N/A' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" style="font-size:12px;font-weight:bold;">
                                                    Email
                                                </td>
                                                <td colspan="3">
                                                    {{ $project->customer_contract != '' ? $project->customer_contract->contacts->email : 'N/A' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:350px;">
                            <table>
                                <tr>
                                    <td style="font-size:12px;font-weight:bold;">Contract Amount:</td>
                                    <td style="font-size:12px;">
                                        ${{ isset($jobInfoSheet) && $jobInfoSheet != '' && !is_null($jobInfoSheet->contract_amount) ? $jobInfoSheet->contract_amount : '0' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width:350px;">
                            <table>
                                <tr>
                                    <td style="font-size:12px;font-weight:bold;">First day of work:</td>
                                    <td style="font-size:12px;">
                                        {{ isset($jobInfoSheet) && $jobInfoSheet != '' ? $jobInfoSheet->first_day_of_work : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size:12px;font-weight:bold;width:350px;">
                            If your customer is the General Contractor?
                            @if (isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->is_gc == '1')
                                No
                            @else
                                Yes
                            @endif
                            {{-- Yes <input type="radio" name="is_gc" class="is_gc" value="0" {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->is_gc == '0' ? 'checked' : ($project->originalCustomer->name == 'Original Contractor' ? 'checked' : '') }}>
                        No <input type="radio" name="is_gc" class="is_gc" value="1" {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->is_gc == '1' ? 'checked' : ($project->originalCustomer->name != 'Original Contractor' ? 'checked' : '') }}> --}}
                        </td>
                    </tr>
                </table>
                @if (count($projectContacts) > 0)
                    @foreach ($projectContacts as $key => $contact)
                        <table style="width:700px; padding-top: 35px;">
                            @if ($jobInfoSheet->is_gc == '0' && $contact['type'] == 'General Contractor')
                            @else
                                <tr>
                                    <th colspan="2" style="text-align:center;">{{ $contact['type'] }}</th>
                                </tr>
                                <tr>
                                    <td>
                                        <table valign="top">
                                            <tr>
                                                <td colspan="2" style="font-size:12px;font-weight:bold;">Company:</td>
                                                <td colspan="4" style="font-size:12px;">
                                                    {{ $contact['company'] ? $contact['company']->company : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="font-size:12px;font-weight:bold;">Address:</td>
                                                <td colspan="4" style="font-size:12px;">
                                                    {{ $contact['company'] ? $contact['company']->address : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size:12px;font-weight:bold;">City:</td>
                                                <td style="font-size:12px;">
                                                    {{ $contact['company'] ? $contact['company']->city : '' }}
                                                </td>
                                                <td style="font-size:12px;font-weight:bold;">State:</td>
                                                <td style="font-size:12px;">
                                                    {{ $contact['company'] ? $contact['company']->state->name : '' }}
                                                </td>
                                                <td style="font-size:12px;font-weight:bold; padding-left:25px;">Zip:
                                                </td>
                                                <td style="font-size:12px;">
                                                    {{ $contact['company'] ? $contact['company']->zip : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="font-size:12px;font-weight:bold;">Telephone:</td>
                                                <td colspan="4" style="font-size:12px;">
                                                    {{ $contact['company'] ? $contact['company']->phone : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="font-size:12px;font-weight:bold;">Fax:</td>
                                                <td colspan="4" style="font-size:12px;">
                                                    {{ $contact['company'] ? $contact['company']->fax : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" style="font-size:12px;font-weight:bold;">Web:</td>
                                                <td colspan="4" style="font-size:12px;">
                                                    {{ $contact['company'] ? $contact['company']->website : '' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td>
                                        <table>
                                            @if (count($contact['customers']) > 0)
                                                @foreach ($contact['customers'] as $customerKey => $contactInformation)
                                                    <tr>
                                                        <td colspan="2">
                                                            <table>
                                                                <tr>
                                                                    <td style="font-size:12px;font-weight:bold;">First:
                                                                    </td>
                                                                    <td>{{ $contactInformation->first_name != '' ? $contactInformation->first_name : 'N/A' }}
                                                                    </td>
                                                                    <td style="font-size:12px;font-weight:bold;">Last:
                                                                    </td>
                                                                    <td>{{ $contactInformation->last_name != '' ? $contactInformation->last_name : 'N/A' }}
                                                                    </td>
                                                                    <td style="font-size:12px;font-weight:bold;">Title:
                                                                    </td>
                                                                    <td>{{ $contactInformation->title != '' ? ($contactInformation->title == 'Other' ? $contactInformation->title_other : $contactInformation->title) : 'N/A' }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3"
                                                                        style="font-size:12px;font-weight:bold;">
                                                                        Direct Phone
                                                                    </td>
                                                                    <td colspan="3">
                                                                        {{ $contactInformation->phone }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3"
                                                                        style="font-size:12px;font-weight:bold;">
                                                                        Cell Phone
                                                                    </td>
                                                                    <td colspan="3">
                                                                        {{ $contactInformation->cell }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="3"
                                                                        style="font-size:12px;font-weight:bold;">
                                                                        Email
                                                                    </td>
                                                                    <td colspan="3">
                                                                        {{ $contactInformation->email }}
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </table>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    @endforeach
                @endif
                <table {{-- style="height: 100px;" --}}>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>

                <table align="left" valign="top">
                    <tr>
                        <td style="padding-bottom: 20px;">
                            <strong>Agree Terms and Conditions</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 20px;">
                            Please remember to fax to BNC all documentation related to this
                            project. This includes contracts, invoices, notices, purchase orders,
                            etc.<strong>FAX: (800) 226-1631</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            I understand that <strong>BUILDERS NOTICE CORP</strong> may not be able to ascertain to whom
                            the Notice to Owner should
                            be sent. Further, it is expressly understood that I hereby waive any claim again Builders
                            Notice Corp. that I or my firm
                            have or may have in the future due to the inability of Builders Notice Corp. to ascertain
                            who should receive NOTICE to OWNER
                            copies. I hereby agree on behalf of myself and my firm to pay for any research, preparation
                            and serving expenses generated
                            by my request or my representatives upon receipt of the invoice. In the event that payment
                            is not made within 30 days of the
                            date of invoice I agree to pay interest of 18% per cent per year as well as all costs of
                            collection including reasonable attorneys
                            fees. I also understand that should I or my firm suffer any damages as a result of the
                            utilization of the Builders Notice Corp.
                            provides or agrees to provide, it is agreed that the limit of liability of Builders Notice
                            Corp., and/or its affiliates, officers,
                            employees and assigns inclusive of any interest, costs, and attorney fees shall not exceed
                            One Thousand Dollars ($1000). In the
                            event that additional protection is desired which would require more than our standard
                            service, specific arrangements can be made
                            for increased liability at increased cost. I authorize Builders Notice Corp. personnel to
                            sign on behalf of myself and /or
                            my firm any notices that we request you to prepare. The undersigned further authorizes
                            BUILDERS NOTICE CORP. to distribute to
                            other subscribers the jobs upon which we are working in connection with the Network Credit
                            Service.
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 700px; text-align: center;">
                            <img src="{{ env('ASSET_URL') }}/images/bnc_logo.png" alt="NLB" align="middle">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label><input type="checkbox" checked name="Agree" id="agree">
                                &nbsp;By submitting this
                                form, you agree to the Liability Limitation terms stated herein.</label>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="padding-top: 20px;">
                                <tr>
                                    <td style="width:550px;">
                                        <strong>Customer Signature:</strong>
                                        {{ isset($lien) && $lien != '' && !is_null($lien->signature) ? $lien->signature : '' }}
                                    </td>
                                    <td>
                                        <strong>Date:</strong>
                                        {{ isset($lien) && $lien != '' && !is_null($lien->signature_date) ? date('Y-m-d', strtotime($lien->signature_date)) : 'N/A' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script> --}}
</body>

</html>
