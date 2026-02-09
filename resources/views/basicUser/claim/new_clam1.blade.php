@extends('basicUser.layout.main')

@section('title', 'New Claim')

@section('content')


    <section class="bodypart">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-sm-12 col-md-offset-1">
                    <div class="center-part">
                        <h1><a href="#"><span>i</span>Company™ Claim Data</a></h1>
                    </div><br>
                    <div class="project-name">Project Name</div>
                    <div class="time-nav">
                        <div class="header-progress-container header-progress-new">
                            <ol class="header-progress-list">

                                <li class="header-progress-item done first"> <a href="#">Project Details</a></li>
                                <li class="header-progress-item done second"> <a href="#">&nbsp;</a></li>
                                <li class="header-progress-item done third"> <a href="#">&nbsp;</a></li>
                                <li class="header-progress-item todo forth "> <a href="#">&nbsp;</a></li>
                                <li class="header-progress-item todo done"> Submit</li>

                            </ol>
                        </div>
                    </div>
                    <br>
                    <div class="tab-content-body">


                        <div class="step-two" style="display: none;">
                            <div class="row">
                                <div class="col-md-8 col-sm-8">
                                    <div class="border-table shadow left-box">
                                        <h2>Liability Limitation</h2>
                                        <p>
                                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                            Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                            unknown printer took a galley of type and scrambled it to make a type specimen
                                            book. It has survived not only five centuries, but also the leap into electronic
                                            typesetting, remaining essentially unchanged. Lorem Ipsum is simply dummy text
                                            of the printing and typesetting industry. Lorem Ipsum has been the industry's
                                            standard dummy text ever since the 1500s, when an unknown printer took a galley
                                            of type and scrambled it to make a type specimen book. It has survived not only
                                            five centuries, but also the leap into electronic typesetting, remaining
                                            essentially unchanged.
                                        </p>
                                        <div class="checkbox-area">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                                <label class="form-check-label" for="exampleCheck1">By checking this box I
                                                    agree to these terms</label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="border-table shadow right-box">
                                        <div class="center-part">
                                            <h2>PROJECT STATE</h2>
                                            <a href="#">CHOOSE ONE</a>
                                        </div>
                                    </div>
                                    <div class="tab-right center-part">

                                        <a href="#"><img src="{{ env('ASSET_URL') }}/images/next-step.jpg"
                                                alt="img"></a>

                                    </div>
                                </div>
                            </div>
                        </div>






                        <div class="step-three" style="display: none;">
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
                                                                    <input type="radio" id="f-option" name="selector">
                                                                    <label for="f-option">Materials Only</label>
                                                                    <div class="check"></div>
                                                                </li>
                                                                <li>
                                                                    <input type="radio" id="s-option" name="selector">
                                                                    <label for="s-option">Labor Only</label>
                                                                    <div class="check">
                                                                        <div class="inside"></div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <input type="radio" id="t-option" name="selector">
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
                                                                    <input type="radio" id="f-option1" name="selector">
                                                                    <label for="f-option1">Yes</label>
                                                                    <div class="check"></div>
                                                                </li>
                                                                <li>
                                                                    <input type="radio" id="s-option1" name="selector">
                                                                    <label for="s-option1">No</label>
                                                                    <div class="check">
                                                                        <div class="inside"></div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="upload-btn-wrapper">
                                                                        <button class="btn">If Yes Contract<br>
                                                                            Dates</button>
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
                                                                        <input type="file" name="myfile" />
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
                                            <li><a
                                                    href="http://dev.nlb.local/member/project/date/view?project_id=1&amp;view=detailed">Skip</a>
                                            </li>
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
                        </div>






                        <div class="step-four" style="display: none;">
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
                                                                    <input id="f-option5" name="selector" type="radio">
                                                                    <label for="f-option5">Multi-Unit Residential</label>
                                                                    <div class="check"></div>
                                                                </li>
                                                                <li>
                                                                    <input id="f-option6" name="selector" type="radio">
                                                                    <label for="f-option6">Subdivision</label>
                                                                    <div class="check"></div>
                                                                </li>
                                                                <li>
                                                                    <input id="f-option7" name="selector" type="radio">
                                                                    <label for="f-option7">New</label>
                                                                    <div class="check"></div>
                                                                </li>
                                                                <li class="big-menu">
                                                                    <input id="f-option8" name="selector" type="radio">
                                                                    <label for="f-option8">Personal Residence</label>
                                                                    <div class="check"></div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-md-6 col-xs-12 spec-col">
                                                            <ul>
                                                                <li class="big-menu">
                                                                    <input id="f-option9" name="selector" type="radio">
                                                                    <label for="f-option9">Municipal Government</label>
                                                                    <div class="check"></div>
                                                                </li>
                                                                <li>
                                                                    <input id="f-option10" name="selector" type="radio">
                                                                    <label for="f-option10">Commercial</label>
                                                                    <div class="check"></div>
                                                                </li>
                                                                <li>
                                                                    <input id="f-option11" name="selector" type="radio">
                                                                    <label for="f-option11">Rehab</label>
                                                                    <div class="check"></div>
                                                                </li>
                                                                <li>
                                                                    <input id="f-option12" name="selector" type="radio">
                                                                    <label for="f-option12">Other</label>
                                                                    <div class="check"></div>
                                                                    <input class="form-control" type="text">
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
                                                            <input class="form-control form-date"
                                                                placeholder="First Day of Work" type="text" id="datepicker">
                                                            to <input class="form-control form-date"
                                                                placeholder="Last Day of Work" type="text" id="datepicker1">
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
                                                                    <input id="f-option5" name="selector" type="radio">
                                                                    <label for="f-option5">Yes</label>
                                                                    <div class="check"></div>
                                                                </li>
                                                                <li>
                                                                    <input id="f-option6" name="selector" type="radio">
                                                                    <label for="f-option6">No</label>
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
                                                                    <input id="f-option20" name="selector" type="radio">
                                                                    <label for="f-option20">Yes</label>
                                                                    <div class="check"></div>
                                                                </li>
                                                                <li>
                                                                    <input id="f-option21" name="selector" type="radio">
                                                                    <label for="f-option21">No</label>
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
                                <div class="col-md-3 col-xs-12">
                                    <div class="tab-right">
                                        <ul>
                                            <li><a
                                                    href="http://dev.nlb.local/member/project/date/view?project_id=1&amp;view=detailed">Skip</a>
                                            </li>
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
                            <div class="row">
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
                            </div>
                        </div>




                        <div class="step-one">
                            <div class="row">
                                <div class="col-md-4 col-sm-4">
                                    <div class="border-table shadow radio-style left-box box-height2 box-height3">
                                        <div class="center-part">
                                            <h2>TYPE OF FILING/CLAIM:</h2>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 col-xs-12 spec-col">

                                                <ul>
                                                    <li class="big-menu">
                                                        <input id="f-option30" name="selector" type="radio">
                                                        <label for="f-option30">Preliminary<br> Notice</label>
                                                        <div class="check"></div>
                                                    </li>
                                                    <li>
                                                        <input id="f-option31" name="selector" type="radio">
                                                        <label for="f-option31">Lien Claim</label>
                                                        <div class="check"></div>
                                                    </li>
                                                    <li>
                                                        <input id="f-option32" name="selector" type="radio">
                                                        <label for="f-option32">Collection</label>
                                                        <div class="check"></div>
                                                    </li>
                                                    <li>
                                                        <input id="f-option33" name="selector" type="radio">
                                                        <label for="f-option33">Releases</label>
                                                        <div class="check"></div>
                                                    </li>
                                                    <li>
                                                        <input id="f-option34" name="selector" type="radio">
                                                        <label for="f-option34">U.C.C. Filling</label>
                                                        <div class="check"></div>
                                                    </li>

                                                </ul>
                                            </div>
                                            <div class="col-md-6 col-xs-12 spec-col">
                                                <ul>
                                                    <li class="big-menu">
                                                        <input id="f-option9" name="selector" type="radio">
                                                        <label for="f-option9">Enrollment of Judgment</label>
                                                        <div class="check"></div>
                                                    </li>
                                                    <li>
                                                        <input id="f-option10" name="selector" type="radio">
                                                        <label for="f-option10">Bond Claim</label>
                                                        <div class="check"></div>
                                                    </li>
                                                    <li class="big-menu">
                                                        <input id="f-option11" name="selector" type="radio">
                                                        <label for="f-option11">Bankruptcy Preference</label>
                                                        <div class="check"></div>
                                                    </li>
                                                    <li>
                                                        <input id="f-option12" name="selector" type="radio">
                                                        <label for="f-option12">Other</label>
                                                        <div class="check"></div>
                                                        <input class="form-control" type="text">
                                                    </li>


                                                </ul>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-8 col-sm-8">
                                    <div class="border-table shadow radio-style left-box box-height2 box-height3">
                                        <div class="center-part">
                                            <h2>CONTRACT INFORMATION</h2>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="center-part contract-center">
                                                    <div class="radio-style">
                                                        <ul>
                                                            <li>
                                                                <input id="f-option41" name="selector" type="radio">
                                                                <label for="f-option41">Written contract</label>
                                                                <div class="check"></div>
                                                            </li>
                                                        </ul>
                                                    </div><br>
                                                    - or -
                                                    <ul>
                                                        <li>
                                                            <input id="f-option42" name="selector" type="radio">
                                                            <label for="f-option42">Verbal contract</label>
                                                            <div class="check"></div>
                                                        </li>
                                                    </ul>
                                                    ----------
                                                    <p>
                                                        Does all extra work relate to original contract?
                                                    </p>
                                                    <div class="radio-style left-style1">
                                                        <ul>
                                                            <li>
                                                                <input id="f-option35" name="selector" type="radio">
                                                                <label for="f-option35">Yes</label>
                                                                <div class="check"></div>
                                                            </li>
                                                            <li>
                                                                <input id="f-option36" name="selector" type="radio">
                                                                <label for="f-option36">No</label>
                                                                <div class="check"></div>
                                                            </li>

                                                        </ul>

                                                    </div>



                                                </div>
                                            </div>
                                            <div class="col-md-9 border1-left">
                                                <div class="border-table12">
                                                    <div class="table-responsive amount-table">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <td>Base Contract Amount</td>
                                                                    <th class="align-right spc-td">
                                                                        $35,000.00
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>+ Value of Extras of Changes</td>
                                                                    <td class="spc-td">$4,000.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>= Revised Contract Subtotal</td>
                                                                    <td class="spc-td">$39,000.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>- Payments/Credits to Date</td>
                                                                    <td class="spc-td">$10.00</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>= Total Claim Amount </td>
                                                                    <td class="spc-td"> $38,000.00</td>
                                                                </tr>
                                                            </tbody>

                                                        </table>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 col-md-offset-4">
                                    <div class="row bottom-upload">
                                        <div class="col-sm-3">
                                            <div class="bottom-upload">
                                                <div class="upload-btn-wrapper">
                                                    <button class="btn">Upload</button>
                                                    <input name="myfile" type="file">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="file-msg">
                                                <p>Attach Delivery Tickets</p>
                                                <p>Project Certificate</p>
                                                <p>Notice of Completion</p>
                                                <p>Notice of Acceptance</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="tab-right">
                                                <ul>
                                                    <li><a href="#">Skip</a></li>
                                                    <li>
                                                        <a href="#">
                                                            <img src="{{ env('ASSET_URL') }}/images/next-step.jpg"
                                                                alt="img">
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
                                </div>
                            </div>
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
    {{-- </div>
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
                                    <button type="button" class="btn btn-info btn-md" id="first_back_button">PREVIOUS STEP</button>
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
                                                               class="form-control1" style="display: none;"></li>
                                                </ul>
                                            </div>
                                        </div>
                                        {{ csrf_field() }}
                                        <input type="hidden" name="claim_id" id="claim_id" value="0">
                                        <div class="form-group" style="text-align:center">
                                            <button type="button" class="btn btn-info btn-md" id="second_back_button">PREVIOUS STEP</button>
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
    </section> --}}

@endsection

@section('script')
    <script>
        $(document).ready(function() {

            // $('.first-step').hide();
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
    </script>
@endsection
