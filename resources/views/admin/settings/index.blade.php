@extends('layouts.admin')

@section('title', 'Paramètres')

@section('content')
    <div class="page-header">
        <h2>Paramètres du site</h2>
    </div>

    @if(session('success'))
        <div style="background:#16a34a22;border:1px solid #16a34a;color:#16a34a;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @foreach($sections as $group => $section)
                <div class="section-header">{{ $section['label'] }}</div>
                @foreach($section['fields'] as $key => $field)
                    <div class="form-group">
                        <label for="setting_{{ $key }}">{{ $field['label'] }}</label>

                        @if($field['type'] === 'textarea')
                            <textarea id="setting_{{ $key }}" name="settings[{{ $key }}]" rows="3">{{ $values[$key] ?? '' }}</textarea>

                        @elseif($field['type'] === 'logo')
                            {{-- Current logo preview --}}
                            @php $currentLogo = $values[$key] ?? 'images/logo-niwa.png'; @endphp
                            <div style="display:flex;align-items:center;gap:16px;margin-bottom:10px;">
                                <img src="{{ asset($currentLogo) }}" alt="Logo actuel"
                                     style="width:56px;height:56px;object-fit:contain;border-radius:50%;background:#2a2a2a;padding:4px;border:2px solid #444;"
                                     id="logo_preview">
                                <span style="color:#888;font-size:13px;">Logo actuel</span>
                            </div>
                            {{-- File upload --}}
                            <label for="logo_upload" style="display:block;margin-bottom:6px;color:#aaa;font-size:13px;">Remplacer le logo</label>
                            <input type="file" id="logo_upload" name="logo_upload" accept="image/*"
                                   style="color:#ccc;font-size:13px;"
                                   onchange="document.getElementById('logo_preview').src = URL.createObjectURL(this.files[0])">
                            {{-- Hidden field to keep current path if no new upload --}}
                            <input type="hidden" name="settings[{{ $key }}]" value="{{ $currentLogo }}">
                            <p style="color:#666;font-size:12px;margin-top:6px;">Formats acceptés : JPG, PNG, WebP, SVG (max 2 Mo). Le fichier sera enregistré sous <code>public/images/logo.ext</code></p>

                        @else
                            <input type="text" id="setting_{{ $key }}" name="settings[{{ $key }}]" value="{{ $values[$key] ?? '' }}">
                        @endif
                    </div>
                @endforeach
            @endforeach

            <button type="submit" class="btn btn-primary" style="margin-top:20px;">Enregistrer les paramètres</button>
        </form>
    </div>
@endsection
