<?php

namespace App\Controller\Controller;

use App\Builder\Contract\StatementBuilderInterface;
use App\Director\StatementDirector;
use App\Entity\Poll;
use App\Entity\PollAnswer;
use App\Entity\PollOption;
use App\Entity\User;
use App\Form\PollAnswerFormType;
use App\Form\PollFormType;
use App\Repository\PollAnswerRepository;
use App\Repository\PollOptionRepository;
use App\Repository\PollRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class PollController extends AbstractController
{


    public function __construct(
        private PollRepository       $pollRepository,
        private PollAnswerRepository $pollAnswerRepository,
        private PollOptionRepository $pollOptionRepository
    )
    {
    }

    #[Route(path: '/poll/{id}', name: 'show_poll')]
    public function show(Poll $poll): Response
    {
        $pollPercentages = $this->pollOptionRepository->getPollPercentages($poll);
        return $this->render('poll/show.html.twig', [
            'poll' => $poll,
            'options' => $pollPercentages
        ]);
    }

    #[Route(path: '/answer/poll/{id}', name: 'answer_poll')]
    public function pollAnswerForm(Poll $poll, Request $request)
    {
        $currentUser = $this->getUser();
        assert($currentUser instanceof User && $currentUser instanceof UserInterface);

        $answer = new PollAnswer();
        $answer->setPoll($poll);
        $answer->setOwner($currentUser);
        $pollAnswerForm = $this->createForm(PollAnswerFormType::class, $answer, [
            'poll' => $poll
        ]);
        $pollAnswerForm->handleRequest($request);
        if ($pollAnswerForm->isSubmitted() && $pollAnswerForm->isValid()) {
            $pollAnswerOption = $pollAnswerForm->get('options')->getData();
            assert($pollAnswerOption instanceof PollOption);
            $answer->setPollOption($pollAnswerOption);
            $this->pollAnswerRepository->add($answer, true);

            return $this->redirectToRoute('show_poll', ['id' => $poll->getId()]);
        }

        return $this->render('poll/answer.html.twig', [
            'poll' => $poll,
            'pollAnswerForm' => $pollAnswerForm->createView(),
        ]);
    }

    #[Route(path: 'create/poll', name: 'create_poll')]
    public function create(Request $request, StatementBuilderInterface $pollBuilder): Response
    {

        $currentUser = $this->getUser();
        assert($currentUser instanceof User && $currentUser instanceof UserInterface);
        $pollForm = $this->createForm(PollFormType::class);


        $pollForm->handleRequest($request);
        if ($pollForm->isSubmitted() && $pollForm->isValid()) {
            $statementDirector = new StatementDirector();
            $poll = $statementDirector->makePoll($pollBuilder, $pollForm, $currentUser);
            $this->pollRepository->add($poll, true);
            return $this->redirectToRoute('show_poll', ['id' => $poll->getId()]);
        }

        return $this->render('poll/new.html.twig', [
            'pollForm' => $pollForm->createView()
        ]);
    }

    #[Route(path: '/delete/poll/{id}', name: 'delete_poll')]
    public function delete(Poll $poll) : Response
    {
//        $this->denyAccessUnlessGranted('', $poll);
        $this->pollRepository->remove($poll, true);
        return $this->redirectToRoute('profile', ['id' => $this->getUser()->getId()]);
    }
}