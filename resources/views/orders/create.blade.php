@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Register New Order (Sales)</h5>
                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary">Back to Orders</a>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('orders.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="invoice_number" class="form-label fw-bold">Consecutive Invoice Number (*)</label>
                                <input type="text" class="form-control @error('invoice_number') is-invalid @enderror" id="invoice_number" name="invoice_number" value="{{ old('invoice_number', $suggestedInvoice) }}" required>
                                @error('invoice_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="customer_number" class="form-label fw-bold">Arbitrary Customer Number (*)</label>
                                <input type="text" class="form-control @error('customer_number') is-invalid @enderror" id="customer_number" name="customer_number" value="{{ old('customer_number', $suggestedCustomer) }}" required>
                                @error('customer_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-7">
                                <label for="customer_name" class="form-label fw-bold">Customer Name / Company Name (*)</label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <label for="order_datetime" class="form-label fw-bold">Order Date and Time (*)</label>
                                <input type="datetime-local" class="form-control @error('order_datetime') is-invalid @enderror" id="order_datetime" name="order_datetime" value="{{ old('order_datetime', date('Y-m-d\TH:i')) }}" required>
                                @error('order_datetime')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="fiscal_data" class="form-label fw-bold">Customer Fiscal Data (For Physical Invoice)</label>
                            <textarea class="form-control @error('fiscal_data') is-invalid @enderror" id="fiscal_data" name="fiscal_data" rows="2" placeholder="e.g. RFC, Tax Address, Tax Regime, CFDI Usage...">{{ old('fiscal_data') }}</textarea>
                            @error('fiscal_data')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="delivery_address" class="form-label fw-bold">Delivery Address (*)</label>
                            <textarea class="form-control @error('delivery_address') is-invalid @enderror" id="delivery_address" name="delivery_address" rows="2" required placeholder="Street address, neighborhood, municipality, references...">{{ old('delivery_address') }}</textarea>
                            @error('delivery_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold">Notes / Extra Information</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2" placeholder="Order items details, delivery timing preferences, internal notes...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-secondary py-2 mb-4">
                            <small><strong>Note:</strong> Newly created orders are registered with default status <strong>"Ordered"</strong> and will be visible immediately to all company departments.</small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Register Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
