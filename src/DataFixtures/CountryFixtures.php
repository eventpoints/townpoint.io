<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Data\Countries;
use App\Entity\Country;
use App\Repository\CountryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class CountryFixtures extends Fixture
{
    public function __construct(
        private readonly Countries $countries,
        private readonly CountryRepository $countryRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $count = $this->countryRepository->count([]);
        if ($count === 249) {
            return;
        }

        $countries = $this->countries->getCountries();
        $slugger = new AsciiSlugger();

        foreach ($countries as $code => $data) {
            $country = new Country(
                name: strtolower($data['name']),
                slug: $slugger->slug(strtolower($data['name']))->toString(),
                alpha2: strtolower($data['alpha2']),
                alpha3: strtolower($data['alpha3']),
                num: $data['num'],
                isd: $data['isd'],
                continent: $data['continent'],
            );
            $manager->persist($country);
        }
        $manager->flush();
    }
}
