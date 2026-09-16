<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ville;
use Illuminate\Http\Request;

class VilleController extends Controller
{
    /**
     * Recherche de villes pour l'autocomplétion.
     */
    public function recherche(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $villes = Ville::actives()
            ->where('nom', 'like', "%{$q}%")
            ->orderBy('nom')
            ->limit(10)
            ->get(['id', 'nom', 'region']);

        return response()->json($villes);
    }
}