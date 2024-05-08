<?php

namespace App\Controller\Controller;

use App\DataTransferObject\StatementFilterDto;
use App\Entity\Statement;
use App\Entity\Town;
use App\Enum\StatementTypeEnum;
use App\Repository\StatementRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatementController extends AbstractController
{
    public function __construct(
        private readonly StatementRepository $statementRepository
    ) {
    }

    #[Route(path: '/statement/{id}', name: 'show_statement')]
    public function showCountry(
        Statement $statement
    ): Response {
        return $this->render('statement/show.html.twig', [
            'statement' => $statement,
        ]);
    }

    #[Route(path: '/statement/{town_slug}/filter', name: 'htmx_statements')]
    public function filter(
        #[MapEntity(mapping: [
            'town_slug' => 'slug',
        ])]
        Town $town,
        Request $request
    ): Response {
        $keyword = $request->get('keyword');
        $type = StatementTypeEnum::match($request->get('type'));
        $statementFilterDto = new StatementFilterDto($keyword, $type);
        $statements = $this->statementRepository->findByFilterAndTown($statementFilterDto, $town);
        return $this->render('statement/hx_index.html.twig', [
            'statements' => $statements,
            'town' => $town,
        ]);
    }
}
