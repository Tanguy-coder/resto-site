@extends('layouts.admin')

@section('title', $product->exists ? 'Modifier le produit' : 'Nouveau produit')

@section('content')
    <div class="page-header">
        <h2>{{ $product->exists ? 'Modifier le produit' : 'Nouveau produit' }}</h2>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Retour</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            @if($product->exists) @method('PUT') @endif

            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="sub_category_id">Sous-catégorie</label>
                <select id="sub_category_id" name="sub_category_id" required>
                    <option value="">-- Sélectionner --</option>
                    @foreach($subCategories->groupBy('category.name') as $catName => $subs)
                        <optgroup label="{{ $catName }}">
                            @foreach($subs as $sub)
                                <option value="{{ $sub->id }}" {{ old('sub_category_id', $product->sub_category_id) == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                @error('sub_category_id') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description">{{ old('description', $product->description) }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="price">Prix (FCFA)</label>
                <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                @error('price') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image" accept="image/*">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="img-preview" alt="">
                @endif
                @error('image') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <div class="checkbox-wrap">
                    <input type="hidden" name="is_best_seller" value="0">
                    <input type="checkbox" id="is_best_seller" name="is_best_seller" value="1" {{ old('is_best_seller', $product->is_best_seller) ? 'checked' : '' }}>
                    <label for="is_best_seller">Best-seller</label>
                </div>
            </div>

            <div class="form-group">
                <div class="checkbox-wrap">
                    <input type="hidden" name="is_composable" value="0">
                    <input type="checkbox" id="is_composable" name="is_composable" value="1" {{ old('is_composable', $product->is_composable) ? 'checked' : '' }}>
                    <label for="is_composable">À composer</label>
                </div>
            </div>

            <div class="form-group">
                <div class="checkbox-wrap">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->exists ? $product->is_active : true) ? 'checked' : '' }}>
                    <label for="is_active">Actif</label>
                </div>
            </div>

            <div class="form-group">
                <label for="sort_order">Ordre d'affichage</label>
                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $product->sort_order ?? 0) }}">
            </div>

            <button type="submit" class="btn btn-primary">{{ $product->exists ? 'Mettre à jour' : 'Créer' }}</button>
        </form>
    </div>
@endsection
