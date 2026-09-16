<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Parametre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ParametreController extends Controller
{
    /**
     * Page paramètres avec onglets (config + compte).
     */
    public function index()
    {
        $parametres = Parametre::orderBy('categorie')
            ->orderBy('ordre')
            ->orderBy('cle')
            ->get()
            ->groupBy('categorie');

        $categoriesLibelles = [
            'general'   => 'Général',
            'contact'   => 'Contact',
            'reseaux'   => 'Réseaux sociaux',
            'livraison' => 'Livraison',
            'seo'       => 'SEO',
        ];

        $user = Auth::user();

        return view('admin.parametres.index', compact('parametres', 'categoriesLibelles', 'user'));
    }

    /**
     * Mise à jour des paramètres globaux.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'parametres'   => ['required', 'array'],
            'parametres.*' => ['nullable', 'string'],
        ]);

        foreach ($data['parametres'] as $cle => $valeur) {
            Parametre::where('cle', $cle)->update([
                'valeur'      => $valeur,
                'modifie_par' => Auth::id(),
            ]);
        }

        Parametre::flushCache();

        return back()->with('success', 'Paramètres enregistrés.');
    }

    /**
     * Mise à jour du compte connecté.
     */
    public function updateCompte(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $data = $request->validate([
            'name'      => ['required', 'string', 'min:2', 'max:100', 'regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\' \-]+$/'],
            'surname'   => ['required', 'string', 'min:2', 'max:100', 'regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\' \-]+$/'],
            'email'     => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'     => ['nullable', 'string', 'regex:/^(\+225)?[0-9]{10}$/'],
            'password'  => ['nullable', 'string', 'min:8', 'confirmed'],
            'langue'    => ['required', 'in:fr,en'],
            'theme'     => ['required', 'in:light,dark,auto'],
        ], [
            'name.regex'         => 'Le prénom ne peut contenir que des lettres, espaces et tirets.',
            'surname.regex'      => 'Le nom ne peut contenir que des lettres, espaces et tirets.',
            'phone.regex'        => 'Le téléphone doit contenir 10 chiffres (avec ou sans +225).',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'email.unique'       => 'Cet email est déjà utilisé par un autre compte.',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()
            ->route('admin.parametres.index')
            ->with('success', 'Votre profil a été mis à jour.')
            ->with('active_tab', 'compte');
    }
}