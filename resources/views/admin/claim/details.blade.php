<!-- Extends main layout form layout folder -->
@extends('layout.main')
<!-- Addind Dynamic layout -->
@section('title', 'Claim Details')

@section('style')
    <style>
        .border-table {
            background: #fff;
            border: 1px solid #958283;
            border-radius: 10px;
            padding: 10px;
        }

        .border-table {
            margin-bottom: 20px;
        }

        .shadow {
            box-shadow: 1px 2px #535353;
        }

        .text-center {
            text-align: center;
            background: #1084ff;
            color: #fff;
            font-size: 24px;
            margin: 0;
            padding: 7px 19px;
            margin-bottom: 15px;
        }

    </style>
@endsection
<!-- Main Content -->
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Claim Details
            </h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="border-table">
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-md-offset-0">
                                            <div class="border-table">
                                                <h1 class="text-center"> Claim Details</h1>
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6 col-md-offset-3">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Claim Name :
                                                            </a>
                                                            <strong>{{ $claimData->project_name or '' }}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                User Name:
                                                            </a>
                                                            <strong>{{ $claimData->user->name or '' }}</strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Filling Type :
                                                            </a>


                                                            <strong>{{ $claimData->filling_type or '' }}</strong>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Contract Name :
                                                            </a>


                                                            <strong>{{ $claimData->contact_name or '' }}</strong>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Is original :
                                                            </a>


                                                            <strong>{{ $claimData->original or '' }}</strong>


                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Base Amount :
                                                            </a>


                                                            <strong>{{ $claimData->base_amount or '' }}</strong>


                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Extra Amount :
                                                            </a>


                                                            <strong>{{ $claimData->extra_amount or '' }}</strong>


                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Payment :
                                                            </a>


                                                            <strong>{{ $claimData->payment or '' }}</strong>


                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Notice file :
                                                            </a>
                                                            <strong>{{ $claimData->notice_step1 or '' }}</strong>

                                                            @if ($claimData->notice_step1 != '')
                                                                <a href="{{ route('admin.claim.download', [$claimData->notice_step1]) }}"
                                                                    title="{{ $claimData->notice_step1 or '' }}">
                                                                    <i class="fa fa-download"></i>
                                                            @endif
                                                            </a>


                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Status :
                                                            </a>


                                                            <strong>{{ $claimData->status or '' }}</strong>


                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Is custom :
                                                            </a>


                                                            <strong>{{ $claimData->custom }}</strong>


                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Date file :
                                                            </a>
                                                            @if ($claimData->preliminary == 'Yes')
                                                                <strong>{{ $claimData->myfile_date or '' }}</strong>
                                                                @if ($claimData->myfile_date != '')
                                                                    <a href="{{ route('admin.claim.download', [$claimData->myfile_date]) }}"
                                                                        title="{{ $claimData->myfile_date or '' }}">
                                                                        <i class="fa fa-download"></i></a>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Preliminary Notice :
                                                            </a>

                                                            @if ($claimData->preliminary == '')
                                                                <strong>No</strong>
                                                            @else
                                                                <strong>Yes</strong>
                                                            @endif


                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Preliminary File:
                                                            </a>

                                                            @if ($claimData->preliminary == 'Yes')
                                                                <strong>{{ $claimData->myfile_preliminary or '' }}</strong>

                                                                @if ($claimData->myfile_preliminary != '')
                                                                    <a href="{{ route('admin.claim.download', [$claimData->myfile_preliminary]) }}"
                                                                        title="{{ $claimData->myfile_preliminary or '' }}">
                                                                        <i class="fa fa-download"></i></a>
                                                                @endif
                                                            @endif

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Lien :
                                                            </a>

                                                            @if ($claimData->lien == '')
                                                                <strong>No</strong>
                                                            @else
                                                                <strong>Yes</strong>
                                                            @endif


                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Lien File:
                                                            </a>

                                                            @if ($claimData->lien == 'Yes')
                                                                <strong>{{ $claimData->myfile_lien or '' }}</strong>

                                                                @if ($claimData->myfile_lien != '')
                                                                    <a href="{{ route('admin.claim.download', [$claimData->myfile_lien]) }}"
                                                                        title="{{ $claimData->myfile_lien or '' }}">
                                                                        <i class="fa fa-download"></i></a>
                                                                @endif
                                                            @endif

                                                        </div>
                                                    </div>

                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Product File:
                                                            </a>

                                                            @if ($claimData->myfile != '')
                                                                <strong>{{ $claimData->myfile or '' }}</strong>
                                                            @endif
                                                            @if ($claimData->myfile != '')
                                                                <a href="{{ route('admin.claim.download', [$claimData->myfile]) }}"
                                                                    title="{{ $claimData->myfile or '' }}">
                                                                    <i class="fa fa-download"></i></a>
                                                            @endif



                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Construction Type:
                                                            </a>


                                                            <strong>{{ $claimData->construction or '' }}</strong>



                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                First date:
                                                            </a>


                                                            <strong>{{ $claimData->first_date or '' }}</strong>



                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Last date:
                                                            </a>


                                                            <strong>{{ $claimData->last_date or '' }}</strong>



                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Shipping:
                                                            </a>


                                                            <strong>{{ $claimData->shipping or '' }}</strong>



                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Whole Completed:
                                                            </a>


                                                            <strong>{{ $claimData->whole or '' }}</strong>



                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Final file:
                                                            </a>


                                                            <strong>{{ $claimData->myfile_next or '' }}</strong>
                                                            @if ($claimData->myfile_next != '')

                                                                <a href="{{ route('admin.claim.download', [$claimData->myfile_next]) }}"
                                                                    title="{{ $claimData->myfile_next or '' }}">
                                                                    <i class="fa fa-download"></i></a>

                                                            @endif


                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 col-sm-6">
                                                        <div class="border-table shadow">
                                                            <a href="javascript:void(0)">
                                                                Project State
                                                            </a>


                                                            <strong>{{ $claimData->state->name or '' }}</strong>



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
                <!-- /.box -->
            </div>
    </div>
    </section>
    </div>
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
                $('#contact_total').val(total);
                $('#claim_amount').val(claim_total);
            }
        });
    </script>
@endsection
