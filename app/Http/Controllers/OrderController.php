<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'service_type' => 'required|in:sur_place,a_emporter,livraison',
            'customer_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:30',
            'table_number' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:500',
            'note' => 'nullable|string|max:1000',
            'promo_code' => 'nullable|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.variant_id' => 'nullable|integer',
            'items.*.name' => 'required|string|max:200',
            'items.*.variant_name' => 'nullable|string|max:100',
            'items.*.price' => 'required|integer|min:0',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.removed_ingredients' => 'nullable|array',
        ]);

        $order = DB::transaction(function () use ($validated) {
            $total = collect($validated['items'])->sum(fn($i) => $i['price'] * $i['quantity']);

            $order = Order::create([
                'order_number' => Order::generateNumber(),
                'location_id' => $validated['location_id'],
                'service_type' => $validated['service_type'],
                'status' => 'received',
                'customer_name' => $validated['customer_name'],
                'phone' => $validated['phone'] ?? null,
                'table_number' => $validated['table_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'note' => $validated['note'] ?? null,
                'promo_code' => $validated['promo_code'] ?? null,
                'total' => $total,
            ]);

            foreach ($validated['items'] as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['variant_id'] ?? null,
                    'name' => $item['name'],
                    'variant_name' => $item['variant_name'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'removed_ingredients' => $item['removed_ingredients'] ?? null,
                ]);
            }

            return $order;
        });

        return response()->json([
            'order_number' => $order->order_number,
            'status' => $order->status,
        ]);
    }

    public function track(Request $request)
    {
        $request->validate([
            'order_number' => 'required|string',
            'location_id' => 'required|exists:locations,id',
        ]);

        $order = Order::with('items', 'location')
            ->where('order_number', $request->order_number)
            ->where('location_id', $request->location_id)
            ->first();

        if (!$order) {
            return response()->json(['found' => false], 404);
        }

        $steps = [
            ['key' => 'received', 'label' => 'Reçue', 'icon' => '📥'],
            ['key' => 'confirmed', 'label' => 'Confirmée', 'icon' => '✅'],
            ['key' => 'preparing', 'label' => 'En préparation', 'icon' => '👨‍🍳'],
            ['key' => 'ready', 'label' => 'Prête', 'icon' => '🔔'],
            ['key' => 'delivered', 'label' => 'Terminée', 'icon' => '🎉'],
        ];

        $statusOrder = array_column($steps, 'key');
        $currentIndex = array_search($order->status, $statusOrder);

        return response()->json([
            'found' => true,
            'order_number' => $order->order_number,
            'location_id'  => $order->location_id,
            'status' => $order->status,
            'status_label' => $order->status_label,
            'service_type' => $order->service_type,
            'service_label' => $order->service_label,
            'customer_name' => $order->customer_name,
            'location' => $order->location->name,
            'total' => $order->total,
            'created_at' => $order->created_at->format('H:i'),
            'items' => $order->items->map(fn($i) => [
                'name' => $i->name,
                'variant_name' => $i->variant_name,
                'price' => $i->price,
                'quantity' => $i->quantity,
                'removed_ingredients' => $i->removed_ingredients,
            ]),
            'steps' => collect($steps)->map(fn($s, $i) => [
                ...$s,
                'done' => $currentIndex !== false && $i <= $currentIndex,
                'current' => $s['key'] === $order->status,
            ]),
            'cancelled' => $order->status === 'cancelled',
        ]);
    }
}
