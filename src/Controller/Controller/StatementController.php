<?php

namespace App\Controller\Controller;

use App\Builder\Contract\StatementBuilderInterface;
use App\Director\StatementDirector;
use App\Entity\Statement;
use App\Entity\User;
use App\Form\StatementFormType;
use App\Repository\StatementRepository;
use App\Security\Voter\StatementVoter;
use App\Service\CurrentUserService;
use App\Service\ImageUploadService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class StatementController extends AbstractController
{

    public function __construct(
        private StatementRepository         $statementRepository,
        private readonly ImageUploadService $imageUploadService,
        private readonly CurrentUserService $currentUserService

    )
    {
    }

    #[Route(path: '/statement/{id}', name: 'show_statement')]
    public function show(Statement $statement)
    {
        return $this->render('statement/show.html.twig', [
            'statement' => $statement
        ]);
    }

    #[Route(path: '/statements/delete/{id}', name: 'delete_statement')]
    public function delete(Statement $statement): Response
    {
        $this->denyAccessUnlessGranted(StatementVoter::DELETE, $statement);
        $this->statementRepository->remove($statement, true);
        return $this->redirectToRoute('profile', ['id' => $this->getUser()->getId()]);
    }

    #[Route(path: '/statements/create', name: 'create_statement')]
    public function create(Request $request, StatementBuilderInterface $statementBuilder)
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        if ($this->isGranted(StatementVoter::CREATE)) {

            $statementForm = $this->createForm(StatementFormType::class);

            $statementForm->handleRequest($request);
            if ($statementForm->isSubmitted() && $statementForm->isValid()) {

                $statementDirector = new StatementDirector();
                /** @var UploadedFile $file */
                $file = $statementForm->get('photo')->getData();

                $image = null;
                if ($file) {
                    $image = $this->imageUploadService->processStatementPhoto($file);
                }

                $statement = $statementDirector->makeStatement($statementBuilder, $statementForm, $currentUser, $image);
                $this->statementRepository->add($statement, true);
                return $this->redirectToRoute('profile', ['id' => $currentUser->getId()]);
            }

            return $this->render('statement/new.html.twig', [
                'statementForm' => $statementForm->createView()
            ]);

        }
    }

}