<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Search;

use App\Form\KeywordFormType;
use App\Repository\Event\EventRepository;
use App\Repository\Group\GroupRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly GroupRepository $groupRepository,
        private readonly EventRepository $eventRepository,
        private readonly PaginatorInterface $paginator,
    ) {
    }

    #[Route(path: '/search', name: 'search')]
    public function search(Request $request): Response
    {
        $searchForm = $this->createForm(KeywordFormType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $keyword = $searchForm->get('keyword')
                ->getData();

            $usersQuery = $this->userRepository->findByKeyword(keyword: $keyword, isQuery: true);
            $eventsQuery = $this->eventRepository->findByKeyword(keyword: $keyword, isQuery: true);
            $groupsQuery = $this->groupRepository->findByKeyword(keyword: $keyword, isQuery: true);

            $usersPagination = $this->paginator->paginate($usersQuery, $request->query->getInt('users-page', 1), 30);
            $eventsPagination = $this->paginator->paginate($eventsQuery, $request->query->getInt('events-page', 1), 30);
            $groupsPagination = $this->paginator->paginate($groupsQuery, $request->query->getInt('groups-page', 1), 30);

            return $this->render('search/index.html.twig', [
                'usersPagination' => $usersPagination,
                'eventsPagination' => $eventsPagination,
                'groupsPagination' => $groupsPagination,
            ]);
        }

        return $this->render('search/search.html.twig', [
            'searchForm' => $searchForm->createView(),
            'usersPagination' => null,
            'eventsPagination' => null,
            'groupsPagination' => null,
        ]);
    }
}
