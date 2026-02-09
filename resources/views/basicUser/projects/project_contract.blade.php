@extends('basicUser.projects.create')

@section('body')
    @php
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $mobile =
        preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) ||
        preg_match(
            '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
            substr($useragent, 0, 4),
        );
    @endphp
    <span id="stepNumDetailed" data-step="5"></span>

    @include('basicUser.partials.multi-step-form')

    @if (isset($_GET['edit']))
        <span id="editFlag"></span>
        <form action="#" method="post"
            class="project_contracts form-horizontal project-form project_details create-project-form form-no-padding create-project-form--edit">
        @else
            <form action="#" method="post"
                class="project_contracts form-horizontal project-form project_details create-project-form">
    @endif

    <div class="buttons-on-top row button-area">
        @if (!isset($_GET['edit']))
            <a href="javascript:void(0)" id="activate-step-3" class="project-create-continue ">
                Add Contacts
            </a>
        @else
            <a href="javascript:void(0)" id="skip-button-5-out" class="skip-contacts project-create-skip">
                Skip
            </a>
            <a href="javascript:void(0)" id="activate-step-3-out" class="project-create-continue ">
                Save & Continue
            </a>
        @endif
    </div>

    <div class="create-project-form-bgcolor">
        <div class="create-project-form-header">
            <h2>Contract Details</h2>
            @if (isset($_GET['edit']) and $mobile)
                <span class="mobile-nav--menu" onclick="openNav()" data-target="detailed"><i class="fa fa-ellipsis-v"
                        aria-hidden="true"></i></span>
            @endif
        </div>
        @if (isset($_GET['edit']))
            <div class="form-padding-wrapper match-width">
        @endif
        <div class="contract--wrapper">
            <label>Base Contract Amount</label>
            <input class="form-control" type="number" name="base_amount" id="base_amount"
                value="{{ isset($contract) && $contract != '' && $contract->base_amount != '' ? $contract->base_amount : '0' }}">
            <label>+ Additional Costs</label>
            <input class="form-control" type="number" name="extra_amount" id="extra_amount"
                value="{{ isset($contract) && $contract != '' && $contract->extra_amount != '' ? $contract->extra_amount : '0' }}">
            <label>Revised Cost</label>
            <input class="form-control" type="text" id="contact_total" disabled="disabled"></th>
            <label>- Payments/Credits</label>
            <input class="form-control" type="number" name="payment" id="payment"
                value="{{ isset($contract) && $contract != '' && $contract->credits != '' ? $contract->credits : '0' }}">
            <label>Unpaid Balance</label>
            <input class="form-control" type="text" id="claim_amount" name="claim_amount" disabled="disabled">
        </div>

        <div class="contract--wrapper">
            <label>Your Job/Project No.</label>
            <input class="form-control" type="number" name="job_no"
                value="{{ isset($contract) && $contract != '' && $contract->job_no != '' ? $contract->job_no : '' }}">
        </div>

        <div class="contract--wrapper">
            <label>General description of materials, services and/or labor furnished</label>
            <textarea class="form-control" type="text"
                name="general_description">{{ isset($contract) && $contract != '' && $contract->general_description != '' ? $contract->general_description : '' }}</textarea>
        </div>

        {{ csrf_field() }}
        <input type="hidden" name="form_type" value="{{ isset($contract) && $contract != '' ? 'edit' : 'add' }}">
        <input type="hidden" name="project_id" value="{{ $project_id }}" class="project_id">

    </div>
    </div>

    <div class="flex items-center save-skip">
        @if (!isset($_GET['edit']))
            <a href="javascript:void(0)" id="activate-step-3" class="project-create-continue ">
                Add Contacts
            </a>
        @else
            <a href="javascript:void(0)" id="skip-button-5" class="skip">
                Skip
            </a>
            <a href="javascript:void(0)" id="activate-step-3" class="orange-btn">
                Save & Continue
            </a>
        @endif
    </div>
    </form>
    </div>
@endsection

