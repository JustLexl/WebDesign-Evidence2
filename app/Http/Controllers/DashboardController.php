<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $ordersCount = Order::count();
        $archivedCount = Order::onlyTrashed()->count();
        $usersCount = User::count();

        return view('dashboard', compact('ordersCount', 'archivedCount', 'usersCount'));
    }
}
