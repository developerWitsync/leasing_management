<table class="table table-bordered table-responsive">
    <tbody>
        @foreach($lease->assets as $key=>$asset)
            <tr class="sub_table">
                <td colspan="5" class="tableInner">
                    <table class="table table-bordered table-responsive">
                        <tr>
                            <th>No of Lease Payment</th>
                            <th>Lease Payment Name</th>
                            <th>Lease Payment Type</th>
                            <th>Lease Payment Nature</th>
                            <th>Action</th>
                        </tr>
                        @foreach($asset->payments as $innerKey => $payment)
                            <tr>
                                <td>{{ $innerKey + 1 }}</td>
                                <td>{{ $payment->name }}</td>
                                <td>{{ $payment->category->title }}</td>
                                <td>{{ $payment->paymentNature->title }}</td>
                                <td>
                                    <a href="{{ route('lease.escalation.create', ['id' => $payment->id, 'lease' => $asset->lease->id]) }}" class="btn btn-sm btn-primary">Provide Escalation Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>