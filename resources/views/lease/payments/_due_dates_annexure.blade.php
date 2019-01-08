<div class="modal-header">
    <h5 class="modal-title">Confirm Lease Payment Due Dates</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">

    @if (!empty($errors))
        <div class="alert alert-danger">
            <ul>
                @foreach (array_unique($errors->all()) as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="alert alert-danger">
            <strong>Note!</strong> The below information is calculated on the basis of the input dates, your previous changes(if any) has been lost. However you can edit the dates again.
        </div>

        <div class="row">
            <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        @foreach($years as $year)
                            <th>{{ $year }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                @foreach($months as $key=>$month)
                    <tr>
                        <td>
                            <strong>
                                {{ $month }}
                            </strong>
                        </td>
                        @foreach($years as $year)
                            @if(isset($final_payout_dates[$year][$month]))
                                <td class="success">
                                    @foreach($final_payout_dates[$year][$month] as $date)
                                        <span class="alter_due_dates_info">
                                            {{ \Carbon\Carbon::parse($date)->format('l jS \of F Y') }}
                                        </span><br/>
                                        <input type="text" data-month="{{ $key }}" data-year="{{ $year }}" class="form-control alter_due_dates_input hidden" name="altered_payment_due_date[]" value="{{ $date }}">
                                    @endforeach
                                </td>
                            @else
                                <td class="info">
                                    <input type="text" data-month="{{ $key }}" data-year="{{ $year }}" class="form-control alter_due_dates_input hidden" name="altered_payment_due_date[]" value="">
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>
        </div>
    @endif

</div>
<div class="modal-footer">
    @if (empty($errors))
        <button type="button" class="btn btn-danger edit_payment_due_dates">Edit</button>
        <button type="button" class="btn btn-success confirm_payment_due_dates">Confirm</button>
    @else
        &nbsp;

    @endif
</div>
