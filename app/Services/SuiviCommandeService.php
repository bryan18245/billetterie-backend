<?php

namespace App\Services;

use App\Models\Commande;
use App\Models\SuiviCommande;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SuiviCommandeService
{
    /**
     * Créer un suivi pour une commande.
     */
    public function creer(Commande $commande, string $canal = 'whatsapp'): SuiviCommande
    {
        $suivi = SuiviCommande::create([
            'commande_id' => $commande->id,
            'code'        => SuiviCommande::genererCode(),
            'token'       => SuiviCommande::genererToken(),
            'canal'       => $canal,
            'expires_at'  => now()->addWeek(),
        ]);

        return $suivi;
    }

    /**
     * Retrouve un suivi via son token.
     */
    public function trouverParToken(string $token): ?SuiviCommande
    {
        return SuiviCommande::where('token', $token)
            ->with(['commande.client', 'commande.lignes', 'commande.adresse'])
            ->first();
    }

    /**
     * Dernier suivi d'une commande.
     */
    public function trouverParCommande(Commande $commande): ?SuiviCommande
    {
        return SuiviCommande::where('commande_id', $commande->id)
            ->latest()
            ->first();
    }

    /**
     * Envoie un message WhatsApp via l'API NoraSend.
     */
    public function envoyerParWhatsApp(Commande $commande, SuiviCommande $suivi): bool
    {
        $lien = route('commande.suivi.token', $suivi->token);

        $message = "Bonjour {$commande->client->nom},\n\n"
            . "Votre commande *{$commande->reference_unique}* est bien enregistrée.\n"
            . "Montant : " . number_format($commande->montant_total, 0, ',', ' ') . " FCFA\n\n"
            . "Lien de suivi :\n{$lien}\n\n"
            . "Code de suivi : *{$suivi->code}* (6 chiffres)\n\n"
            . "Conservez ce message : vous pourrez suivre votre commande à tout moment "
            . "avec ce lien et ce code, jusqu'au "
            . $suivi->expires_at->format('d/m/Y') . ".\n\n"
            . "Merci pour votre confiance 🙏";

        // Normalisation du numéro pour l'API
        $telephone = $this->normaliserTelephone($commande->client->telephone);

        if (!$telephone) {
            Log::warning('❌ Numéro WhatsApp invalide', [
                'commande'  => $commande->reference_unique,
                'telephone' => $commande->client->telephone,
            ]);
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.norasend.token'),
                'Content-Type'  => 'application/json',
            ])->post(config('services.norasend.url'), [
                'to'   => $telephone,
                'type' => 'text',
                'text' => $message,
            ]);

            if ($response->successful()) {
                Log::info('✅ WhatsApp envoyé via NoraSend', [
                    'telephone' => $telephone,
                    'reference' => $commande->reference_unique,
                ]);
                return true;
            }

            Log::error('❌ Échec NoraSend', [
                'telephone' => $telephone,
                'status'    => $response->status(),
                'body'      => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('❌ Exception NoraSend', [
                'telephone' => $telephone,
                'message'   => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Normalise un numéro pour l'API NoraSend.
     *
     * Format attendu : 225 + 8 chiffres (sans le préfixe 01/05/07).
     * Exemple : "2250102444595" → "22502444595"
     */
    protected function normaliserTelephone(?string $telephone): ?string
    {
        if (!$telephone) {
            return null;
        }

        // 1. Retire tout sauf les chiffres
        $tel = preg_replace('/\D/', '', $telephone);

        // 2. Retire le préfixe 225 s'il existe
        if (str_starts_with($tel, '225')) {
            $tel = substr($tel, 3);
        }

        // 3. Le numéro local doit faire 10 chiffres (ex: 0102444595)
        if (strlen($tel) !== 10) {
            return null;
        }

        // 4. Vérifie que le préfixe est 01, 05 ou 07
        $prefixe = substr($tel, 0, 2);
        if (!in_array($prefixe, ['01', '05', '07'])) {
            return null;
        }

        // 5. Retire les 2 premiers chiffres (le préfixe)
        $tel = substr($tel, 2);  // → 8 chiffres

        // 6. Retourne 225 + 8 chiffres
        return '225' . $tel;
    }

    /**
     * Vérifie un code saisi par l'utilisateur.
     */
    public function verifierCode(SuiviCommande $suivi, string $codeSaisi): bool
    {
        if (!$suivi->estValide()) {
            return false;
        }

        $ok = trim($codeSaisi) === (string) $suivi->code;

        if (!$ok) {
            $suivi->increment('tentatives');
        }

        return $ok;
    }

    /**
     * Renvoyer un nouveau code.
     */
    public function renvoyer(Commande $commande, string $canal = 'whatsapp'): SuiviCommande
    {
        $suivi = $this->creer($commande, $canal);

        if ($canal === 'whatsapp') {
            $this->envoyerParWhatsApp($commande, $suivi);
        }

        return $suivi;
    }
}