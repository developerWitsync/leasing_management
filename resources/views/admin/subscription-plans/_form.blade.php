<form method="post" action="" enctype="multipart/form-data">
    {{ @csrf_field() }}

    <div class="row">
        <div class="col-lg-9 col-xl-9">

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="title">Subscription Plan Title (required)</label>
                    <input id="title" type="text"
                           class="form-control @if($errors->has('title')) is-invalid @endif"
                           name="title" value="{{ old('title', $model->title) }}"
                           autocomplete="off">

                    @if ($errors->has('title'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('title') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group col-md-6">
                    <label for="hosting_type">Select Plan Price Type(required)</label>
                    <select class="form-control @if($errors->has('plan_price_type')) is-invalid @endif" name="plan_price_type">
                        <option value="">--Select Plan Price Type--</option>
                        <option value="1">Non-Customizable</option>
                        <option value="2">Customizable</option>
                    </select>

                    @if ($errors->has('plan_price_type'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('plan_price_type') }}</strong>
                        </div>
                    @endif
                </div>



                <div class="form-group col-md-6 plan_price" style="display: none">
                    <label for="price">Plan Price(Leave Blank in case of Free)</label>
                    <input id="price" type="text"
                           class="form-control @if($errors->has('price')) is-invalid @endif"
                           name="price"
                           value="{{ old('price', $model->price) }}" autocomplete="off">

                    @if ($errors->has('price'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('price') }}</strong>
                        </div>
                    @endif
                </div>
            </div>



            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="available_leases">Lease Assets Limit
                        (Leave Blank in Case of Unlimited)</label>
                    <input id="available_leases" type="number"
                           class="form-control @if($errors->has('available_leases')) is-invalid @endif"
                           name="available_leases"
                           value="{{ old('available_leases', $model->available_leases) }}"
                           autocomplete="off">

                    @if ($errors->has('available_leases'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('available_leases') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group col-md-6">
                    <label for="available_users">Available Sub-users (Leave Blank in Case of Unlimited)</label>
                    <input id="available_users" type="number"
                           class="form-control @if($errors->has('available_users')) is-invalid @endif"
                           name="available_users" value="{{ old('available_users', $model->available_users) }}" autocomplete="off">

                    @if ($errors->has('available_users'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('available_users') }}</strong>
                        </div>
                    @endif
                </div>

            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="hosting_type">Hosting Type(required)</label>
                    <select class="form-control @if($errors->has('username')) is-invalid @endif" name="hosting_type">
                        <option value="">--Select Hosting Type--</option>
                        <option value="cloud">Cloud Hosting</option>
                        <option value="on-premise">On Premises</option>
                        <option value="both">Both</option>
                    </select>

                    @if ($errors->has('hosting_type'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('hosting_type') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group col-md-6">
                    <label for="validity">Validity - In Days (Leave Blank in case of Unlimited)</label>
                    <input id="validity" type="number"
                           class="form-control @if($errors->has('validity')) is-invalid @endif"
                           name="validity" value="{{ old('validity', $model->validity) }}" autocomplete="off">

                    @if ($errors->has('validity'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('validity') }}</strong>
                        </div>
                    @endif
                </div>
            </div>

            <button class="btn btn-primary" type="submit">Submit</button>
            <a href="{{ route('admin.subscriptionplans.index') }}" class="btn btn-danger">Cancel</a>

        </div>


    </div>

</form>
@section('footer-script')
    <script>
        $('select[name="plan_price_type"]').on('change', function(){
            var selected_value = $(this).val();
            if(selected_value == '1'){
                $('.plan_price').show();
            } else {
                $('#price').val('');
                $('.plan_price').hide();
            }
        });
    </script>
@endsection