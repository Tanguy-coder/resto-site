@extends('layouts.admin')

@section('title', 'Témoignages')

@section('content')
    <div class="page-header">
        <h2>Témoignages & Avis</h2>
        <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">+ Nouveau témoignage</a>
    </div>

    @php $pending = $testimonials->where('is_active', false)->count(); @endphp
    @if($pending > 0)
        <div style="background:rgba(234,179,8,0.15);border:1px solid rgba(234,179,8,0.4);border-radius:8px;padding:12px 18px;margin-bottom:20px;color:#fbbf24;font-size:0.9rem;">
            ⏳ <strong>{{ $pending }}</strong> avis en attente de validation
        </div>
    @endif

    <table class="admin-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Lieu</th>
                <th>Contenu</th>
                <th>Note</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($testimonials as $testimonial)
                <tr style="{{ !$testimonial->is_active ? 'background:rgba(234,179,8,0.08)' : '' }}">
                    <td>
                        {{ $testimonial->name }}
                        @if(!$testimonial->is_active)
                            <span style="background:#f59e0b;color:#000;font-size:0.65rem;font-weight:700;padding:2px 6px;border-radius:4px;margin-left:6px;letter-spacing:0.05em;">EN ATTENTE</span>
                        @endif
                    </td>
                    <td>{{ $testimonial->location ?? '-' }}</td>
                    <td>{{ Str::limit($testimonial->content, 50) }}</td>
                    <td>{{ $testimonial->rating }}/5</td>
                    <td>
                        @if(!$testimonial->is_active)
                            {{-- Avis en attente : deux actions claires --}}
                            <form action="{{ route('admin.testimonials.approve', $testimonial) }}" method="POST" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm" style="background:#10b981;color:#fff;border:none;cursor:pointer">✓ Approuver</button>
                            </form>
                            <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" style="display:inline;" onsubmit="return confirm('Rejeter et supprimer cet avis ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#ef4444;color:#fff;border:none;cursor:pointer">✗ Rejeter</button>
                            </form>
                        @else
                            {{-- Avis publié : toggle pour masquer --}}
                            <form action="{{ route('admin.testimonials.approve', $testimonial) }}" method="POST" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm" style="background:#10b981;color:#fff;border:none;cursor:pointer">✓ Publié</button>
                            </form>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn btn-secondary btn-sm">Modifier</a>
                        @if($testimonial->is_active)
                            <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" style="display:inline;" onsubmit="return confirm('Supprimer ce témoignage ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        @endif
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
