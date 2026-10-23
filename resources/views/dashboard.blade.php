@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Administrative Dashboard</h2>
            <p class="text-muted mb-0">Welcome back, <strong>{{ Auth::user()->name }}</strong> (Department: {{ Auth::user()->department->name ?? 'General Staff' }})</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('orders.create') }}" class="btn btn-primary">+ Create New Order</a>
            <a href="{{ route('users.create') }}" class="btn btn-outline-dark">+ Create User</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-primary h-100">
                <div class="card-body">
                    <span class="text-muted small text-uppercase fw-bold">Active Orders</span>
                    <h3 class="fw-bold text-primary mt-2 mb-0">{{ $totalOrders }}</h3>
                    <small class="text-muted">Total non-archived orders</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-warning h-100">
                <div class="card-body">
                    <span class="text-muted small text-uppercase fw-bold">In Process / Picking</span>
                    <h3 class="fw-bold text-warning mt-2 mb-0">{{ $inProcessCount }}</h3>
                    <small class="text-muted">Warehouse preparation</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-info h-100">
                <div class="card-body">
                    <span class="text-muted small text-uppercase fw-bold">In Route / Distributing</span>
                    <h3 class="fw-bold text-info mt-2 mb-0">{{ $inRouteCount }}</h3>
                    <small class="text-muted">Vehicle dispatched</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-success h-100">
                <div class="card-body">
                    <span class="text-muted small text-uppercase fw-bold">Delivered Orders</span>
                    <h3 class="fw-bold text-success mt-2 mb-0">{{ $deliveredCount }}</h3>
                    <small class="text-muted">Completed deliveries</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Orders Management</h5>
                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">View All Orders</a>
                </div>
                <div class="card-body">
                    <p class="text-muted">Manage the full lifecycle of orders from registration by sales to preparation and delivery.</p>
                    <div class="list-group mb-3">
                        <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>General Orders List (Newest to Oldest)</span>
                            <span class="badge bg-primary rounded-pill">{{ $totalOrders }}</span>
                        </a>
                        <a href="{{ route('orders.create') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>Register New Order (Sales)</span>
                            <span class="badge bg-success rounded-pill">+ New</span>
                        </a>
                        <a href="{{ route('orders.archived') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>Archived Orders (Logically Deleted)</span>
                            <span class="badge bg-danger rounded-pill">{{ $archivedCount }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Users and Roles Management</h5>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-dark">View All Users</a>
                </div>
                <div class="card-body">
                    <p class="text-muted">Manage system users, department assignments (Sales, Purchasing, Warehouse, Route), and status.</p>
                    <div class="list-group mb-3">
                        <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>General List of Users</span>
                            <span class="badge bg-dark rounded-pill">{{ $totalUsers }} total</span>
                        </a>
                        <a href="{{ route('users.create') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>Register New User & Assign Department</span>
                            <span class="badge bg-success rounded-pill">+ Add</span>
                        </a>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Active Users: <strong>{{ $activeUsers }}</strong></span>
                            <span>Inactive Users: <strong class="text-danger">{{ $inactiveUsers }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Recent Orders</h5>
            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-primary">See All</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Invoice #</th>
                            <th>Customer #</th>
                            <th>Customer Name</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td class="fw-bold">{{ $order->invoice_number }}</td>
                                <td>{{ $order->customer_number }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->order_datetime ? $order->order_datetime->format('Y-m-d H:i') : 'N/A' }}</td>
                                <td>
                                    @if($order->status === 'Delivered')
                                        <span class="badge bg-success">Delivered</span>
                                    @elseif($order->status === 'In route')
                                        <span class="badge bg-primary">In route</span>
                                    @elseif($order->status === 'In process')
                                        <span class="badge bg-warning text-dark">In process</span>
                                    @else
                                        <span class="badge bg-secondary">Ordered</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
