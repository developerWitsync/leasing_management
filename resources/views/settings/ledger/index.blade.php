<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Define Ledgers</h4>
</div>
<div class="modal-body">
    <form id="save_ledgers" action="{{ route('settings.ledger.save') }}" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="category_id" value="{{ $category_id }}">
        <table class="table table-bordered table-active">
            <thead>
            <tr>
                <th scope="col">Lease Accounting Ledgers</th>
                <th scope="col">GL Account Name</th>
                <th scope="col">GL Account Code</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><strong>Lease Asset</strong></td>
                <td>
                    <input type="text" class="form-control" value="@if(isset($ledgers_data['lease_asset'])){{ $ledgers_data['lease_asset']['account_name'] }}@endif" name="ledger[lease_asset][account_name]">
                </td>
                <td>
                    <input type="text" class="form-control" value="@if(isset($ledgers_data['lease_asset'])){{ $ledgers_data['lease_asset']['account_code'] }}@endif" name="ledger[lease_asset][account_code]">
                </td>
            </tr>
            <tr>
                <td><strong>Lease Liability</strong></td>
                <td>
                    <input type="text" class="form-control" value="@if(isset($ledgers_data['lease_liability'])){{ $ledgers_data['lease_liability']['account_name'] }}@endif" name="ledger[lease_liability][account_name]">
                </td>
                <td>
                    <input type="text" class="form-control" value="@if(isset($ledgers_data['lease_liability'])){{ $ledgers_data['lease_liability']['account_code'] }}@endif" name="ledger[lease_liability][account_code]">
                </td>
            </tr>
            <tr>
                <td><strong>Lease Interest</strong></td>
                <td>
                    <input type="text" class="form-control" value="@if(isset($ledgers_data['lease_interest'])){{ $ledgers_data['lease_interest']['account_name'] }}@endif" name="ledger[lease_interest][account_name]">
                </td>
                <td>
                    <input type="text" class="form-control" value="@if(isset($ledgers_data['lease_interest'])){{ $ledgers_data['lease_interest']['account_code'] }}@endif" name="ledger[lease_interest][account_code]">
                </td>
            </tr>
            <tr>
                <td><strong>Lease Depreciation</strong></td>
                <td>
                    <input type="text" class="form-control" value="@if(isset($ledgers_data['lease_depreciation'])){{ $ledgers_data['lease_depreciation']['account_name'] }}@endif" name="ledger[lease_depreciation][account_name]">
                </td>
                <td>
                    <input type="text" class="form-control" value="@if(isset($ledgers_data['lease_depreciation'])){{ $ledgers_data['lease_depreciation']['account_code'] }}@endif" name="ledger[lease_depreciation][account_code]">
                </td>
            </tr>
            <tr>
                <td><strong>Lease Accumulated Depreciation</strong></td>
                <td>
                    <input type="text" class="form-control" value="@if(isset($ledgers_data['lease_accumulated_depreciation'])){{ $ledgers_data['lease_accumulated_depreciation']['account_name'] }}@endif" name="ledger[lease_accumulated_depreciation][account_name]">
                </td>
                <td>
                    <input type="text" class="form-control" value="@if(isset($ledgers_data['lease_accumulated_depreciation'])){{ $ledgers_data['lease_accumulated_depreciation']['account_code'] }}@endif" name="ledger[lease_accumulated_depreciation][account_code]">
                </td>
            </tr>
            <tr>
                <td><strong>Lease Expense</strong></td>
                <td>
                    <input type="text" class="form-control" value="@if(isset($ledgers_data['lease_expense'])){{ $ledgers_data['lease_expense']['account_name'] }}@endif" name="ledger[lease_expense][account_name]">
                </td>
                <td>
                    <input type="text" class="form-control" value="@if(isset($ledgers_data['lease_expense'])){{ $ledgers_data['lease_expense']['account_code'] }}@endif" name="ledger[lease_expense][account_code]">
                </td>
            </tr>
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary pull-right">Submit</button>
    </form>
</div>
<div class="modal-footer">
    &nbsp;&nbsp;&nbsp;
</div>