@extends('layouts.admin')

@section('title', 'Détail audit')
@section('page-title', 'Détail de l\'action')

@section('page-actions')
<a href="{{ route('admin.audits.index') }}" class="btn btn-outline btn-small">
    <i class="fa-solid fa-arrow-left"></i> Retour
</a>
@endsection

@section('content')

<div class="admin-card">
    <div class="admin-card-header">
        <h2>
            <i class="fa-solid fa-history"></i>
            <span class="badge badge-{{ $audit->action_badge }}">
                {{ $audit->action_label }}
            </span>
            {{ $audit->table_label }}
        </h2>
    </div>

    <div class="info-list">
        <div class="info-line">
            <span>Date</span>
            <strong>{{ $audit->date->format('d/m/Y à H:i:s') }}</strong>
        </div>
        <div class="info-line">
            <span>Utilisateur</span>
            <strong>
                @if($audit->user)
                {{ $audit->user->name }} {{ $audit->user->surname }}
                <small class="text-muted">({{ $audit->user->email }})</small>
                @else
                Système
                @endif
            </strong>
        </div>
        <div class="info-line">
            <span>Table cible</span>
            <strong><code>{{ $audit->table_cible }}</code></strong>
        </div>
        <div class="info-line">
            <span>ID cible</span>
            <strong>#{{ $audit->id_cible }}</strong>
        </div>
        <div class="info-line">
            <span>Élément</span>
            <strong>{{ $audit->details['label'] ?? '—' }}</strong>
        </div>
        <div class="info-line">
            <span>IP</span>
            <code>{{ $audit->ip ?? '—' }}</code>
        </div>
        <div class="info-line">
            <span>User Agent</span>
            <small class="text-muted">{{ $audit->user_agent ?? '—' }}</small>
        </div>
    </div>
</div>

@if(!empty($audit->details['avant']) || !empty($audit->details['apres']))
<div class="admin-card">
    <div class="admin-card-header">
        <h2><i class="fa-solid fa-code-compare"></i> Différences</h2>
    </div>

    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Champ</th>
                    <th>Avant</th>
                    <th>Après</th>
                </tr>
            </thead>
            <tbody>
                @foreach($audit->details['apres'] ?? [] as $champ => $nouvelleValeur)
                <tr>
                    <td><strong>{{ $champ }}</strong></td>
                    <td>
                        <code style="color: var(--danger);">
                            {{ $audit->details['avant'][$champ] ?? '—' }}
                        </code>
                    </td>
                    <td>
                        <code style="color: var(--success);">
                            {{ $nouvelleValeur }}
                        </code>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if(!empty($audit->details) && empty($audit->details['avant']))
<div class="admin-card">
    <div class="admin-card-header">
        <h2><i class="fa-solid fa-info-circle"></i> Détails</h2>
    </div>

    <div class="admin-card-body">
        <pre class="log-viewer">{{ json_encode($audit->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
</div>
@endif

@endsection