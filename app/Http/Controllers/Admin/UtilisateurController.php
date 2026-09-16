<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UtilisateurController extends Controller
{
    /**
     * Vérifie que l'utilisateur connecté est super_admin.
     */
    private function checkSuperAdmin()
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user || !$user->isSuperAdmin()) {
            abort(403, 'Accès réservé aux super-administrateurs.');
        }
    }

    /**
     * Liste des utilisateurs.
     */
    public function index(Request $request)
    {
        $this->checkSuperAdmin();

        $query = User::with('role');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role_id', $request->role);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $utilisateurs = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $roles = Role::orderBy('libelle')->get();

        $stats = [
            'total'       => User::count(),
            'admins'      => User::whereHas('role', fn($q) => $q->whereIn('libelle', ['admin', 'super_admin']))->count(),
            'clients'     => User::whereHas('role', fn($q) => $q->where('libelle', 'client'))->count(),
            'actifs'      => User::where('statut', 'actif')->count(),
        ];

        return view('admin.utilisateurs.index', compact('utilisateurs', 'roles', 'stats'));
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        $this->checkSuperAdmin();

        $roles = Role::orderBy('libelle')->get();

        return view('admin.utilisateurs.create', compact('roles'));
    }

    /**
     * Enregistrer un utilisateur.
     */
    public function store(Request $request)
    {
        $this->checkSuperAdmin();

        $data = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'surname'   => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'     => ['nullable', 'string', 'max:20'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'role_id'   => ['required', 'exists:roles,id'],
            'langue'    => ['required', 'in:fr,en'],
            'theme'     => ['required', 'in:light,dark,auto'],
            'statut'    => ['required', 'in:actif,inactif,suspendu'],
        ], [
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'email.unique'       => 'Cet email est déjà utilisé.',
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()
            ->route('admin.utilisateurs.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Détail d'un utilisateur.
     */
    public function show($id)
    {
        $this->checkSuperAdmin();

        $utilisateur = User::with(['role', 'client', 'audits'])
            ->findOrFail($id);

        return view('admin.utilisateurs.show', compact('utilisateur'));
    }

    /**
     * Formulaire d'édition.
     */
    public function edit($id)
    {
        $this->checkSuperAdmin();

        $utilisateur = User::findOrFail($id);
        $roles = Role::orderBy('libelle')->get();

        return view('admin.utilisateurs.edit', compact('utilisateur', 'roles'));
    }

    /**
     * Mettre à jour un utilisateur.
     */
    public function update(Request $request, $id)
    {
        $this->checkSuperAdmin();

        $utilisateur = User::findOrFail($id);

        $data = $request->validate([
            'name'      => ['required', 'string', 'max:100'],
            'surname'   => ['required', 'string', 'max:100'],
            'email'     => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'phone'     => ['nullable', 'string', 'max:20'],
            'password'  => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id'   => ['required', 'exists:roles,id'],
            'langue'    => ['required', 'in:fr,en'],
            'theme'     => ['required', 'in:light,dark,auto'],
            'statut'    => ['required', 'in:actif,inactif,suspendu'],
        ], [
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        // Empêcher de se retirer soi-même le rôle super_admin
        if ($utilisateur->id === Auth::id()) {
            $superAdminRole = Role::where('libelle', 'super_admin')->first();
            if ($data['role_id'] != $superAdminRole->id) {
                return back()->withErrors(['role_id' => 'Vous ne pouvez pas modifier votre propre rôle super-admin.']);
            }
            if ($data['statut'] !== 'actif') {
                return back()->withErrors(['statut' => 'Vous ne pouvez pas désactiver votre propre compte.']);
            }
        }

        // Mettre à jour le mot de passe seulement s'il est fourni
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $utilisateur->update($data);

        return redirect()
            ->route('admin.utilisateurs.index')
            ->with('success', 'Utilisateur mis à jour.');
    }

    /**
     * Supprimer un utilisateur.
     */
    public function destroy($id)
    {
        $this->checkSuperAdmin();

        $utilisateur = User::findOrFail($id);

        // Empêcher de se supprimer soi-même
        if ($utilisateur->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        // Empêcher de supprimer un client qui a des commandes
        if ($utilisateur->client && $utilisateur->client->commandes()->count() > 0) {
            return back()->with(
                'error',
                'Impossible de supprimer cet utilisateur : il a des commandes enregistrées.'
            );
        }

        $utilisateur->delete();

        return redirect()
            ->route('admin.utilisateurs.index')
            ->with('success', 'Utilisateur supprimé.');
    }

    /**
     * Activer / désactiver un utilisateur.
     */
    public function toggleStatut($id)
    {
        $this->checkSuperAdmin();

        $utilisateur = User::findOrFail($id);

        if ($utilisateur->id === Auth::id()) {
            return back()->with('error', 'Vous ne pouvez pas modifier votre propre statut.');
        }

        $utilisateur->update([
            'statut' => $utilisateur->statut === 'actif' ? 'inactif' : 'actif',
        ]);

        return back()->with(
            'success',
            $utilisateur->statut === 'actif' ? 'Utilisateur activé.' : 'Utilisateur désactivé.'
        );
    }
}