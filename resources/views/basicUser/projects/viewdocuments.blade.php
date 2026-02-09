@extends('basicUser.projects.create')
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>

@section('body')
    @php
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    $mobile =
        preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) ||
        preg_match(
            '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',
            substr($useragent, 0, 4),
        );
    @endphp
    @if (isset($_GET['view']) && $_GET['view'] === 'detailed')
        <span id="stepNumDetailed" data-step="4"></span>
    @else
        <span id="stepNum" data-step="3"></span>
    @endif

    @include('basicUser.partials.multi-step-form')

    @if (isset($_GET['edit']))
        <span id="editFlag"></span>
        {{-- <form action="#" method="post" class="form-horizontal project-form project_details create-project-form form-no-padding create-project-form--edit"> --}}
        <form id="document_form" action={{ route('save.new.job.document') }} method="post"
            class="form-horizontal project-form project_details create-project-form form-no-padding create-project-form--edit create-project-form--large"
            style="width:100%; margin:auto;">

        @else
            <form action="#" method="post" class="form-horizontal project-form project_details create-project-form">
    @endif
    <div class="buttons-on-top row button-area">
        <a href="javascript:void(0)" id="skip-button-3-out" class="skip-job-description project-create-skip">
            Skip
        </a>

        <a href="javascript:void(0)" id="activate-step-8-out" class="save-job-description project-create-continue">
            Save & Continue
        </a>
    </div>
    <div class="create-project-form-bgcolor">
        <div class="create-project-form-header">
            <h2>Project Documents</h2>
        </div>
        @if (isset($_GET['edit']))
            <div class="form-padding-wrapper">
        @endif

        <div>

            {{-- <div class="row">
                <input id="go_back" class="btn btn-primary btn-view-jobsheet project-create-continue"type="button" value="Back"/>
            </div> --}}
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
                            <h4>No Documents found for this Project</h4>
                        </div>
                    </div>
                @endif

                {{-- <form id="document_form" action={{ route('save.new.job.document') }} method="post" class="form-horizontal project-form project_details create-project-form form-no-padding create-project-form--edit create-project-form--large" style="width:100%; margin:auto;"> --}}
                {{ csrf_field() }}
                <input id="lien" name="lien" class="lien" hidden />
                <div class="row center-part" style="text-align:center">
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
                        {{-- <input class="btn btn-info pull-right" type="submit" value="Save"> --}}
                    </div>
                </div>
            </div>
        </div>
</div>
</div>
        <div class="flex items-center save-skip">
            <a href="javascript:void(0)" id="skip-button-3" class="skip">
                Skip
            </a>
            <a href="javascript:void(0)" type="submit" id="saveDocuments"
                class="orange-btn">
                Save & Continue
            </a>
        </div>
        </form>

        <div class="col-md-12 col-sm-12 no-pad">
            @if (!isset($_GET['edit']))
                <h4 class="text-center">If you would like to complete a claim please select File A Claim, if not you may
                    save and quit.</h4>
                @if (isset($_GET['view']) && $_GET['view'] === 'detailed')
                    <a href="{{ route('member.create.edit.jobdescription', [$project_id]) . '?create=true&view=detailed' }}"
                        id="activate-step-4" class="project-create-continue">
                        File A Claim
                    </a>
                @else
                    <a href="{{ route('member.create.edit.jobdescription', [$project_id]) . '?create=true&view=detailed' }}"
                        id="activate-step-4" class="project-create-continue">
                        File A Claim
                    </a>
                @endif
                <a href="" id="save_quit" class="project-save-quit">
                    Save & Quit
                </a>
            @endif
        </div>
    </div>
    </div>
    </form>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('html, body').animate({
                scrollTop: $('#progressbar').offset().top
            }, 'slow');

            $(document).on('click', '#save_quit', function(e) {
                e.preventDefault()
                window.location = "{{ route('member.dashboard') }}"
            })

            $(document).on('click', '.mobile-nav-tab', function() {
                let tab = $(this).attr('data-tab')
                $('.mobile-nav--menu').attr('data-target', tab)
            })

            $(document).on('click', '.sidenav', function() {
                $(".sidenav").css('width', '0px');
            })

            $('.skip').on('click', function(event) {
                //event.stopPropagation();
                event.preventDefault();
                swal({
                    title: 'Are you sure you want to skip this?',
                    // text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    // buttonsStyling: false
                }).then(function() {
                    window.location.href = '/member/create/project/deadlines/' + project_id +
                        '?edit=true';
                })
            });

            $('.save-job-description').on('click', function(event) {
                //event.stopPropagation();
                event.preventDefault();
                window.location.href = '/member/project/summary/view/' + project_id + '?edit=true';
            });

            $(document).on('click', '#saveDocuments', function(event) {
                event.preventDefault();
                let data = $('#document_form').serializeArray();

                console.log(data);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('save.new.job.document') }}",
                    data: data,
                    success: function(data) {
                        window.location.href = "{{ route('create.deadlines', $project->id) . '?edit=true' }}";
                    }
                });
            });
        })

        function openNav(e) {
            let menu = $('.mobile-nav--menu').attr('data-target')

            if (menu == 'express') {
                $('#mobileNav').css('width', '100%');
            } else {
                $('#mobileNavDetailed').css('width', '100%')
            }
        }

        function closeNav() {
            $(".sidenav").css('width', '0px');
        }

        var uploadedDocumentMap = {}
        Dropzone.options.documentDropzone = {
            url: "{{ route('add.job.file') }}",
            maxFilesize: 1024, // MB
            addRemoveLinks: true,
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

    <script>
        let token = '{{ csrf_token() }}'
        let baseUrl = "{{ env('ASSET_URL') }}"
        let project_id = "{{ $project_id }}"
        let submitDate = "{{ route('project.dates.submit') }}"
        let updateDate = "{{ route('project.dates.update') }}"
    </script>

    <script src="{{ env('ASSET_URL') }}/js/modals/contacts/job_info.js"></script>
    <script src="{{ env('ASSET_URL') }}/js/job_info_dates.js"></script>
    <script src="{{ env('ASSET_URL') }}/js/createProject.js"></script>
@endsection
