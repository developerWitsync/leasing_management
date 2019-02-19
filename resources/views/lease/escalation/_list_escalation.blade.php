<table class="table table-bordered table-responsive">
    <thead>
    <tr>
        <th>Sr. No.</th>
        <th>Lease Asset</th>
        <th>Lease Asset Classification</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
        @foreach($lease->assets as $key=>$asset)
            <tr>
                <td>{{ $key + 1 }}</td>
               
                <td>
                    {{ $asset->name }}
                </td>
                <td>
                    {{ $asset->subcategory->title }}
                </td>
                <td>
                    <a href="javascript:void(0)" class="btn btn-success sub_drop_escalation" title="Provide Escalation Details"><i class="fa fa-minus-square"></i></a>
                </td>
            </tr>
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