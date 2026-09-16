@extends('layouts.admin')

@section('title', 'Catégories')
@section('page-title', 'Catégories')

@section('page-actions')
<a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-small">
    <i class="fa-solid fa-plus"></i> Nouvelle catégorie
</a>
@endsection

@section('content')

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <i class="fa-solid fa-folder-tree"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Total</span>
            <strong class="stat-value">{{ $stats['total'] }}</strong>
            <small class="stat-hint">catégories</small>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Actives</span>
            <strong class="stat-value">{{ $stats['actives'] }}</strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon info">
            <i class="fa-solid fa-layer-group"></i>
        </div>
        <div class="stat-content">
            <span class="stat-label">Racines</span>
            <strong class="stat-value">{{ $stats['racines'] }}</strong>
            <small class="stat-hint">catégories principales</small>
        </div>
    </div>
</div>

{{-- Filtres --}}
<div class="admin-card">
    <div class="admin-card-header">
        <h2><i class="fa-solid fa-filter"></i> Filtres</h2>
    </div>

    <form method="GET" action="{{ route('admin.categories.index') }}" class="admin-filters">
        <div class="filter-row">
            <div class="filter-group">
                <label>Recherche</label>
                <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Nom de la catégorie...">
            </div>

            <div class="filter-group">
                <label>Statut</label>
                <select name="actif" class="form-control">
                    <option value="">Toutes</option>
                    <option value="1" {{ request('actif') === '1' ? 'selected' : '' }}>Actives</option>
                    <option value="0" {{ request('actif') === '0' ? 'selected' : '' }}>Inactives</option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-search"></i> Filtrer
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline">
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
            {{ $categories->total() }} catégorie(s)
        </h2>
    </div>

    <div class="table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Libellé</th>
                    <th>Parent</th>
                    <th>Produits</th>
                    <th>Ordre</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $categorie)
                <tr>
                    <td data-label="Libellé">
                        <strong>{{ $categorie->libelle }}</strong>
                        @if($categorie->description)
                        <br><small class="text-muted">{{ Str::limit($categorie->description, 60) }}</small>
                        @endif
                    </td>
                    <td data-label="Parent">
                        @if($categorie->parent)
                        <span class="badge badge-info">{{ $categorie->parent->libelle }}</span>
                        @else
                        <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td data-label="Produits">
                        <span class="badge badge-{{ $categorie->produits_count > 0 ? 'primary' : 'secondary' }}">
                            {{ $categorie->produits_count }}
                        </span>
                    </td>
                    <td data-label="Ordre">{{ $categorie->ordre }}</td>
                    <td data-label="Statut">
                        @if($categorie->actif)
                        <span class="badge badge-success">Active</span>
                        @else
                        <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td data-label="Actions">
                        <div class="action-buttons">
                            <a href="{{ route('admin.categories.edit', $categorie->id) }}" class="btn-icon"
                                title="Modifier">
                                <i class="fa-solid fa-pen"></i>
                            </a>

                            <form method="POST" action="{{ route('admin.categories.destroy', $categorie->id) }}"
                                data-confirm="Voulez-vous vraiment supprimer la catégorie « {{ $categorie->libelle }} » ?"
                                data-confirm-title="Supprimer la catégorie" style="display: inline;">
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
                    <td colspan="6" class="text-center text-muted">Aucune catégorie trouvée.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
    <div class="admin-pagination">
        {{ $categories->links() }}
    </div>
    @endif
</div>

@endsection