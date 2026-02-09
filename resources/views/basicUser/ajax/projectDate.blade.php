<div class="col-md-12">
    @foreach ($dates as $date)
        <div class="form-group">
            <label class="col-md-6 control-label">{{ $date->date_name }}</label>
            <div class="col-md-6">
                <input type="text" class="form-control date" name="remedyDates[{{ $date->id }}]"
                    data-provide="datepicker">
            </div>
        </div>
    @endforeach
</div>
