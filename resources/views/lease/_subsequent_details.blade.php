@if($subsequent_modify_required)
    <div class="clearfix">
        <div class="col-md-12 subsmentBx">
            <div class="col-md-6" style="padding: 0">
                Subsequent Remeasurement Effective From
            </div>
            <div class="col-md-6"
                 style="padding: 0;text-align: right">{{ \Carbon\Carbon::parse($lease->modifyLeaseApplication->last()->effective_from)->format(config('settings.date_format')) }}</div>
        </div>
        <div class="col-md-12 subsmentBx">
            <div class="col-md-6" style="padding: 0">
                Subsequent Remeasurement No.
            </div>
            <div class="col-md-6"
                 style="padding: 0;text-align: right">#{{ $lease->modifyLeaseApplication->last()->id }}</div>
        </div>
    </div>
@endif