@section('script')
    {{-- <script src="{{ env('ASSET_URL') }}/js/create_project.js"></script> --}}
    <script src="{{ env('ASSET_URL') }}/js/createProject.js"></script>
    <script>
        $(document).ready(function() {
            var project_id = {{ $project_id }}

            $(document).on('click', '#save_quit', function(e) {
                e.preventDefault()
                window.location = "{{ route('member.dashboard') }}"
            })

            $(document).on('click', '.mobile-nav-tab', function() {
                let tab = $(this).attr('data-tab')
                $('.mobile-nav--menu').attr('data-target', tab)
            })

            $(document).on('click', '.sidenav', function() {
                $(".sidenav").css('width', '0px');
            })

            // skip buttons
            // 13-aug-2019
            $('#skip-button-5').on('click', function(event) {
                //event.stopPropagation();
                event.preventDefault();
                swal({
                    title: 'Are you sure you want to skip this?',
                    // text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    // buttonsStyling: false
                }).then(function() {
                    window.location.href = '/member/create/projectcontacts/' + project_id +
                        '?edit=true';
                })

            });

        })

        function openNav(e) {
            let menu = $('.mobile-nav--menu').attr('data-target')
            if (menu == 'express') {
                $('#mobileNav').css('width', '100%');
            } else {
                $('#mobileNavDetailed').css('width', '100%')
            }
        }

        function closeNav() {
            $(".sidenav").css('width', '0px');
        }
    </script>
    @php
    if (isset($_GET['edit'])) {
        $contactURL = route('member.create.projectcontacts', [$project->id]) . '?edit=true';
    } else {
        $contactURL = route('member.create.projectcontacts', [$project->id]) . '?view=detailed';
    }
    @endphp

    <script>
        $(document).ready(function() {
            $('html, body').animate({
                scrollTop: $('#progressbar').offset().top
            }, 'slow');

            var base_amount = 0;
            var extra_amount = 0;
            var payment = 0;
            var total = 0;
            var claim_total = 0;
            totalAmount();

            $('#base_amount,#extra_amount,#payment').focus(function() {
                //$(this).val('');//aleks should comment here
            });

            $('#base_amount,#extra_amount,#payment').on('change', function() {
                totalAmount();
            });

            function totalAmount() {
                base_amount = $('#base_amount').val();
                extra_amount = $('#extra_amount').val();
                payment = $('#payment').val();
                total = ((base_amount !== '') ? parseFloat(base_amount) : parseFloat(0)) + ((extra_amount !== '') ?
                    parseFloat(extra_amount) : 0);
                claim_total = ((total !== '') ? parseFloat(total) : 0) - ((payment !== '') ? parseFloat(payment) :
                    0);
                $('#contact_total').val(total);
                $('#claim_amount').val(claim_total);
            }

            $('#activate-step-3, #activate-step-3-out').on('click', function(e) {
                var data = $('.project_contracts').serialize();
                var state = $('#state').val();
                var project_type = $('#project_type').val();
                var tier = $('#customer').val();
                var buttonName = $(this);

                $.ajax({
                    type: "POST",
                    url: "{{ route('project.contract.submit') }}",
                    data: data,
                    success: function(data) {
                        if (data.status) {
                            $.ajax({
                                type: "POST",
                                url: "{{ route('project.get.date') }}",
                                data: {
                                    tier: tier,
                                    state: state,
                                    project_type: project_type,
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(data) {
                                    window.location.href = "{{ $contactURL }}";
                                }
                            });
                        } else {
                            swal(
                                'Error',
                                data.message,
                                'error'
                            );
                        }
                    }
                });
            });

            $('#save_quit').on('click', function(e) {
                var data = $('.project_contracts').serialize();
                var state = $('#state').val();
                var project_type = $('#project_type').val();
                var tier = $('#customer').val();
                var buttonName = $(this);
                console.log(data);
                $.ajax({
                    type: "POST",
                    url: '{{ route('project.contract.submit') }}',
                    data: data,
                    success: function(data) {
                        if (data.status) {
                            $.ajax({
                                type: "POST",
                                url: "{{ route('project.get.date') }}",
                                data: {
                                    tier: tier,
                                    state: state,
                                    project_type: project_type,
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(data) {
                                    window.location.href =
                                        '{{ route('member.project') }}';
                                    buttonName.remove();
                                }
                            });
                        } else {
                            swal(
                                'Error',
                                data.message,
                                'error'
                            );
                        }
                    }
                });
            });
        });
    </script>
@endsection
