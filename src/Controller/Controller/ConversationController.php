<?php

declare(strict_types = 1);

namespace App\Controller\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageFormType;
use App\Repository\ConversationRepository;
use App\Service\CurrentUserService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ConversationController extends AbstractController
{
    public function __construct(
        private readonly ConversationRepository $conversationRepository,
        private readonly PaginatorInterface $paginator,
        private readonly CurrentUserService $currentUserService
    ) {
    }

    #[Route(path: '/conversations', name: 'conversations')]
    public function index(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());

        $conversationsQuery = $this->conversationRepository->findByUser($currentUser);
        $pagination = $this->paginator->paginate($conversationsQuery, $request->query->getInt('page', 1), 30);

        return $this->render('conversations/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route(path: 'new/conversation/user/{id}', name: 'new_conversation')]
    public function create(User $user): Response
    {
        $currentUser = $this->getUser();
        assert($currentUser instanceof User && $currentUser instanceof UserInterface);
        $conversation = new Conversation();
        $conversation->addUser($currentUser);
        $conversation->addUser($user);

        $this->conversationRepository->add($conversation, true);

        return $this->redirectToRoute('conversation', [
            'id' => $conversation->getId(),
        ]);
    }

    #[Route(path: '/conversation/{id}', name: 'conversation')]
    public function show(Conversation $conversation, Request $request): Response
    {
        $currentUser = $this->getUser();
        assert($currentUser instanceof User && $currentUser instanceof UserInterface);
        $message = new Message();
        $message->setConversation($conversation);
        $message->setUser($currentUser);
        $messageForm = $this->createForm(MessageFormType::class, $message);

        $messageForm->handleRequest($request);
        if ($messageForm->isSubmitted() && $messageForm->isValid()) {
            $conversation->addMessage($message);
            $this->conversationRepository->add($conversation, true);

            return $this->redirectToRoute('conversation', [
                'id' => $conversation->getId(),
            ]);
        }

        return $this->render('conversations/show.html.twig', [
            'conversation' => $conversation,
            'messageForm' => $messageForm->createView(),
        ]);
    }
}
