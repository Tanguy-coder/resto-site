@extends('layouts.admin')

@section('title', 'Commandes')

@section('content')

    <div class="page-header">
        <h2>📋 Commandes</h2>
    </div>

    {{-- Filters --}}
    <div style="display:flex; gap:12px; margin-bottom:20px; flex-wrap:wrap; align-items:center;">
        <form method="GET" style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
            <select name="status" onchange="this.form.submit()" style="padding:8px 14px; background:#1a1a2e; border:1px solid rgba(255,255,255,0.15); border-radius:6px; color:#e0e0e0; font-size:0.85rem;">
                <option value="">Tous les statuts</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ $currentStatus === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @if($currentStatus || $currentLocation)
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">Réinitialiser</a>
            @endif
        </form>
    </div>

    @if($orders->isEmpty())
        <div class="card" style="text-align:center; padding:60px 20px;">
            <p style="font-size:3rem; margin-bottom:12px;">📋</p>
            <p style="color:#94a3b8;">Aucune commande pour le moment.</p>
        </div>
    @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Client</th>
                    <th>Restaurant</th>
                    <th>Service</th>
                    <th>Total</th>
                    <th>Statut</th>
                    <th>Heure</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
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
                        <td><strong style="font-size:1.1rem; color:#e94560;">#{{ $order->order_number }}</strong></td>
                        <td>
                            {{ $order->customer_name }}
                            @if($order->phone)<br><small style="color:#94a3b8;">{{ $order->phone }}</small>@endif
                        </td>
                        <td>{{ $order->location->name }}</td>
                        <td>{{ $order->service_label }}</td>
                        <td><strong>{{ number_format($order->total, 0, ',', ' ') }} FCFA</strong></td>
                        <td>
                            <span style="display:inline-block; padding:4px 12px; border-radius:20px; font-size:0.78rem; font-weight:600; background:{{ $color }}20; color:{{ $color }}; border:1px solid {{ $color }}40;">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td style="color:#94a3b8; font-size:0.85rem;">{{ $order->created_at->format('H:i') }}<br><small>{{ $order->created_at->format('d/m') }}</small></td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary btn-sm">Voir</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $orders->withQueryString()->links('pagination::simple-default') }}
        </div>
    @endif

@endsection
