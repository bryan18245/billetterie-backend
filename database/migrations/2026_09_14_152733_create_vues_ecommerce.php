<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Vue 1 : Statistiques produits
        DB::statement("
            CREATE OR REPLACE VIEW v_stats_produit AS
            SELECT 
                p.id AS id_produit,
                p.nom,
                p.prix,
                p.stock,
                p.note_moyenne,
                p.nb_ventes,
                COUNT(DISTINCT a.id) AS nb_avis
            FROM produits p
            LEFT JOIN avis a ON a.produit_id = p.id AND a.statut = 'approuve'
            GROUP BY p.id, p.nom, p.prix, p.stock, p.note_moyenne, p.nb_ventes
        ");

        // Vue 2 : Top produits
        DB::statement("
            CREATE OR REPLACE VIEW v_top_produits AS
            SELECT 
                p.id AS id_produit,
                p.nom,
                SUM(lc.quantite) AS total_vendu,
                SUM(lc.sous_total) AS total_ca
            FROM produits p
            JOIN lignes_commandes lc ON lc.produit_id = p.id
            JOIN commandes c ON lc.commande_id = c.id AND c.statut = 'payee'
            GROUP BY p.id, p.nom
            ORDER BY total_vendu DESC
        ");

        // Vue 3 : CA mensuel
        DB::statement("
            CREATE OR REPLACE VIEW v_chiffre_affaires_mensuel AS
            SELECT 
                DATE_FORMAT(date_commande, '%Y-%m') AS mois,
                COUNT(*) AS nb_commandes,
                SUM(montant_total) AS chiffre_affaires
            FROM commandes
            WHERE statut = 'payee'
            GROUP BY DATE_FORMAT(date_commande, '%Y-%m')
            ORDER BY mois DESC
        ");
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS v_stats_produit");
        DB::statement("DROP VIEW IF EXISTS v_top_produits");
        DB::statement("DROP VIEW IF EXISTS v_chiffre_affaires_mensuel");
    }
};