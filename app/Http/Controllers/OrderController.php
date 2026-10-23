<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderEvidence;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Order::with(['user', 'evidences'])->latest();

        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', 'like', '%' . trim($request->invoice_number) . '%');
        }

        if ($request->filled('customer_number')) {
            $query->where('customer_number', 'like', '%' . trim($request->customer_number) . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('order_datetime', $request->date);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $lastOrder = Order::withTrashed()->latest('id')->first();
        $nextNumber = $lastOrder ? ($lastOrder->id + 1) : 1001;
        $suggestedInvoice = 'INV-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $suggestedCustomer = 'CUST-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return view('orders.create', compact('suggestedInvoice', 'suggestedCustomer'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'required|string|max:100|unique:orders,invoice_number',
            'customer_number' => 'required|string|max:100',
            'customer_name' => 'required|string|max:255',
            'fiscal_data' => 'nullable|string',
            'order_datetime' => 'required|date',
            'delivery_address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        Order::create([
            'invoice_number' => $validated['invoice_number'],
            'customer_number' => $validated['customer_number'],
            'customer_name' => $validated['customer_name'],
            'fiscal_data' => $validated['fiscal_data'],
            'order_datetime' => $validated['order_datetime'],
            'delivery_address' => $validated['delivery_address'],
            'notes' => $validated['notes'],
            'status' => 'Ordered',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $order->load(['user', 'evidences.user']);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_number' => 'required|string|max:100',
            'customer_name' => 'required|string|max:255',
            'fiscal_data' => 'nullable|string',
            'order_datetime' => 'required|date',
            'delivery_address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('orders.show', $order)->with('success', 'Order updated successfully.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:Ordered,In process,In route,Delivered',
            'evidence_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'evidence_notes' => 'nullable|string|max:500',
        ]);

        $newStatus = $validated['status'];
        $order->status = $newStatus;

        if ($newStatus === 'In process' && !$order->process_date) {
            $order->process_date = Carbon::now();
        }

        if ($request->hasFile('evidence_photo')) {
            $destinationDir = public_path('uploads/evidences');
            if (!file_exists($destinationDir)) {
                mkdir($destinationDir, 0755, true);
            }

            $file = $request->file('evidence_photo');
            $fileName = time() . '_' . strtolower(str_replace(' ', '_', $newStatus)) . '_' . $file->getClientOriginalName();
            $file->move($destinationDir, $fileName);
            $relativePath = 'uploads/evidences/' . $fileName;

            if ($newStatus === 'In route') {
                $order->route_photo = $relativePath;
            } elseif ($newStatus === 'Delivered') {
                $order->delivery_photo = $relativePath;
            }

            OrderEvidence::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'stage' => $newStatus,
                'photo_path' => $relativePath,
                'notes' => $request->input('evidence_notes'),
            ]);
        }

        $order->save();

        return redirect()->route('orders.show', $order)->with('success', 'Order status updated to ' . $newStatus . '.');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted logically and moved to archived orders.');
    }

    public function archived()
    {
        $archivedOrders = Order::onlyTrashed()->with('user')->latest()->get();
        return view('orders.archived', compact('archivedOrders'));
    }

    public function restore($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();

        return redirect()->route('orders.archived')->with('success', 'Order ' . $order->invoice_number . ' retrieved and restored successfully.');
    }
}
