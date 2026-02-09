@section('style')
    <style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0 !important;
    }

    .blue-btn-ext{
        background: #1084ff;
        color: #fff;
    }
    .input-error {
        border: 2px solid red;

    }
</style>
@endsection
<div id="addCustomerModel" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <form class="form-horizontal" id="TypeForm" method="post" action="#">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span class="modal_title"></span> Contact</h4>
                </div>

                <div class="modal-body">

                    <div class="form-group contractType">
                        <label class="col-md-4 control-label">Role : </label>
                        <div class="col-md-8">
                            <select name="ContactType" class="form-control error" id="contactType" required autocomplete="off">
                                <option value="">Select a role</option>
                                <option value="Architect">Architect</option>
                                <option value="Bonding Company">Bonding Company</option>
                                <option value="Engineer">Engineer</option>
                                <option value="General Contractor">General Contractor</option>
                                <option value="Lender">Lender</option>
                                <option value="Owner">Owner</option>
                                <option value="Sub-Contractor">Sub-Contractor</option>
                                <option value="Title Company">Title Company</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Company : </label>
                        <div class="col-md-8">
                            <select id="company_new" readonly onfocus="this.removeAttribute('readonly');" autocomplete="off" required data-type="customer" name="company">
                            {{-- <select class="form-control error autocomplete" data-type="customer" name="company" id="company"  placeholder="Enter Company Name" required autocomplete="off">

                                
                                 @isset($customerContracts)
                                    @foreach($customerContracts as $customerContract)
                                        <option value="{{ $customerContract->id }}" {{ (isset($project) && $project != '' && $project->customer_contract_id == $customerContract->id)?'selected':'' }}>{{ $customerContract->contacts->first_name.' '.$customerContract->contacts->last_name.' ( '.$customerContract->company->company.' ) ' }}</option>
                                    @endforeach
                                @endisset--}}
                            </select>
                        </div>
                    </div>
                    <div id="add_new_company_button" style="display:none;float:right">Oops, nothing found! <a class='add_company_from_search'>Click here to add company</a> <span id="comp_new"> </span></div>
                    <div style="display:none" id="new_company_name"></div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Website : </label>
                        <div class="col-md-8">
                            <input class="form-control error" type="text" name="website" id="website"
                                placeholder="Enter Website" autocomplete="off"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Address : </label>
                        <div class="col-md-8">
                            <textarea name="address" class="form-control error" placeholder="Enter Address"
                                id="addressType"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">City : </label>
                        <div class="col-md-8">
                            <input class="form-control error" type="text" name="city" id="cityType"
                                placeholder="Enter City" autocomplete="off"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">State : </label>
                        <div class="col-md-8">
                            <select class="form-control error" name="state" id="stateModal" required autocomplete="off">
                                <option value="">Select a state</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Zip : </label>
                        <div class="col-md-8">
                            <input class="form-control error" type="text" name="zip" id="zipType"
                                placeholder="Enter Zip Code" autocomplete="off"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Phone : </label>
                        <div class="col-md-8">
                            <input class="form-control error phone" type="text" name="phone" id="phone"
                                placeholder="Enter Phone Number" autocomplete="off"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Fax : </label>
                        <div class="col-md-8">
                            <input class="form-control error" type="text" name="fax" id="fax"
                                placeholder="Enter Fax Number" autocomplete="off"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Contacts : </label>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12 table-responsive">
                            <table class="table field_wrapper modalTable">
                                <thead>
                                    <tr>
                                        <td>Title</td>
                                        <td>First Name</td>
                                        <td>Last Name</td>
                                        <td>Email</td>
                                        <td>Direct Phone</td>
                                        <td>Cell</td>
                                        <td>Action</td>
                                    </tr>
                                </thead>

                                <tbody class="contact_table_body">
                                    <tr class="contact_row">
                                        <td>
                                            <select class="form-control error title" id="title" name="contacts[0][title]" autocomplete="off" value="N/A">
                                                <option value="CEO">CEO</option>
                                                <option value="CFO">CFO</option>
                                                <option value="Credit">Credit</option>
                                                <option value="PM">PM</option>
                                                <option value="Corporation Counsel">Corporation Counsel</option>
                                                <option value="A/R Manager">A/R Manager</option>
                                                <option value="Other">Other</option>
                                            </select>

                                            <div class="title_other" style="display: none;">
                                                <input type="text" id="title_other" name="contacts[0][titleOther]"
                                                class="form-control error" autocomplete="off">
                                                <a href="#" class="titleOtherBtn">Change</a>
                                            </div>
                                        </td>
                                        <td>
                                            <input id="first_name"
                                                class="form-control error contacts first_name autocomplete_contact_first_name"
                                                type="text" name="contacts[0][firstName]"
                                                autocomplete="off"
                                                data-field="first_name"
                                                placeholder="First Name"  value="N/A"/>
                                            <input id="customerId" type="hidden" data-field="customer_id" class="customer_id" name="contacts[0][customerId]" value="0"/>
                                        </td>
                                        <td>
                                            <input id="lastName"
                                                class="form-control error contacts last_name"
                                                type="text" name="contacts[0][lastName]"
                                                data-field="last_name"
                                                placeholder="Last Name"
                                                autocomplete="off"
                                                value="N/A"
                                            />
                                        </td>
                                        <td>
                                            <input id="email" class="form-control error contacts email" type="text" name="contacts[0][email]"
                                                data-field="email"  placeholder="Email" autocomplete="off" value="N/A"/>
                                        </td>
                                        <td>
                                            <input id="directPhone" class="form-control error contacts phone" type="number" name="contacts[0][directPhone]" autocomplete="off"
                                                data-field="phone"
                                                placeholder="Direct Phone" value=""/>
                                        </td>
                                        <td>
                                            <input id="cellPhone" class="form-control error cell" type="number" name="contacts[0][cellPhone]" data-field="cell"
                                                placeholder="Cell Phone" autocomplete="off" value=""/>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" class="add_button" title="Add field"><img
                                                src="{{ env('ASSET_URL') }}/images/add-icon.png" height="30px"
                                                width="30px"/></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <input type="hidden" id="dataType">

                        <div class="form-group">
                            <div class="col-md-12">
                                <div id="error-message">
                                </div>
                            </div>
                        </div>
        </div>

        <div class="modal-footer">
            <button class="btn blue-btn-ext mr-auto formSubmit" type="submit">Save</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</form>
</div>
</div>
