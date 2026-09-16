<?php

namespace App\Services;

use App\Models\Commande;
use App\Models\SuiviCommande;
use Illuminate\Support\Facades\Log;

class SuiviCommandeService
{
    /**
     * Créer un suivi pour une commande.
     * N suivis actifs en parallèle : les anciens restent valides.
     * Expiration : 1 semaine.
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
     * Retrouver un suivi via son token.
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
     * Envoyer le lien (token) + code par WhatsApp.
     */
    public function envoyerParWhatsApp(Commande $commande, SuiviCommande $suivi): bool
    {
        $lien = route('commande.suivi.token', $suivi->token);

        $message = "Bonjour {$commande->client->nom},\n\n"
            . "Votre commande *{$commande->reference_unique}* est bien enregistrée.\n"
            . "Montant : " . number_format($commande->montant_total, 0, ',', ' ') . " FCFA\n\n"
            . "🔗 Lien de suivi :\n{$lien}\n\n"
            . "🔐 Code de suivi : *{$suivi->code}* (6 chiffres)\n\n"
            . "Conservez ce message : vous pourrez suivre votre commande à tout moment "
            . "avec ce lien et ce code, jusqu'au "
            . $suivi->expires_at->format('d/m/Y') . ".\n\n"
            . "Merci pour votre confiance 🙏";

        // === DEV ===
        Log::info('📱 WhatsApp de suivi', [
            'telephone' => $commande->client->telephone,
            'message'   => $message,
        ]);

        // === PROD : brancher un provider (Twilio, Meta, etc.) ===

        return true;
    }

    /**
     * Envoyer le lien + code par email (optionnel).
     */
    public function envoyerParEmail(Commande $commande, SuiviCommande $suivi): bool
    {
        if (!$commande->client->email) {
            return false;
        }

        $lien = route('commande.suivi.token', $suivi->token);

        Log::info('📧 Email de suivi', [
            'email' => $commande->client->email,
            'lien'  => $lien,
            'code'  => $suivi->code,
        ]);

        return true;
    }

    /**
     * Vérifier un code saisi.
     * Le code reste valide jusqu'à expiration.
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
     * Renvoyer un nouveau code (nouveau suivi).
     */
    public function renvoyer(Commande $commande, string $canal = 'whatsapp'): SuiviCommande
    {
        $suivi = $this->creer($commande, $canal);

        if ($canal === 'whatsapp') {
            $this->envoyerParWhatsApp($commande, $suivi);
        } else {
            $this->envoyerParEmail($commande, $suivi);
        }

        return $suivi;
    }
}