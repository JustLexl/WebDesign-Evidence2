@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0">Halcon - Order Tracking Portal</h4>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted mb-4">
                        Check the real-time status of your construction materials order by entering your invoice number below.
                    </p>

                    <form method="GET" action="{{ route('home') }}" class="row g-3">
                        <div class="col-md-7">
                            <label for="invoice_number" class="form-label fw-bold">Invoice Number (*):</label>
                            <input type="text" class="form-control form-control-lg" id="invoice_number" name="invoice_number" value="{{ $invoiceNumber ?? '' }}" placeholder="e.g. INV-1001" required>
                        </div>
                        <div class="col-md-5">
                            <label for="customer_number" class="form-label fw-bold">Customer Number (Optional):</label>
                            <input type="text" class="form-control form-control-lg" id="customer_number" name="customer_number" value="{{ $customerNumber ?? '' }}" placeholder="e.g. CUST-001">
                        </div>
                        <div class="col-12 d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary btn-lg px-4">Track Order</button>
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg px-3">Clear</a>
                        </div>
                    </form>

                    <div class="mt-4 pt-3 border-top">
                        <span class="text-muted small me-2">Quick test samples:</span>
                        <a href="{{ route('home', ['invoice_number' => 'INV-1001']) }}" class="badge bg-success text-decoration-none me-1">INV-1001 (Delivered)</a>
                        <a href="{{ route('home', ['invoice_number' => 'INV-1002']) }}" class="badge bg-info text-dark text-decoration-none me-1">INV-1002 (In route)</a>
                        <a href="{{ route('home', ['invoice_number' => 'INV-1003']) }}" class="badge bg-warning text-dark text-decoration-none me-1">INV-1003 (In process)</a>
                        <a href="{{ route('home', ['invoice_number' => 'INV-1004']) }}" class="badge bg-secondary text-decoration-none">INV-1004 (Ordered)</a>
                    </div>
                </div>
            </div>

            @if ($searched)
                @if ($order)
                    <div class="card shadow-sm mb-4 border-2 @if($order->status === 'Delivered') border-success @elseif($order->status === 'In process') border-warning @elseif($order->status === 'In route') border-primary @else border-secondary @endif">
                        <div class="card-header d-flex justify-content-between align-items-center @if($order->status === 'Delivered') bg-success text-white @elseif($order->status === 'In process') bg-warning text-dark @elseif($order->status === 'In route') bg-primary text-white @else bg-secondary text-white @endif">
                            <h5 class="mb-0">Order Status: {{ $order->status }}</h5>
                            <span class="badge bg-light text-dark fs-6">{{ $order->invoice_number }}</span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted small">Customer Name:</p>
                                    <h6 class="fw-bold">{{ $order->customer_name }}</h6>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted small">Customer Number:</p>
                                    <h6 class="fw-bold">{{ $order->customer_number }}</h6>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted small">Order Date & Time:</p>
                                    <h6>{{ $order->order_datetime ? $order->order_datetime->format('Y-m-d H:i') : 'N/A' }}</h6>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted small">Delivery Address:</p>
                                    <h6>{{ $order->delivery_address }}</h6>
                                </div>
                            </div>

                            @if ($order->status === 'In process')
                                <div class="alert alert-warning border-warning">
                                    <h5 class="alert-heading fw-bold">Process Information</h5>
                                    <p class="mb-1"><strong>Process Name:</strong> In process</p>
                                    <p class="mb-1"><strong>Process Date:</strong> {{ $order->process_date ? $order->process_date->format('Y-m-d H:i') : $order->updated_at->format('Y-m-d H:i') }}</p>
                                    <hr>
                                    <p class="mb-0 text-muted">The order is currently at the warehouse. Staff is picking items from storage or coordinating low-stock materials with the Purchasing department.</p>
                                </div>
                            @endif

                            @if ($order->status === 'In route')
                                <div class="alert alert-primary border-primary mb-4">
                                    <h5 class="alert-heading fw-bold">Route Distribution in Progress</h5>
                                    <p class="mb-0">Your materials have been dispatched on the delivery vehicle and are currently en route to your specified address.</p>
                                </div>

                                @if ($order->route_photo)
                                    <div class="card mb-3">
                                        <div class="card-header bg-light">
                                            <strong>Loaded Unit Photo Evidence</strong>
                                        </div>
                                        <div class="card-body text-center">
                                            <img src="{{ asset($order->route_photo) }}" alt="Loaded Unit Evidence" class="img-fluid rounded border shadow-sm" style="max-height: 350px;">
                                        </div>
                                    </div>
                                @endif
                            @endif

                            @if ($order->status === 'Delivered')
                                <div class="alert alert-success border-success mb-4">
                                    <h5 class="alert-heading fw-bold">Order Delivered</h5>
                                    <p class="mb-0">This order has been successfully delivered and unloaded at the destination site.</p>
                                </div>

                                @if ($order->delivery_photo)
                                    <div class="card mb-3 border-success">
                                        <div class="card-header bg-success text-white">
                                            <strong>Delivery Photo Evidence</strong>
                                        </div>
                                        <div class="card-body text-center p-3">
                                            <img src="{{ asset($order->delivery_photo) }}" alt="Delivered Material Evidence" class="img-fluid rounded border shadow-sm" style="max-height: 400px;">
                                            <p class="text-muted mt-2 mb-0 small">Photographic evidence of delivered and unloaded material at destination.</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($order->route_photo)
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <strong>Departure Loaded Unit Photo</strong>
                                        </div>
                                        <div class="card-body text-center">
                                            <img src="{{ asset($order->route_photo) }}" alt="Loaded Unit" class="img-fluid rounded border" style="max-height: 250px;">
                                        </div>
                                    </div>
                                @endif
                            @endif

                            @if ($order->status === 'Ordered')
                                <div class="alert alert-secondary border-secondary">
                                    <h5 class="alert-heading fw-bold">Order Registered</h5>
                                    <p class="mb-0">The order has been created by our sales team and is visible to warehouse personnel for preparation.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger shadow-sm">
                        <h5 class="alert-heading fw-bold">Order Not Found</h5>
                        <p class="mb-0">No active order was found matching invoice number <strong>"{{ $invoiceNumber }}"</strong>. Please verify the invoice number or contact customer support.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
