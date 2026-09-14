@extends('layouts.admin')

@section('title', 'Slides Hero')

@section('content')
    <div class="page-header">
        <h2>Slides Hero</h2>
        <a href="{{ route('admin.hero-slides.create') }}" class="btn btn-primary">+ Nouveau slide</a>
    </div>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Image</th>
                <th>Ordre</th>
                <th>Actif</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($slides as $slide)
                <tr>
                    <td>{{ $slide->title }}</td>
                    <td>
                        @if($slide->image)
                            <img src="{{ asset('storage/' . $slide->image) }}" class="img-thumb" alt="">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $slide->sort_order }}</td>
                    <td>{{ $slide->is_active ? 'Oui' : 'Non' }}</td>
                    <td>
                        <a href="{{ route('admin.hero-slides.edit', $slide) }}" class="btn btn-secondary btn-sm">Modifier</a>
                        <form action="{{ route('admin.hero-slides.destroy', $slide) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce slide ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;padding:30px;color:#94a3b8;">Aucun slide</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $slides->links() }}
    </div>
@endsection
