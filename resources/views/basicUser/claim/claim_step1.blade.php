@extends('basicUser.layout.main')

@section('title', 'New Claim')

@section('content')


    <section class="bodypart">
        <div class="container">
            <form method="post" id="claim_data_sheet" action="{{ route('submit.new.claim') }}"
                enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="step" value="1">
                <div class="row">
                    <div class="col-md-10 col-sm-12 col-md-offset-1">
                        <div class="center-part">
                            <h1><a href="#"><span>i</span>Company™ Claim Data</a></h1>
                        </div>
                        <br>
                        <div class="project-name">
                            <input type="text" name="project_name" value="{{ $project->project_name }}">
                        </div>
                        <div class="time-nav">

                            <div class="header-progress-container header-progress-new">
                                <ol class="header-progress-list">

                                    <li class="header-progress-item done first"><a
                                            href="{{ route('admin.new.claim_step1') }}">Project Details</a></li>
                                    <li class="header-progress-item todo second"><a
                                            href="{{ route('admin.new.claim_step2') }}">&nbsp;</a></li>
                                    <li class="header-progress-item todo third"><a
                                            href="{{ route('admin.new.claim_step3') }}">&nbsp;</a></li>
                                    <li class="header-progress-item todo forth "><a
                                            href="{{ route('admin.new.claim_step4') }}">&nbsp;</a></li>
                                    <li class="header-progress-item todo done"> Submit</li>

                                </ol>
                            </div>
                        </div>
                        <br>
                        <div class="tab-content-body">

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
                                                            <input id="f-option30" name="filing_type" type="radio"
                                                                value="Preliminary">
                                                            <label for="f-option30">Preliminary<br> Notice</label>
                                                            <div class="check"></div>
                                                        </li>
                                                        <li>
                                                            <input id="f-option31" name="filing_type" type="radio"
                                                                value="Lien">
                                                            <label for="f-option31">Lien Claim</label>
                                                            <div class="check"></div>
                                                        </li>
                                                        <li>
                                                            <input id="f-option32" name="filing_type" type="radio"
                                                                value="Collection">
                                                            <label for="f-option32">Collection</label>
                                                            <div class="check"></div>
                                                        </li>
                                                        <li>
                                                            <input id="f-option33" name="filing_type" type="radio"
                                                                value="Releases">
                                                            <label for="f-option33">Releases</label>
                                                            <div class="check"></div>
                                                        </li>
                                                        <li>
                                                            <input id="f-option34" name="filing_type" type="radio"
                                                                value="U.C.C.">
                                                            <label for="f-option34">U.C.C. Filling</label>
                                                            <div class="check"></div>
                                                        </li>

                                                    </ul>
                                                </div>
                                                <div class="col-md-6 col-xs-12 spec-col">
                                                    <ul>
                                                        <li class="big-menu">
                                                            <input id="f-option9" name="filing_type" type="radio"
                                                                value="Judgment">
                                                            <label for="f-option9">Enrollment of Judgment</label>
                                                            <div class="check"></div>
                                                        </li>
                                                        <li>
                                                            <input id="f-option10" name="filing_type" type="radio"
                                                                value="Bond">
                                                            <label for="f-option10">Bond Claim</label>
                                                            <div class="check"></div>
                                                        </li>
                                                        <li class="big-menu">
                                                            <input id="f-option11" name="filing_type" type="radio"
                                                                value="Bankruptcy Preference">
                                                            <label for="f-option11">Bankruptcy Preference</label>
                                                            <div class="check"></div>
                                                        </li>
                                                        <li>
                                                            <input id="f-option12" name="filing_type" type="radio"
                                                                value="Other">
                                                            <label for="f-option12">Other</label>
                                                            <div class="check"></div>
                                                            <input class="form-control" name="other_data" type="text"
                                                                id="other_data">
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
                                                                    <input id="f-option41" name="contract_type" type="radio"
                                                                        value="Written">
                                                                    <label for="f-option41">Written contract</label>
                                                                    <div class="check"></div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <br>
                                                        - or -
                                                        <ul>
                                                            <li>
                                                                <input id="f-option42" name="contract_type" type="radio"
                                                                    value="Verbal">
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
                                                                    <input id="f-option35" name="original" type="radio"
                                                                        value="yes">
                                                                    <label for="f-option35">Yes</label>
                                                                    <div class="check"></div>
                                                                </li>
                                                                <li>
                                                                    <input id="f-option36" name="original" type="radio"
                                                                        value="no">
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
                                                                        <th>$ <input type="number" name="base_amount"
                                                                                id="base_amount"
                                                                                value="{{ isset($project->project_contract) && $project->project_contract != '' && $project->project_contract->base_amount != '' ? $project->project_contract->base_amount : '0' }}">
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>+ Value of Extras of Changes</td>
                                                                        <th>$ <input type="number" name="extra_amount"
                                                                                id="extra_amount"
                                                                                value="{{ isset($project->project_contract) && $project->project_contract != '' && $project->project_contract->extra_amount != '' ? $project->project_contract->extra_amount : '0' }}">
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>= Revised Contract Subtotal</td>
                                                                        <th>$ <input type="text" id="contact_total"
                                                                                disabled="disabled"></th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>- Payments/Credits to Date</td>
                                                                        <th>$ <input type="number" name="payment"
                                                                                id="payment"
                                                                                value="{{ isset($project->project_contract) && $project->project_contract != '' && $project->project_contract->credits != '' ? $project->project_contract->credits : '0' }}">
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>= Total Claim Amount</td>
                                                                        <th>$ <input type="text" id="claim_amount"
                                                                                name="claim_amount" disabled="disabled">
                                                                        </th>
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
                                        <div class="row ">
                                            <div class="col-sm-3">
                                                <div class="bottom-upload">
                                                    <div class="upload-btn-wrapper">
                                                        <button class="btn">Upload</button>
                                                        <input type="file" name="notice_step1" id="file"
                                                            onchange="javascript:product()">

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
                                                <div id="fileList"></div>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="tab-right">
                                                    <ul>
                                                        <li><a href="{{ route('admin.new.claim_step2') }}">Skip</a></li>
                                                        <li>
                                                            <button type="submit" class="next-btn">
                                                                <!-- <img src="{{ env('ASSET_URL') }}/images/next-step.jpg" alt="img"> -->
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
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
                console.log("hello");
                totalAmount();
                //console.log(base_amount);

            });

            function totalAmount() {
                base_amount = $('#base_amount').val();
                console.log(base_amount);
                extra_amount = $('#extra_amount').val();
                payment = $('#payment').val();
                total = parseFloat(base_amount) + parseFloat(extra_amount);
                claim_total = parseFloat(total) - parseFloat(payment);
                $('#contact_total').val(parseFloat(total));
                $('#claim_amount').val(parseFloat(claim_total));
            }

            // $('#f-option12').on('click', function () {
            //  // alert('hii');
            //     //$('.first-step').hide();
            //     $('#other_data').show();
            //     //$('.third-step').hide();
            // });
            $('input[name="filing_type"]').on('click', function() {
                if ($('#f-option12').prop('checked')) {
                    $('#other_data').show();
                } else {
                    $('#other_data').hide();
                }
            });
            $("#data_sheet").click(function() {

                // show Modal
                $('#myModal').modal('show');
            });
            $("#save_project").click(function() {

                // show Modal
                window.location.href = "{{ route('admin.new.claim_data_sheet_new') }}"
            });
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

        product = function() {
            var input = document.getElementById('file');
            var output = document.getElementById('fileList');

            //output.innerHTML = '<ul>';
            for (var i = 0; i < input.files.length; ++i) {
                output.innerHTML += input.files.item(i).name + ' file attached';
            }
            //output.innerHTML += '</ul>';
        }
        //     function readURL(input) {
        //     if (input.files && input.files[0]) {
        //         var reader = new FileReader();

        //         reader.onload = function (e) {
        //             $('#blah')
        //                 .attr('src', e.target.result)
        //                 .width(150)
        //                 .height(200);
        //         };

        //         reader.readAsDataURL(input.files[0]);
        //     }
        // }
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
