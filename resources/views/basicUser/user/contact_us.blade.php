@extends('basicUser.layout.main')

@section('title', 'Contact Us')

@section('content')


    <div class="">
        <div class="">
            <div class="">
                <div class="">
                    <div class="">
                        <div class="">
                            <div class="page-head">
                                <h2>Contact US</h2>
                            </div>
                            <div class="sub-heading">
                                <h3>We will be happy to answer any questions so feel free to contact us.</h3>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="gray-form">
                            <form action="{{ route('member.contact.us.post') }}" method="post" class="form-horizontal">

                                <div class="form-group">
                                    <!-- <label class="control-label col-md-4">UserName : </label> -->
                                    <div class="col-md-12">
                                        <input type="text" name="name" class="form-control"
                                            value="{{ Auth::user()->name }}" placeholder="Enter your name" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <!-- <label class="control-label col-md-4">Email : </label> -->
                                    <div class="col-md-12">
                                        <input type="text" name="email" class="form-control"
                                            value="{{ Auth::user()->email }}" placeholder="Enter email" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="">
                                            <select class="form-control" name="department">
                                                @foreach ($role as $role1)
                                                    <option>{{ $role1->project_roles }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <!-- <label class="control-label col-md-4">Message : </label> -->
                                    <div class="col-md-12">
                                        <textarea name="message" class="form-control" placeholder="Enter message"
                                            rows="10"></textarea>
                                    </div>
                                </div>
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                {{ csrf_field() }}
                                @if ($errors->any())
                                    <div class="alert alert-danger">
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
                                <div class="form-group" style="margin-top: 25px;">
                                    <div class="col-md-12">
                                        <button type="submit" class="orange-btn">
                                            Send Message
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

    <div class="card-grid">
        <div class="card-row">
            <div class="card">
                <a href="#">
                    <img src="{{ asset('images/location-icon.png') }}" alt="">
                    <span class="font-normal">440 Central Avenue Highland Park, IL 60035</span>
                </a>
            </div>
            <div class="card">
                <a href="#">
                    <img src="{{ asset('images/chat-icon.png') }}" alt="">
                    <span class="font-normal">(800) 432-7799</span>
                </a>
            </div>
            <div class="card">
                <a href="#">
                    <img src="{{ asset('images/fax-icon.png') }}" alt="">
                    <span class="font-normal">(847) 432-8950</span>
                </a>
            </div>
        </div>
    </div>
@endsection
