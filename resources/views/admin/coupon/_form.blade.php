<form method="post" action="">
    {{ @csrf_field() }}

    <div class="form-row">

        <div class="form-group col-md-6">
            <label for="name">Code (required)</label>
            <input type="text" class="form-control @if($errors->has('code')) is-invalid @endif" value="{{ old('code', $model->code) }}" name="code" id="code" placeholder="Coupon Code" autocomplete="off">
            @if($errors->has('code'))
                <div class="invalid-feedback">
                    {{ $errors->first('code') }}
                </div>
            @endif
        </div>

        <div class="form-group col-md-6">
            <label for="iso_code">Select User (Leave blank in case you want to create for all users)</label>
            <select class="form-control select2" name="user_id">
                @if($model->user)
                    <option value="{{ $model->user->id }}" selected="selected">{{ $model->user->email }}</option>
                @endif
            </select>
            @if($errors->has('user_id'))
                <div class="invalid-feedback">
                    {{ $errors->first('user_id') }}
                </div>
            @endif
        </div>

    </div>


    <div class="form-row">

        <div class="form-group col-md-6">
            <label for="iso_code">Select Plan (Leave blank in case it can be applied anywhere)</label>
            <select class="form-control" id="plan" name="plan_id">
                <option value="">--Select Plan--</option>
                @foreach($plans as $plan)
                    <option value="{{$plan->id}}" @if(old('plan_id', $model->plan_id) == $plan->id) selected="selected" @endif>{{ $plan->title }}</option>
                @endforeach
            </select>
            @if($errors->has('plan_id'))
                <div class="invalid-feedback">
                    {{ $errors->first('plan_id') }}
                </div>
            @endif
        </div>

        <div class="form-group col-md-6">
            <label for="discount">Discount(In Percentage)</label>
            <input type="text" class="form-control @if($errors->has('discount')) is-invalid @endif" id="discount" name="discount" placeholder="Discount" value="{{ old('discount', $model->discount) }}" autocomplete="off">
            @if($errors->has('discount'))
                <div class="invalid-feedback">
                    {{ $errors->first('discount') }}
                </div>
            @endif
        </div>

    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="status">Status (required)</label>
            <select name="status" id="status" class="form-control @if($errors->has('status')) is-invalid @endif">
                <option value="">Select Status</option>
                <option value="1" @if(old('status',  $model->status) == "1") selected="selected" @endif>Enable</option>
                <option value="0" @if(old('status', $model->status) == "0") selected="selected" @endif>Disable</option>
            </select>
            @if($errors->has('status'))
                <div class="invalid-feedback">
                    {{ $errors->first('status') }}
                </div>
            @endif
        </div>
    </div>

    <button class="btn btn-primary" type="submit">Submit</button>
    <a href="{{ route('admin.coupon.index') }}" class="btn btn-danger">Cancel</a>

</form>