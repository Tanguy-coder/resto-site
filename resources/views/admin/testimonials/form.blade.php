@extends('layouts.admin')

@section('title', $testimonial->exists ? 'Modifier le témoignage' : 'Nouveau témoignage')

@section('content')
    <div class="page-header">
        <h2>{{ $testimonial->exists ? 'Modifier le témoignage' : 'Nouveau témoignage' }}</h2>
        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">Retour</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial) : route('admin.testimonials.store') }}">
            @csrf
            @if($testimonial->exists) @method('PUT') @endif

            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" id="name" name="name" value="{{ old('name', $testimonial->name) }}" required>
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="location">Lieu</label>
                <input type="text" id="location" name="location" value="{{ old('location', $testimonial->location) }}">
                @error('location') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="content">Contenu</label>
                <textarea id="content" name="content" required>{{ old('content', $testimonial->content) }}</textarea>
                @error('content') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="rating">Note</label>
                <select id="rating" name="rating">
                    @for($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ old('rating', $testimonial->rating ?? 5) == $i ? 'selected' : '' }}>{{ $i }} / 5</option>
                    @endfor
                </select>
                @error('rating') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <div class="checkbox-wrap">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $testimonial->exists ? $testimonial->is_active : true) ? 'checked' : '' }}>
                    <label for="is_active">Actif</label>
                </div>
            </div>

            <div class="form-group">
                <label for="sort_order">Ordre d'affichage</label>
                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}">
            </div>

            <button type="submit" class="btn btn-primary">{{ $testimonial->exists ? 'Mettre à jour' : 'Créer' }}</button>
        </form>
    </div>
@endsection
