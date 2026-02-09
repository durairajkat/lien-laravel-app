@extends('basicUser.layout.main')

@section('title', 'Invite users')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-10">
                                <h4>Invite Users</h4>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('member.invite.post') }}" method="post" class="form-horizontal">
                            <div class="col-md-8 col-md-offset-1">
                                <div class="form-group">
                                    <label class="control-label col-md-4">Message : </label>
                                    <div class="col-md-8">
                                        <textarea name="message" class="form-control" placeholder="Enter message"
                                            rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row col-md-12 row-invite">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-md-offset-4">Details of Invitees</label>
                                </div>
                                <div class="form-group main-div">
                                    <div class="col-md-3">
                                        <input class="form-control" type="text" name="companyName[]"
                                            placeholder="Company Name" autocomplete="off">
                                    </div>
                                    <div class="col-md-2">
                                        <input class="form-control" type="text" name="firstName[]" placeholder="First Name"
                                            autocomplete="off">
                                    </div>
                                    <div class="col-md-2">
                                        <input class="form-control" type="text" name="lastName[]" placeholder="Last Name"
                                            autocomplete="off">
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="email" name="email[]" placeholder="Email"
                                            autocomplete="off">
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-success add-more add_field_button" type="button"><i
                                                class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                                <div id="items"></div>
                            </div>
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
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
                                {{-- <div class="col-md-2 ">
                                    <button class="btn btn-success add-more" type="button"><i class="fa fa-plus"></i> Add More Users </button>
                                </div> --}}
                                <div class="col-md-2">
                                    <button type="submit" class="form-control btn btn-success"><i
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
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            var max_fields = 10; //maximum input boxes allowed
            var wrapper = $("#items"); //Fields wrapper
            var add_button = $(".add_field_button"); //Add button ID

            var x = 1; //initlal text box count
            $(add_button).click(function(e) { //on add input button click
                e.preventDefault();
                if (x < max_fields) { //max input box allowed
                    x++; //text box increment
                    $(wrapper).append('<div class="form-group main-div">' +
                        '<div class="col-md-3">' +
                        '<input class="form-control" name="companyName[]" type="text" placeholder="Company Name">' +
                        '</div>' +
                        '<div class="col-md-2">' +
                        '<input class="form-control" name="firstName[]" type="text" placeholder="First Name">' +
                        '</div>' +
                        '<div class="col-md-2">' +
                        '<input class="form-control" name="lastName[]" type="text" placeholder="Last Name">' +
                        '</div>' +
                        '<div class="col-md-3">' +
                        '<input class="form-control" name="email[]" type="email" placeholder="Email">' +
                        '</div>' +
                        '<div class="col-md-2">' +
                        '<button class="btn btn-warning add-more remove_field" type="button"><i class="fa fa-minus"></i> </button>' +
                        '</div></div>'); //add input box
                }
            });

            $(wrapper).on("click", ".remove_field", function(e) { //user click on remove field
                e.preventDefault();
                $(this).parent().parent('div').remove();
                x--;
            })
        });
    </script>
@endsection
