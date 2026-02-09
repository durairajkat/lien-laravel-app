<!-- Extends main layout form layout folder -->
@extends('layout.main')
<!-- Addind Dynamic layout -->
@section('title', 'Member')
<!-- Main Content -->
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Members</h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">Update member details</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">

                            <form action="{{ route('user.member.edit.action') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="user_id" value="{{ $id }}">
                                <table class="table profile-table">
                                    @if (Session::has('success-update'))
                                        <p class="alert alert-success">{{ Session::get('success-update') }}</p>
                                    @endif
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <tr class="table-select">
                                        <td colspan="3">
                                            <table class="table inner-table">
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="pad15 email">
                                                            Email: <input type="text" name="email" id="userEmail"
                                                                class="form-control" value="{{ $email }}" readonly>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="pad15 email">
                                                            Company : <input type="text" name="companyName"
                                                                value="{{ !is_null($user->getCompany) && isset($user->getCompany->company) && !is_null($user->getCompany->company) ? $user->getCompany->company : '' }}"
                                                                id="userCompany" class="form-control autocomplete">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="pad15 email">
                                                            First Name:<input type="text" name="firstName"
                                                                value="{{ isset($user->first_name) ? $user->first_name : '' }}"
                                                                id="userFirstName" class="form-control">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="pad15 email">
                                                            Last Name:<input type="text" name="lastName"
                                                                value="{{ isset($user->last_name) ? $user->last_name : '' }}"
                                                                id="userLastName" class="form-control">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="pad15 email">
                                                            Address:<textarea name="address" id="userAddress"
                                                                class="form-control">{{ !is_null($user->getCompany) && isset($user->getCompany->address) && !is_null($user->getCompany->address) ? $user->getCompany->address : '' }}</textarea>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="pad15 email">
                                                            City:<input type="text" name="city"
                                                                value="{{ !is_null($user->getCompany) && isset($user->getCompany->city) && !is_null($user->getCompany->city) ? $user->getCompany->city : '' }}"
                                                                id="userCity" class="form-control">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="pad15 email">
                                                            State:
                                                            <select class="form-control" name="state">
                                                                @foreach ($states as $states)
                                                                    <option value="{{ $states->id }}"
                                                                        <?php echo $states->id == $state_id ? 'selected' : ''; ?>>{{ $states->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="pad15 email">
                                                            Zip:<input type="text" name="zip"
                                                                value="{{ !is_null($user->getCompany) && isset($user->getCompany->zip) && !is_null($user->getCompany->zip) ? $user->getCompany->zip : '' }}"
                                                                id="userZip" class="form-control">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="pad15 email">
                                                            Phone:<input type="text" name="phone"
                                                                value="{{ isset($user->phone) ? $user->phone : '' }}"
                                                                id="userPhone" class="form-control">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="pad15 email">
                                                            Provider:
                                                            <div class="radio-inline">
                                                                @if (empty($user->lien_status))
                                                                    <input type="radio" name="provider" value="1" checked>
                                                                    Local Lien Provider
                                                                @else
                                                                    <input type="radio" name="provider" value="1"
                                                                        {{ $user->lien_status == '1' ? 'checked' : '' }}> Local
                                                                    Lien Provider
                                                                @endif
                                                            </div>
                                                            <div class="radio-inline">
                                                                <input type="radio" name="provider" value="0"
                                                                    {{ $user->lien_status == '0' ? 'checked' : '' }}> National
                                                                Lien Provider - National Lien & Bond Claim Systems
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="pad15 email lienProviders"
                                                            style="display: {{ $user->lien_status == '0' ? 'none' : '' }}">
                                                            Lien Provider:
                                                            <select name="lienProviders[]" id="lienProviders" multiple
                                                                class="chosen-select form-control error">
                                                                @if (count($lienProviders))
                                                                    @foreach ($lienProviders as $key => $provider)
                                                                        <option value="{{ $provider->id }}"
                                                                            {{ in_array($provider->id, $selectedLienPros) ? 'selected' : '' }}>
                                                                            {{ $provider->company }}</option>l>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            {{-- @if (count($lienProviders))
                                                                @foreach ($lienProviders as $key => $provider)
                                                                <div class="checkbox">
                                                                  <label><input name="lienProviders[]" type="checkbox" value="{{$provider->id}}" {{(in_array($provider->id, $selectedLienPros))? 'checked':''}}>{{ $provider->company }}</label>
                                                              </div>
                                                            @endforeach
                                                          @endif --}}

                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <div class="text-center">
                                                <input type="submit" class="btn btn-info" value="Save Changes">
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                        <!-- /.box-body -->
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
            $(".member-menu").css("display", "block");
            $(".member-menu li:first-child").addClass("active");

            $("input[name='provider']").click(function() {
                if ($('input:radio[name=provider]:checked').val() == "1") {
                    $(".lienProviders").show();
                } else {
                    $(".lienProviders").hide();
                }
            });
        });

        $(".chosen-select").chosen({
            width: "100%"
        });

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
                        console.log(data);
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
                $('#phone').val(ui.item.data.phone);
                $('#fax').val(ui.item.data.fax);
            }
        });
    </script>
@endsection
