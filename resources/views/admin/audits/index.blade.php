@extends('layouts.admin')

@section('title', 'Audits')
@section('page-title', 'Journal d\'activité')

@section('page-actions')
<form method="POST" action="{{ route('admin.audits.nettoyer') }}"
    data-confirm="Supprimer tous les audits de plus de 90 jours ?" data-confirm-title="Nettoyer les audits"
    style="display: inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-outline btn-small">
        <i class="fa-solid fa-broom"></i> Nettoyer (90j+)
    </button>
</form>
@endsection

@section('content')

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fa-solid fa-history"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Total</span>
            <strong class="stat-value">{{ $stats['total'] }}</strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fa-solid fa-calendar-day"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Aujourd'hui</span>
            <strong class="stat-value">{{ $stats['aujourdhui'] }}</strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fa-solid fa-calendar-week"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Cette semaine</span>
            <strong class="stat-value">{{ $stats['cette_semaine'] }}</strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Utilisateurs actifs</span>
            <strong class="stat-value">{{ $stats['utilisateurs'] }}</strong>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h2><i class="fa-solid fa-filter"></i> Filtres</h2>
    </div>

    <form method="GET" action="{{ route('admin.audits.index') }}" class="admin-filters">
        <div class="filter-row">
            <div class="filter-group">
                <label>Recherche</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Label, IP...">
            </div>

            <div class="filter-group">
                <label>Utilisateur</label>
                <select name="user_id" class="form-control">
                    <option value="">Tous</option>
                    @foreach($utilisateurs as $u)
                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                        {{ $u->name }} {{ $u->surname }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>Action</label>
                <select name="action" class="form-control">
                    <option value="">Toutes</option>
                    @foreach($actions as $action)
                    <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                        {{ ucfirst($action) }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>Table</label>
                <select name="table_cible" class="form-control">
                    <option value="">Toutes</option>
                    @foreach($tablesCibles as $table)
                    <option value="{{ $table }}" {{ request('table_cible') === $table ? 'selected' : '' }}>
                        {{ ucfirst(rtrim($table, 's')) }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>Date début</label>
                <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
            </div>

            <div class="filter-group">
                <label>Date fin</label>
                <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-search"></i> Filtrer
                </button>
                <a href="{{ route('admin.audits.index') }}" class="btn btn-outline">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h2>
            <i class="fa-solid fa-list"></i>
            {{ $audits->total() }} action(s)
        </h2>
    </div>

    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Utilisateur</th>
                    <th>Action</th>
                    <th>Élément</th>
                    <th>IP</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($audits as $audit)
                <tr>
                    <td data-label="Date">
                        <strong>{{ $audit->date->format('d/m/Y') }}</strong>
                        <br>
                        <small class="text-muted">{{ $audit->date->format('H:i:s') }}</small>
                    </td>
                    <td data-label="Utilisateur">
                        @if($audit->user)
                        <strong>{{ $audit->user->name }} {{ $audit->user->surname }}</strong>
                        @else
                        <span class="text-muted">Système</span>
                        @endif
                    </td>
                    <td data-label="Action">
                        <span class="badge badge-{{ $audit->action_badge }}">
                            {{ $audit->action_label }}
                        </span>
                    </td>
                    <td data-label="Élément">
                        <strong>{{ $audit->table_label }}</strong>
                        <br>
                        <small class="text-muted">{{ $audit->details['label'] ?? "#{$audit->id_cible}" }}</small>
                    </td>
                    <td data-label="IP">
                        <code class="text-small">{{ $audit->ip ?? '—' }}</code>
                    </td>
                    <td data-label="Actions">
                        <a href="{{ route('admin.audits.show', $audit->id) }}" class="btn-icon" title="Voir">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Aucun audit trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($audits->hasPages())
    <div class="admin-pagination">
        {{ $audits->links() }}
    </div>
    @endif
</div>

@endsection