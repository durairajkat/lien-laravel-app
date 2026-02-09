@extends('basicUser.layout.main')

@section('title', 'New Claim')

@section('content')


    <section class="bodypart">
        <div class="container">
            <form method="post" id="claim_data_sheet" action="{{ route('submit.new.claim') }}"
                enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="step" value="3">
                <input type="hidden" name="id" value="{{ $id or '' }}">
                <div class="row">
                    <div class="col-md-10 col-sm-12 col-md-offset-1">
                        <div class="center-part">
                            <h1><a href="#"><span>i</span>Company™ Claim Data</a></h1>
                        </div><br>
                        <div class="project-name"><input type="text" name="project_name" value="{{ $project_name or '' }}">
                        </div>
                        <div class="time-nav">
                            <div class="header-progress-container header-progress-new">
                                <ol class="header-progress-list">

                                    <li class="header-progress-item done first"> <a
                                            href="{{ route('admin.new.claim_step1') }}">Project Details</a></li>
                                    <li class="header-progress-item done second"> <a
                                            href="{{ route('admin.new.claim_step2') }}">&nbsp;</a></li>
                                    <li class="header-progress-item done third"> <a
                                            href="{{ route('admin.new.claim_step3') }}">&nbsp;</a></li>
                                    <li class="header-progress-item todo forth "> <a
                                            href="{{ route('admin.new.claim_step4') }}">&nbsp;</a></li>
                                    <li class="header-progress-item todo done"> Submit</li>

                                </ol>
                            </div>
                        </div>
                        <br>
                        <div class="tab-content-body">

                            {{-- <div class="step-three">
   <div class="row">
      <div class="col-md-8 col-sm-8">
         <div class="row">
            <div class="col-md-6 col-sm-6">
               <div class="border-table shadow box-height left-box center-part">
                  <h2>DESCRIPTION OF YOUR<br> PRODUCT/SERVICE PROVIDED:</h2>
                  <div class="row">
                     <div class="col-md-6 col-xs-12 spec-col">
                        <div class="radio-style">
                           <ul>
                              <li>
                                 <input type="radio" id="f-option" name="construction">
                                 <label for="f-option">Materials Only</label>
                                 <div class="check"></div>
                              </li>
                              <li>
                                 <input type="radio" id="s-option" name="construction">
                                 <label for="s-option">Labor Only</label>
                                 <div class="check">
                                    <div class="inside"></div>
                                 </div>
                              </li>
                              <li>
                                 <input type="radio" id="t-option" name="construction">
                                 <label for="t-option">Materials & Labor</label>
                                 <div class="check">
                                    <div class="inside"></div>
                                 </div>
                              </li>
                           </ul>
                        </div>
                     </div>
                     <div class="col-md-6 col-xs-12 border-left">
                        <p>
                           Is your product custom manufactured 
                           for the projects?
                        </p>
                        <div class="radio-style radio-style-spc">
                           <ul>
                              <li>
                                 <input type="radio" id="f-option1" name="construction">
                                 <label for="f-option1">Yes</label>
                                 <div class="check"></div>
                              </li>
                              <li>
                                 <input type="radio" id="s-option1" name="construction">
                                 <label for="s-option1">No</label>
                                 <div class="check">
                                    <div class="inside"></div>
                                 </div>
                              </li>
                              <li>
                                 <div class="upload-btn-wrapper">
                                    <button class="btn">If Yes Contract<br> Dates</button>
                                    <input type="file" name="myfile" />
                                 </div>
                              </li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-6 col-sm-6">
               <div class="border-table shadow box-height left-box center-part">
                  <h2>PAYMENT</h2>
                  <div class="row">
                     <div class="col-md-6 col-xs-12 spec-col">
                        <p>Have you issued a preliminary notice?</p>
                        <div class="radio-style radio-style-spc">
                           <ul>
                              <li>
                                 <input type="radio" id="f-option2" name="selector">
                                 <label for="f-option2">Yes</label>
                                 <div class="check"></div>
                              </li>
                              <li>
                                 <input type="radio" id="s-option2" name="selector">
                                 <label for="s-option2">No</label>
                                 <div class="check">
                                    <div class="inside"></div>
                                 </div>
                              </li>
                              <li>
                                 <div class="upload-btn-wrapper">
                                    <button class="btn">If Yes,<br> Upload Copy</button>
                                    <input type="file" name="myfile" />
                                 </div>
                              </li>
                           </ul>
                        </div>
                     </div>
                     <div class="col-md-6 col-xs-12 spec-col border-left">
                        <p>Have you issued a preliminary notice?</p>
                        <div class="radio-style radio-style-spc">
                           <ul>
                              <li>
                                 <input type="radio" id="f-option3" name="selector">
                                 <label for="f-option3">Yes</label>
                                 <div class="check"></div>
                              </li>
                              <li>
                                 <input type="radio" id="s-option3" name="selector">
                                 <label for="s-option3">No</label>
                                 <div class="check">
                                    <div class="inside"></div>
                                 </div>
                              </li>
                              <li>
                                 <div class="upload-btn-wrapper">
                                    <button class="btn">If Yes,<br> Upload Copy</button>
                                    <input type="file" name="myfile"/>
                                 </div>
                              </li>
                           </ul>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="row">
            <div class="col-md-8">
               <div class="row bottom-upload">
                  <div class="col-sm-3">
                     <div class="bottom-upload">
                        <div class="upload-btn-wrapper">
                           <button class="btn">Upload</button>
                           <input name="myfile" type="file">
                        </div>
                     </div>
                  </div>
                  <div class="col-sm-9">
                     <div class="file-msg">
                        <p>Attach Delivery Tickets</p>
                        <p>Project Certificate</p>
                        <p>Notice of Completion</p>
                        <p>Notice of Acceptance</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="col-md-4 col-sm-4">
         <div class="tab-right">
            <ul>
               <li><a href="http://dev.nlb.local/member/project/date/view?project_id=1&amp;view=detailed">Skip</a></li>
               <li>
                  <a href="javascript:void(0)" id="activate-step-3">
                  <img src="{{ env('ASSET_URL') }}/images/next-step.jpg" alt="img">
                  </a>
               </li>
            </ul>
            <div class="sq">
               <p class="align-right">
                  <a href="#" id="save_quit">
                  Save &amp; Quit
                  </a>
               </p>
            </div>
         </div>
      </div>
   </div>
</div> --}}

                            <div class="step-four">
                                <div class="row">
                                    <div class="col-md-9 col-xs-12">
                                        <div class="">
                                            <div class="row">
                                                <div class="col-md-5 col-xs-12 spec-col1">
                                                    <div
                                                        class="border-table shadow radio-style left-box box-height2 box-height3">
                                                        <div class="center-part">
                                                            <h2>CONSTRUCTION TYPE:</h2>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 col-xs-12 spec-col">
                                                                <ul>
                                                                    <li class="big-menu">
                                                                        <input id="f-option5" name="construction"
                                                                            type="radio" value="Multi-Unit">
                                                                        <label for="f-option5">Multi-Unit
                                                                            Residential</label>
                                                                        <div class="check"></div>
                                                                    </li>
                                                                    <li>
                                                                        <input id="f-option6" name="construction"
                                                                            type="radio" value="Subdivision">
                                                                        <label for="f-option6">Subdivision</label>
                                                                        <div class="check"></div>
                                                                    </li>
                                                                    <li>
                                                                        <input id="f-option7" name="construction"
                                                                            type="radio" value="New">
                                                                        <label for="f-option7">New</label>
                                                                        <div class="check"></div>
                                                                    </li>
                                                                    <li class="big-menu">
                                                                        <input id="f-option8" name="construction"
                                                                            type="radio" value="Personal">
                                                                        <label for="f-option8">Personal Residence</label>
                                                                        <div class="check"></div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-md-6 col-xs-12 spec-col">
                                                                <ul>
                                                                    <li class="big-menu">
                                                                        <input id="f-option9" name="construction"
                                                                            type="radio" value="Municipal">
                                                                        <label for="f-option9">Municipal Government</label>
                                                                        <div class="check"></div>
                                                                    </li>
                                                                    <li>
                                                                        <input id="f-option10" name="construction"
                                                                            type="radio" value="Commercial">
                                                                        <label for="f-option10">Commercial</label>
                                                                        <div class="check"></div>
                                                                    </li>
                                                                    <li>
                                                                        <input id="f-option11" name="construction"
                                                                            type="radio" value="Rehab">
                                                                        <label for="f-option11">Rehab</label>
                                                                        <div class="check"></div>
                                                                    </li>
                                                                    <li>
                                                                        <input id="f-option12" name="construction"
                                                                            type="radio" value="Other">
                                                                        <label for="f-option12">Other</label>
                                                                        <div class="check"></div>
                                                                        <input class="form-control" type="text"
                                                                            id="other_data">
                                                                    </li>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7 col-xs-12 spec-col">
                                                    <div class="border-table shadow left-box box-height2">
                                                        <div class="center-part">
                                                            <h2>DATES OF WORK/FURNISHING/SHIPPING</h2>
                                                            <div class="form-group">
                                                                <input class="form-control form-date" name="first_date"
                                                                    placeholder="First Day of Work" type="text"
                                                                    id="datepicker"> to <input
                                                                    class="form-control form-date"
                                                                    placeholder="Last Day of Work" name="last_date"
                                                                    type="text" id="datepicker1">
                                                            </div>
                                                            <div class="form-group1">
                                                                <p>
                                                                    Is there a project certificate or notice of<br>
                                                                    completion or acceptance field?
                                                                </p>
                                                            </div>
                                                            <div class="radio-style left-style">
                                                                <ul>
                                                                    <li>
                                                                        <input id="f-option13" name="shipping" type="radio"
                                                                            value="yes">
                                                                        <label for="f-option13">Yes</label>
                                                                        <div class="check"></div>
                                                                    </li>
                                                                    <li>
                                                                        <input id="f-option14" name="shipping" type="radio"
                                                                            value="no">
                                                                        <label for="f-option14">No</label>
                                                                        <div class="check"></div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                            <hr>
                                                            <div class="form-group1">
                                                                <p>
                                                                    Is the project as a whole completed?
                                                                </p>
                                                            </div>
                                                            <div class="radio-style left-style">
                                                                <ul>
                                                                    <li>
                                                                        <input id="f-option15" name="whole" type="radio"
                                                                            value="yes">
                                                                        <label for="f-option15">Yes</label>
                                                                        <div class="check"></div>
                                                                    </li>
                                                                    <li>
                                                                        <input id="f-option16" name="whole" type="radio"
                                                                            value="no">
                                                                        <label for="f-option16">No</label>
                                                                        <div class="check"></div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 col-md-offset-3">
                                            <div class="row bottom-upload">
                                                <div class="col-sm-3">
                                                    <div class="bottom-upload">
                                                        <div class="upload-btn-wrapper">
                                                            <button class="btn">Upload</button>
                                                            <input name="myfile" type="file" id="file"
                                                                onchange="javascript:product()">

                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="col-sm-9">
                                                    <div class="file-msg">
                                                        <p>Attach Delivery Tickets</p>
                                                        <p>Project Certificate</p>
                                                        <p>Notice of Completion</p>
                                                        <p>Notice of Acceptance</p>
                                                    </div>
                                                    <div id="fileList"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-xs-12">
                                            <div class="tab-right">
                                                <ul>
                                                    <li><a href="{{ route('admin.new.claim_step4') }}">Skip</a></li>
                                                    <li>
                                                        <!--  <a href="javascript:void(0)" id="activate-step-3">
                      <img src="{{ env('ASSET_URL') }}/images/next-step.jpg" alt="img">
                      </a> -->
                                                        <button type="submit" class="next-btn">
                                                            <!-- <img src="{{ env('ASSET_URL') }}/images/next-step.jpg" alt="img"> -->
                                                        </button>
                                                    </li>
                                                </ul>
                                                {{-- <div class="sq">
               <p class="align-right">
                  <a href="#" id="save_quit">
                  Save &amp; Quit
                  </a>
               </p>
            </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--  <div class="row">
          <div class="col-md-6 col-sm-12 col-md-offset-3">
             <div class="row bottom-upload">
                <div class="col-sm-3">
                   <div class="bottom-upload">
                      <div class="upload-btn-wrapper">
                         <button class="btn">Upload</button>
                         <input name="myfile" type="file">
                      </div>
                   </div>
                </div>
                <div class="col-sm-9">
                   <div class="file-msg">
                      <p>Attach Delivery Tickets</p>
                      <p>Project Certificate</p>
                      <p>Notice of Completion</p>
                      <p>Notice of Acceptance</p>
                   </div>
                </div>
             </div>
          </div>
       </div> -->
                            </div>


                        </div>




                    </div>
                </div>
        </div>
    </section>



    <!--  <section class="bodypart">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-sm-12 col-md-offset-1">
                        <div class="row main-page">
                            <form method="post" id="claim_data_sheet" action="{{ route('submit.new.claim') }}">
                                <div class="col-md-12 col-sm-12">
                                <div class="first-step border-table">
                                    <div class="center-part">
                                        <h1><a href="#">new claim data sheet</a></h1>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="border-table">
                                                <div class="form-group">
                                                    <label>Your Company Name:</label>
                                                    <input type="text" name="company_name" class="form-control"
                                                           placeholder="Your company name">
                                                </div>
                                                <div class="form-group">
                                                    <label>Contact Name:</label>
                                                    <input type="text" name="contact_name" class="form-control"
                                                           placeholder="Your name">
                                                </div>
                                                <div class="form-group">
                                                    <label>Address:</label>
                                                    <input type="text" name="address" class="form-control"
                                                           placeholder="Your address">
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-6">
                                                            <div class="row">
                                                                <div class="col-md-3 col-sm-3"><label>City</label></div>
                                                                <div class="col-md-9 col-sm-9">
                                                                    <input type="text" name="city" class="form-control"
                                                                           placeholder="City">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-6">
                                                            <div class="row">
                                                                <div class="col-md-3 col-sm-3"><label>State </label>
                                                                </div>
                                                                <div class="col-md-9 col-sm-9">
                                                                    <input type="text" name="state" class="form-control"
                                                                           placeholder="State">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-6">
                                                            <div class="row">
                                                                <div class="col-md-3 col-sm-3"><label>Zip </label></div>
                                                                <div class="col-md-9 col-sm-9">
                                                                    <input type="text" name="zip" class="form-control"
                                                                           placeholder="Zip">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-6">
                                                            <div class="row">
                                                                <div class="col-md-3 col-sm-3"><label>Phone </label>
                                                                </div>
                                                                <div class="col-md-9 col-sm-9">
                                                                    <input type="text" name="phone" class="form-control"
                                                                           placeholder="Phone">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-6">
                                                            <div class="row">
                                                                <div class="col-md-3 col-sm-3"><label>FAX </label></div>
                                                                <div class="col-md-9 col-sm-9">
                                                                    <input type="text" name="fax" class="form-control"
                                                                           placeholder="FAX">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 col-sm-6">
                                                            <div class="row">
                                                                <div class="col-md-3 col-sm-3"><label>Email </label>
                                                                </div>
                                                                <div class="col-md-9 col-sm-9">
                                                                    <input type="text" name="email" class="form-control"
                                                                           placeholder="Email">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="border-table">
                                        <div class="form-group list-style">
                                            <label>CHECK TYPE OF CLAIM:</label>
                                            <ul>
                                                <li><input name="claim_type" value="preliminary_notice" type="radio">
                                                    Preliminary Notice
                                                </li>
                                                <li><input name="claim_type" value="lien_claim" type="radio"> Lien Claim
                                                </li>
                                                <li><input name="claim_type" value="bond_claim" type="radio"> Bond Claim
                                                </li>
                                                <li><input name="claim_type" value="collection" type="radio"> Collection
                                                </li>
                                                <li><input name="claim_type" value="precipitation" type="radio">
                                                    Precipitation
                                                </li>
                                                <li><input name="claim_type" value="litigation" type="radio"> Litigation
                                                </li>
                                                <li><input name="claim_type" value="bankruptcy" type="radio"> Bankruptcy
                                                </li>
                                                <li><input name="claim_type" value="other" type="radio"> Other <input
                                                            name="other_claim" id="other_claim" type="text"
                                                            style="display: none;"></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="border-table">
                                        <label>YOUR CONTRACT INFORMATION:</label>
                                        <div class="form-group">
                                            <label>Contract Date</label>
                                            <input name="contract_date" type="text" class="form-control1 date">
                                        </div>
                                        <hr>
                                        <div class="align-center">
                                            <div class="form-group">
                                                <label>Base contract amount</label>
                                                $<input name="base_amount" type="text" id="base_amount" value="0"
                                                        class="form-control1">
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <label>Do you have a</label>
                                            <table class="table no-margin no-border">
                                                <tr>
                                                    <td>
                                                        <input name="contract_type" type="radio"> written (Fax copies to
                                                        NLB) or
                                                    </td>
                                                    <td>
                                                        + Value of extras or changes
                                                    </td>
                                                    <td>
                                                        $ <input name="extra_amount" type="text" id="extra_amount"
                                                                 value="0" class="form-control1">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 200px;">
                                                        <input name="contract_type" type="radio"> verbal contract?
                                                        (Fax copies of P.O.'s, contracts, invoices, delivery tickets,
                                                        change orders, and billing statements,etc. to NLB)
                                                    </td>
                                                    <td colspan="2">
                                                        <table class="table no-border no-margin">
                                                            <tr>
                                                                <td>= Revised Contract subtotal</td>
                                                                <td>$ <input name="contact_total" type="text"
                                                                             id="contact_total" class="form-control1">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>- Less Amount Paid to date & credits</td>
                                                                <td>$ <input name="payment" type="text" id="payment"
                                                                             value="0" class="form-control1"></td>
                                                            </tr>
                                                            <tr>
                                                                <td>= Claim Amount Total</td>
                                                                <td>$ <input name="claim_amount" type="text"
                                                                             id="claim_amount" class="form-control1">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3">
                                                        Does all extra work relate to original contract?
                                                        <div class="form-group list-style">
                                                            <ul class="inline-block">
                                                                <li><input name="extra_work" type="radio"> Yes</li>
                                                                <li><input name="extra_work" type="radio"> No</li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="border-table">
                                        <label>CIRCLE PROJECT STATE:</label>
                                        <select name="project_state" class="form-control1">
                                            <option value="">Select State</option>
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div style="text-align:center">
                                        <a href="#" id="first_step_button">
                                            <img src="{{ env('ASSET_URL') }}/images/next-step.jpg" alt="img">
                                        </a>
                                    </div>
                                    <!--
                                       <div class="border-table">
                                           <label>DESCRIPTION OF YOUR PRODUCT/SERVICES PROVIDED</label> ( fax to NLB your product literature)
                                       <div class="form-group list-style">
                                       <ul>
                                           <li>Did you provide?</li>
                                           <li><input type="radio"> Materials only</li>
                                           <li><input type="radio"> Labor only</li>
                                           <li><input type="radio">  Materials & Labor
                                       Is your product custom manufactured for the project? </li>
                                           <li><input type="radio"> Yes, first date of fabrication (includes shop drawings) <input type="text" class="form-control1"></li>
                                           <li><input type="radio"> No</li>
                                       </ul>

                                       </div>
                                       </div>
                                       -->


@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $('#other_data').hide();
            //  $('.second-step').show();
            //    $('.third-step').hide();

            var base_amount = 0;
            var extra_amount = 0;
            var payment = 0;
            var total = 0;
            var claim_total = 0;
            totalAmount();

            $('#base_amount,#extra_amount,#payment').on('change', function() {
                totalAmount();
            });

            function totalAmount() {
                base_amount = $('#base_amount').val();
                extra_amount = $('#extra_amount').val();
                payment = $('#payment').val();
                total = parseFloat(base_amount) + parseFloat(extra_amount);
                claim_total = parseFloat(total) - parseFloat(payment);
                $('#contact_total').val(parseFloat(total));
                $('#claim_amount').val(parseFloat(claim_total));
            }

            $('#first_step_button').on('click', function() {
                $('.first-step').hide();
                $('.second-step').show();
                $('.third-step').hide();
            });
            $('#first_back_button').on('click', function() {
                $('.first-step').show();
                $('.second-step').hide();
                $('.third-step').hide();
            });
            $('#second_step_button').on('click', function() {
                $('.first-step').hide();
                $('.second-step').hide();
                $('.third-step').show();
            });
            $('#second_back_button').on('click', function() {
                $('.first-step').hide();
                $('.second-step').show();
                $('.third-step').hide();
            });
            $('input[name="claim_type"]').on('click', function() {
                if ($(this).val() == 'other') {
                    $('#other_claim').show();
                } else {
                    $('#other_claim').hide();
                }
            });
            $('input[name="whole_completed"]').on('click', function() {
                if ($(this).val() == 'yes') {
                    $('#whole_completed_date').show();
                } else {
                    $('#whole_completed_date').hide();
                }
            });
            $('input[name="payment_bound"]').on('click', function() {
                if ($(this).val() == 'yes') {
                    $('#payment_bound_div').show();
                } else {
                    $('#payment_bound_div').hide();
                }
            });
            $('input[name="project_status"]').on('click', function() {
                if ($(this).val() == 'other') {
                    $('#project_status_other').show();
                } else {
                    $('#project_status_other').hide();
                }
            });
            $('input[name="construction"]').on('click', function() {
                if ($('#f-option12').prop('checked')) {
                    $('#other_data').show();
                } else {
                    $('#other_data').hide();
                }
            });
            $('input[name="web_search"]').on('click', function() {
                if ($(this).val() == 'other') {
                    $('#web_search_other').show();
                } else {
                    $('#web_search_other').hide();
                }
            });
            // window.setInterval(saveForm, 10000);
        });

        function saveForm() {
            var formData = $('#claim_data_sheet').serialize();
            $.ajax({
                type: "POST",
                url: "{{ route('save.new.claim') }}",
                data: formData,
                success: function(data) {
                    $('#claim_id').val(data.data);
                }
            });
        }
    </script>
    <script type="text/javascript">
        $("#file-upload").change(function() {
            $("#file-name").text(this.files[0].name);
        });
    </script>

    <script>
        $(function() {
            $("#datepicker").datepicker();
        });
        $(function() {
            $("#datepicker1").datepicker();
        });
        product = function() {
            var input = document.getElementById('file');
            var output = document.getElementById('fileList');

            //output.innerHTML = '<ul>';
            for (var i = 0; i < input.files.length; ++i) {
                output.innerHTML += input.files.item(i).name;
            }
            //output.innerHTML += '</ul>';
        }
    </script>
@endsection
