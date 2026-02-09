@extends('basicUser.layout.main')

@section('title', 'New Claim')

@section('content')
    <section class="bodypart">
        <div class="container">

            <input type="hidden" name="step" value="4">
            <input type="hidden" name="id" value="{{ $id or '' }}">
            <div class="row">
                <div class="col-md-10 col-sm-12 col-md-offset-1">
                    <div class="center-part">
                        <h1><a href="#"><span>i</span>Company™ Claim Data</a></h1>
                    </div>
                    <br>
                    <div class="project-name"><input type="text" name="project_name" value="{{ $project_name or '' }}">
                    </div>

                    <div class="time-nav">
                        <div class="header-progress-container header-progress-new">
                            <ol class="header-progress-list">

                                <li class="header-progress-item done first"><a
                                        href="{{ route('admin.new.claim_step1') }}">Project Details</a></li>
                                <li class="header-progress-item done second"><a
                                        href="{{ route('admin.new.claim_step2') }}">&nbsp;</a></li>
                                <li class="header-progress-item done third"><a
                                        href="{{ route('admin.new.claim_step3') }}">&nbsp;</a></li>
                                <li class="header-progress-item done forth "><a
                                        href="{{ route('admin.new.claim_step4') }}">&nbsp;</a></li>
                                <li class="header-progress-item done done"> Submit</li>

                            </ol>
                        </div>
                    </div>
                    <br>
                    <div class="tab-content-body">
                        <div class="tab-right center-part">

                            <!-- <a href="#"><img src="{{ env('ASSET_URL') }}/images/next-step.jpg" alt="img"></a> -->

                            <!--  <img src="{{ env('ASSET_URL') }}/images/download (1).jpeg" alt="img"> -->
                            @if ($success != 'null')
                                <p class="alert alert-success">{{ $success }}</p>
                                <div><a href="{{ route('member.dashboard') }}" class="btn btn-info">Back To Dashboard</a>
                                </div>

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
