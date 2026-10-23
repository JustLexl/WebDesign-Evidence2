@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Order Details: {{ $order->invoice_number }}</h2>
            <p class="text-muted mb-0">Registered by: {{ $order->user->name ?? 'System' }} | Date: {{ $order->order_datetime ? $order->order_datetime->format('Y-m-d H:i') : 'N/A' }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Back to Orders</a>
            <a href="{{ route('orders.edit', $order) }}" class="btn btn-outline-primary">Edit Order</a>
            <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('Are you sure you want to logically delete this order?');" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">Delete (Logical)</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">General Information</h5>
                    <span class="badge @if($order->status === 'Delivered') bg-success @elseif($order->status === 'In route') bg-primary @elseif($order->status === 'In process') bg-warning text-dark @else bg-secondary @endif fs-6">
                        {{ $order->status }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small">Invoice Number:</label>
                            <p class="fw-bold mb-2">{{ $order->invoice_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Customer Number:</label>
                            <p class="fw-bold mb-2">{{ $order->customer_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Customer Name / Company:</label>
                            <p class="fw-bold mb-2">{{ $order->customer_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">Order Date & Time:</label>
                            <p class="fw-bold mb-2">{{ $order->order_datetime ? $order->order_datetime->format('Y-m-d H:i') : 'N/A' }}</p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small">Fiscal Data (For Physical Invoice):</label>
                            <p class="mb-2 bg-light p-2 rounded border">{{ $order->fiscal_data ?: 'No fiscal data specified' }}</p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small">Delivery Address:</label>
                            <p class="mb-2 bg-light p-2 rounded border">{{ $order->delivery_address }}</p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small">Notes / Extra Information:</label>
                            <p class="mb-0 bg-light p-2 rounded border">{{ $order->notes ?: 'No extra notes provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Evidence History & Photographs</h5>
                </div>
                <div class="card-body">
                    @if ($order->route_photo || $order->delivery_photo || $order->evidences->count() > 0)
                        <div class="row g-3">
                            @if ($order->route_photo)
                                <div class="col-md-6">
                                    <div class="card h-100 border">
                                        <div class="card-header bg-light py-2">
                                            <span class="badge bg-primary me-1">In route</span>
                                            <small class="fw-bold">Loaded Unit Photo</small>
                                        </div>
                                        <div class="card-body text-center p-2">
                                            <img src="{{ asset($order->route_photo) }}" alt="Loaded Unit Photo" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($order->delivery_photo)
                                <div class="col-md-6">
                                    <div class="card h-100 border-success">
                                        <div class="card-header bg-success text-white py-2">
                                            <span class="badge bg-light text-dark me-1">Delivered</span>
                                            <small class="fw-bold">Delivered Evidence Photo</small>
                                        </div>
                                        <div class="card-body text-center p-2">
                                            <img src="{{ asset($order->delivery_photo) }}" alt="Delivered Photo" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($order->evidences->count() > 0)
                            <h6 class="mt-4 mb-2 fw-bold text-muted">Evidence Logs:</h6>
                            <ul class="list-group">
                                @foreach ($order->evidences as $evidence)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $evidence->stage }}</strong>
                                            <span class="text-muted small">by {{ $evidence->user->name ?? 'Staff' }} on {{ $evidence->created_at->format('Y-m-d H:i') }}</span>
                                            @if ($evidence->notes)
                                                <p class="mb-0 small text-secondary mt-1">{{ $evidence->notes }}</p>
                                            @endif
                                        </div>
                                        <a href="{{ asset($evidence->photo_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">View Photo</a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    @else
                        <p class="text-muted text-center py-4 mb-0">No photographic evidence uploaded yet for this order.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">Change Order Status</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('orders.update-status', $order) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">Select New Status (*)</label>
                            <select class="form-select form-select-lg @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="Ordered" {{ $order->status === 'Ordered' ? 'selected' : '' }}>1. Ordered</option>
                                <option value="In process" {{ $order->status === 'In process' ? 'selected' : '' }}>2. In process (Warehouse preparation / Purchasing)</option>
                                <option value="In route" {{ $order->status === 'In route' ? 'selected' : '' }}>3. In route (Unit loaded & dispatched)</option>
                                <option value="Delivered" {{ $order->status === 'Delivered' ? 'selected' : '' }}>4. Delivered (Unloaded at client premises)</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="evidence_photo" class="form-label fw-bold">
                                Photographic Evidence
                                <span class="badge bg-info text-dark ms-1">Required for Route & Delivery</span>
                            </label>
                            <input type="file" class="form-control @error('evidence_photo') is-invalid @enderror" id="evidence_photo" name="evidence_photo" accept="image/*" capture="environment">
                            <small class="text-muted d-block mt-1">Take photo or upload image file (loaded unit or delivered material evidence).</small>
                            @error('evidence_photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="evidence_notes" class="form-label fw-bold">Status Notes / Observations</label>
                            <textarea class="form-control" id="evidence_notes" name="evidence_notes" rows="2" placeholder="e.g. Unit loaded by Luis, signed by receiver..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                            Update Order Status
                        </button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-header bg-light py-2">
                    <small class="fw-bold text-muted text-uppercase">Lifecycle Guide</small>
                </div>
                <div class="card-body">
                    <ol class="small text-muted ps-3 mb-0">
                        <li class="mb-1"><strong>Ordered:</strong> Sales registers order. Visible to all company staff.</li>
                        <li class="mb-1"><strong>In process:</strong> Warehouse picks inventory or coordinates with Purchasing.</li>
                        <li class="mb-1"><strong>In route:</strong> Unit loaded and departing; Route operator uploads photo of loaded unit.</li>
                        <li><strong>Delivered:</strong> Material delivered at destination; Route operator uploads photo of unloaded delivery evidence.</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
