<form class="form-horizontal" id="editMemberForm">
    <div class="box-body">
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">First Name</label>

            <div class="col-sm-10">
                <input type="text" class="form-control error" name="firstName" id="fname"
                    value="{{ isset($user->first_name) ? $user->first_name : '' }}" placeholder="First Name">
            </div>
        </div>
        <div class="form-group">
            <label for="name" class="col-sm-2 control-label">Last Name</label>

            <div class="col-sm-10">
                <input type="text" class="form-control error" id="lname" name="lastName"
                    value="{{ isset($user->last_name) ? $user->last_name : '' }}" placeholder="Last Name">
            </div>
        </div>
        <div class="form-group">
            <label for="user_name" class="col-sm-2 control-label">User name</label>

            <div class="col-sm-10">
                <input type="text" class="form-control error" id="user_name"
                    value="{{ isset($username) ? $username : '' }}" {{ isset($edit) ? 'disabled' : '' }}
                    placeholder="User name">
            </div>
        </div>
        <div class="form-group">
            <label for="email" class="col-sm-2 control-label">Email</label>

            <div class="col-sm-10">
                <input type="email" class="form-control error" id="email" value="{{ isset($email) ? $email : '' }}"
                    {{ isset($edit) ? 'disabled' : '' }} placeholder="Email">
            </div>
        </div>
        <div class="form-group">
            <label for="company" class="col-sm-2 control-label">Company</label>

            <div class="col-sm-10">
                <input type="text" class="form-control error autocomplete" id="company" name="companyName"
                    value="{{ !is_null($user->getCompany) && isset($user->getCompany->company) && !is_null($user->getCompany->company) ? $user->getCompany->company : '' }}"
                    placeholder="Company">
            </div>
        </div>
        <div class="form-group">
            <label for="address" class="col-sm-2 control-label">Address</label>

            <div class="col-sm-10">
                <textarea name="address" class="form-control error" placeholder="Enter Address"
                    id="address">{{ !is_null($user->getCompany) && isset($user->getCompany->address) && !is_null($user->getCompany->address) ? $user->getCompany->address : '' }}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="city" class="col-sm-2 control-label">City</label>

            <div class="col-sm-10">
                <input type="text" class="form-control error" id="city" name="city"
                    value="{{ !is_null($user->getCompany) && isset($user->getCompany->city) && !is_null($user->getCompany->city) ? $user->getCompany->city : '' }}"
                    placeholder="City">
            </div>
        </div>
        <div class="form-group">
            <label for="state" class="col-sm-2 control-label">State</label>

            <div class="col-sm-10">
                <select class="form-control" name="state" id="state">
                    @foreach ($states as $state)
                        <option value="{{ $state->id }}" <?php echo $state->id == $state_id ? 'selected' : ''; ?>>{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="city" class="col-sm-2 control-label">Zip</label>

            <div class="col-sm-10">
                <input type="text" class="form-control error" name="zip" id="zip"
                    value="{{ !is_null($user->getCompany) && isset($user->getCompany->zip) && !is_null($user->getCompany->zip) ? $user->getCompany->zip : '' }}"
                    placeholder="Zip">
            </div>
        </div>
        <div class="form-group">
            <label for="city" class="col-sm-2 control-label">Phone</label>

            <div class="col-sm-10">
                <input type="text" class="form-control error" name="phone" id="phone"
                    value="{{ isset($user->phone) ? $user->phone : '' }}" placeholder="Phone">
            </div>
        </div>
        <div class="form-group">
            <label for="password" class="col-sm-2 control-label">Password</label>

            <div class="col-sm-10">
                <input type="password" class="form-control error" id="password" name="password" placeholder="Password">
            </div>
        </div>
        <div class="form-group">
            <label for="cPassword" class="col-sm-2 control-label">Confirm password</label>

            <div class="col-sm-10">
                <input type="password" class="form-control error" id="cPassword" name="cPassword"
                    placeholder="Confirm password">
            </div>
        </div>
        <div class="form-group">
            <label for="provider" class="col-sm-2 control-label">Provider:</label>

            <div class="col-sm-10">
                <div class="radio">
                    <input type="radio" name="provider" value="1"
                        {{ isset($user->lien_status) && $user->lien_status == '1' ? 'checked' : '' }}> Local Lien Provider

                </div>
                <div class="radio">
                    <input type="radio" name="provider" value="0"
                        {{ isset($user->lien_status) && $user->lien_status == '0' ? 'checked' : '' }}> National Lien
                    Provider - National Lien & Bond Claim Systems
                </div>
            </div>
        </div>
        <div class="form-group lienProviders" style="display: {{ $user->lien_status == '0' ? 'none' : '' }}">
            <label class="col-sm-2 control-label">Lien Provider:</label>

            <div class="col-sm-10">
                <select name="lienProviders[]" id="lienProviders" multiple class="chosen-select form-control error">
                    @if (count($lienProviders))
                        @foreach ($lienProviders as $key => $provider)
                            <option value="{{ $provider->id }}"
                                {{ in_array($provider->id, $selectedLienPros) ? 'selected' : '' }}>
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
    </div>
    <div class="form-group error-tag-field" style="display: none;">
        <label for="error" class="col-sm-2 control-label">Error</label>

        <div class="col-sm-10">
            <span id="error-field" style="color: red;"></span>
        </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer text-center">
        <button type="button" class="btn btn-info" id="editMemberButton"><i class="fa fa-spinner fa-spin loader"
                style="display: none;"></i>
            {{ isset($edit) ? 'Update' : 'Add' }} Member
        </button>
    </div>
    <input type="hidden" name="user_id" value="{{ isset($id) ? $id : '' }}">
    <input type="hidden" name="editModal" value="{{ isset($edit) ? $edit : 0 }}">
    <!-- /.box-footer -->
</form>
