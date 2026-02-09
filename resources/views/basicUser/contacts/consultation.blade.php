@extends('basicUser.layout.main')

@section('title', 'Consultation')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-10">
                                <h4>Consultation</h4>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-8 col-md-offset-2">
                            <form action="{{ route('member.consultation.post') }}" method="post" class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-md-4">First Name :</label>
                                    <div class="col-md-8">
                                        <input class="form-control" name="first_name" placeholder="Enter first name"
                                            value="{{ old('first_name') }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Last Name :</label>
                                    <div class="col-md-8">
                                        <input class="form-control" name="last_name" placeholder="Enter last name"
                                            value="{{ old('last_name') }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Phone Number :</label>
                                    <div class="col-md-8">
                                        <input class="form-control" name="phone_number" placeholder="Enter phone number"
                                            value="{{ old('phone_number') }}" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Email : </label>
                                    <div class="col-md-8">
                                        <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                            placeholder="Enter email" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Claim Amount : </label>
                                    <div class="col-md-8">
                                        <input type="text" name="claim_amount" class="form-control"
                                            value="{{ old('claim_amount') }}" placeholder="Enter claim amount" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-4">Description : </label>
                                    <div class="col-md-8">
                                        <textarea name="description" class="form-control" placeholder="Enter Description"
                                            rows="10" required>{{ old('description') }}</textarea>
                                    </div>
                                </div>
                                <input type="hidden" name="type" value="1">

                                {{ csrf_field() }}
                                @if ($errors->any())
                                    <div class="alert alert-danger col-md-8 col-md-offset-4">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @if (Session::has('error'))
                                    <div class="form-group col-md-12">
                                        <p class="alert alert-danger">{{ Session::get('error') }}</p>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="form-control btn blue-btn"><i
                                                class="fa fa-send fa-fw"></i> Submit
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
