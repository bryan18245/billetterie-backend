<?php

namespace Database\Seeders;

use App\Models\Ville;
use Illuminate\Database\Seeder;

class VilleSeeder extends Seeder
{
    public function run(): void
    {
        $villes = [
            // [nom, region, chef_lieu_region]
            ['Fresco',                'GBÔKLE',              false],
            ['Sassandra',             'GBÔKLE',              true],

            ['Abidjan',               'ABIDJAN',             true],

            ['Agboville',             'AGNÉBY-TIASSA',       true],
            ['Azaguié',               'AGNÉBY-TIASSA',       false],
            ['Sikensi',               'AGNÉBY-TIASSA',       false],
            ['Taabo',                 'AGNÉBY-TIASSA',       false],
            ['Tiassalé',              'AGNÉBY-TIASSA',       false],

            ['Gagnoa',                'GÔH',                 true],
            ['Ouragahio',             'GÔH',                 false],
            ['Oumé',                  'GÔH',                 false],

            ['Divo',                  'LÔH-DJIBOUA',         true],
            ['Guitry',                'LÔH-DJIBOUA',         false],
            ['Hiré',                  'LÔH-DJIBOUA',         false],
            ['Lakota',                'LÔH-DJIBOUA',         false],

            ['Grand-Béréby',          'SAN-PÉDRO',           false],
            ['Grabo',                 'SAN-PÉDRO',           false],
            ['San-Pédro',             'SAN-PÉDRO',           true],
            ['Tabou',                 'SAN-PÉDRO',           false],

            ['Grand-Zattry',          'NAWA',                false],
            ['Méagui',                'NAWA',                false],
            ['Soubré',                'NAWA',                true],

            ['Bloléquin',             'CAVALLY',             false],
            ['Guiglo',                'CAVALLY',             true],
            ['Taï',                   'CAVALLY',             false],
            ['Toulepleu',             'CAVALLY',             false],

            ['Bonon',                 'MARAHOUÉ',            false],
            ['Bouaflé',               'MARAHOUÉ',            true],
            ['Sinfra',                'MARAHOUÉ',            false],
            ['Zuénoula',              'MARAHOUÉ',            false],

            ['Bangolo',               'GUEMON',              false],
            ['Duékoué',               'GUEMON',              true],
            ['Kouibly',               'GUEMON',              false],

            ['Biankouma',             'TONKPI',              false],
            ['Danané',                'TONKPI',              false],
            ['Man',                   'TONKPI',              true],
            ['Zouan-Hounien',         'TONKPI',              false],

            ['Booko',                 'BAFING',              false],
            ['Borotou',               'BAFING',              false],
            ['Touba',                 'BAFING',              true],

            ['Boundiali',             'BAGOUÉ',              true],
            ['Kasséré',               'BAGOUÉ',              false],
            ['Tengréla',              'BAGOUÉ',              false],

            ['Dianra',                'BÉRÉ',                false],
            ['Kongasso',              'BÉRÉ',                false],
            ['Mankono',               'BÉRÉ',                true],

            ['Goula',                 'FOLON',               false],
            ['Kaniasso',              'FOLON',               false],
            ['Minignan',              'FOLON',               true],
            ['Tienko',                'FOLON',               false],

            ['Bako',                  'KABADOUGOU',          false],
            ['Gbéléban',              'KABADOUGOU',          false],
            ['Odienné',               'KABADOUGOU',          true],
            ['Séguelon',              'KABADOUGOU',          false],

            ['Dikodougou',            'PORO',                false],
            ['Korhogo',               'PORO',                true],
            ['Napié',                 'PORO',                false],
            ['Sirasso',               'PORO',                false],

            ['Ferkessédougou',        'TCHOLOGO',            true],
            ['Kong',                  'TCHOLOGO',            false],
            ['Koumbal',               'TCHOLOGO',            false],
            ['Ouangolodougou',        'TCHOLOGO',            false],

            ['Dabakala',              'HAMBOL',              false],
            ['Katiola',               'HAMBOL',              true],
            ['Tafiré',                'HAMBOL',              false],
            ['Tortiya-Niakaramadougou', 'HAMBOL',             false],

            ['Bouna',                 'BOUNKANI',            true],
            ['Doropo',                'BOUNKANI',            false],
            ['Nassian',               'BOUNKANI',            false],
            ['Téhini',                'BOUNKANI',            false],

            ['Bondoukou',             'GONTOUGO',            true],
            ['Koun-Fao',              'GONTOUGO',            false],
            ['Tanda',                 'GONTOUGO',            false],
            ['Transua',               'GONTOUGO',            false],

            ['Abengourou',            'INDÉNIÉ-DJUABLIN',    true],
            ['Agnibilékro',           'INDÉNIÉ-DJUABLIN',    false],
            ['Niablé',                'INDÉNIÉ-DJUABLIN',    false],

            ['Aboisso',               'SUD-COMOÉ',           true],
            ['Adiaké',                'SUD-COMOÉ',           false],
            ['Bonoua',                'SUD-COMOÉ',           false],

            ['Adzopé',                'LA MÉ',               true],
            ['Akoupé',                'LA MÉ',               false],
            ['Alépé',                 'LA MÉ',               false],

            ['Dabou',                 'GRANDS-PONTS',        true],
            ['Grand-Lahou',           'GRANDS-PONTS',        false],
            ['Jacqueville',           'GRANDS-PONTS',        false],

            ['Daoukro',               'IFOU',                true],
            ['M\'Bahiakro',           'IFOU',                false],
            ['Ouellé',                'IFOU',                false],

            ['Arrah',                 'MORONOU',             false],
            ['Bongouanou',            'MORONOU',             true],

            ['Bocanda',               'N\'ZI',               false],
            ['Dimbokro',              'N\'ZI',               true],

            ['Didiévi',               'BÉLIER',              false],
            ['Djékanou',              'BÉLIER',              false],
            ['Tiébissou',             'BÉLIER',              false],
            ['Toumodi',               'BÉLIER',              false],
            ['Yamoussoukro',          'BÉLIER',              true],

            ['Daloa',                 'HAUT-SASSANDRA',      true],
            ['Issia',                 'HAUT-SASSANDRA',      false],
            ['Saïoua',                'HAUT-SASSANDRA',      false],
            ['Vavoua',                'HAUT-SASSANDRA',      false],

            ['Bouaké',                'GBÊKÊ',               true],
            ['Diabo',                 'GBÊKÊ',               false],
            ['Djébonoua',             'GBÊKÊ',               false],
            ['Sakassou',              'GBÊKÊ',               false],
        ];

        foreach ($villes as [$nom, $region, $chefLieu]) {
            Ville::create([
                'nom'              => $nom,
                'region'           => $region,
                'chef_lieu_region' => $chefLieu,
                'actif'            => true,
            ]);
        }

        $this->command->info('✅ ' . count($villes) . ' villes créées.');
    }
}