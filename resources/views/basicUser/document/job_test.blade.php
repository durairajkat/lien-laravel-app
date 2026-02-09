<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $job_info->job_name }}</title>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="center-part">
                    <h2>
                        <div style="text-align: center;">
                            @if (Auth::user()->details->image != '')
                                <img src="{{ env('ASSET_URL') }}/image_logo/{{ Auth::user()->details->image }}"
                                    alt="Logo" width="100px">
                            @else
                                <img src="{{ env('ASSET_URL') }}/images/nlb_black.png" alt="Logo" width="200px">
                            @endif
                        </div>
                    </h2>
                    <h3 style="text-align: center; padding-bottom: 20px;">JOB INFORMATION</h3>
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
                                    <td colspan="4" style="font-size:12px;">{{ $job_info->company_name }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="font-size:12px;font-weight:bold;">Address:</td>
                                    <td colspan="4" style="font-size:12px;">{{ $job_info->company_address }}</td>
                                </tr>
                                <tr>
                                    <td style="font-size:12px;font-weight:bold;">City:</td>
                                    <td style="padding-right:10px; font-size:12px">{{ $job_info->company_city }}</td>
                                    <td style="font-size:12px;font-weight:bold;">State:</td>
                                    <td style="padding-right: 20px;font-size:12px">
                                        {{ $job_info->getCompanyState->name }}</td>
                                    <td style="font-size:12px;font-weight:bold;">Zip:</td>
                                    <td style="font-size:12px;">{{ $job_info->company_zip }}</td>

                                </tr>
                                <tr>
                                    <td colspan="2" style="font-size:12px;font-weight:bold;">Office Number:</td>
                                    <td colspan="4" style="font-size:12px;">{{ $job_info->company_office_phone }}</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width:350px;">
                            <table valign="top">
                                <tr>
                                    <td style="font-size:12px;font-weight:bold;">First:</td>
                                    <td style="font-size:12px;">{{ $job_info->company_fname }}</td>
                                    <td style="font-size:12px;font-weight:bold; padding-left:25px;">Last:</td>
                                    <td style="font-size:12px;">{{ $job_info->company_lname }}</td>
                                </tr>
                                <tr>
                                    <td colspan="" style="font-size:12px;font-weight:bold;">Email:</td>
                                    <td colspan="3" style="font-size:12px;">{{ $job_info->company_email }}</td>
                                </tr>
                                <tr>
                                    <td colspan="" style="font-size:12px;font-weight:bold;">Phone:</td>
                                    <td colspan="3" style="font-size:12px;">{{ $job_info->company_phone }}</td>
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
                                    <td colspan="3" style="font-size:12px;">{{ $job_info->job_name }}</td>
                                </tr>
                                <tr>
                                    <td colspan="" style="font-size:12px;font-weight:bold;">JOB ADDRESS:</td>
                                    <td colspan="3" style="font-size:12px;"> {{ $job_info->job_address }}</td>
                                </tr>
                                <tr>
                                    <td colspan="" style="font-size:12px;font-weight:bold;">JOB CITY:</td>
                                    <td colspan="3" style="font-size:12px;">{{ $job_info->job_city }}</td>
                                </tr>
                                <tr>
                                    <td style="font-size:12px;font-weight:bold;">STATE:</td>
                                    <td style="font-size:12px;">{{ $job_info->getJobState->name }}</td>
                                    <td style="font-size:12px;font-weight:bold; padding-left:25px;">ZIP:</td>
                                    <td style="font-size:12px;">{{ $job_info->job_zip }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <table style="width:700px; padding-top: 35px;">
                    <tr>
                        <th colspan="2" style="text-align:center;">
                            Your Customer <span class="gc_show"
                                style="display: {{ $job_info->is_gc == '0' ? 'block' : 'none' }};">/ General
                                Contractor</span>
                        </th>
                    </tr>
                    <tr>
                        <td style="width:350px;">
                            <table valign="top">
                                <tr>
                                    <td colspan="2" style="font-size:12px;font-weight:bold;">Company:</td>
                                    <td colspan="4" style="font-size:12px;">
                                        {{ $job_info->customerContract ? $job_info->customerContract->company : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="font-size:12px;font-weight:bold;">Address:</td>
                                    <td colspan="4" style="font-size:12px;">
                                        {{ $job_info->customerContract ? $job_info->customerContract->address : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size:12px;font-weight:bold;">City:</td>
                                    <td style="font-size:12px;">
                                        {{ $job_info->customerContract ? $job_info->customerContract->city : '' }}
                                    </td>
                                    <td style="font-size:12px;font-weight:bold;">State:</td>
                                    <td style="font-size:12px;">
                                        {{ $job_info->customerContract ? $job_info->customerContract->state->name : '' }}
                                    </td>
                                    <td style="font-size:12px;font-weight:bold; padding-left:25px;">Zip:</td>
                                    <td style="font-size:12px;">
                                        {{ $job_info->customerContract ? $job_info->customerContract->zip : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="font-size:12px;font-weight:bold;">Telephone:</td>
                                    <td colspan="4" style="font-size:12px;">
                                        {{ $job_info->customerContract ? $job_info->customerContract->phone : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="font-size:12px;font-weight:bold;">Fax:</td>
                                    <td colspan="4" style="font-size:12px;">
                                        {{ $job_info->customerContract ? $job_info->customerContract->fax : '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="font-size:12px;font-weight:bold;">Web:</td>
                                    <td colspan="4" style="font-size:12px;">
                                        {{ $job_info->customerContract ? $job_info->customerContract->website : '' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width:350px;">
                            <table valign="top">
                                @if ($job_info->customerContract != '' && count($job_info->customerContract->contactInformation) > 0)
                                    @foreach ($job_info->customerContract->contactInformation as $customerKey => $contactInformation)
                                        <tr>
                                            <td colspan="2">
                                                <table>
                                                    <tr>
                                                        <td style="font-size:12px;font-weight:bold;">First: </td>
                                                        <td>{{ $contactInformation->first_name != '' ? $contactInformation->first_name : 'N/A' }}
                                                        </td>
                                                        <td style="font-size:12px;font-weight:bold;">Last: </td>
                                                        <td>{{ $contactInformation->last_name != '' ? $contactInformation->last_name : 'N/A' }}
                                                        </td>
                                                        <td style="font-size:12px;font-weight:bold;">Title: </td>
                                                        <td>{{ $contactInformation->title != '' ? ($contactInformation->title == 'Other' ? $contactInformation->title_other : $contactInformation->title) : 'N/A' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" style="font-size:12px;font-weight:bold;">
                                                            Direct Phone
                                                        </td>
                                                        <td colspan="3">
                                                            {{ $contactInformation->direct_phone }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" style="font-size:12px;font-weight:bold;">
                                                            Cell Phone
                                                        </td>
                                                        <td colspan="3">
                                                            {{ $contactInformation->cell }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" style="font-size:12px;font-weight:bold;">
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
                    <tr>
                        <td style="width:350px;">
                            <table>
                                <tr>
                                    <td style="font-size:12px;font-weight:bold;">Contract Amount:</td>
                                    <td style="font-size:12px;">
                                        ${{ $job_info->contract_amount != '' ? $job_info->contract_amount : 0 }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width:350px;">
                            <table>
                                <tr>
                                    <td style="font-size:12px;font-weight:bold;">First day of work:</td>
                                    <td style="font-size:12px;">
                                        {{ $job_info->first_day_of_work }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size:12px;font-weight:bold;width:350px;">
                            If your customer is the General Contractor?
                            Yes <input type="radio" name="is_gc" class="is_gc" value="0"
                                {{ $job_info->is_gc == '0' ? 'checked' : '' }}>
                            No <input type="radio" name="is_gc" class="is_gc" value="1"
                                {{ $job_info->is_gc == '1' ? 'checked' : '' }}>
                        </td>
                    </tr>
                </table>
                @if (count($job_info->industryContacts) > 0)
                    @foreach ($job_info->industryContacts as $key => $contact)
                        <table style="width:700px; padding-top: 35px;">
                            <tr>
                                <th colspan="2" style="text-align:center;">{{ $contact->contacts->contact_type }}
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <table valign="top">
                                        <tr>
                                            <td colspan="2" style="font-size:12px;font-weight:bold;">Company:</td>
                                            <td colspan="4" style="font-size:12px;">
                                                {{ $contact->contacts ? $contact->contacts->company : '' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="font-size:12px;font-weight:bold;">Address:</td>
                                            <td colspan="4" style="font-size:12px;">
                                                {{ $contact->contacts ? $contact->contacts->address : '' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:12px;font-weight:bold;">City:</td>
                                            <td style="font-size:12px;">
                                                {{ $contact->contacts ? $contact->contacts->city : '' }}
                                            </td>
                                            <td style="font-size:12px;font-weight:bold;">State:</td>
                                            <td style="font-size:12px;">
                                                {{ $contact->contacts ? $contact->contacts->state->name : '' }}
                                            </td>
                                            <td style="font-size:12px;font-weight:bold; padding-left:25px;">Zip:</td>
                                            <td style="font-size:12px;">
                                                {{ $contact->contacts ? $contact->contacts->zip : '' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="font-size:12px;font-weight:bold;">Telephone:</td>
                                            <td colspan="4" style="font-size:12px;">
                                                {{ $contact->contacts ? $contact->contacts->phone : '' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="font-size:12px;font-weight:bold;">Fax:</td>
                                            <td colspan="4" style="font-size:12px;">
                                                {{ $contact->contacts ? $contact->contacts->fax : '' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="font-size:12px;font-weight:bold;">Web:</td>
                                            <td colspan="4" style="font-size:12px;">
                                                {{ $contact->contacts ? $contact->contacts->website : '' }}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                <td>
                                    <table>
                                        @if (count($contact->contacts->contactInformation) > 0)
                                            @foreach ($contact->contacts->contactInformation as $customerKey => $contactInformation)
                                                <tr>
                                                    <td colspan="2">
                                                        <table>
                                                            <tr>
                                                                <td style="font-size:12px;font-weight:bold;">First:
                                                                </td>
                                                                <td>{{ $contactInformation->first_name != '' ? $contactInformation->first_name : 'N/A' }}
                                                                </td>
                                                                <td style="font-size:12px;font-weight:bold;">Last: </td>
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
                                                                    {{ $contactInformation->direct_phone }}
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
                        </table>
                    @endforeach
                @endif
                <table style="height: 100px;">
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>

                <table align="left" valign="top">
                    <tr>
                        <td style="text-align: center; padding-bottom: 20px;">
                            <strong>Agree Terms and Conditions</strong>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 20px;">
                            Please remember to fax to NLB all documentation related to this
                            project. This includes contracts, invoices, notices, purchase orders,
                            etc.<strong>FAX: 847-432-8950</strong>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Liability Limitations:</strong> National Lien and Bond Claim Systems, a division of
                            Network*50, Inc (NLB) does not guarantee or in any way represent or warrant the
                            information transmitted or received by customer or third parties. Customer acknowledges
                            and agrees that the service provided by NLB consists solely of providing access to a filing
                            network which may in appropriate cases involve attorneys. NLB is not in any way
                            responsible or liable for errors, omissions, inadequacy or interruptions. In the event any
                            errors is attributable to NLB or to the equipment, customer should be entitled only to a
                            refund of the cost for preparation of any notices. The refund shall be exclusively in lieu
                            of
                            any other damages or remedies against NLB.
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 700px; text-align: center;">
                            <img src="{{ env('ASSET_URL') }}/images/nlb_black.png" alt="NLB" align="middle">
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
                                        {{ $job_info->signature }}
                                    </td>
                                    <td>
                                        <strong>Date:</strong>
                                        {{ $job_info->signature_date }}
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
