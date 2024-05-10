<?php

namespace App\Controller\Controller;

use App\Entity\Conversation;
use App\Entity\ConversationParticipant;
use App\Entity\Message;
use App\Entity\User;
use App\Form\Form\MessageFormType;
use App\Repository\ConversationRepository;
use App\Service\RandomService\Contract\RandomGeneratorInterface;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ConversationController extends AbstractController
{
    public function __construct(
        private readonly ConversationRepository $conversationRepository,
        private readonly RandomGeneratorInterface $randomConversationNameService
    ) {
    }

    #[Route(path: '/conversations', name: 'conversations')]
    public function index(): Response
    {
        return $this->render('/conversation/index.html.twig');
    }

    /**
     * @throws NonUniqueResultException
     */
    #[Route(path: '/conversations/dm/{id}', name: 'create_direct_message', methods: [Request::METHOD_GET])]
    public function directMessage(#[CurrentUser] User $currentUser, User $user): Response
    {
        $conversation = $this->conversationRepository->findByTwoParticipants(currentUser: $currentUser, target: $user);
        if ($conversation instanceof Conversation) {
            return $this->redirectToRoute('show_conversation', [
                'id' => $conversation->getId(),
            ]);
        }

        $conversation = new Conversation(title: $this->randomConversationNameService->generate());
        $owner = new ConversationParticipant(conversation: $conversation, owner: $currentUser);
        $target = new ConversationParticipant(conversation: $conversation, owner: $user);
        $conversation->addConversationParticipant($owner);
        $conversation->addConversationParticipant($target);

        $this->conversationRepository->save(entity: $conversation, flush: true);
        return $this->redirectToRoute('show_conversation', [
            'id' => $conversation->getId(),
        ]);
    }

    #[Route(path: '/conversations/{id}', name: 'show_conversation')]
    public function show(Conversation $conversation, #[CurrentUser] User $currentUser, Request $request): Response
    {
        $message = new Message(owner: $currentUser, conversation: $conversation);
        $messageForm = $this->createForm(MessageFormType::class, $message);

        $messageForm->handleRequest($request);
        if ($messageForm->isSubmitted() && $messageForm->isValid()) {
            $conversation->addMessage($message);
            $this->conversationRepository->save(entity: $conversation, flush: true);
            return $this->redirectToRoute('show_conversation', [
                'id' => $conversation->getId(),
            ]);
        }

        return $this->render('/conversation/show.html.twig', [
            'messageForm' => $messageForm,
            'conversation' => $conversation,
        ]);
    }

    #[Route(path: '/conversations/{id}/send/message/', name: 'send_message')]
    public function sendMessage(Request $request, Conversation $conversation, #[CurrentUser] User $currentUser): Response
    {
        $content = $request->request->get('content');
        $message = new Message(content: $content, owner: $currentUser, conversation: $conversation);

        $conversation->addMessage($message);
        $this->conversationRepository->save(entity: $conversation, flush: true);

        return $this->redirectToRoute('show_conversation');
    }

    //    #[Route(path: '/conversations/add/{id}', name: 'add_conversation_participant')]
    //    public function add(Conversation $conversation): Response
    //    {
    //
    //    }
}
