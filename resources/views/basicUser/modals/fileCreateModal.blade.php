<div id="fileCreateModal" class="modal modal--small fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Open A Job Information Sheet</h4>
      </div>
      <div class="modal-body">
          @php
            $user = \App\User::find(Auth::user()->id);
            $projects = \App\Models\ProjectDetail::where('user_id', '=', $user->id)->orderByRaw('id DESC')->get();
            $preferences = \App\Models\UserPreferences::where('user_id', $user->id)->first();
        @endphp
        <label for="selectProject" class="blue-label">Select A Project</label>
        <select id="selectProject" name="selectProject" class="form-control">
            @foreach($projects as $project)
                @if(!empty($project))
                <option value="{{$project->id}}">{{$project->project_name}}</option>
                @else
                @endif
            @endforeach
        </select>
        <input type="hidden" id="jobInfoHide" value="{{ csrf_token() }}">
        <input type="hidden" id="jobInfoURL" value="{{ env('ASSET_URL') }}">
      </div>
      <div class="modal-footer">
          @if(isset($preferences) && !empty($preferences))
          <button type="button" class="btn btn-info" id="launchJobInfo" data-id="{{$user->id}}" data-url="{{ route('member.create.jobinfo.blank') }}">Create New Claim</button>
          @endif
        <button type="button" class="btn btn-primary" id="openFile">Open Job Info</button>
      </div>
    </div>
  </div>
</div>
