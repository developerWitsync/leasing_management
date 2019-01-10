<div class="modal-header">
    <h5 class="modal-title">Confirm Lease Payment Due Dates</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">

        <div class="alert alert-info">
            <strong>Note!</strong> Below is the list of the payment dates that have been saved previously. If you want to overrider these dates please click on the "Confirm Lease Payment Due Dates".
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Payment Due Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payment->paymentDueDates as $key=>$date)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <strong>
                                    {{ \Carbon\Carbon::parse($date->date)->format('l jS \of F Y') }}
                                </strong>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

</div>
<div class="modal-footer">
   &nbsp;
</div>
