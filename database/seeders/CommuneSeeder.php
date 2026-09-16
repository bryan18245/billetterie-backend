<?php
// database/seeders/CommuneSeeder.php

namespace Database\Seeders;

use App\Models\Commune;
use App\Models\Ville;
use Illuminate\Database\Seeder;

class CommuneSeeder extends Seeder
{
    public function run(): void
    {
        // Données extraites du fichier officiel
        // Format : [ville (département), commune]
        $donnees = [
            // ═══════════════ LAGUNES — ABIDJAN ═══════════════
            ['ville' => 'Abidjan',       'commune' => 'Abobo'],
            ['ville' => 'Abidjan',       'commune' => 'Adjamé'],
            ['ville' => 'Abidjan',       'commune' => 'Attécoubé'],
            ['ville' => 'Abidjan',       'commune' => 'Cocody'],
            ['ville' => 'Abidjan',       'commune' => 'Koumassi'],
            ['ville' => 'Abidjan',       'commune' => 'Marcory'],
            ['ville' => 'Abidjan',       'commune' => 'Plateau'],
            ['ville' => 'Abidjan',       'commune' => 'Port-Bouët'],
            ['ville' => 'Abidjan',       'commune' => 'Treichville'],
            ['ville' => 'Abidjan',       'commune' => 'Yopougon'],
            ['ville' => 'Abidjan',       'commune' => 'Anyama'],
            ['ville' => 'Abidjan',       'commune' => 'Bingerville'],
            ['ville' => 'Abidjan',       'commune' => 'Songon'],
            ['ville' => 'Alépé',         'commune' => 'Alépé'],
            ['ville' => 'Alépé',         'commune' => 'Oghwyapo'],
            ['ville' => 'Dabou',         'commune' => 'Dabou'],
            ['ville' => 'Sikensi',       'commune' => 'Sikensi'],
            ['ville' => 'Grand-Lahou',   'commune' => 'Grand-Lahou'],
            ['ville' => 'Jacqueville',   'commune' => 'Jacqueville'],
            ['ville' => 'Tiassalé',      'commune' => 'Tiassalé'],
            ['ville' => 'Tiassalé',      'commune' => 'Taabo'],

            // ═══════════════ SAVANES — KORHOGO ═══════════════
            ['ville' => 'Boundiali',     'commune' => 'Dikodougou'],
            ['ville' => 'Boundiali',     'commune' => 'Guiembé'],
            ['ville' => 'Boundiali',     'commune' => 'Karakoro'],
            ['ville' => 'Boundiali',     'commune' => 'Korhogo'],
            ['ville' => 'Boundiali',     'commune' => 'Komborodougou'],
            ['ville' => 'Boundiali',     'commune' => 'M\'bengué'],
            ['ville' => 'Boundiali',     'commune' => 'Napiélédougou'],
            ['ville' => 'Boundiali',     'commune' => 'Niofoin'],
            ['ville' => 'Boundiali',     'commune' => 'Tioroniaradougou'],
            ['ville' => 'Boundiali',     'commune' => 'Sinematiali'],
            ['ville' => 'Boundiali',     'commune' => 'Sirasso'],
            ['ville' => 'Boundiali',     'commune' => 'Gbon'],
            ['ville' => 'Boundiali',     'commune' => 'Boundiali'],
            ['ville' => 'Boundiali',     'commune' => 'Kasséré'],
            ['ville' => 'Boundiali',     'commune' => 'Kolia'],
            ['ville' => 'Boundiali',     'commune' => 'Kouto'],
            ['ville' => 'Ferkessédougou', 'commune' => 'Diawala'],
            ['ville' => 'Ferkessédougou', 'commune' => 'Ferkessédougou'],
            ['ville' => 'Ferkessédougou', 'commune' => 'Kong'],
            ['ville' => 'Ferkessédougou', 'commune' => 'Koumbala'],
            ['ville' => 'Ferkessédougou', 'commune' => 'Niellé'],
            ['ville' => 'Ferkessédougou', 'commune' => 'Ouangolodougou'],
            ['ville' => 'Tengréla',      'commune' => 'Kanakono'],
            ['ville' => 'Tengréla',      'commune' => 'Tengréla'],

            // ═══════════════ SUD-COMOE — ABOISSO ═══════════════
            ['ville' => 'Aboisso',       'commune' => 'Aboisso'],
            ['ville' => 'Aboisso',       'commune' => 'Ayamé'],
            ['ville' => 'Aboisso',       'commune' => 'Bianouan'],
            ['ville' => 'Aboisso',       'commune' => 'Maféré'],
            ['ville' => 'Aboisso',       'commune' => 'Tiapoum'],
            ['ville' => 'Adiaké',        'commune' => 'Adiaké'],
            ['ville' => 'Adiaké',        'commune' => 'Assinie-Mafia'],
            ['ville' => 'Adiaké',        'commune' => 'Etuéboué'],
            ['ville' => 'Grand-Bassam',  'commune' => 'Grand-Bassam'],
            ['ville' => 'Grand-Bassam',  'commune' => 'Bonoua'],

            // ═══════════════ HAUT-SASSANDRA — DALOA ═══════════════
            ['ville' => 'Daloa',         'commune' => 'Bediala'],
            ['ville' => 'Daloa',         'commune' => 'Daloa'],
            ['ville' => 'Daloa',         'commune' => 'Gadouan'],
            ['ville' => 'Daloa',         'commune' => 'Gboguhé'],
            ['ville' => 'Daloa',         'commune' => 'Zaïbo'],
            ['ville' => 'Daloa',         'commune' => 'Zoukougbeu'],
            ['ville' => 'Issia',         'commune' => 'Boguedia'],
            ['ville' => 'Issia',         'commune' => 'Iboguhe'],
            ['ville' => 'Issia',         'commune' => 'Issia'],
            ['ville' => 'Issia',         'commune' => 'Saïoua'],
            ['ville' => 'Vavoua',        'commune' => 'Dania'],
            ['ville' => 'Vavoua',        'commune' => 'Seitifla'],
            ['ville' => 'Vavoua',        'commune' => 'Vavoua'],

            // ═══════════════ AGNEBY — AGBOVILLE ═══════════════
            ['ville' => 'Agboville',     'commune' => 'Agboville'],
            ['ville' => 'Agboville',     'commune' => 'Azaguié'],
            ['ville' => 'Agboville',     'commune' => 'Cechi'],
            ['ville' => 'Agboville',     'commune' => 'Grand Morié'],
            ['ville' => 'Agboville',     'commune' => 'Oress-Krobou'],
            ['ville' => 'Agboville',     'commune' => 'Rubino'],
            ['ville' => 'Adzopé',        'commune' => 'Adzopé'],
            ['ville' => 'Adzopé',        'commune' => 'Agou'],
            ['ville' => 'Adzopé',        'commune' => 'Afféry'],
            ['ville' => 'Adzopé',        'commune' => 'Becedi-Brignan'],
            ['ville' => 'Adzopé',        'commune' => 'Akoupé'],
            ['ville' => 'Adzopé',        'commune' => 'Assikoi'],
            ['ville' => 'Adzopé',        'commune' => 'Yakassé-Attobrou'],

            // ═══════════════ MOYEN COMOE — ABENGOUROU ═══════════════
            ['ville' => 'Abengourou',    'commune' => 'Abengourou'],
            ['ville' => 'Abengourou',    'commune' => 'Bettié'],
            ['ville' => 'Abengourou',    'commune' => 'Niablé'],
            ['ville' => 'Agnibilékrou',  'commune' => 'Agnibilékrou'],

            // ═══════════════ VALLEE DU BANDAMA — BOUAKE ═══════════════
            ['ville' => 'Bouaké',        'commune' => 'Diabo'],
            ['ville' => 'Bouaké',        'commune' => 'Djébonoua'],
            ['ville' => 'Bouaké',        'commune' => 'Brobo'],
            ['ville' => 'Bouaké',        'commune' => 'Botro'],
            ['ville' => 'Bouaké',        'commune' => 'Bouaké'],
            ['ville' => 'Bouaké',        'commune' => 'Languibonou'],
            ['ville' => 'Béoumi',        'commune' => 'Béoumi'],
            ['ville' => 'Béoumi',        'commune' => 'Bodokro'],
            ['ville' => 'Béoumi',        'commune' => 'Ando-Kekrenou'],
            ['ville' => 'Béoumi',        'commune' => 'Kondrobo'],
            ['ville' => 'Dabakala',      'commune' => 'Bassawa'],
            ['ville' => 'Dabakala',      'commune' => 'Bonieredougou'],
            ['ville' => 'Dabakala',      'commune' => 'Dabakala'],
            ['ville' => 'Dabakala',      'commune' => 'Foumbolo'],
            ['ville' => 'Dabakala',      'commune' => 'Satama-Sokoro'],
            ['ville' => 'Dabakala',      'commune' => 'Satama-Sokouro'],
            ['ville' => 'Katiola',       'commune' => 'Fronan'],
            ['ville' => 'Katiola',       'commune' => 'Katiola'],
            ['ville' => 'Katiola',       'commune' => 'Niakaramandougou'],
            ['ville' => 'Katiola',       'commune' => 'Tafiré'],
            ['ville' => 'Katiola',       'commune' => 'Tortiya'],
            ['ville' => 'Katiola',       'commune' => 'Timbe'],
            ['ville' => 'Sakassou',      'commune' => 'Sakassou'],
            ['ville' => 'Sakassou',      'commune' => 'Tomoudi-Sakassou'],

            // ═══════════════ MONTAGNES — MAN ═══════════════
            ['ville' => 'Man',           'commune' => 'Man'],
            ['ville' => 'Man',           'commune' => 'Facobly'],
            ['ville' => 'Man',           'commune' => 'Kouibly'],
            ['ville' => 'Man',           'commune' => 'Logoualé'],
            ['ville' => 'Man',           'commune' => 'Sangouiné'],
            ['ville' => 'Man',           'commune' => 'Sémien'],
            ['ville' => 'Man',           'commune' => 'Niadrou'],
            ['ville' => 'Man',           'commune' => 'Totrodrou'],
            ['ville' => 'Bangolo',       'commune' => 'Bangolo'],
            ['ville' => 'Bangolo',       'commune' => 'Dieouzon'],
            ['ville' => 'Bangolo',       'commune' => 'Zeo'],
            ['ville' => 'Bangolo',       'commune' => 'Zou'],
            ['ville' => 'Biankouma',     'commune' => 'Biankouma'],
            ['ville' => 'Biankouma',     'commune' => 'Gbonné'],
            ['ville' => 'Biankouma',     'commune' => 'Sipilou'],
            ['ville' => 'Danané',        'commune' => 'Danané'],
            ['ville' => 'Danané',        'commune' => 'Bin-Houyé'],
            ['ville' => 'Danané',        'commune' => 'Zouan-Hounien'],
            ['ville' => 'Danané',        'commune' => 'Mahapleu'],

            // ═══════════════ SUD-BANDAMA — DIVO ═══════════════
            ['ville' => 'Divo',          'commune' => 'Divo'],
            ['ville' => 'Divo',          'commune' => 'Fresco'],
            ['ville' => 'Divo',          'commune' => 'Guitry'],
            ['ville' => 'Divo',          'commune' => 'Hiré'],
            ['ville' => 'Divo',          'commune' => 'Yocoboué'],
            ['ville' => 'Lakota',        'commune' => 'Lakota'],
            ['ville' => 'Lakota',        'commune' => 'Niambézaria'],
            ['ville' => 'Lakota',        'commune' => 'Zikisso'],

            // ═══════════════ FROMAGER — GAGNOA ═══════════════
            ['ville' => 'Gagnoa',        'commune' => 'Bayota'],
            ['ville' => 'Gagnoa',        'commune' => 'Gagnoa'],
            ['ville' => 'Gagnoa',        'commune' => 'Gnagbodougnoa'],
            ['ville' => 'Gagnoa',        'commune' => 'Guibéroua'],
            ['ville' => 'Gagnoa',        'commune' => 'Ouragahio'],
            ['ville' => 'Oumé',          'commune' => 'Diégonéfla'],
            ['ville' => 'Oumé',          'commune' => 'Oumé'],

            // ═══════════════ LACS — YAMOUSSOUKRO ═══════════════
            ['ville' => 'Yamoussoukro',  'commune' => 'Yamoussoukro'],
            ['ville' => 'Yamoussoukro',  'commune' => 'Didiévi'],
            ['ville' => 'Yamoussoukro',  'commune' => 'Attiégouakro'],
            ['ville' => 'Tiébissou',     'commune' => 'Tiébissou'],
            ['ville' => 'Tiébissou',     'commune' => 'Tié-N\'diekro'],
            ['ville' => 'Toumodi',       'commune' => 'Toumodi'],
            ['ville' => 'Toumodi',       'commune' => 'Djékanou'],
            ['ville' => 'Toumodi',       'commune' => 'Angoda'],
            ['ville' => 'Toumodi',       'commune' => 'Kpouebo'],
            ['ville' => 'Toumodi',       'commune' => 'Kokoumbo'],

            // ═══════════════ MOYEN CAVALLY — GUIGLO ═══════════════
            ['ville' => 'Duékoué',       'commune' => 'Duékoué'],
            ['ville' => 'Duékoué',       'commune' => 'Bagohouo'],
            ['ville' => 'Duékoué',       'commune' => 'Gbapleu'],
            ['ville' => 'Duékoué',       'commune' => 'Guéhiébly'],
            ['ville' => 'Duékoué',       'commune' => 'Guézon'],
            ['ville' => 'Guiglo',        'commune' => 'Bloléquin'],
            ['ville' => 'Guiglo',        'commune' => 'Guiglo'],
            ['ville' => 'Guiglo',        'commune' => 'Taï'],
            ['ville' => 'Toulepleu',     'commune' => 'Toulepleu'],
            ['ville' => 'Toulepleu',     'commune' => 'Bakoubly'],
            ['ville' => 'Toulepleu',     'commune' => 'Péhé'],
            ['ville' => 'Toulepleu',     'commune' => 'Tiobly'],

            // ═══════════════ ZANZAN — BONDOUKOU ═══════════════
            ['ville' => 'Bondoukou',     'commune' => 'Bondoukou'],
            ['ville' => 'Bondoukou',     'commune' => 'Gouméré'],
            ['ville' => 'Bondoukou',     'commune' => 'Tabagne'],
            ['ville' => 'Bondoukou',     'commune' => 'Taoudi'],
            ['ville' => 'Bondoukou',     'commune' => 'Sorobango'],
            ['ville' => 'Bondoukou',     'commune' => 'Sapli'],
            ['ville' => 'Bondoukou',     'commune' => 'Sandégué'],
            ['ville' => 'Bouna',         'commune' => 'Bouna'],
            ['ville' => 'Bouna',         'commune' => 'Doropo'],
            ['ville' => 'Bouna',         'commune' => 'Nassian'],
            ['ville' => 'Bouna',         'commune' => 'Téhini'],
            ['ville' => 'Tanda',         'commune' => 'Tanda'],
            ['ville' => 'Tanda',         'commune' => 'Assuefry'],
            ['ville' => 'Tanda',         'commune' => 'Kouassi-Datekro'],
            ['ville' => 'Tanda',         'commune' => 'Transua'],
            ['ville' => 'Tanda',         'commune' => 'Tankéssé'],

            // ═══════════════ BAFFING — TOUBA ═══════════════
            ['ville' => 'Touba',         'commune' => 'Booko'],
            ['ville' => 'Touba',         'commune' => 'Borotou'],
            ['ville' => 'Touba',         'commune' => 'Guintéguéla'],
            ['ville' => 'Touba',         'commune' => 'Koonan'],
            ['ville' => 'Touba',         'commune' => 'Koro'],
            ['ville' => 'Touba',         'commune' => 'Ouaninou'],
            ['ville' => 'Touba',         'commune' => 'Touba'],
            ['ville' => 'Touba',         'commune' => 'Foungbesso'],

            // ═══════════════ BAS-SASSANDRA — SAN-PEDRO ═══════════════
            ['ville' => 'San-Pédro',     'commune' => 'Grand-Béréby'],
            ['ville' => 'San-Pédro',     'commune' => 'San-Pédro'],
            ['ville' => 'Sassandra',     'commune' => 'Guéyo'],
            ['ville' => 'Sassandra',     'commune' => 'Sassandra'],
            ['ville' => 'Sassandra',     'commune' => 'Sago'],
            ['ville' => 'Soubré',        'commune' => 'Buyo'],
            ['ville' => 'Soubré',        'commune' => 'Grand-Zattry'],
            ['ville' => 'Soubré',        'commune' => 'Méagui'],
            ['ville' => 'Soubré',        'commune' => 'Okrouyo'],
            ['ville' => 'Soubré',        'commune' => 'Soubré'],
            ['ville' => 'Tabou',         'commune' => 'Grabo'],
            ['ville' => 'Tabou',         'commune' => 'Tabou'],

            // ═══════════════ N'ZI COMOE — DIMBOKRO ═══════════════
            ['ville' => 'Dimbokro',      'commune' => 'Dimbokro'],
            ['ville' => 'Bongouanou',    'commune' => 'Anoumaba'],
            ['ville' => 'Bongouanou',    'commune' => 'Arrah'],
            ['ville' => 'Bongouanou',    'commune' => 'Bongouanou'],
            ['ville' => 'Bongouanou',    'commune' => 'M\'Batto'],
            ['ville' => 'Bongouanou',    'commune' => 'Tiemelékro'],
            ['ville' => 'Bocanda',       'commune' => 'Bocanda'],
            ['ville' => 'Daoukro',       'commune' => 'Daoukro'],
            ['ville' => 'Daoukro',       'commune' => 'Ettrokro'],
            ['ville' => 'Daoukro',       'commune' => 'Kouassi-Kouassikro'],
            ['ville' => 'Daoukro',       'commune' => 'Ouellé'],
            ['ville' => 'M\'Bahiakro',   'commune' => 'M\'Bahiakro'],
            ['ville' => 'M\'Bahiakro',   'commune' => 'Banguera'],
            ['ville' => 'M\'Bahiakro',   'commune' => 'Koffi-Amonkro'],
            ['ville' => 'M\'Bahiakro',   'commune' => 'Prikro'],

            // ═══════════════ MARAHOUE — BOUAFLE ═══════════════
            ['ville' => 'Bouaflé',       'commune' => 'Bonon'],
            ['ville' => 'Bouaflé',       'commune' => 'Bouaflé'],
            ['ville' => 'Sinfra',        'commune' => 'Sinfra'],
            ['ville' => 'Sinfra',        'commune' => 'Kouétinfla'],
            ['ville' => 'Sinfra',        'commune' => 'Bazré'],
            ['ville' => 'Sinfra',        'commune' => 'Konéfla'],
            ['ville' => 'Zuénoula',      'commune' => 'Gohitafla'],
            ['ville' => 'Zuénoula',      'commune' => 'Zuénoula'],

            // ═══════════════ WORODOUGOU — SEGUELA ═══════════════
            ['ville' => 'Mankono',       'commune' => 'Dianra'],
            ['ville' => 'Mankono',       'commune' => 'Kongasso'],
            ['ville' => 'Mankono',       'commune' => 'Kounahiri'],
            ['ville' => 'Mankono',       'commune' => 'Mankono'],
            ['ville' => 'Mankono',       'commune' => 'Marandallah'],
            ['ville' => 'Mankono',       'commune' => 'Sarhala'],
            ['ville' => 'Mankono',       'commune' => 'Tieningboue'],
            ['ville' => 'Séguéla',       'commune' => 'Djibrosso'],
            ['ville' => 'Séguéla',       'commune' => 'Dualla'],
            ['ville' => 'Séguéla',       'commune' => 'Kani'],
            ['ville' => 'Séguéla',       'commune' => 'Massala'],
            ['ville' => 'Séguéla',       'commune' => 'Morondo'],
            ['ville' => 'Séguéla',       'commune' => 'Séguéla'],
            ['ville' => 'Séguéla',       'commune' => 'Sifié'],
            ['ville' => 'Séguéla',       'commune' => 'Worofla'],

            // ═══════════════ DENGUELE — ODIENNE ═══════════════
            ['ville' => 'Odienné',       'commune' => 'Bako'],
            ['ville' => 'Odienné',       'commune' => 'Dioulatiédougou'],
            ['ville' => 'Odienné',       'commune' => 'Gbéléban'],
            ['ville' => 'Odienné',       'commune' => 'Goulia'],
            ['ville' => 'Odienné',       'commune' => 'Kaniasso'],
            ['ville' => 'Odienné',       'commune' => 'Madinani'],
            ['ville' => 'Odienné',       'commune' => 'Minignan'],
            ['ville' => 'Odienné',       'commune' => 'Odienné'],
            ['ville' => 'Odienné',       'commune' => 'Samatiguila'],
            ['ville' => 'Odienné',       'commune' => 'Séguélon'],
            ['ville' => 'Odienné',       'commune' => 'Seydougou'],
            ['ville' => 'Odienné',       'commune' => 'Tiémé'],
            ['ville' => 'Odienné',       'commune' => 'Tienko'],
        ];

        $total = 0;
        $ignorees = 0;

        foreach ($donnees as $item) {
            $ville = Ville::where('nom', $item['ville'])->first();

            if (!$ville) {
                $this->command->warn("⚠️  Ville non trouvée : {$item['ville']} (commune : {$item['commune']})");
                $ignorees++;
                continue;
            }

            Commune::firstOrCreate(
                [
                    'ville_id' => $ville->id,
                    'nom'      => $item['commune'],
                ],
                [
                    'actif' => true,
                ]
            );

            $total++;
        }

        $this->command->info("✅ {$total} communes créées.");
        if ($ignorees > 0) {
            $this->command->warn("⚠️  {$ignorees} communes ignorées (ville non trouvée).");
        }
    }
}