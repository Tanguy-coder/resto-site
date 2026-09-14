@extends('layouts.admin')

@section('title', $category->exists ? 'Modifier la catégorie' : 'Nouvelle catégorie')

@section('content')
    <div class="page-header">
        <h2>{{ $category->exists ? 'Modifier la catégorie' : 'Nouvelle catégorie' }}</h2>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Retour</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" enctype="multipart/form-data">
            @csrf
            @if($category->exists) @method('PUT') @endif

            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image" accept="image/*">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" class="img-preview" alt="">
                @endif
                @error('image') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <div class="checkbox-wrap">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $category->exists ? $category->is_active : true) ? 'checked' : '' }}>
                    <label for="is_active">Actif</label>
                </div>
            </div>

            <div class="form-group">
                <label for="sort_order">Ordre d'affichage</label>
                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
            </div>

            <button type="submit" class="btn btn-primary">{{ $category->exists ? 'Mettre à jour' : 'Créer' }}</button>
        </form>
    </div>
@endsection
