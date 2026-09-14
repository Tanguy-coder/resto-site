@extends('layouts.admin')

@section('title', $location->exists ? 'Modifier l\'adresse' : 'Nouvelle adresse')

@section('content')
    <div class="page-header">
        <h2>{{ $location->exists ? 'Modifier l\'adresse' : 'Nouvelle adresse' }}</h2>
        <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary">Retour</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ $location->exists ? route('admin.locations.update', $location) : route('admin.locations.store') }}" enctype="multipart/form-data">
            @csrf
            @if($location->exists) @method('PUT') @endif

            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" id="name" name="name" value="{{ old('name', $location->name) }}" required>
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="address">Adresse</label>
                <input type="text" id="address" name="address" value="{{ old('address', $location->address) }}" required>
                @error('address') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="phone">Téléphone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $location->phone) }}">
                @error('phone') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="hours">Horaires</label>
                <textarea id="hours" name="hours">{{ old('hours', $location->hours) }}</textarea>
                @error('hours') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="map_url">Lien Google Maps</label>
                <input type="url" id="map_url" name="map_url" value="{{ old('map_url', $location->map_url) }}">
                @error('map_url') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="image">Photo</label>
                <input type="file" id="image" name="image" accept="image/*">
                @if($location->image)
                    <img src="{{ asset('storage/' . $location->image) }}" class="img-preview" alt="">
                @endif
                @error('image') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <div class="checkbox-wrap">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $location->exists ? $location->is_active : true) ? 'checked' : '' }}>
                    <label for="is_active">Actif</label>
                </div>
            </div>

            <div class="form-group">
                <label for="sort_order">Ordre d'affichage</label>
                <input type="number" id="sort_order" name="sort_order" value="{{ old('sort_order', $location->sort_order ?? 0) }}">
            </div>

            <button type="submit" class="btn btn-primary">{{ $location->exists ? 'Mettre à jour' : 'Créer' }}</button>
        </form>
    </div>
@endsection
