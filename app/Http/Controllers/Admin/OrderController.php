<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('location', 'items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('location')) {
            $query->where('location_id', $request->location);
        }

        return view('admin.orders.index', [
            'orders' => $query->paginate(20),
            'statuses' => Order::STATUSES,
            'currentStatus' => $request->status,
            'currentLocation' => $request->location,
        ]);
    }

    public function show(Order $order)
    {
        $order->load('items', 'location');

        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => Order::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::STATUSES)),
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', "Commande #{$order->order_number} → {$order->status_label}");
    }
}
