<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Data\Cities;
use App\Entity\Town;
use App\Repository\CountryRepository;
use App\Repository\TownRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class CityFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly CountryRepository $countryRepository,
        private readonly TownRepository $cityRepository,
        private readonly Cities $cities,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $countries = $this->countryRepository->findAll();
        foreach ($countries as $country) {
            $cities = $this->cities->getCountryCities(alpha2: $country->getAlpha2());
            $count = $this->cityRepository->count([
                'country' => $country,
            ]);
            if ($count === count($cities)) {
                continue;
            }

            foreach ($cities as $cityData) {
                $town = $this->cityRepository->findOneBy([
                    'country' => $country,
                    'name' => strtolower((string) $cityData['name']),
                ]);
                if ($town instanceof Town) {
                    continue;
                }

                $slugger = new AsciiSlugger();

                $town = new Town(
                    name: strtolower((string) $cityData['name']),
                    slug: $slugger->slug(strtolower((string) $cityData['name']))->toString(),
                    latitude: $cityData['latitude'],
                    longitude: $cityData['longitude'],
                    country: $country
                );

                if ($cityData['capital']) {
                    $country->setCapitalCity($town);
                }

                $country->addTown($town);
            }

            $manager->persist($country);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CountryFixtures::class,
        ];
    }
}
