<!-- Extends main layout form layout folder -->
@extends('layout.main')

<!-- Addind Dynamic layout -->
@section('title', 'Lien Law Slide Chart')
<!-- Main Content -->
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Lien Law Slide Chart
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
                            <h3 class="box-title">List of all Lien Law Slide Chart</h3>
                            <button class="btn btn-md addLienLaw" type="button">Upload New Excel</button>

                            <div class="box-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" id="remedy_search"
                                        value="{{ isset($_GET['search']) && $_GET['search'] != '' ? $_GET['search'] : '' }}"
                                        class="form-control pull-right" placeholder="Search">

                                    <div class="input-group-btn">
                                        <button type="button" data-type="lien" class="btn btn-default search"><i
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
                                    <th>State</th>
                                    <th>Project Type</th>
                                    <th>Remedy</th>
                                    <th>Description</th>
                                    <th>Tiers</th>
                                </tr>
                                @if (count($lienLaws) > 0)
                                    @foreach ($lienLaws as $key => $lienLaw)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $lienLaw->state->name }}</td>
                                            <td>{{ $lienLaw->projectType->project_type }}</td>
                                            <td>{{ $lienLaw->remedy }}</td>
                                            <td>{{ $lienLaw->description }}</td>
                                            <td>{{ $lienLaw->tier_limit }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6">Lien law slide chart not dound</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        <!-- /.box-body -->


                        <tfoot>
                            <div class="col-sm-12 box-footer clearfix" style="text-align:center">
                                <ul class="pagination pagination-sm no-margin">
                                    {{ $lienLaws->appends(request()->query())->links() }}
                                </ul>
                            </div>

                            <div class="col-sm-12 box-footer clearfix" style="text-align:center">
                                <select name="paginate" id="paginate">
                                    <option value="" @if (isset($_GET['paginate']) && $_GET['paginate'] == ''){{ 'selected' }}@endif>---Select---</option>
                                    <option value="20" @if (isset($_GET['paginate']) && $_GET['paginate'] == '20'){{ 'selected' }}@endif>20</option>
                                    <option value="50" @if (isset($_GET['paginate']) && $_GET['paginate'] == '50'){{ 'selected' }}@endif>50</option>
                                    <option value="100" @if (isset($_GET['paginate']) && $_GET['paginate'] == '100'){{ 'selected' }}@endif>100</option>
                                    <option value="10000" @if (isset($_GET['paginate']) && $_GET['paginate'] == '10000'){{ 'selected' }}@endif>All</option>
                                </select>
                            </div>
                        </tfoot>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('modal')
    <div id="addlienModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Upload new Lien Law File</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal uploadForm" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="lien" class="col-sm-4 control-label">Select Lien law file</label>

                                <div class="col-sm-8">
                                    <input type="file" class="form-control error" name="lien" id="lien"
                                        placeholder="Lien Law File">
                                </div>
                            </div>
                        </div>
                        {{ csrf_field() }}
                        <div class="form-group error-tier-field" style="display: none; color: red;">
                            <label for="error" class="col-sm-4 control-label">Error</label>

                            <div class="col-sm-8">
                                <span id="error-tier"></span>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="button" class="btn btn-info pull-right" id="addLienButton"><i
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

        $('#remedy_search').keyup(function(e){
            if(e.keyCode == 13)
            {
                var string = $('#remedy_search').val();
                var location = appendToQueryString('search', string);
                window.location.href = location;
            }
        });

        $('#paginate').change(function() {
            if ($(this).val() != '') {
                var location = window.location.href;
                var location = appendToQueryString('paginate', $(this).val());
                window.location.href = location;
            } else {
                var location = window.location.href;
                location = removeURLParameter(location, 'paginate');
                window.location.href = location;
            }
        });

        function ValidateExtension() {
            var allowedFiles = [".xls", ".xlsx"];
            var fileUpload = document.getElementById("lien");
            var lblError = document.getElementById("error-tier");
            var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$");
            if (!regex.test(fileUpload.value.toLowerCase())) {
                lblError.innerHTML = "Please upload files having extensions: <b>" + allowedFiles.join(', ') + "</b> only.";
                return false;
            }
            lblError.innerHTML = "";
            return true;
        }

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

        $('.search').on('click', function() {
            var remedy = $('#remedy_search').val();
            var location = appendToQueryString('search', remedy);
            window.location.href = location;
        });

        $('.addLienLaw').on('click', function() {
            $('#addlienModal').modal('show')
        });

        $('#addLienButton').on('click', function() {
            if (ValidateExtension()) {
                var formData = new FormData($('.uploadForm')[0]);
                formData.append("lien", $('#lien')[0].files[0]);
                $.ajax({
                    type: "POST",
                    url: "{{ route('add.lien') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        HoldOn.open();
                    },
                    complete: function() {
                        HoldOn.close();
                    },
                    success: function(data) {
                        if (data.status == true) {
                            $('.uploadForm')[0].reset();
                            window.location.reload();
                        } else {
                            $('#error-tier').text(data.message);
                            $('.error-tier-field').show();
                        }

                    }
                });

            } else {
                $('.error-tier-field').show();
            }
        });
    </script>
@endsection
