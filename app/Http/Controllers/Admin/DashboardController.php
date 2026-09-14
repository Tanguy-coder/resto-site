<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Location;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'productsCount' => Product::count(),
            'categoriesCount' => Category::count(),
            'testimonialsCount' => Testimonial::count(),
            'pendingReviewsCount' => Testimonial::where('is_active', false)->count(),
            'locationsCount' => Location::count(),
            'ordersCount' => Order::count(),
            'ordersTodayCount' => Order::whereDate('created_at', today())->count(),
            'recentOrders' => Order::with('location')->latest()->take(5)->get(),
        ]);
    }
}
