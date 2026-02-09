@extends('basicUser.layout.main')

@section('title', 'Project Documents')

@section('content')
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/create_project.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    @if (isset($_GET['view']) && $_GET['view'] === 'detailed')

    @else
        <span id="stepNum" data-step="4"></span>
    @endif

    <div class="container">
        <div class="row project-document-title">
            <h3>Project Documents</h3>
        </div>

        <div class="row">
            <input id="go_back" class="btn btn-primary btn-view-jobsheet project-create-continue" type="button"
                value="Back" />
        </div>

        <div class="row">
            @if (isset($jobInfoSheet) && count($jobInfoSheet->jobFiles) > 0)
                @foreach ($jobInfoSheet->jobFiles as $file)
                    <div class="col-md-2" id="id{{ $file->id }}">
                        <div class="fileRow projFileRow">
                            <div class="col-xs-10">
                                <div class="fileName">
                                    <a href="{{ env('ASSET_URL') }}/upload/{{ $file->file }}" target="_blank">
                                      <i class="fa fa-file mr-2"></i> {{ $file->file }}</a>
                                    </a>
                                </div>
                            </div>

                            <div class="col-xs-2 fileBtn"
                                style="display: {{ isset($jobInfoSheet) && $jobInfoSheet != '' && $jobInfoSheet->status == '2' ? 'none' : 'block' }};">
                                <button type="button" class="btn btn-xs btn-danger removeBtn"
                                    data-id="{{ $file->id }}"><i class="fa fa-times"></i> </button>
                            </div>
                        </div>
                    </div>
                @endforeach

            @else
                <div class="fileRow projFileRow">
                    <div class="fileName">
                        <h4> No Documents found for this Project </h4>
                    </div>
                </div>
            @endif

            <form id="document_form" action={{ route('save.new.job.document') }} method="post"
                class="form-horizontal project-form project_details create-project-form form-no-padding create-project-form--edit create-project-form--large"
                style="width:100%; margin:auto;">
                {{ csrf_field() }}
                <input id="lien" name="lien" class="lien" hidden />
                <div class="row center-part container" style="text-align:center">
                    <div class="col-sm-12">
                        <h2>Upload Your document</h2>
                    </div>
                    <div class="col-sm-12">
                        <div class="item">
                            <div class="form-group project_div" style="padding:20px;">
                                <input id="project_name" name="project_name" class="project_name"
                                    value="{{ $project->project_name }}" hidden />
                                <input id="project_id" name="project_id" class="project_id" value="{{ $project->id }}"
                                    hidden />
                            </div>
                        </div>
                        <div>
                            <div class="needsclick dropzone" id="document-dropzone">
                            </div>
                        </div>
                        <div style="height:20px; width: 10px;"> </div>
                        <input class="btn btn-info pull-right" type="submit" value="Save">
                    </div>
            </form>
        </div>
    </div>
    </div>
@endsection

@section('script')
    <script>
        var token = '{{ csrf_token() }}';
        var addFileUrl = "{{ route('add.job.file') }}";
        var baseUrl = "{{ env('ASSET_URL') }}";
        var customerContactRoute = "{{ route('customer.submit.contact') }}";
        var removeFile = "{{ route('remove.job.info.file') }}"

        var project_id = "{{ $project->id }}";
        var user_id = "{{ Auth::user()->id }}";
        $('.btn-view-jobsheet').on('click', function(event) {
            //event.stopPropagation();
            event.preventDefault();
            // 16 aug
            window.location.href = '/';

        });
        var uploadedDocumentMap = {}
        Dropzone.options.documentDropzone = {
            url: "{{ route('add.job.file') }}",
            maxFilesize: 1024, // MB
            addRemoveLinks: true,
            clickable: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(file, response) {
                console.log(file);
                var form = $('#document_form');
                console.log(form);
                form.append('lien');

                $('#document-dropzone').append('<input type="hidden" name="newfiles[]" value="' + response.name +
                    '">')
                uploadedDocumentMap[file.name] = response.name
            },
            removedfile: function(file) {
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedDocumentMap[file.name]
                }
                $('#document-dropzone').find('input[name="document[]"][value="' + name + '"]').remove()
            },
            init: function() {
                @if (isset($project) && $project->document)
                    var files =
                    {!! json_encode($project->document) !!}
                    for (var i in files) {
                    var file = files[i]
                    this.options.addedfile.call(this, file)
                    file.previewElement.classList.add('dz-complete')
                    $('#dragandrop').append('<input type="hidden" name="document[]" value="' + file.file_name + '">')
                    }
                @endif
            }
        }
    </script>

    <script src="{{ env('ASSET_URL') }}/js/modals/contacts/job_info.js"></script>

    <script src="{{ env('ASSET_URL') }}/js/job_info_dates.js"></script>
    @if (isset($_GET['create']))
        <script src="{{ env('ASSET_URL') }}/js/createProject.js"></script>
    @endif
@endsection
