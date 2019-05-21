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
                    <label for="price_plan_type">Select Plan Price Type(required)</label>
                    <select class="form-control @if($errors->has('price_plan_type')) is-invalid @endif" name="price_plan_type" disabled="disabled">
                        <option value="">--Select Plan Price Type--</option>
                        <option value="1" selected="selected">Non-Customizable</option>
                        <option value="2">Customizable</option>
                    </select>

                    @if ($errors->has('price_plan_type'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('price_plan_type') }}</strong>
                        </div>
                    @endif
                </div>

            </div>

            <div class="form-row">

                <div class="form-group col-md-6">
                    <label for="email">Email(Email of the user for which the plan needs to be created)</label>
                    <input id="email" type="text"
                           class="form-control @if($errors->has('email')) is-invalid @endif"
                           name="email"
                           value="{{ old('email', $model->email) }}" autocomplete="off">

                    @if ($errors->has('email'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('email') }}</strong>
                        </div>
                    @endif
                </div>

                <div class="form-group col-md-6 plan_price">
                    <label for="price">Plan Price</label>
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
                    <select class="form-control @if($errors->has('hosting_type')) is-invalid @endif" name="hosting_type">
                        <option value="">--Select Hosting Type--</option>
                        <option value="cloud" @if(old('hosting_type', $model->hosting_type) == 'cloud') selected="selected" @endif>Cloud Hosting</option>
                        <option value="on-premise" @if(old('hosting_type', $model->hosting_type) == 'on-premise') selected="selected" @endif>On Premises</option>
                        <option value="both" @if(old('hosting_type', $model->hosting_type) == 'both') selected="selected" @endif>Both</option>
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

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="annual_discount">Annual Discount</label>
                    <input id="annual_discount" type="number"
                           class="form-control @if($errors->has('annual_discount')) is-invalid @endif"
                           name="annual_discount" value="{{ old('annual_discount', $model->annual_discount) }}" autocomplete="off">

                    @if ($errors->has('annual_discount'))
                        <div class="invalid-feedback">
                            <strong>{{ $errors->first('annual_discount') }}</strong>
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
        // $('select[name="price_plan_type"]').on('change', function(){
        //     var selected_value = $(this).val();
        //     if(selected_value == '1'){
        //         $('.plan_price').show();
        //     } else {
        //         $('#price').val('');
        //         $('.plan_price').hide();
        //     }
        // });
    </script>
@endsection