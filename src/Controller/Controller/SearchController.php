<?php

declare(strict_types = 1);

namespace App\Controller\Controller;

use App\Form\UserSearchFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route(path: '/user-search', name: '_user_search')]
    public function _userSearch(): Response
    {
        $searchForm = $this->createForm(UserSearchFormType::class);

        return $this->render('user/search.html.twig', [
            'searchForm' => $searchForm->createView(),
        ]);
    }
}
