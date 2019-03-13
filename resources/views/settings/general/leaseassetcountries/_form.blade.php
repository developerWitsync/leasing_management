<form method="post" class="form-horizontal">
    {{ csrf_field() }}
    <div class="form-group{{ $errors->has('country_id') ? ' has-error' : '' }} col-md-12 required">
        <label for="country_id" class="col-md-4 control-label">Select Country</label>
        <div class="col-md-8">
            <select name="country_id" class="form-control">
                <option value="">--Select Country--</option>
                @foreach($countries as $country)
                    <option value="{{$country->id}}">{{$country->name}}</option>
                @endforeach
            </select>
            @if ($errors->has('country_id'))
                <span class="help-block">
                    <strong>{{ $errors->first('country_id') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <button type="submit" class="btn btn-success">
                Submit
            </button>
        </div>
    </div>

</form>