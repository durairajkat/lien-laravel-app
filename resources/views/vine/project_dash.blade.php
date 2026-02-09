@extends('basicUser.layout.main')

@section('title', 'Job Dashboard')

@section('style')
    <style>
        .dropdown-menu {
            width: 215px;
        }

        .blue-btn-ext {
            background: #1084ff;
            color: #fff;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0 !important;
        }

    </style>
@endsection
@section('content')
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/create_project.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <?php
    
    // THIS IS TEMPORARILY, WILL BE REWRITTEN PROPERLY
    
    require_once "vine/{$vine_view}.php";
    
    ?>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.button-edit').click(function() {
                $('#dragdrop').slideToggle();
            });
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



    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script> -->
    {{-- ...Some more scripts... --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>






    <script>
        var uploadedDocumentMap = {}
        Dropzone.options.documentDropzone = {
            url: "{{ route('project.storeMedia') }}",
            maxFilesize: 1024, // MB
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function(file, response) {
                console.log(file);
                $('#dragandrop').append('<input type="hidden" name="document[]" value="' + response.name + '">')
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
                $('#dragandrop').find('input[name="document[]"][value="' + name + '"]').remove()
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



@endsection
