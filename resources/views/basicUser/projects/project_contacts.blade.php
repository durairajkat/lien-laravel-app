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
    <span id="stepNumDetailed" data-step="6"></span>

    @include('basicUser.partials.multi-step-form')

    @if (isset($_GET['edit']))
        <span id="editFlag"></span>
        <form action="#" method="post"
            class="form-horizontal project-form project_details create-project-form form-no-padding create-project-form--edit">
        @else
            <form action="#" method="post" class="form-horizontal project-form project_details create-project-form" style="min-height: 100vh">
    @endif
    @if (Session::get('express'))
        <div class="buttons-on-top row button-area">

            <a href="{{ route('get.express.job.info.sheet', [$project_id]) }}" id="" class="project-create-continue">
                Save & Continue
            </a>
        </div>
    @else
        <div class="buttons-on-top row button-area">
            <a href="javascript:void(0)" id="skip-button-6-out" class="skip-job-information project-create-skip skip">
                Skip
            </a>

            <a href="javascript:void(0)" id="saveContacts" class="project-create-continue">
                Save & Continue
            </a>
        </div>
    @endif


    <div class="create-project-form-bgcolor">
        <div class="create-project-form-header">
            @if (!isset($_GET['edit']))
                <h2>Add Contacts</h2>
            @else
                <h2>Edit Contacts</h2>
                @if (isset($_GET['edit']) and $mobile)
                    <span class="mobile-nav--menu" onclick="openNav()" data-target="detailed"><i class="fa fa-ellipsis-v"
                            aria-hidden="true"></i></span>
                @endif
            @endif
        </div>

        @if (isset($_GET['edit']))
            <div class="form-padding-wrapper match-width">
        @endif
        <input class="tab-view" type="hidden" data-type="express">
        @if (isset($project->project_name))
            <input type="hidden" name="project_name"
                value="{{ isset($project->project_name) ? $project->project_name : '' }}">
        @endif
        {{ csrf_field() }}
        <input type="hidden" name="form_type" value="contactOnly">
        {{-- <input type="hidden" name="project_id" value="{{ isset($project->project_name) ? $project->project_name :'0' }}"> --}}
        <input type="hidden" name="project_id" value="{{ isset($project) && $project_id > 0 ? $project_id : '0' }}">
        <div class="tab-content-body except-detailed">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="border-table">
                                <label for="customer-contract">Customer Information</label>
                                <select name="customer_contract" class="form-control customer-contract"
                                    id="customer-contract">
                                    <option value="">Select a customer contact</option>
                                    @foreach ($customerContracts as $customerContract)
                                        <option company="{{ $customerContract->company->company }}"
                                            value="{{ $customerContract->id }}"
                                            {{ isset($project) && $project != '' && $project->customer_contract_id == $customerContract->id ? 'selected' : '' }}>
                                            {{ $customerContract->contacts->first_name . ' ' . $customerContract->contacts->last_name . ' ( ' . $customerContract->company->company . ' ) ' }}
                                        </option>
                                    @endforeach
                                </select>
                                <a class="addNew project-overview-button project-overview-button--green"
                                    data-type="Customer" type="button">Add New Customer
                                </a>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <table id="customer_contact_table" class="table activecase table-style">
                                <thead>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </thead>
                                <tbody id="customer_contact_table_body">
                                    @foreach ($customerContracts as $customerContract)
                                        @if (isset($project) && $project != '' && $project->customer_contract_id == $customerContract->id)
                                            <tr>
                                                <td>
                                                    {{ $customerContract->contacts->first_name . ' ' . $customerContract->contacts->last_name }}
                                                </td>

                                                <td>
                                                    {{ isset($customerContract->company->company) && $customerContract->company->company != '' ? $customerContract->company->company : 'N/A' }}
                                                </td>

                                                <td>
                                                    {{ isset($customerContract->contacts->phone) && $customerContract->contacts->phone != '' ? $customerContract->contacts->phone : 'N/A' }}
                                                </td>

                                                <td>
                                                    {{ isset($customerContract->contacts->email) && $customerContract->contacts->email != '' ? $customerContract->contacts->email : 'N/A' }}
                                                </td>

                                                <td>
                                                    <i data-id="{{ $customerContract->contacts->id }}"
                                                        data-type="Customer" title="Edit"
                                                        class="fa fa-edit editContact"></i>
                                                    <i data-id="{{ $customerContract->contacts->id }}"
                                                        data-type="Customer" title="Delete"
                                                        class="fa fa-trash deleteContact"></i>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <div class="border-table">
                                <label for="industry_contract">Project Contacts</label>
                                @php($contactsF = isset($project) && $project != '' ? ($project->industryContacts ? $project->industryContacts->pluck('contactId') : '') : '')
                                <select name="industry_contract[]" class="form-control industry-contract"
                                    id="industry-contract" multiple>
                                    @foreach ($industryContracts as $industryContract)
                                        <option company="{{ $industryContract->company->company }}"
                                            contact_type="{{ $industryContract->contacts->contact_type }}"
                                            value="{{ $industryContract->id }}"
                                            {{ isset($contactsF) && $contactsF != '' && count($contactsF) > 0 ? (in_array($industryContract->id, $contactsF->toArray()) ? 'selected' : '') : '' }}>
                                            {{ $industryContract->contacts->first_name . ' ' . $industryContract->contacts->last_name . ' : ' . $industryContract->contacts->contact_type . ' ( ' . $industryContract->company->company . ' ) ' }}
                                        </option>
                                    @endforeach
                                </select>

                                <a class="addNew project-overview-button project-overview-button--green"
                                    data-type="Industry" type="button">
                                    Add New Contact
                                </a>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12">
                            <table id="industry_contact_table" class="table activecase table-style">
                                <thead>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Company</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </thead>

                                <tbody id="industry_contact_table_body">
                                    @foreach ($industryContracts as $industryContract)
                                        @if (in_array($industryContract->id, $contactsF->toArray()))
                                            <tr>
                                                <td>
                                                    {{ $industryContract->contacts->first_name . ' ' . $industryContract->contacts->last_name }}
                                                </td>

                                                <td>
                                                    {{ $industryContract->contacts->contact_type }}
                                                </td>

                                                <td>
                                                    {{ $industryContract->company->company }}
                                                </td>

                                                <td>
                                                    {{ $industryContract->contacts->phone }}
                                                </td>

                                                <td>
                                                    {{ $industryContract->contacts->email }}
                                                </td>

                                                <td>
                                                    <i data-id="{{ $industryContract->contacts->id }}"
                                                        data-type="Industry" title="Edit"
                                                        class="fa fa-edit editContact"></i>
                                                    <i data-id="{{ $industryContract->contacts->id }}"
                                                        data-type="Industry" title="Delete"
                                                        class="fa fa-trash deleteContact"></i>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="flex items-center save-skip">
        @if (!isset($_GET['edit']))
        @endif
        @if (!isset($_GET['view']) || $_GET['view'] === 'express')
            @if (!isset($_GET['edit']))
                <a href="javascript:void(0)" id="activate-step-2" class="project-create-continue">
                    View Job Information Sheet
                </a>
            @else

                @if (Session::get('express'))
                    <div class="buttons-on-top row button-area">

                        <a href="{{ route('get.express.job.info.sheet', [$project_id]) }}" id=""
                            class="project-create-continue">
                            Save & Continue
                        </a>
                    </div>
                @else
                    <a href="javascript:void(0)" id="skip-button-6-out"
                        class="skip">
                        Skip
                    </a>

                    <a href="javascript:void(0)" id="saveContacts" class="orange-btn">
                        Save & Continue
                    </a>
                @endif


            @endif
        @else
            @if (isset($_GET['edit']))
                <a href="javascript:void(0)" id="activate-step-2" class="project-create-continue">
                    Save
                </a>
            @else
                <a href="javascript:void(0)" id="activate-step-2" class="project-create-continue">
                    View Job Information Sheet
                </a>
            @endif
        @endif
        <div class="tab-right">
            @if (isset($_GET['edit']))
                <ul>
                    @if ($project_id)
                        <li>

                        </li>
                    @endif
                </ul>
            @endif
        </div>
    </div>
    </form>
    </div>
