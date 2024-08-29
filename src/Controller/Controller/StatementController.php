<?php

namespace App\Controller\Controller;

use App\DataTransferObject\StatementFilterDto;
use App\Entity\Statement;
use App\Entity\Town;
use App\Entity\User;
use App\Enum\FlashMessageEnum;
use App\Enum\StatementTypeEnum;
use App\Form\Form\StatementFormType;
use App\Repository\StatementRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class StatementController extends AbstractController
{
    public function __construct(
        private readonly StatementRepository $statementRepository
    )
    {
    }

    #[Route(path: '/statement/{id}', name: 'show_statement')]
    public function show(
        Statement           $statement,
        Request             $request,
        #[CurrentUser] User $currentUser
    ): Response
    {
        $replyStatement = new Statement(owner: $currentUser, town: $statement->getTown(), statement: $statement);
        $replyStatementForm = $this->createForm(StatementFormType::class, $replyStatement, [
            'is_disabled_statement_type' => true,
        ]);
        $replyStatementForm->get('type')->setData(StatementTypeEnum::RESPONSE_STATEMENT);

        $replyStatementForm->handleRequest($request);
        if ($replyStatementForm->isSubmitted() && $replyStatementForm->isValid()) {
            $replyStatement->setType(StatementTypeEnum::RESPONSE_STATEMENT);
            $statement->addStatement($replyStatement);
            $this->statementRepository->save(entity: $replyStatement, flush: true);
            $this->addFlash(FlashMessageEnum::MESSAGE->value, 'statement published');
            return $this->redirectToRoute('show_statement', [
                'id' => $statement->getId()
            ]);
        }

        return $this->render('statement/show.html.twig', [
            'statement' => $statement,
            'statementForm' => $replyStatementForm
        ]);
    }

    #[Route(path: '/statement/{town_slug}/filter', name: 'htmx_statements')]
    public function filter(
        #[MapEntity(mapping: [
            'town_slug' => 'slug',
        ])]
        Town    $town,
        Request $request
    ): Response
    {
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
