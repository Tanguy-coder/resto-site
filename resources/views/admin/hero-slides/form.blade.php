@extends('layouts.admin')

@section('title', $slide->exists ? 'Modifier le slide' : 'Nouveau slide')

@section('content')
    <div class="page-header">
        <h2>{{ $slide->exists ? 'Modifier le slide' : 'Nouveau slide' }}</h2>
        <a href="{{ route('admin.hero-slides.index') }}" class="btn btn-secondary">Retour</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ $slide->exists ? route('admin.hero-slides.update', $slide) : route('admin.hero-slides.store') }}" enctype="multipart/form-data">
            @csrf
            @if($slide->exists) @method('PUT') @endif

            <div class="form-group">
                <label for="title">Titre</label>
                <input type="text" id="title" name="title" value="{{ old('title', $slide->title) }}" required>
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image" accept="image/*" {{ $slide->exists ? '' : 'required' }}>
                @if($slide->image)
                    <img src="{{ asset('storage/' . $slide->image) }}" class="img-preview" alt="">
                @endif
                @error('image') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <div class="checkbox-wrap">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $slide->exists ? $slide->is_active : true) ? 'checked' : '' }}>
                    <label for="is_active">Actif</label>
                </div>
            </div>

            <div class="form-group">
                <label for="sort_order">Ordre d'affichage</label>
                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $slide->sort_order ?? 0) }}">
            </div>

            <button type="submit" class="btn btn-primary">{{ $slide->exists ? 'Mettre à jour' : 'Créer' }}</button>
        </form>
    </div>
@endsection
