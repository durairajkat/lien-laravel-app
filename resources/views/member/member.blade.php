<!-- Extends main layout form layout folder -->
@extends('layout.main')
<!-- Addind Dynamic layout -->
@section('title', 'Member')
<!-- Main Content -->
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Members
                <small>Sub User</small>
            </h1>
            <div class="col-md-4 input-group input-group pull-right" style="display: flex">
                <input id="tier_search" value="{{ isset($_GET['search']) ? $_GET['search'] : '' }}"
                       class="form-control pull-right"
                       placeholder="Enter Email/User Name/Name" type="text">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-default search"><i  class="fa fa-search"></i></button>
                </div>
                <button style="margin-left: 50px" class="btn btn-md btn-success member" data-type="Add" type="button" data-toggle="modal">Add new
                    Member
                </button>
            </div>

        </section>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">List of all Members</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
                                <tr>
                                    <th>#</th>
                                    <th>Company name</th>
                                    <th>Company phone number</th>
                                    <th>Name</th>
                                    <th>User name</th>
                                    <th>User phone number</th>
                                    <th>Email</th>
                                    <th>Lien Provider</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                @if (count($users) > 0)
                                    @foreach ($users as $key => $user)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ !is_null($user->details) && !is_null($user->details->getCompany) && !is_null($user->details->getCompany->company) ? $user->details->getCompany->company : 'N/A' }}
                                            </td>
                                            <td>{{ !is_null($user->details) && !is_null($user->details->getCompany) && !is_null($user->details->getCompany->phone) ? $user->details->getCompany->phone : 'N/A' }}
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->user_name }}</td>
                                            <td>{{ !is_null($user->details) && !is_null($user->details->phone) ? $user->details->phone : 'N/A' }}
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @php
                                                    $lienProvider = [];
                                                    $lienProviderArray = '';
                                                    if ($user->details != '' && $user->details->lien_status == '1') {
                                                        $userProvider = \App\Models\MemberLienMap::where('user_id', $user->id)->get();
                                                        //$userProvider = $user->lienProvider;
                                                        /*if($userProvider != '') {
                                                                                                                 foreach ($userProvider as $pro) {
                                                                                                                     foreach ($pro->getLien as $proL) {
                                                                                                                         $lienProvider[] = '<tr><td>'.$proL->company.'</td><td>'.$proL->firstName.'</td><td> '.$proL->lastName.'</td><td>'.$proL->email.'</td><tr>';
                                                                                                                     }
                                                                                                                 }
                                                                                                             }*/
                                                        if (count($userProvider) > 0) {
                                                            foreach ($userProvider as $pro) {
                                                                $proL = $pro->findLien;

                                                                // foreach ($pro->getLien as $proL) {
                                                                //  $lienProvider[] = '<tr><td>'.$proL->company.'</td><td>'.$proL->firstName.'</td><td> '.$proL->lastName.'</td><td>'.$proL->email.'</td></tr>';
                                                                //}
                                                                $lienProvider[] = '<tr><td>' . (!is_null($proL->getUser) && !is_null($proL->getUser->details) && !is_null($proL->getUser->details->getCompany) ? $proL->getUser->details->getCompany->company : 'N/A') . '</td><td>' . $proL->firstName . '</td><td> ' . $proL->lastName . '</td><td>' . $proL->email . '</td></tr>';
                                                            }
                                                        }
                                                    }
                                                    if (count($lienProvider) > 0) {
                                                        $lienProviderArray = implode(' | ', $lienProvider);
                                                    }
                                                @endphp
                                                @if (empty($lienProviderArray))
                                                    NLB
                                                @else
                                                    <button class="btn btn-warning btn-sm viewProvider"
                                                        data-provider="{!! $lienProviderArray !!}" type="button"
                                                        title="View Lien Provider">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                @endif

                                            </td>
                                            <td>
                                                <input type="checkbox" class="status" data-id="{{ $user->id }}"
                                                    data-status="{{ $user->status }}"
                                                    {{ $user->status == '0' ? 'checked' : '' }} data-toggle="toggle"
                                                    data-on="Active" data-off="Inactive" data-onstyle="success"
                                                    data-offstyle="danger">
                                            </td>
                                            <td>
                                                <button class="btn btn-info btn-xs editMember"
                                                    data-id="{{ $user->id }}" data-type="Edit" type="button"
                                                    data-toggle="modal"><i class="fa fa-pencil"></i></button>
                                                <!-- <a class="btn btn-info btn-xs" href="{{ route('user.member.edit', ['id' => $user->id]) }}"><i class="fa fa-pencil"></i></a> -->
                                                <button class="btn btn-danger btn-xs delete" data-id="{{ $user->id }}"
                                                    type="button" title="Delete user">
                                                    <i class="fa fa-trash"></i></button>
{{--                                                @if (count(--}}
{{--            $user->subscriptions()->whereNull('ends_at')->get(),--}}
{{--        ))--}}
{{--                                                    <button class="btn btn-danger btn-xs cancelSub"--}}
{{--                                                        data-id="{{ $user->id }}" type="button"--}}
{{--                                                        title="Cancel subscription">--}}
{{--                                                        <i class="fa fa-bell-slash-o"></i></button>--}}
{{--                                                @endif--}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No member available.For add business click <a
                                                href="javascript:void(0)" class="member" data-toggle="modal">here</a></td>
                                    </tr>
                                @endif
                            </table>

                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                {{ $users->links() }}
                            </ul>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
    </div>
@endsection

@section('modal')
    <!-- Modal -->
    <div id="addMemberModel" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span class="modalName"></span> Member</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">First Name</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="fname" placeholder="First Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Last Name</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="lname" placeholder="Last Name">
                                </div>
                            </div>
{{--                            <div class="form-group">--}}
{{--                                <label for="user_name" class="col-sm-2 control-label">User name</label>--}}

{{--                                <div class="col-sm-10">--}}
{{--                                    <input type="text" class="form-control error" id="user_name" placeholder="User name">--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">Email</label>

                                <div class="col-sm-10">
                                    <input type="email" class="form-control error" id="email" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="company" class="col-sm-2 control-label">Company</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error autocomplete" id="company"
                                        placeholder="Company">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address" class="col-sm-2 control-label">Address</label>

                                <div class="col-sm-10">
                                    <textarea name="address" class="form-control error" placeholder="Enter Address"
                                        id="address"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="city" class="col-sm-2 control-label">City</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="city" placeholder="City">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="state" class="col-sm-2 control-label">State</label>

                                <div class="col-sm-10">
                                    <select class="form-control error" name="state" id="state">
                                        <option value="">Select a state</option>
                                        @foreach ($states as $state)
                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="city" class="col-sm-2 control-label">Zip</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="zip" placeholder="Zip">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="city" class="col-sm-2 control-label">Phone</label>

                                <div class="col-sm-10">
                                    <input type="text" class="form-control error" id="phone" placeholder="Phone">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-sm-2 control-label">Password</label>

                                <div class="col-sm-10">
                                    <input type="password" class="form-control error" id="password" placeholder="Password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cPassword" class="col-sm-2 control-label">Confirm password</label>

                                <div class="col-sm-10">
                                    <input type="password" class="form-control error" id="cPassword"
                                        placeholder="Confirm password">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="provider" class="col-sm-2 control-label">Provider:</label>

                                <div class="col-sm-10">
                                    <div class="radio">
                                        <input type="radio" class="localLien" name="provider" value="1"> Local Lien Provider
                                    </div>
                                    <div class="radio">
                                        <input type="radio" class="nationalLien" name="provider" value="0" checked> National
                                        Lien Provider - National Lien & Bond Claim Systems
                                    </div>
                                </div>
                            </div>
                            <div class="form-group lienProviders">
                                <label class="col-sm-2 control-label">Lien Provider:</label>

                                <div class="col-sm-10">
                                    <select name="lienProviders[]" id="lienProviders" multiple
                                        class="chosen-select form-control error">
                                        @if (count($lienProviders))
                                            @foreach ($lienProviders as $key => $provider)
                                                <option class=".lienProVal" value="{{ $provider->id }}">
                                                    {{ $provider->firstName . ' ' . $provider->lastName . '( ' . $provider->company . ' )' }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                {{-- @if (count($lienProviders))
                                @foreach ($lienProviders as $key => $provider)
                                <div class="checkbox">
                                  <label><input name="lienProviders[]" type="checkbox" value="{{$provider->id}}">{{ $provider->company }}</label>
                              </div>
                              @endforeach
                              @endif --}}
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Package Type:</label>

                                <div class="col-sm-10">
                                    <select name="packageType" id="packageType" class="form-control error">
                                        <option value="">---Select---</option>
                                        @isset($packages)
                                            @foreach ($packages as $package)
                                                <option value="{{ $package->id }}"
                                                    data-multiple="{{ $package->is_multiple }}">{{ $package->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Membership Type:</label>
                                <div class="col-sm-10">
                                    <select name="memberType" id="memberType" class="form-control error">
                                        <option value="">---Select---</option>
                                        <option value="one_user">Single user</option>
                                        <option value="multiple_user">Multi user</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group packageStateDiv" style="display:none">
                                <label for="packageState" class="col-md-2 control-label">Package State</label>
                                <div class="col-md-10">
                                    <select name="packageState" id="packageState" class="chosen-select form-control error">
                                        @isset($states)
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>

                            <div class="form-group packageStateMultipleDiv" style="display:none">
                                <label for="packageStateMultiple" class="col-md-2 control-label">Package State</label>
                                <div class="col-md-10">
                                    <select name="packageStateMultiple[]" id="packageStateMultiple" multiple
                                        class="chosen-select form-control error">
                                        @isset($states)
                                            @foreach ($states as $state)
                                                <option value="{{ $state->id }}">{{ $state->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>

                            <div class="form-group period" style="display:none">
                                <label for="period" class="col-md-2 control-label">Plan</label>
                                <div class="col-md-10">
                                    <select name="period" id="period" class="form-control error">
                                        @isset($plans)
                                            @foreach ($plans as $key => $plan)
                                                <option value="{{ $key }}">{{ $plan }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="packageCost" class="col-md-2 control-label">Package Cost</label>
                                <div class="col-md-10">
                                    <input id="packageCost" readonly type="number" min="0" step=".01"
                                        class="form-control error" name="packageCost">
                                </div>
                            </div>

                            <div id="payment">
                                <div class="form-group">
                                    <label for="card_no" class="col-md-2 control-label">Card No</label>
                                    <div class="col-md-10">
                                        <input id="card_no" type="text" class="form-control error" name="card_no">
                                    </div>
                                </div>

                                @php
                                    $months = [];
                                    $years = [];
                                    for ($i = 1; $i <= 12; $i++) {
                                        $months[$i] = $i < 10 ? '0' . $i : '' . $i;
                                    }

                                    $startDate = Carbon\Carbon::now()->format('Y');
                                    $enddate = Carbon\Carbon::now()
                                        ->addYears(5)
                                        ->format('Y');

                                    for ($i = $startDate; $i <= $enddate; $i++) {
                                        $years[$i] = '' . $i;
                                    }

                                @endphp
                                <div class="form-group">
                                    <label for="ccExpiryMonth" class="col-md-2 control-label">Expiry Month</label>
                                    <div class="col-md-10">
                                        <select id="ccExpiryMonth" class="form-control error" name="ccExpiryMonth">
                                            @isset($months)
                                                @foreach ($months as $month)
                                                    <option value="{{ $month }}">{{ $month }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="ccExpiryYear" class="col-md-2 control-label">Expiry Year</label>
                                    <div class="col-md-10">
                                        <select id="ccExpiryYear" class="form-control error" name="ccExpiryYear">
                                            @isset($years)
                                                @foreach ($years as $year)
                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="cvvNumber" class="col-md-2 control-label">CVV No.</label>
                                    <div class="col-md-10">
                                        <input id="cvvNumber" type="text" class="form-control error" name="cvvNumber">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-12">
                                        <input id="billing_checkbox" type="checkbox" name="billing_checkbox"> Billing info
                                        is the same as the contact information ?
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="form-group" id="triggerPaymentDiv" style="display:none;">
                            <div class="col-md-12">
                                <input id="triggerPayment" type="checkbox" name="triggerPayment"> Do you want to swap the
                                current plan?
                            </div>
                        </div>

                        <div class="form-group error-tag-field" style="display: none;">
                            <label for="error" class="col-sm-2 control-label">Error</label>

                            <div class="col-sm-10">
                                <span id="error-field" style="color: red;"></span>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer text-center">

                        </div>
                        <input type="hidden" name="user_id" id="user_id" value="">
                        <input type="hidden" name="editModal" id="editModal" value="0">
                        <input type="hidden" id="allStates" name="allStates" value="0">

                        <!-- /.box-footer -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="addMemberButton"><i class="fa fa-spinner fa-spin loader"
                            style="display: none;"></i>
                        Add Member
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#packageType').change(function() {
            $(this).parent().parent('div').removeClass('has-error');
            $(this).parent('div').children('.help-block').remove();
            $('.error-tag-field').hide();
            switch ($(this).find(':selected').data('multiple')) {
                case 0:
                    $('.packageStateDiv').show();
                    $('.period').show();
                    $('#period').removeAttr('data-monthly');
                    $('#period').removeAttr('data-yearly');
                    $('#allStates').val('0');
                    $('.packageStateMultipleDiv').hide();
                    $('#packageStateMultiple').val('').trigger('chosen:updated');
                    $('#packageCost').val('');
                    break;
                case 1:
                    $('.packageStateDiv').hide();
                    $('.period').show();
                    $('#allStates').val('0');
                    $('#period').removeAttr('data-monthly');
                    $('#period').removeAttr('data-yearly');
                    $('.packageStateMultipleDiv').show();
                    $('#packageState').val('').trigger('chosen:updated');
                    $('#packageCost').val('');
                    break;
                case 2:
                    $('.packageStateDiv').hide();
                    $('.period').show();
                    $('#allStates').val('1');
                    $('#period').removeAttr('data-monthly');
                    $('#period').removeAttr('data-yearly');
                    $('.packageStateMultipleDiv').hide();
                    $('#packageState').val('').trigger('chosen:updated');
                    $('#packageStateMultiple').val('').trigger('chosen:updated');
                    $('#packageCost').val('');
                    getMaxPrice($(this).val());
                    break;
                default:
                    $('.packageStateDiv').hide();
                    $('.period').show();
                    $('#allStates').val('0');
                    $('#period').removeAttr('data-monthly');
                    $('#period').removeAttr('data-yearly');
                    $('.packageStateMultipleDiv').hide();
                    $('#packageState').val('').trigger('chosen:updated');
                    $('#packageStateMultiple').val('').trigger('chosen:updated');
                    $('#packageCost').val('');
                    //getMaxPrice($(this).val());
                    break;
            }
        });

        function getMaxPrice(value) {
            $.ajax({
                type: "POST",
                url: "{{ route('package.get.maxPrice') }}",
                data: {
                    id: value,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    console.log(data);
                    if (data.status) {
                        $('#period').attr('data-monthly', data.data);
                        $('#period').attr('data-yearly', ((data.data * 12) - ((data.data * 12 * 9) / 100)));
                        $('#packageCost').val(data.data);
                    } else {
                        $('#period').removeAttr('data-monthly');
                        $('#period').removeAttr('data-yearly');
                        $('#packageCost').val('');
                    }
                }
            });
        }

        $('#packageState').change(function() {
            $(this).parent().parent('div').removeClass('has-error');
            $(this).parent('div').children('.help-block').remove();
            $('.error-tag-field').hide();
            var ids = $(this).val();
            $.ajax({
                type: "POST",
                url: "{{ route('package.state.autopopulate') }}",
                data: {
                    ids: ids,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data.status) {
                        $('#period').attr('data-monthly', data.price);
                        $('#period').attr('data-yearly', ((data.price * 12) - ((data.price * 12 * 9) /
                            100)));
                        if ($('#period').val() == 'Yearly') {
                            $('#packageCost').val(((data.price * 12) - ((data.price * 12 * 9) / 100)));
                        } else {
                            $('#packageCost').val(data.price);
                        }
                    } else {
                        $('#period').removeAttr('data-monthly');
                        $('#period').removeAttr('data-yearly');
                        $('#packageCost').val('');
                    }

                }
            });
        });

        $(document).delegate('.cancelSub', 'click', function() {
            var user_id = $(this).data('id');
            swal({
                title: "Are you sure?",
                text: "You want to cancel this subscription ?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes, cancel!",
                allowOutsideClick: false
            }).then(function() {
                console.log('here');
                $.ajax({
                    type: "POST",
                    url: '{{ route('cancel.subscription') }}',
                    data: {
                        user_id: user_id,
                        _token: '{{ csrf_token() }}'
                    },
                    beforeSend: function() {
                        HoldOn.open();
                    },
                    complete: function() {
                        HoldOn.close();
                    },
                    success: function(data) {
                        if (data.status == true) {
                            swal({
                                position: 'center',
                                type: 'success',
                                title: data.message,
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            swal({
                                position: 'center',
                                type: 'error',
                                title: data.message,
                            });
                        }

                    }
                });
            });
        });

        $('#packageStateMultiple').change(function() {
            $(this).parent().parent('div').removeClass('has-error');
            $(this).parent('div').children('.help-block').remove();
            $('.error-tag-field').hide();
            var ids = $(this).val();
            if (ids !== null && ids.length >= 6) {
                $('#allStates').val('1');
            } else {
                $('#allStates').val('0');
            }
            $.ajax({
                type: "POST",
                url: "{{ route('package.state.autopopulate') }}",
                data: {
                    ids: ids,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data.status) {

                        $('#period').attr('data-monthly', data.price);
                        $('#period').attr('data-yearly', ((data.price * 12) - ((data.price * 12 * 9) /
                            100)));
                        if ($('#period').val() == 'Yearly') {
                            $('#packageCost').val(((data.price * 12) - ((data.price * 12 * 9) / 100)));
                        } else {
                            $('#packageCost').val(data.price);
                        }
                    } else {
                        $('#period').removeAttr('data-monthly');
                        $('#period').removeAttr('data-yearly');
                        $('#packageCost').val('');
                    }

                }
            });
        });

        $('#period').change(function(e) {
            if ($('#period').val() == 'Yearly') {
                $('#packageCost').val(e.target.getAttribute('data-yearly'));
            } else {
                $('#packageCost').val(e.target.getAttribute('data-monthly'));
            }
        });

        $(document).ready(function() {


            $('.member').on('click', function() {
                $('.packageStateDiv').hide();
                $('.packageStateMultipleDiv').hide();
                $('.period').hide();

                $('#payment').show();
                $('#triggerPaymentDiv').hide();
                $('#triggerPayment').prop('checked', false);
                formReset();
                $('#period').removeAttr('data-monthly');
                $('#period').removeAttr('data-yearly');
                // $('#user_name').prop('disabled', false);
                $('#email').prop('disabled', false);
                $('#allStates').val('0');
                $('#editModal').val(0);
                $('.has-error').removeClass('has-error');
                $('.help-block').remove();
                var type = $(this).data('type');
                $('.modalName').text(type);
                $('#addMemberButton').attr('data-type', type);
                $('#addMemberButton').text('Add Member');

                $('#packageType').removeAttr('disabled');
                $('#packageState').removeAttr('disabled').trigger('chosen:updated');
                $('#packageStateMultiple').removeAttr('disabled').trigger('chosen:updated');
                $('#memberType').removeAttr('disabled');
                $('#period').removeAttr('disabled');

                $(".nationalLien").prop("checked", true);
                $(".lienProviders").hide();


                if ($('input:radio[name=provider]:checked').val() == "1") {
                    $(".lienProviders").show();
                } else {
                    $(".lienProviders").hide();
                }
                $('#addMemberModel').modal({
                    show: true,
                    backdrop: 'static',
                    keyboard: false
                });
            });
            $(document).on("click", "input[name='provider']", function() {
                if ($('input:radio[name=provider]:checked').val() == "1") {
                    $(".lienProviders").show();
                } else {
                    $(".lienProviders").hide();
                }
            });
            $('#lienProviders').change(function() {
                $(this).closest('.has-error').removeClass('has-error');
                $(this).closest('.form-group').find('.help-block').remove();
            });
            $('#addMemberButton').on('click', function() {
                var editMode = $('#editModal').val();
                var type = $(this).data('type');
                $('.help-block').replaceWith('');
                var fname = $('#fname').val();
                if (fname == '') {
                    $('#fname').parent().parent('div').addClass('has-error');
                    $('#fname').parent('div').append(
                        '<span class="help-block">Please enter first name</span>');
                    return false;
                }
                var lname = $('#lname').val();
                if (lname == '') {
                    $('#lname').parent().parent('div').addClass('has-error');
                    $('#lname').parent('div').append(
                        '<span class="help-block">Please enter last name</span>');
                    return false;
                }
                // var user_name = $('#user_name').val();
                // if (user_name == '') {
                //     $('#user_name').parent().parent('div').addClass('has-error');
                //     $('#user_name').parent('div').append(
                //         '<span class="help-block">Please enter user name</span>');
                //     return false;
                // }
                var email = $('#email').val();
                if (email == '') {
                    $('#email').parent().parent('div').addClass('has-error');
                    $('#email').parent('div').append('<span class="help-block">Please enter email</span>');
                    return false;
                }
                var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                if (!emailReg.test(email)) {
                    $('#email').parent().parent('div').addClass('has-error');
                    $('#email').parent('div').append(
                        '<span class="help-block">Please enter valid email</span>');
                    return false;
                }
                var company = $('#company').val();
                if (company == '') {
                    $('#company').parent().parent('div').addClass('has-error');
                    $('#company').parent('div').append(
                        '<span class="help-block">Please enter company name</span>');
                    return false;
                }
                var address = $('#address').val();
                if (address == '') {
                    $('#address').parent().parent('div').addClass('has-error');
                    $('#address').parent('div').append(
                        '<span class="help-block">Please enter your address</span>');
                    return false;
                }
                var city = $('#city').val();
                if (city == '') {
                    $('#city').parent().parent('div').addClass('has-error');
                    $('#city').parent('div').append('<span class="help-block">Please enter city</span>');
                    return false;
                }
                var state = $('#state').val();
                if (state == '') {
                    $('#state').parent().parent('div').addClass('has-error');
                    $('#state').parent('div').append('<span class="help-block">Please select state</span>');
                    return false;
                } else {
                    $('#state').parent().parent('div').removeClass('has-error');
                }
                var zip = $('#zip').val();
                if (zip == '') {
                    $('#zip').parent().parent('div').addClass('has-error');
                    $('#zip').parent('div').append('<span class="help-block">Please enter zip code</span>');
                    return false;
                }
                var zipReg = /(^\d{5}$)|(^\d{5}-\d{4}$)/;
                if (!zipReg.test(zip)) {
                    $('#zip').parent().parent('div').addClass('has-error');
                    $('#zip').parent('div').append(
                        '<span class="help-block">Please enter valid zip code</span>');
                    return false;
                }
                var phone = $('#phone').val();
                if (phone == '') {
                    $('#phone').parent().parent('div').addClass('has-error');
                    $('#phone').parent('div').append(
                        '<span class="help-block">Please enter phone number</span>');
                    return false;
                }
                var phoneReg = /^\d{10}$/;
                if (!phoneReg.test(phone)) {
                    $('#phone').parent().parent('div').addClass('has-error');
                    $('#phone').parent('div').append(
                        '<span class="help-block">Please enter valid phone number</span>');
                    return false;
                }
                var password = $('#password').val();
                var cPassword = $('#cPassword').val();
                if (editMode == 0) {
                    if (password == '') {
                        $('#password').parent().parent('div').addClass('has-error');
                        $('#password').parent('div').append(
                            '<span class="help-block">Please enter Password</span>');
                        return false;
                    }
                    if (cPassword == '') {
                        $('#cPassword').parent().parent('div').addClass('has-error');
                        $('#cPassword').parent('div').append(
                            '<span class="help-block">Please enter confirm Password</span>');
                        return false;
                    }

                }
                if (password != '') {
                    if (password < 6) {
                        $('#Password').parent().parent('div').addClass('has-error');
                        $('#Password').parent('div').append(
                            '<span class="help-block">please choose a more secure password. it should be longer than 6 characters</span>'
                            );
                        return false;
                    }
                }
                if (password != cPassword) {
                    $('#cPassword').parent().parent('div').addClass('has-error');
                    $('#cPassword').parent('div').append(
                        '<span class="help-block">Password and confirm Password does not match</span>');
                    return false;
                }

                if ($('.localLien').is(':checked')) {
                    if ($('#lienProviders').val() == '' || $('#lienProviders').val() == null) {
                        $('#lienProviders').parent().parent('div').addClass('has-error');
                        $('#lienProviders').parent('div').append(
                            '<span class="help-block">Please select a lien provider.</span>');
                        return false;
                    }
                }


                var providers = $("input[name='provider']:checked").val();

                if (providers == '1') {
                    var lienProviders = $('#lienProviders :selected').map(function() {
                        return $(this).val();
                    }).get();
                }

                var triggerPayment = $('#triggerPayment').is(':checked');

                var packageType = $('#packageType').val();
                var packageTypeName = $('#packageType').find(':selected').text();
                if (packageType == '' && triggerPayment) {
                    $('#packageType').parent().parent('div').addClass('has-error');
                    $('#packageType').parent('div').append(
                        '<span class="help-block">Please select a package type.</span>');
                    return false;
                }

                if ($('#packageType').find(':selected').data('multiple') == 0) {
                    if (($('#packageState').val() == '' || $('#packageState').val() == null) &&
                        triggerPayment) {
                        $('#packageState').parent().parent('div').addClass('has-error');
                        $('#packageState').parent('div').append(
                            '<span class="help-block">Please select a state.</span>');
                        return false;
                    }
                } else if ($('#packageType').find(':selected').data('multiple') == 1) {
                    if (($('#packageStateMultiple').val() == '' || $('#packageStateMultiple').val() ==
                        null) && triggerPayment) {
                        $('#packageStateMultiple').parent().parent('div').addClass('has-error');
                        $('#packageStateMultiple').parent('div').append(
                            '<span class="help-block">Please select a state.</span>');
                        return false;
                    }
                }
                var packageState = $('#packageState').val();
                var packageStateMultiple = $('#packageStateMultiple').val();


                var packageCost = $('#packageCost').val();
                if (packageCost == '' && triggerPayment) {
                    $('#packageCost').parent().parent('div').addClass('has-error');
                    $('#packageCost').parent('div').append(
                        '<span class="help-block">Please select a package cost.</span>');
                    return false;
                }
                var plan = $('#period').val();
                var cardNumber = $('#card_no').val();
                var expMonth = $('#ccExpiryMonth').val();
                var expYear = $('#ccExpiryYear').val();
                var cvvNumber = $('#cvvNumber').val();

                var billing_info = $('#billing_checkbox').is(':checked');
                var allStates = $('#allStates').val();
                var memberType = $('#memberType').val();
                var period = $('#period').val();

                if (editMode == 0) {
                    if (cardNumber == '') {
                        $('#card_no').parent().parent('div').addClass('has-error');
                        $('#card_no').parent('div').append(
                            '<span class="help-block">Please enter card number.</span>');
                        return false;
                    }
                    if (expMonth == '') {
                        $('#ccExpiryMonth').parent().parent('div').addClass('has-error');
                        $('#ccExpiryMonth').parent('div').append(
                            '<span class="help-block">Please select a expiry month.</span>');
                        return false;
                    }
                    if (expYear == '') {
                        $('#ccExpiryYear').parent().parent('div').addClass('has-error');
                        $('#ccExpiryYear').parent('div').append(
                            '<span class="help-block">Please select a expiry year.</span>');
                        return false;
                    }
                    if (cvvNumber == '') {
                        $('#cvvNumber').parent().parent('div').addClass('has-error');
                        $('#cvvNumber').parent('div').append(
                            '<span class="help-block">Please enter a cc number.</span>');
                        return false;
                    }

                    var url = "{{ route('user.add.member') }}";
                    var data = {
                        type: type,
                        fname: fname,
                        lname: lname,
                        // user_name: user_name,
                        email: email,
                        company: company,
                        address: address,
                        city: city,
                        state: state,
                        zip: zip,
                        phone: phone,
                        password: password,
                        cPassword: cPassword,
                        packageType: packageType,
                        packageTypeName: packageTypeName,
                        packageState: packageState,
                        packageStateMultiple: packageStateMultiple,
                        packageCost: packageCost,
                        cardNumber: cardNumber,
                        expMonth: expMonth,
                        expYear: expYear,
                        cvvNumber: cvvNumber,
                        plan: plan,
                        billing_info: billing_info,
                        providers: providers,
                        allStates: allStates,
                        memberType: memberType,
                        period: period,
                        lienProviders: lienProviders,
                        _token: '{{ csrf_token() }}'
                    };
                } else {
                    var triggerPayment = $('#triggerPayment').is(':checked');
                    var url = "{{ route('user.member.edit.model.action') }}";
                    var data = {
                        user_id: $('#user_id').val(),
                        type: type,
                        fname: fname,
                        lname: lname,
                        // user_name: user_name,
                        email: email,
                        company: company,
                        address: address,
                        city: city,
                        state: state,
                        zip: zip,
                        phone: phone,
                        password: password,
                        cPassword: cPassword,
                        packageType: packageType,
                        packageTypeName: packageTypeName,
                        packageState: packageState,
                        packageStateMultiple: packageStateMultiple,
                        packageCost: packageCost,
                        cardNumber: cardNumber,
                        expMonth: expMonth,
                        expYear: expYear,
                        cvvNumber: cvvNumber,
                        plan: plan,
                        billing_info: billing_info,
                        providers: providers,
                        allStates: allStates,
                        memberType: memberType,
                        period: period,
                        triggerPayment: triggerPayment,
                        lienProviders: lienProviders,
                        _token: '{{ csrf_token() }}'
                    };
                }

                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    beforeSend: function() {
                        HoldOn.open();
                    },
                    complete: function() {
                        HoldOn.close();
                    },
                    success: function(data) {
                        if (data.status == true) {
                            $('#addMemberModel').modal('hide');
                            swal({
                                position: 'center',
                                type: 'success',
                                title: data.message,
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            $('#error-field').text(data.message);
                            $('.error-tag-field').show();
                        }

                    }
                });
            });

            $(".chosen-select").chosen({
                width: "100%"
            });

            $('.error').on('keyup', function() {
                $(this).parent().parent('div').removeClass('has-error');
                $(this).parent('div').children('.help-block').remove();
                $('.error-tag-field').hide();
            });
            $('.status').on('change', function() {
                var id = $(this).data('id');
                var status = $(this).data('status');
                var statusName = (status == "1") ? "Activated" : "Inactivated";
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.status') }}",
                    data: {
                        id: id,
                        status: status,
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
                            swal({
                                position: 'center',
                                type: 'success',
                                title: 'Member ' + statusName + ' successfully',
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            swal(
                                'Oops...',
                                data.message,
                                'error'
                            )
                        }

                    }
                });
            });
            $('.lock').on('click', function() {
                var id = $(this).data('id');
                var status = $(this).data('status');
                var statusName = (status == "1") ? "Activate" : "Inactivate";
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.status') }}",
                    data: {
                        id: id,
                        status: status,
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
                            swal({
                                position: 'center',
                                type: 'success',
                                title: 'Member ' + statusName + ' successfully',
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            swal(
                                'Oops...',
                                data.message,
                                'error'
                            )
                        }

                    }
                });
            });
            $(document).delegate('.viewProvider', 'click', function() {
                var providers = $(this).data('provider');
                if (providers != '') {
                    var providersArray = providers.split('|');
                    var providerString = '<table class="table table-bordered">' +
                        '<thead><tr><th>Company</th><th>First Name</th><th>Last Name</th><th>Email</th></tr></thead>';
                    $.each(providersArray, function(index, value) {
                        providerString += value;
                    });
                    providerString += '</table>';
                    swal({
                        html: providerString,
                        title: 'Assigned Lien Provider',
                        text: providersArray,
                        timer: 10000
                    });
                } else {
                    swal({
                        title: 'Assigned Lien Provider',
                        text: 'No Lien Provider Assigned',
                        timer: 5000
                    });
                }
            });
            $('.delete').on('click', function() {
                var id = $(this).data('id');
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
                        url: "{{ route('member.delete') }}",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(data) {
                            if (data.status) {
                                swal({
                                    position: 'center',
                                    type: 'success',
                                    title: 'Member deleted successfully',
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
        });
        autocom();

        function autocom() {
            $('.autocomplete').autocomplete({
                source: function(request, response) {
                    var key = request.term;
                    $.ajax({
                        url: "{{ route('admin.company.autocomplete') }}",
                        dataType: "json",
                        data: {
                            key: key,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            // console.log(data);
                            var array = $.map(data.data, function(item) {
                                return {
                                    label: item.company,
                                    value: item.company,
                                    id: item.id,
                                    data: item.data
                                }
                            });
                            response(array)
                        }
                    });
                },
                minLength: 1,
                max: 10,
                select: function(event, ui) {
                    $('#address').val(ui.item.data.address);
                    $('#city').val(ui.item.data.city);
                    $('#state').val(ui.item.data.state_id);
                    $('#zip').val(ui.item.data.zip);
                    // $('#phone').val(ui.item.data.phone);
                    $('#fax').val(ui.item.data.fax);
                }
            });
        }

        $('.editMember').on('click', function() {
            $('#payment').hide();
            $('#triggerPaymentDiv').show();
            $('#triggerPayment').prop('checked', false);
            $('.modalName').text('Edit');
            $('.has-error').removeClass('has-error');
            $('.help-block').remove();
            $('#addMemberButton').text('Update Member');
            formReset();
            $('#editModal').val(1);
            $('#addMemberButton').attr('data-type', 'Edit');
            $('#addMemberModel').modal('show');
            // $('#user_name').attr('disabled', 'disabled');
            $('#email').attr('disabled', 'disabled');

            $('#packageType').attr('disabled', 'disabled');
            $('#packageState').attr('disabled', 'disabled');
            $('#packageStateMultiple').attr('disabled', 'disabled');
            $('#memberType').attr('disabled', 'disabled');
            $('#period').attr('disabled', 'disabled');


            var memberID = $(this).data('id');
            $.ajax({
                url: "{{ route('user.member.edit.modal') }}",
                // dataType: "json",
                data: {
                    memberID: memberID,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    // console.log(data);
                    //console.log(data);
                    // console.log(data.selectedLienPros);
                    if (data != 0) {
                        $('#user_id').val(data.id);
                        $('#fname').val(data.user.first_name);
                        $('#lname').val(data.user.last_name);
                        // $('#user_name').val(data.username);
                        $('#email').val(data.email);
                        $('#company').val(data.user.get_company.company);
                        $('#address').val(data.user.get_company.address);
                        $('#city').val(data.user.get_company.city);
                        $('#state').val(data.state_id);
                        $('#zip').val(data.user.get_company.zip);
                        $('#phone').val(data.user.phone);
                        $('#packageType').val(data.package_id);
                        switch ($('#packageType').find(':selected').data('multiple')) {
                            case 0:
                                $('.packageStateDiv').show();
                                $('.period').show();
                                $('#period').attr('data-monthly', data.original_price);
                                $('#period').attr('data-yearly', ((data.original_price * 12) - ((data
                                    .original_price * 12 * 9) / 100)));
                                $('#period').val(data.period);
                                $('#allStates').val('0');
                                $('.packageStateMultipleDiv').hide();
                                $('#packageState').val(data.package_state).trigger('chosen:updated');
                                $('#packageStateMultiple').val('').trigger('chosen:updated');
                                $('#packageCost').val('');
                                break;
                            case 1:
                                $('.packageStateDiv').hide();
                                $('.period').show();
                                $('#allStates').val('0');
                                $('#period').val(data.period);
                                $('#period').attr('data-monthly', data.original_price);
                                $('#period').attr('data-yearly', ((data.original_price * 12) - ((data
                                    .original_price * 12 * 9) / 100)));
                                $('.packageStateMultipleDiv').show();
                                $('#packageStateMultiple').val(data.package_state).trigger(
                                    'chosen:updated');
                                $('#packageState').val('').trigger('chosen:updated');
                                $('#packageCost').val('');
                                break;
                            case 2:
                                $('.packageStateDiv').hide();
                                $('.period').hide();
                                $('#allStates').val('1');
                                $('#period').removeAttr('data-monthly');
                                $('#period').removeAttr('data-yearly');
                                $('#period').val('Monthly');
                                $('.packageStateMultipleDiv').hide();
                                $('#packageStateMultiple').val('').trigger('chosen:updated');
                                $('#packageState').val('').trigger('chosen:updated');
                                $('#packageCost').val('');
                                break;
                            default:
                                $('.packageStateDiv').hide();
                                $('.period').hide();
                                $('#allStates').val('0');
                                $('#period').val('Monthly');
                                $('#period').removeAttr('data-monthly');
                                $('#period').removeAttr('data-yearly');
                                $('.packageStateMultipleDiv').hide();
                                $('#packageStateMultiple').val('').trigger('chosen:updated');
                                $('#packageState').val('').trigger('chosen:updated');
                                $('#packageCost').val('');
                                break;
                        }

                        $('#packageCost').val(data.package_cost);
                        $('#memberType').val(data.membership);
                        if (data.billing_info == '1') {
                            $('#billing_checkbox').prop('checked', true);
                        } else {
                            $('#billing_checkbox').prop('checked', false);
                        }

                        if (data.user.lien_status == 1) {
                            $(".localLien").prop("checked", true);
                            $(".lienProviders").show();
                        } else {
                            $(".nationalLien").prop("checked", true);
                            $(".lienProviders").hide();
                        }
                        $.each(data.selectedLienPros, function(item, val) {
                            $("#lienProviders").find("option[value=" + val + "]").prop(
                                "selected", "selected");
                        });

                    }
                    $(".chosen-select").trigger("chosen:updated");
                    // autocom();
                }
            });
        })

        function formReset() {
            $('#user_id').val();
            $('#fname').val('');
            $('#lname').val('');
            // $('#user_name').val('');
            $('#email').val('');
            $('#company').val('');
            $('#address').val('');
            $('#city').val('');
            $('#state').val('');
            $('#zip').val('');
            $('#phone').val('');
            $('#packageType').val('');
            $('#packageCost').val('');
            $('#card_no').val('');
            $('#password').val('');
            $('#cPassword').val('');
            //$('#ccExpiryMonth').val('');
            //$('#ccExpiryYear').val('');
            $('#cvvNumber').val('');
            $('#billing_checkbox').prop('checked', false);
            $("#lienProviders option:selected").removeAttr("selected");
            $(".chosen-select").trigger("chosen:updated");
            $('#packageStateMultiple').val('').trigger('chosen:updated');
            $('#packageState').val('').trigger('chosen:updated');
            $('#billing_checkbox').prop('checked', false);
            $('#memberType').val('');
        }

        // $(document).delegate('#editMemberButton','click',function () {
        //   $('.help-block').replaceWith('');
        //   var fname = $('#fname').val();
        //   if (fname == '') {
        //       $('#fname').parent().parent('div').addClass('has-error');
        //       $('#fname').parent('div').append('<span class="help-block">Please enter first name</span>');
        //       return false;
        //   }
        //   var lname = $('#lname').val();
        //   if (lname == '') {
        //       $('#lname').parent().parent('div').addClass('has-error');
        //       $('#lname').parent('div').append('<span class="help-block">Please enter last name</span>');
        //       return false;
        //   }
        //   var user_name = $('#user_name').val();
        //   if (user_name == '') {
        //       $('#user_name').parent().parent('div').addClass('has-error');
        //       $('#user_name').parent('div').append('<span class="help-block">Please enter user name</span>');
        //       return false;
        //   }
        //   var email = $('#email').val();
        //   if (email == '') {
        //       $('#email').parent().parent('div').addClass('has-error');
        //       $('#email').parent('div').append('<span class="help-block">Please enter email</span>');
        //       return false;
        //   }
        //   var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        //   if (!emailReg.test(email)) {
        //       $('#email').parent().parent('div').addClass('has-error');
        //       $('#email').parent('div').append('<span class="help-block">Please enter valid email</span>');
        //       return false;
        //   }
        //   var company = $('#company').val();
        //   if (company == '') {
        //       $('#company').parent().parent('div').addClass('has-error');
        //       $('#company').parent('div').append('<span class="help-block">Please enter company name</span>');
        //       return false;
        //   }
        //   var address = $('#address').val();
        //   if (address == '') {
        //       $('#address').parent().parent('div').addClass('has-error');
        //       $('#address').parent('div').append('<span class="help-block">Please enter your address</span>');
        //       return false;
        //   }
        //   var city = $('#city').val();
        //   if (city == '') {
        //       $('#city').parent().parent('div').addClass('has-error');
        //       $('#city').parent('div').append('<span class="help-block">Please enter city</span>');
        //       return false;
        //   }
        //   var state = $('#state').val();
        //   if (state == '') {
        //       $('#state').parent().parent('div').addClass('has-error');
        //       $('#state').parent('div').append('<span class="help-block">Please select state</span>');
        //       return false;
        //   } else {
        //       $('#state').parent().parent('div').removeClass('has-error');
        //   }
        //   var zip = $('#zip').val();
        //   if (zip == '') {
        //       $('#zip').parent().parent('div').addClass('has-error');
        //       $('#zip').parent('div').append('<span class="help-block">Please enter zip code</span>');
        //       return false;
        //   }
        //   var zipReg = /(^\d{5}$)|(^\d{5}-\d{4}$)/;
        //   if (!zipReg.test(zip)) {
        //       $('#zip').parent().parent('div').addClass('has-error');
        //       $('#zip').parent('div').append('<span class="help-block">Please enter valid zip code</span>');
        //       return false;
        //   }
        //   var phone = $('#phone').val();
        //   if (phone == '') {
        //       $('#phone').parent().parent('div').addClass('has-error');
        //       $('#phone').parent('div').append('<span class="help-block">Please enter phone number</span>');
        //       return false;
        //   }
        //   var phoneReg = /^\d{10}$/;
        //   if (!phoneReg.test(phone)) {
        //       $('#phone').parent().parent('div').addClass('has-error');
        //       $('#phone').parent('div').append('<span class="help-block">Please enter valid phone number</span>');
        //       return false;
        //   }
        //   var password = $('#password').val();
        //   if (password == '') {
        //       $('#password').parent().parent('div').addClass('has-error');
        //       $('#password').parent('div').append('<span class="help-block">Please enter Password</span>');
        //       return false;
        //   }
        //   var cPassword = $('#cPassword').val();
        //   if (cPassword == '') {
        //       $('#cPassword').parent().parent('div').addClass('has-error');
        //       $('#cPassword').parent('div').append('<span class="help-block">Please enter confirm Password</span>');
        //       return false;
        //   }
        //   if (password != cPassword) {
        //       $('#cPassword').parent().parent('div').addClass('has-error');
        //       $('#cPassword').parent('div').append('<span class="help-block">Password and confirm Password does not match</span>');
        //       return false;
        //   }
        //   if (password < 6) {
        //       $('#Password').parent().parent('div').addClass('has-error');
        //       $('#Password').parent('div').append('<span class="help-block">please choose a more secure password. it should be longer than 6 characters</span>');
        //       return false;
        //   }
        //   var providers = $("input[name='providerV']:checked").val();
        //
        //   if(providers == '1'){
        //       var lienProviders = $('#lienProviders :selected').map(function(){return $(this).val();}).get();
        //   }
        //
        //   $.ajax({
        //       url: "{{ route('user.member.edit.model.action') }}",
        //       type: 'POST',
        //       headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        //       dataType: "json",
        //       data: $('#editMemberForm').serialize(),
        //       success: function (data) {
        //
        //       }
        //   });
        // });

        $('#triggerPayment').change(function() {
            if ($(this).is(':checked')) {
                $('#payment').show();
                $('#packageType').removeAttr('disabled');
                $('#packageState').removeAttr('disabled').trigger('chosen:updated');
                $('#packageStateMultiple').removeAttr('disabled').trigger('chosen:updated');
                $('#memberType').removeAttr('disabled');
                $('#period').removeAttr('disabled');

            } else {
                $('#payment').hide();
                $('#packageType').attr('disabled', 'disabled');
                $('#packageState').attr('disabled', 'disabled').trigger('chosen:updated');
                $('#packageStateMultiple').attr('disabled', 'disabled').trigger('chosen:updated');
                $('#memberType').attr('disabled', 'disabled');
                $('#period').attr('disabled', 'disabled');
            }
        })

        $('.search').on('click', function() {
            var string = $('#tier_search').val();
            var location = appendToQueryString('search', string);
            window.location.href = location;
        });
        $('#tier_search').keyup(function(e){
            if(e.keyCode == 13)
            {
                var string = $('#tier_search').val();
                var location = appendToQueryString('search', string);
                window.location.href = location;
            }
        });
    </script>
@endsection
