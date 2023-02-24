<?php

declare(strict_types = 1);

namespace App\Controller\Controller\Conversation;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\User;
use App\Form\Conversation\ConversationEditFormType;
use App\Form\MessageFormType;
use App\Repository\ConversationRepository;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: '/conversations')]
class ConversationController extends AbstractController
{
    public function __construct(
        private readonly ConversationRepository $conversationRepository,
        private readonly PaginatorInterface $paginator,
        private readonly CurrentUserService $currentUserService,
        private readonly TranslatorInterface $translator
    ) {
    }

    #[Route(path: '/', name: 'conversations')]
    public function index(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());

        $conversationsQuery = $this->conversationRepository->findByUser($currentUser);
        $pagination = $this->paginator->paginate($conversationsQuery, $request->query->getInt('page', 1), 30);

        return $this->render('conversations/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route(path: '/remove/{id}', name: 'conversation_delete', methods: ['POST'])]
    public function delete(Request $request, Conversation $conversation): Response
    {
        if ($this->isCsrfTokenValid('delete' . $conversation->getId(), (string)$request->request->get('_token'))) {
            $this->conversationRepository->remove($conversation, true);
        }

        $this->addFlash(FlashValueObject::TYPE_SUCCESS, $this->translator->trans('conversation-deleted'));

        return $this->redirectToRoute('conversations', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @throws \Exception
     */
    #[Route(path: '/create/{id}', name: 'new_conversation')]
    public function create(User $user): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $conversation = $this->conversationRepository->findByTwoUsers($currentUser, $user);

        if ($conversation instanceof Conversation) {
            return $this->redirectToRoute('conversation', [
                'id' => $conversation->getId(),
            ]);
        }

        $conversation = new Conversation();
        $conversation->setOwner($currentUser);
        $conversation->addUser($currentUser);
        $conversation->addUser($user);
        $this->conversationRepository->add($conversation, true);

        return $this->redirectToRoute('conversation', [
            'id' => $conversation->getId(),
        ]);
    }

    #[Route(path: '/show/{id}', name: 'conversation')]
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

    #[Route(path: '/edit/{id}', name: 'conversation_edit')]
    public function edit(Request $request, Conversation $conversation): Response
    {
        $conversationForm = $this->createForm(ConversationEditFormType::class, $conversation);
        $conversationForm->handleRequest($request);
        if ($conversationForm->isSubmitted() && $conversationForm->isValid()) {
            $this->conversationRepository->add($conversation, true);

            return $this->redirectToRoute('conversation', [
                'id' => $conversation->getId(),
            ]);
        }

        return $this->render('conversations/edit.html.twig', [
            'conversation' => $conversation,
            'conversationForm' => $conversationForm->createView(),
        ]);
    }
}
