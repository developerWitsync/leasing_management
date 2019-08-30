<div class="panel panel-info">
    <div class="panel-heading">
        Set Up your Ledger Levels
        {{--{!! renderToolTip('Add the expected useful life of the lease asset to tag to your respective lease asset.', null, 'right') !!}--}}
    </div>
    <div class="panel-body settingTble">
        <form action="{{ route('settings.leaseassets.saveledgeroptions') }}" method="post">
            {{ csrf_field() }}
            <div class="setting form-group {{ $errors->has('ledger_level') ? ' has-error' : '' }} required">
                <div class="col-md-12 rightx">
                    <div class="input-group col-md-12">
                        <div class="form-check col-md-4 ">
                            <input class="form-check-input" type="radio"
                                   name="ledger_level" value="1" id="lease_asset_category_level" @if($general_settings->ledger_level == 1) checked="checked" @endif>
                            <label class="form-check-label" for="lease_asset_category_level">
                                Lease Asset Category Level
                            </label>
                        </div>

                        <div class="form-check col-md-4">
                            <input class="form-check-input" type="radio" name="ledger_level" value="2"
                                   id="lease_asset_sub_category_level" @if($general_settings->ledger_level == 2) checked="checked" @endif>
                            <label class="form-check-label" for="lease_asset_sub_category_level">
                                Lease Asset Sub Category Level
                            </label>
                        </div>

                        <div class="form-check col-md-4">
                            <button type="submit" class="btn btn-success">
                                Save
                            </button>
                        </div>

                    </div>
                    @if ($errors->has('ledger_level'))
                        <span class="help-block">
                            <strong>{{ $errors->first('ledger_level') }}</strong>
                        </span>
                    @endif
                </div>

                {{--<div class="col-md-12 rightx">--}}
                {{--<div class="col-md-4">--}}
                {{--<div class="modified_retrospective_approach lease_asset_category_level ledger_option hidden" style="text-align: left">--}}
                {{--If this is selected then, define ledgers will be created at category level only where all ledgers will be same for all sub-categories--}}
                {{--of Lease Asset Created.--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-4">--}}
                {{--<div class="full_retrospective_approach lease_asset_sub_category_level ledger_option  hidden" style="text-align: left">--}}
                {{--If this is selected then, define ledgers will be created at sub-category level only where all ledgers--}}
                {{--will either be same or different for each of the sub-categories of Lease Asset Created.--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
            </div>
        </form>
    </div>
</div>