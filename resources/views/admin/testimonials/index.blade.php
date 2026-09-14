@extends('layouts.admin')

@section('title', 'Témoignages')

@section('content')
    <div class="page-header">
        <h2>Témoignages</h2>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">+ Nouveau témoignage</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Lieu</th>
                <th>Contenu</th>
                <th>Note</th>
                <th>Actif</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($testimonials as $testimonial)
                <tr>
                    <td>{{ $testimonial->name }}</td>
                    <td>{{ $testimonial->location ?? '-' }}</td>
                    <td>{{ Str::limit($testimonial->content, 50) }}</td>
                    <td>{{ $testimonial->rating }}/5</td>
                    <td>{{ $testimonial->is_active ? 'Oui' : 'Non' }}</td>
                    <td>
                        <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn btn-secondary btn-sm">Modifier</a>
                        <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce témoignage ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;padding:30px;color:#94a3b8;">Aucun témoignage</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $testimonials->links() }}
    </div>
@endsection
