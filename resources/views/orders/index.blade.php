@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Orders Management</h2>
            <p class="text-muted mb-0">General list of all active orders (ordered from newest to oldest)</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('orders.create') }}" class="btn btn-primary">+ Create New Order</a>
            <a href="{{ route('orders.archived') }}" class="btn btn-outline-danger">Archived Orders</a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('orders.index') }}" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label for="invoice_number" class="form-label small fw-bold">Invoice #</label>
                    <input type="text" class="form-control form-control-sm" id="invoice_number" name="invoice_number" value="{{ request('invoice_number') }}" placeholder="Search invoice...">
                </div>
                <div class="col-md-3">
                    <label for="customer_number" class="form-label small fw-bold">Customer #</label>
                    <input type="text" class="form-control form-control-sm" id="customer_number" name="customer_number" value="{{ request('customer_number') }}" placeholder="Search customer...">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label small fw-bold">Status</label>
                    <select class="form-select form-select-sm" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="Ordered" {{ request('status') === 'Ordered' ? 'selected' : '' }}>Ordered</option>
                        <option value="In process" {{ request('status') === 'In process' ? 'selected' : '' }}>In process</option>
                        <option value="In route" {{ request('status') === 'In route' ? 'selected' : '' }}>In route</option>
                        <option value="Delivered" {{ request('status') === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date" class="form-label small fw-bold">Date</label>
                    <input type="date" class="form-control form-control-sm" id="date" name="date" value="{{ request('date') }}">
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary flex-grow-1">Filter</button>
                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Active Orders</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice #</th>
                            <th>Customer #</th>
                            <th>Customer Name</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Evidence Photos</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="fw-bold text-primary">{{ $order->invoice_number }}</td>
                                <td>{{ $order->customer_number }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->order_datetime ? $order->order_datetime->format('Y-m-d H:i') : 'N/A' }}</td>
                                <td>
                                    @if ($order->status === 'Delivered')
                                        <span class="badge bg-success">Delivered</span>
                                    @elseif ($order->status === 'In route')
                                        <span class="badge bg-primary">In route</span>
                                    @elseif ($order->status === 'In process')
                                        <span class="badge bg-warning text-dark">In process</span>
                                    @else
                                        <span class="badge bg-secondary">Ordered</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($order->route_photo)
                                        <span class="badge bg-info text-dark me-1" title="Loaded unit photo">Route Photo</span>
                                    @endif
                                    @if ($order->delivery_photo)
                                        <span class="badge bg-success" title="Delivered photo">Delivery Photo</span>
                                    @endif
                                    @if (!$order->route_photo && !$order->delivery_photo)
                                        <span class="text-muted small">None</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary">View</a>
                                        <a href="{{ route('orders.edit', $order) }}" class="btn btn-outline-secondary">Edit</a>
                                        <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('Are you sure you want to logically delete this order?');" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No orders found matching criteria.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($orders->hasPages())
            <div class="card-footer bg-white py-3">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
