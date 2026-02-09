<!-- Extends main layout form layout folder -->
@extends('layout.main')
<!-- Addind Dynamic layout -->
@section('title', 'Consultation')
<!-- Main Content -->
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Compose New Message
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-centered">
                        @if (Session::has('success'))
                            <p class="alert alert-success">{{ Session::get('success') }}</p>
                        @endif
                    </div>
                    <form method="post" action="{{ route('consultation.reply.send') }}" class="form-horizontal col-md-12"
                        enctype="multipart/form-data">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Compose New Message</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input class="form-control" name="to" value="{{ $consultation->email }}"
                                            placeholder="To:" readonly="readonly">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" name="subject" value="Reply From National Lien & Bound"
                                            placeholder="Subject:">
                                    </div>
                                    {{ csrf_field() }}
                                    <input type="hidden" name="consultation_id" value="{{ $consultation->id }}">
                                    <div class="form-group">
                                        <textarea name="message" id="compose-textarea" class="form-control"
                                            style="height: 300px">
                                                <p>Dear {{ $consultation->first_name . ' ' . $consultation->last_name }},</p>
                                            </textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="btn btn-default btn-file">
                                            <i class="fa fa-paperclip"></i> Attachment
                                            <input type="file" name="attachment">
                                        </div>
                                        <p class="help-block">Max. 32MB</p>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer">
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-envelope-o"></i> Send
                                    </button>
                                </div>
                                <button type="reset" class="btn btn-default"><i class="fa fa-times"></i> Discard</button>
                            </div>
                            <!-- /.box-footer -->
                        </div>
                        <!-- /. box -->
                    </form>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $("#compose-textarea").wysihtml5();
        });
    </script>
@endsection
