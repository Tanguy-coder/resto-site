@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h2 style="font-size:1.4rem;font-weight:600;margin-bottom:25px;">Dashboard</h2>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $ordersTodayCount }}</div>
            <div class="stat-label">Commandes aujourd'hui</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $ordersCount }}</div>
            <div class="stat-label">Commandes au total</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $productsCount }}</div>
            <div class="stat-label">Produits</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $categoriesCount }}</div>
            <div class="stat-label">Catégories</div>
        </div>
    </div>

    @if($recentOrders->count())
        <div class="card" style="margin-bottom:20px;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h3 style="font-size:1.1rem;">Dernières commandes</h3>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">Voir tout</a>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Client</th>
                        <th>Restaurant</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Heure</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        @php
                            $statusColors = [
                                'received' => '#f59e0b',
                                'confirmed' => '#3b82f6',
                                'preparing' => '#8b5cf6',
                                'ready' => '#10b981',
                                'delivered' => '#6b7280',
                                'cancelled' => '#ef4444',
                            ];
                            $color = $statusColors[$order->status] ?? '#94a3b8';
                        @endphp
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order) }}" style="color:#e94560; font-weight:700;">#{{ $order->order_number }}</a></td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->location->name }}</td>
                            <td><strong>{{ number_format($order->total, 0, ',', ' ') }} FCFA</strong></td>
                            <td>
                                <span style="display:inline-block; padding:3px 10px; border-radius:20px; font-size:0.75rem; font-weight:600; background:{{ $color }}20; color:{{ $color }}; border:1px solid {{ $color }}40;">
                                    {{ $order->status_label }}
                                </span>
                            </td>
                            <td style="color:#94a3b8; font-size:0.85rem;">{{ $order->created_at->format('H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="card">
        <h3 style="margin-bottom:15px;font-size:1.1rem;">Actions rapides</h3>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary" style="margin-right:10px;">📋 Commandes</a>
        <a href="{{ route('admin.products.create') }}" class="btn btn-secondary" style="margin-right:10px;">+ Nouveau produit</a>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-secondary">+ Nouvelle catégorie</a>
    </div>
@endsection
