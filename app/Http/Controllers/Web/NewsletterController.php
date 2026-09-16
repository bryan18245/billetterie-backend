<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\NewsletterAbonne;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ], [
            'email.required' => 'Veuillez saisir votre email.',
            'email.email'    => 'Email invalide.',
        ]);

        $abonne = NewsletterAbonne::firstOrCreate(
            ['email' => $data['email']],
            ['actif' => true]
        );

        if (!$abonne->wasRecentlyCreated) {
            return back()->with('success', 'Vous êtes déjà inscrit à la newsletter. Merci !');
        }

        return back()->with('success', 'Merci ! Vous êtes bien inscrit à la newsletter.');
    }
}