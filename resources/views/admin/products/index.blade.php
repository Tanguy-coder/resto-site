@extends('layouts.admin')

@section('title', 'Produits')

@section('content')
    <div class="page-header">
        <h2>Produits</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Nouveau produit</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Catégorie</th>
                <th>Prix</th>
                <th>Best-seller</th>
                <th>Actif</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->subCategory->category->name ?? '-' }} / {{ $product->subCategory->name ?? '-' }}</td>
                    <td>{{ number_format($product->price, 0) }} FCFA</td>
                    <td>{{ $product->is_best_seller ? 'Oui' : 'Non' }}</td>
                    <td>{{ $product->is_active ? 'Oui' : 'Non' }}</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary btn-sm">Modifier</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce produit ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;padding:30px;color:#94a3b8;">Aucun produit</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $products->links('pagination.admin') }}
@endsection
