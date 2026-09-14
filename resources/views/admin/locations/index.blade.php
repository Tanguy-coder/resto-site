@extends('layouts.admin')

@section('title', 'Adresses')

@section('content')
    <div class="page-header">
        <h2>Adresses</h2>
        <a href="{{ route('admin.locations.create') }}" class="btn btn-primary">+ Nouvelle adresse</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Adresse</th>
                <th>Téléphone</th>
                <th>Actif</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($locations as $location)
                <tr>
                    <td>{{ $location->name }}</td>
                    <td>{{ $location->address }}</td>
                    <td>{{ $location->phone ?? '-' }}</td>
                    <td>{{ $location->is_active ? 'Oui' : 'Non' }}</td>
                    <td>
                        <a href="{{ route('admin.locations.edit', $location) }}" class="btn btn-secondary btn-sm">Modifier</a>
                        <form action="{{ route('admin.locations.destroy', $location) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer cette adresse ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;padding:30px;color:#94a3b8;">Aucune adresse</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $locations->links('pagination.admin') }}
@endsection
