<?php

namespace App\Controller\Controller;

use App\Entity\Country;
use App\Entity\User;
use App\Enum\ContinentEnum;
use App\Repository\CountryRepository;
use Doctrine\Common\Collections\Order;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class TownController extends AbstractController
{
    public function __construct(
        private readonly CountryRepository $countryRepository
    ) {
    }

    #[Route(path: '/earth', name: 'show_town')]
    public function showTown(
        #[CurrentUser]
        User $currentUser
    ): Response {
        return $this->render('town/show.html.twig');
    }

    #[Route(path: '/earth/{continent}/{country_slug}', name: 'show_country')]
    public function showCountry(
        ContinentEnum $continent,
        #[MapEntity(mapping: [
            'country_slug' => 'slug',
        ])]
        Country $country
    ): Response {
        return $this->render('country/show.html.twig', [
            'continent' => $continent,
            'country' => $country,
        ]);
    }

    #[Route(path: '/earth/{continent}', name: 'show_continent')]
    public function showContinent(
        ContinentEnum $continent,
    ): Response {
        $countries = $this->countryRepository->findBy([
            'continent' => $continent,
        ], [
            'name' => Order::Ascending->value,
        ]);
        return $this->render('continent/show.html.twig', [
            'continent' => $continent,
            'countries' => $countries,
        ]);
    }

    #[Route(path: '/earth', name: 'show_earth')]
    public function showWorld(): Response
    {
        return $this->render('earth/show.html.twig', [
            'continents' => ContinentEnum::cases(),
        ]);
    }
}
