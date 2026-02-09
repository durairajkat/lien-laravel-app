<!-- Extends main layout form layout folder -->
@extends('layout.main')

<!-- Addind Dynamic layout -->
@section('title', 'Project customer')

<!-- Main Content -->
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Project Management
                <small>Customer</small>
            </h1>
        </section>
        <section class="content">
            @if (Session::has('success'))
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">List of all Customers</h3>
                            <button class="btn btn-md addTier" data-type="add" type="button">Add new Customers</button>

                            <div class="box-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" id="tier_search"
                                        value="{{ isset($_GET['projectTier']) && $_GET['projectTier'] != '' ? $_GET['projectTier'] : '' }}"
                                        class="form-control pull-right" placeholder="Search">

                                    <div class="input-group-btn">
                                        <button type="button" data-type="tier" class="btn btn-default search"><i
                                                class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-striped">
                                <tr>
                                    <th>#</th>
                                    <th>Role</th>
                                    <th>Customer</th>
                                    <th>Customer Code</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                                @if (count($tiers) > 0)
                                    @foreach ($tiers as $key => $tier)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $tier->role->project_roles }}</td>
                                            <td>{{ $tier->customer->name }}</td>
                                            <td>
                                                {{ $tier->tier_code }}
                                            </td>
                                            <td>
                                                {{ $tier->tier_limit }}
                                            </td>
                                            <td>
                                                <button class="btn btn-info btn-xs addTier"
                                                    data-role="{{ $tier->role_id }}"
                                                    data-limit="{{ $tier->tier_limit }}"
                                                    data-code="{{ $tier->tier_code }}" data-id="{{ $tier->id }}"
                                                    data-type="edit" type="button" title="Edit Tier"><i
                                                        class="fa fa-pencil"></i>
                                                </button>
                                                <button class="btn btn-danger btn-xs delete" type="button" data-type="tier"
                                                    data-id="{{ $tier->id }}" title="Delete Tier">
                                                    <i class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No Tier available.</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                {{ $tiers->links() }}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('modal')
    <div id="addTierModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span class="tierName"></span></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="role_id" class="col-sm-4 control-label">Role</label>
                                <div class="col-sm-8">
                                    <select name="role_id" id="role_id" class="form-control error">
                                        <option value="">Select a Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->project_roles }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="customer_id" class="col-sm-4 control-label">Customer</label>
                                <div class="col-sm-8">
                                    <select name="customer_id" id="customer_id" class="form-control error">
                                        <option value="">Select a Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tierLimit" class="col-sm-4 control-label">Customer Limit</label>

                                <div class="col-sm-8">
                                    <input type="text" class="form-control error" id="tierLimit"
                                        placeholder="Customer Limit">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="tierCode" class="col-sm-4 control-label">Customer Code</label>

                                <div class="col-sm-8">
                                    <input type="text" class="form-control error" id="tierCode" placeholder="Customer Code">
                                </div>
                            </div>
                            <input type="hidden" id="tierId">
                        </div>
                        <div class="form-group error-tier-field" style="display: none; color: red;">
                            <label for="error" class="col-sm-4 control-label">Error</label>

                            <div class="col-sm-8">
                                <span id="error-tier"></span>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="reset" class="btn btn-default">Reset</button>
                            <button type="button" class="btn btn-info pull-right" id="addTierButton"><i
                                    class="fa fa-spinner fa-spin loader" style="display: none;"></i>
                                Submit
                            </button>
                        </div>
                        <!-- /.box-footer -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.error').on('keyup', function() {
            $(this).parent().parent('div').removeClass('has-error');
            $(this).parent('div').children('.help-block').remove();
            $('.error-tag-field').hide();
            $('.error-tier-field').hide();
        });
        $('.error').on('change', function() {
            $(this).parent().parent('div').removeClass('has-error');
            $(this).parent('div').children('.help-block').remove();
            $('.error-tag-field').hide();
            $('.error-tier-field').hide();
        });
        $('#tier_search').keyup(function(e){
            if(e.keyCode == 13)
            {
                var tier = $('#tier_search').val();
                var location = appendToQueryString('projectTier', tier);
                window.location.href = location;
            }
        });
        $('#role_search').keyup(function(e){
            if(e.keyCode == 13)
            {
                var role = $('#role_search').val();
                var location = appendToQueryString('projectRole', role);
                window.location.href = location;
            }
        });
        $('#type_search').keyup(function(e){
            if(e.keyCode == 13)
            {
                var type = $('#type_search').val();
                var location = appendToQueryString('projectType', type);
                window.location.href = location;
            }
        });
        $('.search').on('click', function() {
            var type = $(this).data('type');
            if (type == 'role') {
                var role = $('#role_search').val();
                var location = appendToQueryString('projectRole', role);
                window.location.href = location;
            } else if (type == 'tier') {
                var tier = $('#tier_search').val();
                var location = appendToQueryString('projectTier', tier);
                window.location.href = location;
            } else {
                var type = $('#type_search').val();
                var location = appendToQueryString('projectType', type);
                window.location.href = location;
            }
        });
        $('.delete').on('click', function() {
            var id = $(this).data('id');
            var type = $(this).data('type');
            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false
            }).then(function() {
                $.ajax({
                    type: "POST",
                    url: "{{ route('management.delete') }}",
                    data: {
                        id: id,
                        type: type,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (data.status) {
                            swal({
                                position: 'center',
                                type: 'success',
                                title: type + ' deleted successfully',
                            }).then(function() {
                                window.location.reload();
                            });

                        } else {
                            swal(
                                'Error',
                                data.message,
                                'error'
                            )
                        }
                    }
                });
            });
        });
        $('.addTier').on('click', function() {
            var type = $(this).data('type');
            $('.error-tier-field').hide();
            $('#addTierButton').find('form').trigger('reset');
            if (type == 'edit') {
                var role = $(this).data('role');
                $('#role_id').val(role);
                var customer_id = $(this).data('customer_id');
                $('#customer_id').val(customer_id);
                var limit = $(this).data('limit');
                $('#tierLimit').val(limit);
                var code = $(this).data('code');
                $('#tierCode').val(code);
                var id = $(this).data('id');
                $('#tierId').val(id);
                $('.tierName').text('Edit Customer');
                $('#addTierButton').attr('data-type', 'edit');
            } else {
                $('.tierName').text('Add Customer');
                $('#addTierButton').attr('data-type', 'add');
            }
            $('#addTierModal').modal('show');
        });
        $('#addTierButton').on('click', function() {
            var role_id = $('#role_id').val();
            if (role_id == '') {
                $('#role_id').parent().parent('div').addClass('has-error');
                $('#role_id').parent('div').append('<span class="help-block">Please select a role</span>');
                return false;
            }
            var customer_id = $('#customer_id').val();
            if (customer_id == '') {
                $('#customer_id').parent().parent('div').addClass('has-error');
                $('#customer_id').parent('div').append('<span class="help-block">Please select a customer</span>');
                return false;
            }
            var tierLimit = $('#tierLimit').val();
            if (tierLimit == '') {
                $('#tierLimit').parent().parent('div').addClass('has-error');
                $('#tierLimit').parent('div').append('<span class="help-block">Please enter a tier limit</span>');
                return false;
            }
            var tierCode = $('#tierCode').val();
            if (tierCode == '') {
                $('#tierCode').parent().parent('div').addClass('has-error');
                $('#tierCode').parent('div').append('<span class="help-block">Please enter a tier code</span>');
                return false;
            }
            var id = $('#tierId').val();
            var type = $(this).data('type');
            $.ajax({
                type: "POST",
                url: "{{ route('add.project.tier') }}",
                data: {
                    id: id,
                    type: type,
                    role_id: role_id,
                    customer_id: customer_id,
                    tierLimit: tierLimit,
                    tierCode: tierCode,
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    $('.loader').show();
                },
                complete: function() {
                    $('.loader').hide();
                },
                success: function(data) {
                    if (data.status == true) {
                        window.location.reload();
                    } else {
                        $('#error-tier').text(data.message);
                        $('.error-tier-field').show();
                    }

                }
            });
        });
    </script>
@endsection
