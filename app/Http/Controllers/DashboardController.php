<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Department;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $inactiveUsers = User::where('is_active', false)->count();

        $totalOrders = Order::count();
        $orderedCount = Order::where('status', 'Ordered')->count();
        $inProcessCount = Order::where('status', 'In process')->count();
        $inRouteCount = Order::where('status', 'In route')->count();
        $deliveredCount = Order::where('status', 'Delivered')->count();
        $archivedCount = Order::onlyTrashed()->count();

        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'totalOrders',
            'orderedCount',
            'inProcessCount',
            'inRouteCount',
            'deliveredCount',
            'archivedCount',
            'recentOrders'
        ));
    }
}
