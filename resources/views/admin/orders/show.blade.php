@extends('layouts.admin')

@section('title', "Commande #$order->order_number")

@section('content')

    <div class="page-header">
        <h2>Commande <span style="color:#e94560;">#{{ $order->order_number }}</span></h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary btn-sm">← Retour</a>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:25px;">
        {{-- Info card --}}
        <div class="card">
            <h3 class="section-header" style="margin-top:0;">Informations</h3>
            <table style="width:100%; font-size:0.9rem;">
                <tr><td style="padding:8px 0; color:#94a3b8; width:140px;">Client</td><td style="padding:8px 0;"><strong>{{ $order->customer_name }}</strong></td></tr>
                <tr><td style="padding:8px 0; color:#94a3b8;">Restaurant</td><td style="padding:8px 0;">{{ $order->location->name }}</td></tr>
                <tr><td style="padding:8px 0; color:#94a3b8;">Service</td><td style="padding:8px 0;">{{ $order->service_label }}</td></tr>
                @if($order->phone)<tr><td style="padding:8px 0; color:#94a3b8;">Téléphone</td><td style="padding:8px 0;"><a href="tel:{{ $order->phone }}" style="color:#e94560;">{{ $order->phone }}</a></td></tr>@endif
                @if($order->table_number)<tr><td style="padding:8px 0; color:#94a3b8;">Table</td><td style="padding:8px 0;">{{ $order->table_number }}</td></tr>@endif
                @if($order->address)<tr><td style="padding:8px 0; color:#94a3b8;">Adresse</td><td style="padding:8px 0;">{{ $order->address }}</td></tr>@endif
                @if($order->note)<tr><td style="padding:8px 0; color:#94a3b8;">Note</td><td style="padding:8px 0; font-style:italic;">{{ $order->note }}</td></tr>@endif
                <tr><td style="padding:8px 0; color:#94a3b8;">Heure</td><td style="padding:8px 0;">{{ $order->created_at->format('d/m/Y à H:i') }}</td></tr>
            </table>
        </div>

        {{-- Status card --}}
        <div class="card">
            <h3 class="section-header" style="margin-top:0;">Statut</h3>

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

            <div style="text-align:center; padding:20px 0;">
                <span style="display:inline-block; padding:8px 24px; border-radius:20px; font-size:1rem; font-weight:700; background:{{ $color }}20; color:{{ $color }}; border:1px solid {{ $color }}40;">
                    {{ $order->status_label }}
                </span>
            </div>

            <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST" style="margin-top:20px;">
                @csrf
                @method('PATCH')
                <label style="display:block; margin-bottom:8px; font-size:0.85rem; color:#94a3b8;">Changer le statut :</label>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    @foreach($statuses as $key => $label)
                        @php $sc = $statusColors[$key] ?? '#94a3b8'; @endphp
                        <button type="submit" name="status" value="{{ $key }}"
                            @if($order->status === $key) disabled @endif
                            style="padding:8px 16px; border-radius:8px; border:1px solid {{ $sc }}40; background:{{ $order->status === $key ? $sc.'30' : 'transparent' }}; color:{{ $sc }}; font-size:0.82rem; font-weight:600; cursor:{{ $order->status === $key ? 'default' : 'pointer' }}; opacity:{{ $order->status === $key ? '0.5' : '1' }}; transition:all 0.2s;">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </form>
        </div>
    </div>

    {{-- Items --}}
    <div class="card">
        <h3 class="section-header" style="margin-top:0;">Articles</h3>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Qté</th>
                    <th>Article</th>
                    <th>Variante</th>
                    <th>Retiré</th>
                    <th style="text-align:right;">Prix unit.</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td><strong>×{{ $item->quantity }}</strong></td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->variant_name ?? '—' }}</td>
                        <td>
                            @if($item->removed_ingredients && count($item->removed_ingredients))
                                <span style="color:#ef4444; font-size:0.8rem;">sans {{ implode(', ', $item->removed_ingredients) }}</span>
                            @else
                                —
                            @endif
                        </td>
                        <td style="text-align:right;">{{ number_format($item->price, 0, ',', ' ') }} FCFA</td>
                        <td style="text-align:right;"><strong>{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} FCFA</strong></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="text-align:right; padding:14px 16px; font-size:1rem; color:#94a3b8; font-weight:600;">Total</td>
                    <td style="text-align:right; padding:14px 16px; font-size:1.2rem; color:#e94560; font-weight:700;">{{ number_format($order->total, 0, ',', ' ') }} FCFA</td>
                </tr>
            </tfoot>
        </table>
    </div>

@endsection
