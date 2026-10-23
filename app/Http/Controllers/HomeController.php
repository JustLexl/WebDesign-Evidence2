<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $invoiceNumber = trim($request->input('invoice_number', ''));
        $customerNumber = trim($request->input('customer_number', ''));
        $order = null;
        $searched = false;

        if ($request->filled('invoice_number')) {
            $searched = true;
            $query = Order::where('invoice_number', $invoiceNumber);
            if (!empty($customerNumber)) {
                $query->where('customer_number', $customerNumber);
            }
            $order = $query->first();
        }

        return view('landingpage', compact('order', 'searched', 'invoiceNumber', 'customerNumber'));
    }
}
