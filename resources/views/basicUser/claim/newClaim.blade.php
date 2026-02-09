@extends('basicUser.layout.main')

@section('title', 'New Claim')

@section('content')
    <section class="bodypart">
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
                                                        $ <input name="extra_amount" type="text" id="extra_amount" value="0"
                                                            class="form-control1">
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
                                </div>
                                <div class="second-step border-table">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="border-table">
                                                <label>CONTRACT/PAYMENT:</label>
                                                <div class="table-responsive">
                                                    <table class="table no-margin">
                                                        <tr>
                                                            <td>Have you issued a preliminary notice?</td>
                                                            <td><input name="preliminary_notice" value="yes" type="radio">
                                                                Yes ( please fax to NLB )
                                                            </td>
                                                            <td><input name="preliminary_notice" value="no" type="radio"> No
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Have you issued lien waiver?</td>
                                                            <td><input name="lien_waiver" value="yes" type="radio"> Yes (
                                                                please fax to NLB )
                                                            </td>
                                                            <td><input name="lien_waiver" value="no" type="radio"> No</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="border-table">
                                                <label>CONSTRUCTION TYPE:</label>
                                                <div class="form-group list-style">
                                                    <ul>
                                                        <li><input name="construction_type" value="new" type="radio"> New
                                                        </li>
                                                        <li><input name="construction_type" value="rehab" type="radio">
                                                            Rehab
                                                        </li>
                                                        <li><input name="construction_type" value="multi-unit-residential"
                                                                type="radio"> Multi-Unit Residential
                                                        </li>
                                                        <li><input name="construction_type" value="personal-residence"
                                                                type="radio"> Personal Residence
                                                        </li>
                                                        <li><input name="construction_type" value="government" type="radio">
                                                            Municipal/Government
                                                        </li>
                                                        <li><input name="construction_type" value="subdivision"
                                                                type="radio"> Subdivision
                                                        </li>
                                                        <li><input name="construction_type" value="commercial" type="radio">
                                                            Commercial
                                                        </li>
                                                        <li><input name="construction_type" value="other" type="radio">
                                                            Other
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="border-table">
                                                <label>DATES OF WORK/FURNISHING/SHIPPING:</label> (fax to NLB your delivery
                                                tickets)
                                                <strong>Is there a project certificate or notice of completion or acceptance
                                                    filed? </strong>
                                                <div class="form-group list-style">
                                                    <ul class="inline-block">
                                                        <li><input name="acceptance_filed" value="yes" type="radio"> Yes
                                                        </li>
                                                        <li><input name="acceptance_filed" value="no" type="radio"> No</li>
                                                    </ul>
                                                </div>
                                                <br>
                                                <label>Is the project as a whole completed? </label>
                                                <div class="form-group list-style">
                                                    <ul class="inline-block">
                                                        <li><input name="whole_completed" value="yes" type="radio"> Yes</li>
                                                        <li><input name="whole_completed" value="no" type="radio"> No</li>
                                                    </ul>
                                                </div>
                                                <br>
                                                <div id="whole_completed_date" style="display: none;">
                                                    <label>If Yes, Date of completion</label>
                                                    <input name="date_of_completion" type="text" class="form-control1 date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div style="text-align:center">
                                        <button type="button" class="btn btn-info btn-md" id="first_back_button">PREVIOUS
                                            STEP</button>
                                        <a href="#" id="second_step_button">
                                            <img src="{{ env('ASSET_URL') }}/images/next-step.jpg" alt="img">
                                        </a>
                                    </div>
                                </div>
                                <div class="third-step border-table">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="border-table">
                                                <div class="form-group">
                                                    <label>Project Name: </label>
                                                    <input name="project_name" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Address:</label>
                                                    <input name="project_address" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <div class="row list-group">
                                                        <div class="col-md-4">
                                                            <label>City:</label>
                                                            <input name="city" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>State:</label>
                                                            <input name="state" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Zip:</label>
                                                            <input name="zip" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label>Phone & Fax:</label>
                                                            <input name="phone" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-7">
                                                            <label>County of Property:</label>
                                                            <input name="county_of_property" type="text"
                                                                class="form-control1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="border-table">
                                                <div class="form-group">
                                                    <strong>Project Owner or Public Agency:</strong>
                                                </div>
                                                <div class="form-group">
                                                    <label>Contact Name:</label>
                                                    <input name="project_owner" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Address:</label>
                                                    <input name="contact_address" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <div class="row list-group">
                                                        <div class="col-md-4">
                                                            <label>City:</label>
                                                            <input name="contact_city" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>State:</label>
                                                            <input name="contact_state" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Zip:</label>
                                                            <input name="contact_zip" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label>Phone & Fax:</label>
                                                            <input name="contact_phone" type="text" class="form-control1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="border-table">
                                                <div class="form-group">
                                                    <strong>Original Contractor:</strong>
                                                </div>
                                                <div class="form-group">
                                                    <label>Contact Name:</label>
                                                    <input name="original_contractor" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Address:</label>
                                                    <input name="oc_address" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <div class="row list-group">
                                                        <div class="col-md-4">
                                                            <label>City:</label>
                                                            <input name="oc_city" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>State:</label>
                                                            <input name="oc_state" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Zip:</label>
                                                            <input name="oc_zip" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label>Phone & Fax:</label>
                                                            <input name="oc_phone" type="text" class="form-control1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="border-table">
                                                <div class="form-group">
                                                    <strong>Subcontractor:</strong><br>
                                                    <label>Contact Name:</label>
                                                    <input name="sc_name" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Address:</label>
                                                    <input name="sc_address" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <div class="row list-group">
                                                        <div class="col-md-4">
                                                            <label>City:</label>
                                                            <input name="sc_city" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>State:</label>
                                                            <input name="sc_state" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Zip:</label>
                                                            <input name="sc_zip" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label>Phone & Fax:</label>
                                                            <input name="sc_phone" type="text" class="form-control1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="border-table">
                                                <div class="form-group">
                                                    <label>Is the Project:</label>
                                                    <div class="list-style">
                                                        <ul>
                                                            <li>
                                                                <input name="is_project" value="privately_owned"
                                                                    type="radio"> Privately Owned
                                                            </li>
                                                            <li>
                                                                <input name="is_project" value="public_works" type="radio">
                                                                Public Works
                                                            </li>
                                                            <li>
                                                                <input name="is_project" value="local_owned" type="radio">
                                                                Local Owned
                                                            </li>
                                                            <li>
                                                                <input name="is_project" value="federal_works" type="radio">
                                                                Federal Works
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Contract #:</label>
                                                    <input name="contract" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Project#:</label>
                                                    <input name="project" type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="border-table">
                                                <div class="form-group">
                                                    <label>Project Notice:</label>
                                                    <p><strong>Has the GC or Owner filed a notice that the Project has
                                                            commenced?</strong></p>
                                                    <div class="list-style">
                                                        <ul>
                                                            <li><input name="project_notice" value="yes" type="radio"> Yes
                                                                (Fax copies to NLB)
                                                            </li>
                                                            <li><input name="project_notice" value="no" type="radio"> No
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="border-table">
                                                <div class="form-group">
                                                    <label>All Jobs:</label>
                                                    <strong>Is there a payment bond?</strong>
                                                    <div class="list-style">
                                                        <ul class="inline-block">
                                                            <li><input name="payment_bound" value="yes" type="radio"> Yes
                                                            </li>
                                                            <li><input name="payment_bound" value="no" type="radio"> No</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div id="payment_bound_div" style="display: none">
                                                    <div class="form-group">
                                                        <label>If Yes, give payment bond #: </label>
                                                        <input name="payment_bond" type="text" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Name of bonding company:</label>
                                                        <input name="pb_company" type="text" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Contact Name:</label>
                                                        <input name="pb_name" type="text" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Address:</label>
                                                        <input name="pb_address" type="text" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row list-group">
                                                            <div class="col-md-4">
                                                                <label>City:</label>
                                                                <input name="pb_city" type="text" class="form-control1">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label>State:</label>
                                                                <input name="pb_state" type="text" class="form-control1">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label>Zip:</label>
                                                                <input name="pb_zip" type="text" class="form-control1">
                                                            </div>
                                                            <div class="col-md-5">
                                                                <label>Phone:</label>
                                                                <input name="pb_phone" type="text" class="form-control1">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="border-table">
                                                <div class="form-group">
                                                    <label>Misc. Project Information:</label>
                                                    <p>List other people involved in Project - Architects, Engineers, Title
                                                        Co., Lenders and any others:</p>
                                                </div>
                                                <div class="form-group">
                                                    <label>Contact Name:</label>
                                                    <input name="mpi_name" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Project Relationship:</label>
                                                    <select name="mpi_relationship" class="form-control">
                                                        <option>Management</option>
                                                        <option>Architect</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Business Name:</label>
                                                    <input name="business_name" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-6">
                                                            <label>Phone:</label>
                                                            <input name="mpi_phone" type="text" class="form-control">
                                                        </div>
                                                        <div class="col-md-6 col-sm-6">
                                                            <label>Email:</label>
                                                            <input name="mpi_email" type="text" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="border-table">
                                                <div class="form-group">
                                                    <strong>Your Customer:</strong>
                                                </div>
                                                <div class="form-group">
                                                    <label>Contact Name:</label>
                                                    <input name="customer_name" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Address:</label>
                                                    <input name="customer_address" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <div class="row list-group">
                                                        <div class="col-md-4">
                                                            <label>City:</label>
                                                            <input name="customer_city" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>State:</label>
                                                            <input name="customer_state" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Zip:</label>
                                                            <input name="customer_zip" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label>Phone:</label>
                                                            <input name="customer_phone" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-7">
                                                            <label>No:</label>
                                                            <input name="customer_no" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-7">
                                                            <label>Account #:</label>
                                                            <input name="customer_account" type="text"
                                                                class="form-control1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="border-table">
                                                <div class="form-group">
                                                    <label>Your Order:</label><br>
                                                    <strong>Value of order: $ </strong>
                                                    <input name="value_of_order" type="text" class="form-control1">
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-6">
                                                            <label>Job No.</label>
                                                            <input name="job_no" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-6 col-sm-6">
                                                            <label>P.O.No.</label>
                                                            <input name="po_no" type="text" class="form-control1">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-6">
                                                            <label>Contract No.</label>
                                                            <input name="contract_no" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-6 col-sm-6">
                                                            <label>Date Products needed.</label>
                                                            <input name="date_product_needed" type="text"
                                                                class="form-control1">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Approximate start work date: </label>
                                                    <input name="start_work_date" type="text" class="form-control1">
                                                </div>
                                            </div>
                                            <div class="border-table">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-6 col-sm-6">
                                                            <label>Credit Information:</label><br>
                                                            <label>Payment Terms:</label>
                                                            <input name="payment_terms" type="text" class="form-control1">
                                                        </div>
                                                        <div class="col-md-6 col-sm-6">
                                                            <label>Billing Cycle:</label>
                                                            <input name="billing_cycle" type="text" class="form-control1">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group list-style">
                                                    <ul>
                                                        <li><input name="credit_information" value="joint_check"
                                                                type="radio">Joint Check
                                                        </li>
                                                        <li><input name="credit_information" value="direct_payment"
                                                                type="radio">Direct Payment
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="border-table">
                                                <div class="form-group">
                                                    <label>Your Status in Project:</label>
                                                    <div class="list-style">
                                                        <ul>
                                                            <li><input name="project_status" value="general_contractor"
                                                                    type="radio"> General Contractor
                                                            </li>
                                                            <li><input name="project_status" value="subcontractor"
                                                                    type="radio"> Subcontractor
                                                            </li>
                                                            <li><input name="project_status" value="supplier_subcontractor"
                                                                    type="radio"> Supplier to Subcontractor
                                                            </li>
                                                            <li><input name="project_status" value="supplier_supplier"
                                                                    type="radio"> Supplier to
                                                                Supplier(ie.Representative/Wholesaler/Distributor)
                                                            </li>
                                                            <li><input name="project_status" value="other" type="radio">
                                                                Other <input name="project_status_other"
                                                                    id="project_status_other" type="text"
                                                                    class="form-control1" style="display: none;">
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="border-table">
                                                <div class="form-group">
                                                    <label>Your Document Check List:</label>
                                                    <div class="list-style">
                                                        <ul>
                                                            <li><input name="purchase_order" value="1" type="checkbox">
                                                                Purchase Order / Contract
                                                            </li>
                                                            <li><input name="delivery_trick" value="1" type="checkbox">
                                                                Invoices & Delivery Tickets
                                                            </li>
                                                            <li><input name="copies_of_waiver_lien" value="1"
                                                                    type="checkbox"> Copies of Waiver of Lien
                                                            </li>
                                                            <li><input name="legal_description" value="1" type="checkbox">
                                                                Legal Description
                                                            </li>
                                                            <li><input name="payment_bond" value="1" type="checkbox">
                                                                Payment Bond
                                                            </li>
                                                            <li><input name="joint_check_agreement" value="1"
                                                                    type="checkbox"> Joint Check Agreement
                                                            </li>
                                                            <li><input name="preliminary" value="1" type="checkbox">
                                                                Preliminary Notice
                                                            </li>
                                                            <li><input name="other" value="1" type="checkbox"> Other <input
                                                                    name="check_list_other" id="check_list_other"
                                                                    type="text" style="display: none;"
                                                                    class="form-control1"></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="border-table">
                                                <div class="form-group">
                                                    <label>Miscellaneous</label>
                                                    <input name="miscellaneous" type="text" class="form-control">
                                                </div>
                                            </div>
                                            <div class="border-table">
                                                <div class="form-group list-style">
                                                    <h2>Please Fax your contracts, invoices, purchase orders, and all other
                                                        related documents to us at:</h2>
                                                    <label>Please let us know how you heard about NLB! Thanks for your
                                                        business! </label><br>
                                                    <strong>Web search</strong>
                                                    <ul>
                                                        <li><input name="web_search" value="google" type="radio"> Google
                                                        </li>
                                                        <li><input name="web_search" value="aol" type="radio"> AOL</li>
                                                        <li><input name="web_search" value="msn" type="radio"> MSN</li>
                                                        <li><input name="web_search" value="other" type="radio"> Other
                                                            <input type="text" name="web_search_other" id="web_search_other"
                                                                class="form-control1" style="display: none;">
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            {{ csrf_field() }}
                                            <input type="hidden" name="claim_id" id="claim_id" value="0">
                                            <div class="form-group" style="text-align:center">
                                                <button type="button" class="btn btn-info btn-md"
                                                    id="second_back_button">PREVIOUS STEP</button>
                                                <button class="btn btn-success btn-md" type="submit">SAVE & EXIT</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
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
            $('input[name="other"]').on('click', function() {
                if ($('input[name="other"]').prop('checked')) {
                    $('#check_list_other').show();
                } else {
                    $('#check_list_other').hide();
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
@endsection
