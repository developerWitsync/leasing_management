<table class="table table-bordered table-responsive">
    <thead>
    <tr>
        <th>Sr. No.</th>
        <th>Unique ULA Code</th>
        <th>Name of the Underlying Lease Asset</th>
        <th>Underlying Lease Asset Classification</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
        @foreach($lease->assets as $key=>$asset)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td style="width: 10%">
                    {{ $asset->uuid}}
                </td>
                <td>
                    {{ $asset->name }}
                </td>
                <td>
                    {{ $asset->subcategory->title }}
                </td>
                <td>
                    <a href="javascript:void(0)" class="btn btn-success sub_drop_escalation" title="Provide Escalation Details"><i class="fa fa-plus-square"></i></a>
                </td>
            </tr>
            <tr class="sub_table" style="display: none;">
                <td colspan="5" class="tableInner">
                    <table class="table table-bordered table-responsive">
                        <tr>
                            <th>Number of Lease Payment</th>
                            <th>Name of Lease Payment</th>
                            <th>Type of Lease Payment</th>
                            <th>Nature of Lease Payment</th>
                            <th>Action</th>
                        </tr>
                        @foreach($asset->payments as $payment)
                            <tr>
                                <td>{{ $payment->id }}</td>
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