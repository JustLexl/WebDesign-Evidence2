@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Archived Orders</h2>
            <p class="text-muted mb-0">List of logically deleted orders with the option to retrieve and restore them</p>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">Back to Active Orders</a>
    </div>

    <div class="card shadow-sm border-danger">
        <div class="card-header bg-danger text-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Archived (Logically Deleted) Orders</h5>
            <span class="badge bg-light text-dark fs-6">{{ count($archivedOrders) }} Total</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice #</th>
                            <th>Customer #</th>
                            <th>Customer Name</th>
                            <th>Original Status</th>
                            <th>Deleted At</th>
                            <th>Delivery Address</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($archivedOrders as $order)
                            <tr>
                                <td class="fw-bold text-danger">{{ $order->invoice_number }}</td>
                                <td>{{ $order->customer_number }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $order->status }}</span>
                                </td>
                                <td>{{ $order->deleted_at ? $order->deleted_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                <td>{{ Str::limit($order->delivery_address, 40) }}</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('orders.restore', $order->id) }}" onsubmit="return confirm('Do you want to retrieve and restore order {{ $order->invoice_number }} back to active orders?');" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            Retrieve / Restore Order
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No archived orders found in the trash.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
