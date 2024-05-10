<?php

namespace App\Data;

class Cities
{
    /**
     * @return array<string, array<mixed>>
     */
    public function getCountryCities(string $alpha2): array
    {
        return match ($alpha2) {
            'cz' => $this->getCzechCities(),
            'de' => $this->getCitiesInGermany(),
            'gb' => $this->getCitiesInUnitedKingdom(),
            default => []
        };
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function getCzechCities(): array
    {
        return [
            [
                'name' => 'Prague',
                'latitude' => 50.0755,
                'longitude' => 14.4378,
                'country_code' => 'CZ',
                'capital' => true,
            ],
            [
                'name' => 'Brno',
                'latitude' => 49.1951,
                'longitude' => 16.6068,
                'country_code' => 'CZ',
                'capital' => false,
            ],
            [
                'name' => 'Ostrava',
                'latitude' => 49.8209,
                'longitude' => 18.2625,
                'country_code' => 'CZ',
                'capital' => false,
            ],
            [
                'name' => 'Plzeň',
                'latitude' => 49.7384,
                'longitude' => 13.3736,
                'country_code' => 'CZ',
                'capital' => false,
            ],
            [
                'name' => 'Liberec',
                'latitude' => 50.7671,
                'longitude' => 15.0565,
                'country_code' => 'CZ',
                'capital' => false,
            ],
            [
                'name' => 'Olomouc',
                'latitude' => 49.5938,
                'longitude' => 17.2508,
                'country_code' => 'CZ',
                'capital' => false,
            ],
            [
                'name' => 'České Budějovice',
                'latitude' => 48.9745,
                'longitude' => 14.4744,
                'country_code' => 'CZ',
                'capital' => false,
            ],
            [
                'name' => 'Hradec Králové',
                'latitude' => 50.2093,
                'longitude' => 15.8323,
                'country_code' => 'CZ',
                'capital' => false,
            ],
            [
                'name' => 'Ústí nad Labem',
                'latitude' => 50.6607,
                'longitude' => 14.0328,
                'country_code' => 'CZ',
                'capital' => false,
            ],
            [
                'name' => 'Pardubice',
                'latitude' => 50.0379,
                'longitude' => 15.7815,
                'country_code' => 'CZ',
                'capital' => false,
            ],
            [
                'name' => 'Karlovy Vary',
                'latitude' => 50.2310,
                'longitude' => 12.8713,
                'country_code' => 'CZ',
                'capital' => false,
            ],
            [
                'name' => 'Český Krumlov',
                'latitude' => 48.8116,
                'longitude' => 14.3140,
                'country_code' => 'CZ',
                'capital' => false,
            ],
            [
                'name' => 'Zlín',
                'latitude' => 49.2264,
                'longitude' => 17.6677,
                'country_code' => 'CZ',
                'capital' => false,
            ],
            [
                'name' => 'Česká Lípa',
                'latitude' => 50.6855,
                'longitude' => 14.5390,
                'country_code' => 'CZ',
                'capital' => false,
            ],
        ];
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function getCitiesInGermany(): array
    {
        return [
            [
                'name' => 'Berlin',
                'latitude' => 52.5200,
                'longitude' => 13.4050,
                'country_code' => 'DE',
                'capital' => true,
            ],
            [
                'name' => 'Hamburg',
                'latitude' => 53.5511,
                'longitude' => 9.9937,
                'country_code' => 'DE',
                'capital' => false,
            ],
            [
                'name' => 'Munich',
                'latitude' => 48.1351,
                'longitude' => 11.5820,
                'country_code' => 'DE',
                'capital' => false,
            ],
            [
                'name' => 'Cologne',
                'latitude' => 50.9375,
                'longitude' => 6.9603,
                'country_code' => 'DE',
                'capital' => false,
            ],
            [
                'name' => 'Frankfurt',
                'latitude' => 50.1109,
                'longitude' => 8.6821,
                'country_code' => 'DE',
                'capital' => false,
            ],
            [
                'name' => 'Stuttgart',
                'latitude' => 48.7758,
                'longitude' => 9.1829,
                'country_code' => 'DE',
                'capital' => false,
            ],
            [
                'name' => 'Düsseldorf',
                'latitude' => 51.2277,
                'longitude' => 6.7735,
                'country_code' => 'DE',
                'capital' => false,
            ],
            [
                'name' => 'Dortmund',
                'latitude' => 51.5136,
                'longitude' => 7.4653,
                'country_code' => 'DE',
                'capital' => false,
            ],
            [
                'name' => 'Essen',
                'latitude' => 51.4556,
                'longitude' => 7.0116,
                'country_code' => 'DE',
                'capital' => false,
            ],
            [
                'name' => 'Leipzig',
                'latitude' => 51.3397,
                'longitude' => 12.3731,
                'country_code' => 'DE',
                'capital' => false,
            ],
            [
                'name' => 'Bremen',
                'latitude' => 53.0793,
                'longitude' => 8.8017,
                'country_code' => 'DE',
                'capital' => false,
            ],
            [
                'name' => 'Hanover',
                'latitude' => 52.3759,
                'longitude' => 9.7320,
                'country_code' => 'DE',
                'capital' => false,
            ],
            [
                'name' => 'Dresden',
                'latitude' => 51.0504,
                'longitude' => 13.7373,
                'country_code' => 'DE',
                'capital' => false,
            ],
            [
                'name' => 'Nuremberg',
                'latitude' => 49.4521,
                'longitude' => 11.0767,
                'country_code' => 'DE',
                'capital' => false,
            ],
            [
                'name' => 'Duisburg',
                'latitude' => 51.4344,
                'longitude' => 6.7623,
                'country_code' => 'DE',
                'capital' => false,
            ],
            [
                'name' => 'Bochum',
                'latitude' => 51.4818,
                'longitude' => 7.2194,
                'country_code' => 'DE',
                'capital' => false,
            ],
            [
                'name' => 'Wuppertal',
                'latitude' => 51.2562,
                'longitude' => 7.1507,
                'country_code' => 'DE',
                'capital' => false,
            ],
            [
                'name' => 'Bielefeld',
                'latitude' => 52.0302,
                'longitude' => 8.5325,
                'country_code' => 'DE',
                'capital' => false,
            ],
            [
                'name' => 'Bonn',
                'latitude' => 50.7374,
                'longitude' => 7.0982,
                'country_code' => 'DE',
                'capital' => false,
            ],
            [
                'name' => 'Mannheim',
                'latitude' => 49.4875,
                'longitude' => 8.4660,
                'country_code' => 'DE',
                'capital' => false,
            ],
        ];
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function getCitiesInUnitedKingdom(): array
    {
        return [
            [
                'name' => 'London',
                'latitude' => 51.5074,
                'longitude' => -0.1278,
                'country_code' => 'GB',
                'capital' => true,
            ],
            [
                'name' => 'Birmingham',
                'latitude' => 52.4862,
                'longitude' => -1.8904,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Manchester',
                'latitude' => 53.4839,
                'longitude' => -2.2446,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Glasgow',
                'latitude' => 55.8642,
                'longitude' => -4.2518,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Edinburgh',
                'latitude' => 55.9533,
                'longitude' => -3.1883,
                'country_code' => 'GB',
                'capital' => true,
            ],
            [
                'name' => 'Liverpool',
                'latitude' => 53.4084,
                'longitude' => -2.9916,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Bristol',
                'latitude' => 51.4545,
                'longitude' => -2.5879,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Leeds',
                'latitude' => 53.8008,
                'longitude' => -1.5491,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Sheffield',
                'latitude' => 53.3811,
                'longitude' => -1.4701,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Newcastle upon Tyne',
                'latitude' => 54.9783,
                'longitude' => -1.6174,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Cardiff',
                'latitude' => 51.4816,
                'longitude' => -3.1791,
                'country_code' => 'GB',
                'capital' => true,
            ],
            [
                'name' => 'Belfast',
                'latitude' => 54.597,
                'longitude' => -5.9301,
                'country_code' => 'GB',
                'capital' => true,
            ],
            [
                'name' => 'Dublin',
                'latitude' => 53.3498,
                'longitude' => -6.2603,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Leicester',
                'latitude' => 52.6369,
                'longitude' => -1.1398,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Nottingham',
                'latitude' => 52.9548,
                'longitude' => -1.1581,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Plymouth',
                'latitude' => 50.3755,
                'longitude' => -4.1427,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Southampton',
                'latitude' => 50.9097,
                'longitude' => -1.4044,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Aberdeen',
                'latitude' => 57.1497,
                'longitude' => -2.0943,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Swansea',
                'latitude' => 51.6214,
                'longitude' => -3.9436,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'York',
                'latitude' => 53.959,
                'longitude' => -1.0817,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Oxford',
                'latitude' => 51.752,
                'longitude' => -1.2577,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Cambridge',
                'latitude' => 52.2053,
                'longitude' => 0.1218,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Dundee',
                'latitude' => 56.462,
                'longitude' => -2.9707,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Inverness',
                'latitude' => 57.4778,
                'longitude' => -4.2247,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Stirling',
                'latitude' => 56.1165,
                'longitude' => -3.9369,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Perth',
                'latitude' => 56.3969,
                'longitude' => -3.4376,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Chester',
                'latitude' => 53.1934,
                'longitude' => -2.8931,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Newport',
                'latitude' => 51.5881,
                'longitude' => -2.9931,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Exeter',
                'latitude' => 50.7184,
                'longitude' => -3.5339,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Lancaster',
                'latitude' => 54.0496,
                'longitude' => -2.7983,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Salisbury',
                'latitude' => 51.0688,
                'longitude' => -1.7945,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Winchester',
                'latitude' => 51.0597,
                'longitude' => -1.3101,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Canterbury',
                'latitude' => 51.2804,
                'longitude' => 1.0789,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Bath',
                'latitude' => 51.3813,
                'longitude' => -2.359,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Durham',
                'latitude' => 54.7753,
                'longitude' => -1.5849,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Lichfield',
                'latitude' => 52.6833,
                'longitude' => -1.8262,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Worcester',
                'latitude' => 52.192,
                'longitude' => -2.2201,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'St. Albans',
                'latitude' => 51.75,
                'longitude' => -0.3353,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Bangor',
                'latitude' => 53.2268,
                'longitude' => -4.1297,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Lisburn',
                'latitude' => 54.5097,
                'longitude' => -6.0352,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Derry',
                'latitude' => 54.9945,
                'longitude' => -7.3343,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Armagh',
                'latitude' => 54.352,
                'longitude' => -6.6528,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Stoke-on-Trent',
                'latitude' => 53.0027,
                'longitude' => -2.1791,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Wolverhampton',
                'latitude' => 52.5862,
                'longitude' => -2.1288,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Preston',
                'latitude' => 53.7632,
                'longitude' => -2.7031,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Blackpool',
                'latitude' => 53.8175,
                'longitude' => -3.0357,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Lincoln',
                'latitude' => 53.2344,
                'longitude' => -0.5383,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Norwich',
                'latitude' => 52.6309,
                'longitude' => 1.2974,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Derby',
                'latitude' => 52.9228,
                'longitude' => -1.4762,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Peterborough',
                'latitude' => 52.5695,
                'longitude' => -0.2405,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Brighton',
                'latitude' => 50.8225,
                'longitude' => -0.1372,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Huddersfield',
                'latitude' => 53.6458,
                'longitude' => -1.785,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Leicester',
                'latitude' => 52.6369,
                'longitude' => -1.1398,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Bradford',
                'latitude' => 53.7974,
                'longitude' => -1.5416,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Swindon',
                'latitude' => 51.5558,
                'longitude' => -1.7797,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Reading',
                'latitude' => 51.4543,
                'longitude' => -0.9781,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Bournemouth',
                'latitude' => 50.7192,
                'longitude' => -1.8808,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Walsall',
                'latitude' => 52.5862,
                'longitude' => -1.9829,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Southend-on-Sea',
                'latitude' => 51.545,
                'longitude' => 0.7075,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Swansea',
                'latitude' => 51.6214,
                'longitude' => -3.9436,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Milton Keynes',
                'latitude' => 52.0406,
                'longitude' => -0.7594,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Portsmouth',
                'latitude' => 50.8198,
                'longitude' => -1.087,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Luton',
                'latitude' => 51.8787,
                'longitude' => -0.420,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Blackburn',
                'latitude' => 53.7486,
                'longitude' => -2.4878,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Oldham',
                'latitude' => 53.5409,
                'longitude' => -2.1183,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Warrington',
                'latitude' => 53.3901,
                'longitude' => -2.5967,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Woking',
                'latitude' => 51.319,
                'longitude' => -0.5589,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Wigan',
                'latitude' => 53.5454,
                'longitude' => -2.6375,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Southport',
                'latitude' => 53.6461,
                'longitude' => -3.00,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Basingstoke',
                'latitude' => 51.2625,
                'longitude' => -1.0873,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Oxford',
                'latitude' => 51.752,
                'longitude' => -1.2577,
                'country_code' => 'GB',
                'capital' => false,
            ],
            [
                'name' => 'Hove',
                'latitude' => 50.8296,
                'longitude' => -0.167,
                'country_code' => 'GB',
                'capital' => false,
            ],
        ];
    }
}
