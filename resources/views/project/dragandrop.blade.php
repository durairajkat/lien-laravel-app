@section('content')
    <link rel="stylesheet" href="{{ env('ASSET_URL') }}/css/create_project.css">
    {{-- CSS assets in head section --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />

    {{-- ... a lot of main HTML code ... --}}
    <form id="dragdrop" action="{{ route('project.save') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Name/Description fields, irrelevant for this article --}}

        <div class="form-group">
            <label for="document">Documents</label>
            <div class="needsclick dropzone" id="document-dropzone">

            </div>
        </div>
        <div>
            <input class="btn btn-danger" type="submit">
        </div>
    </form>

    @include('basicUser.project.dragandrop')
@endsection
