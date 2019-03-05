<form class="form-horizontal" method="POST" action="">
    {{ csrf_field() }}
    <div class="form-group{{ $errors->has('display_name') ? ' has-error' : '' }} required">
        <label for="display_name" class="col-md-4 control-label">Name</label>
        <div class="col-md-6">
            <div class="input-group reportTble">
                <input id="display_name" type="text" placeholder="Display Name"
                       class="form-control" name="display_name" value="{{ old('display_name', $model->display_name) }}">
            </div>
            @if ($errors->has('display_name'))
                <span class="help-block">
                    <strong>{{ $errors->first('display_name') }}</strong>
                </span>
            @endif
        </div>
    </div>


    <div class="form-group{{ $errors->has('permission') ? ' has-error' : '' }} required">
        <label for="description" class="col-md-4 control-label">Permissions</label>
        <div class="col-md-6">
            @foreach($permissions as $permission)
                <div class="checkbox">
                    <label><input type="checkbox" value="{{$permission->id}}"
                                  @if(in_array($permission->id, $assignedPermissions)) checked="checked"
                                  @endif @if($permission->id == "2") disabled="disabled"
                                  @endif name="permission[]">{{ $permission->display_name }}</label>
                </div>
            @endforeach
            @if ($errors->has('permission'))
                <span class="help-block">
                    <strong>{{ $errors->first('permission') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }} ">
        <label for="description" class="col-md-4 control-label">Description</label>
        <div class="col-md-6">
            <div class="input-group reportTble">
                <textarea id="description" placeholder="Description" class="form-control"
                          name="description">{{ old('description', $model->description) }}</textarea>
            </div>
            @if ($errors->has('description'))
                <span class="help-block">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <a href="{{ route('settings.useraccess') }}" class="btn btn-danger">Cancel</a>
            <button type="submit" class="btn btn-success">
                Submit
            </button>
        </div>
    </div>

</form>