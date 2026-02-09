
<div id="addUserModel" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <form class="form-horizontal" method="post" id="addUserForm" autocomplete="off">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> Create Sub User</h4>
            </div>

            <div class="modal-body">

                    <!-- <div class="form-group">
                        <label class="col-md-4 control-label">Contact Type : </label>
                        <div class="col-md-8">
                            <select name="ContactType" class="form-control error" id="contactType1">
                                <option value="">Select a contract type</option>
                                <option value="General Contractor">General Contractor</option>
                                <option value="Architect">Architect</option>
                                <option value="Sub-Contractor">Sub-Contractor</option>
                                <option value="Bonding Company">Bonding Company</option>
                                <option value="Owner">Owner</option>
                                <option value="Lender">Lender</option>
                                <option value="Title Company">Title Company</option>
                                <option value="Engineer">Engineer</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div> -->

                    <div class="form-group">
                        <label class="col-md-4 control-label">Company : </label>
                        <div class="col-md-8">
                            <input class="form-control error" type="text" name="company" id="company1" value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->company) ? $user->details->getCompany->company : ''}}"
                                   placeholder="Enter Company Name" readonly autocomplete="off"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">First Name : </label>
                        <div class="col-md-8">
                            <input class="form-control error" type="text" name="firstName" id="firstName1"
                                   placeholder="Enter First Name" autocomplete="off"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Last Name : </label>
                        <div class="col-md-8">
                            <input class="form-control error" type="text" name="lastName" id="lastName1"
                                   placeholder="Enter Last Name" autocomplete="off"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Address : </label>
                        <div class="col-md-8">
                                <textarea name="address" class="form-control error" placeholder="Enter Address"
                                          id="address1">{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->address)? $user->details->getCompany->address : ''}}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">City : </label>
                        <div class="col-md-8">
                            <input class="form-control error" type="text" name="city" id="city1" value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->city)? $user->details->getCompany->city : ''}}"
                                   placeholder="Enter City" autocomplete="off"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">State : </label>
                        <div class="col-md-8">
                            <select class="form-control error" name="state" id="state1" autocomplete="off">
                                <option value="">Select a state</option>
                                @foreach($states as $key => $state)
                                    <option value="{{ $key }}" {{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->state_id) && $key == $user->details->getCompany->state_id ?  'selected' : '' }}>{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Zip : </label>
                        <div class="col-md-8">
                            <input class="form-control error" type="text" name="zip" id="zip1" value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->zip)? $user->details->getCompany->zip : ''}}"
                                   placeholder="Enter Zip Code" autocomplete="off"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Phone : </label>
                        <div class="col-md-8">
                            <input class="form-control error" type="text" name="phone" id="phone1" value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->phone)? $user->details->getCompany->phone : ''}}"
                                   placeholder="Enter Phone Number" autocomplete="off"/>
                        </div>
                    </div>

                    <!--  <div class="form-group">
                         <label class="col-md-4 control-label">Fax : </label>
                         <div class="col-md-8">
                             <input class="form-control error" type="text" name="fax" id="fax1"
                                    placeholder="Enter Fax Number"/>
                         </div>
                     </div> -->

                    <div class="form-group">
                        <label class="col-md-4 control-label">Email : </label>
                        <div class="col-md-8">
                            <input class="form-control error" type="email" name="email" id="email1"
                                   placeholder="Enter Email" autocomplete="off"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">User Name : </label>
                        <div class="col-md-8">
                            <input class="form-control error" type="test" name="username" id="username1"
                                   placeholder="Enter username" autocomplete="off"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-4 control-label">Password : </label>
                        <div class="col-md-8">
                            <input autocomplete="new-password" class="form-control error" type="password" name="password" id="password1"
                                   placeholder="Enter password"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <div id="error-message">

                            </div>
                        </div>
                    </div>


                    <input type="hidden" value="{{ !is_null($user->details) && !is_null($user->details->getCompany) && isset($user->details->getCompany->id) ?  $user->details->getCompany->id : 0 }}" id="companyId">
                    <input type="hidden" value="0" id="subUserId">
                    {{--<div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button class="btn blue-btn  formSubmit" type="button"><span
                                        class="title"></span> Contact
                            </button>
                        </div>
                    </div>--}}

            </div>
            <div class="modal-footer">
                <button class="btn blue-btn-ext mr-auto formSubmit" type="button"><span
                            class="title"></span> Contact
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        </form>
    </div>
</div>