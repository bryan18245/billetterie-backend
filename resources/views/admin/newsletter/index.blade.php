@extends('layouts.admin')

@section('title', 'Newsletter')
@section('page-title', 'Newsletter')

@section('page-actions')
<a href="{{ route('admin.newsletter.export') }}" class="btn btn-outline btn-small">
    <i class="fa-solid fa-download"></i> Exporter CSV
</a>
<button type="button" class="btn btn-primary btn-small"
    onclick="document.getElementById('newsletterModal').classList.add('show')">
    <i class="fa-solid fa-paper-plane"></i> Envoyer une newsletter
</button>
@endsection

@section('content')

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fa-solid fa-envelope"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Total abonnés</span>
            <strong class="stat-value">{{ $stats['total'] }}</strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Actifs</span>
            <strong class="stat-value">{{ $stats['actifs'] }}</strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <i class="fa-solid fa-circle-pause"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Inactifs</span>
            <strong class="stat-value">{{ $stats['inactifs'] }}</strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fa-solid fa-calendar-check"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Ce mois</span>
            <strong class="stat-value">{{ $stats['ce_mois'] }}</strong>
            <small class="stat-hint">nouveaux abonnés</small>
        </div>
    </div>
</div>

{{-- Filtres --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h2><i class="fa-solid fa-filter"></i> Filtres</h2>
    </div>

    <form method="GET" action="{{ route('admin.newsletter.index') }}" class="admin-filters">
        <div class="filter-row">
            <div class="filter-group">
                <label>Recherche</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Email...">
            </div>

            <div class="filter-group">
                <label>Statut</label>
                <select name="actif" class="form-control">
                    <option value="">Tous</option>
                    <option value="1" {{ request('actif') === '1' ? 'selected' : '' }}>Actifs</option>
                    <option value="0" {{ request('actif') === '0' ? 'selected' : '' }}>Inactifs</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-search"></i> Filtrer
                </button>
                <a href="{{ route('admin.newsletter.index') }}" class="btn btn-outline">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </div>
    </form>
</div>

{{-- Liste --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h2>
            <i class="fa-solid fa-list"></i>
            {{ $abonnes->total() }} abonné(s)
        </h2>
    </div>

    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Statut</th>
                    <th>Inscrit le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($abonnes as $abonne)
                <tr>
                    <td data-label="Email">
                        <div class="newsletter-email">
                            <i class="fa-solid fa-envelope"></i>
                            <a href="mailto:{{ $abonne->email }}">{{ $abonne->email }}</a>
                        </div>
                    </td>
                    <td data-label="Statut">
                        @if($abonne->actif)
                        <span class="badge badge-success">
                            <i class="fa-solid fa-circle-check"></i> Actif
                        </span>
                        @else
                        <span class="badge badge-warning">
                            <i class="fa-solid fa-circle-pause"></i> Inactif
                        </span>
                        @endif
                    </td>
                    <td data-label="Inscrit le">
                        {{ $abonne->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td data-label="Actions">
                        <div class="action-buttons">
                            <form method="POST" action="{{ route('admin.newsletter.toggle', $abonne->id) }}"
                                style="display: inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn-icon"
                                    title="{{ $abonne->actif ? 'Désactiver' : 'Activer' }}">
                                    <i class="fa-solid fa-{{ $abonne->actif ? 'circle-pause' : 'circle-play' }}"></i>
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.newsletter.destroy', $abonne->id) }}"
                                data-confirm="Voulez-vous vraiment supprimer l'abonné « {{ $abonne->email }} » ?"
                                data-confirm-title="Supprimer l'abonné" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon btn-icon-danger" title="Supprimer">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">Aucun abonné trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($abonnes->hasPages())
    <div class="admin-pagination">
        {{ $abonnes->links() }}
    </div>
    @endif
</div>

@endsection