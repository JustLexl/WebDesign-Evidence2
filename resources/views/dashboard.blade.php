@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">Dashboard</h5>
                </div>
                <div class="card-body p-4">
                    <p class="fs-5 mb-4">
                        Welcome, <strong>{{ Auth::user()->name }}</strong> 
                        <span class="badge bg-secondary ms-1">{{ Auth::user()->department->name ?? 'Staff' }}</span>
                    </p>

                    <div class="list-group gap-2 mb-4">
                        <a href="{{ route('orders.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 border rounded">
                            <div>
                                <h6 class="mb-1 fw-bold text-primary">Orders</h6>
                                <small class="text-muted">View orders list, create new order, update status, and upload evidence photos.</small>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $ordersCount }} active</span>
                        </a>

                        <a href="{{ route('orders.archived') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 border rounded">
                            <div>
                                <h6 class="mb-1 fw-bold text-danger">Archived Orders</h6>
                                <small class="text-muted">View logically deleted orders with the option to retrieve and restore them.</small>
                            </div>
                            <span class="badge bg-danger rounded-pill">{{ $archivedCount }} archived</span>
                        </a>

                        <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 border rounded">
                            <div>
                                <h6 class="mb-1 fw-bold text-dark">Users</h6>
                                <small class="text-muted">View active and inactive users, create user with role/department, or edit user data.</small>
                            </div>
                            <span class="badge bg-dark rounded-pill">{{ $usersCount }} users</span>
                        </a>

                        <a href="{{ route('home') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center p-3 border rounded">
                            <div>
                                <h6 class="mb-1 fw-bold text-success">Track Order (Public View)</h6>
                                <small class="text-muted">Search order by invoice number and view delivery status / evidence.</small>
                            </div>
                            <span class="badge bg-success rounded-pill">Search</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