@endsection

@section('modal')
    @include('basicUser.modals.contact_modal',['companies' => $companies,'first_names' => $first_names])
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('html, body').animate({
                scrollTop: $('#progressbar').offset().top
            }, 'slow');

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

            $('.skip').on('click', function(event) {
                console.log('skip-button-6-out');
                //event.stopPropagation();
                event.preventDefault();
                swal({
                    title: 'Are you sure you want to skip this?',
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
                    window.location.href = '/member/project/project-documents/' + project_id +
                        '?edit=true';
                })
            });
            // 10-10-2021

            $(document).on('click', '#saveContacts', function(event) {
                event.preventDefault();

                window.location.href = "{{ route('get.project.documents', $project->id) . '?edit=true' }}";
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

    <script>
        var saveSession = "{{ route('project.save.session') }}";
        var submitContactForm = "{{ route('customer.submit.contact') }}";
        var userId = '{{ Auth::user()->id }}';
        var token = '{{ csrf_token() }}';
        var projectSubmit = "{{ route('project.contact.submit') }}";
        var customerContacts = @json($customerContracts);
        var industryContacts = @json($industryContracts);

        @if (isset($_GET['edit']))
            let edit = true
        @else
            let edit = false
        @endif

        var remedyURL = "{{ route('create.remedydates', [0]) }}"
        var view = '{{ isset($_GET['view']) ? $_GET['view'] : 'express' }}';
        var lienUrl = "{{ route('project.lien.view') }}";
        var contractUrl = "{{ route('get.job.info.sheet', [$project_id]) }}";
        var memberProject = '{{ route('member.project') }}';
        var checkRole = "{{ route('project.role.check') }}";
        var checkQuestion = "{{ route('project.check.question') }}";
        var project_id = '{{ isset($project_id) ? $project_id : '0' }}';
        var condition = '0';
        var autoCompleteCompanyOnRoleChange = "{{ route('autocomplete.contact.company.rolechange') }}";
        var fetchCompanies = "{{ route('fetch.companies') }}";
        var deleteContacts = "{{ route('customer.delete.contact') }}";
        @if ((Session::has('state') && Session::get('state') > 0 && Session::has('role') && Session::get('role') > 0 && Session::has('customer') && Session::get('customer') > 0 && Session::has('projectType') && Session::get('projectType') > 0) || (isset($project) && $project_id > 0))
            condition = '1';
        @endif
        var answer1 = "{!! isset($project->answer1) ? $project->answer : '' !!}";
        var answer2 = "{!! isset($project->answer2) ? $project->answer2 : '' !!}";
        var checkName = "{{ route('project.name.check') }}";
        var url = '{{ env('ASSET_URL') }}';
        var page = 'project_details';
        var autoComplete = "{{ route('autocomplete.contact.company') }}";
        var autoCompleteCompany = "{{ route('autocomplete.company') }}";
        var addFileUrl = "{{ route('add.job.info.file') }}";
        var baseUrl = "{{ env('ASSET_URL') }}";
        var customerContactRoute = "{{ route('customer.submit.contact') }}";
        var user_id = "{{ Auth::user()->id }}";
        var autoCompleteContact = "{{ route('autocomplete.contact.firstname') }}";
        var getContactDetailsURL = "{{ route('autocomplete.contact.details') }}";
        var editJobDescriptionRoute = "{{ route('edit.job.description') }}";
        var getContactData = "{{ route('get.contact.data') }}";
        var newContacts = "{{ route('create.new.contacts') }}";
    </script>

    <script src="{{ env('ASSET_URL') }}/js/project_details.js"></script>
    <script src="{{ env('ASSET_URL') }}/js/modals/contacts/contact.js"></script>
    <script src="{{ env('ASSET_URL') }}/js/createProject.js"></script>
    <script>
        $(document).ready(function() {
            $('#addCustomerModel input').mousedown(function() {
                $(this).attr('autocomplete', 'chrome-off')
            });
            $('#addCustomerModel textarea').mousedown(function() {
                $(this).attr('autocomplete', 'chrome-off')
            });
        })
    </script>
@endsection
