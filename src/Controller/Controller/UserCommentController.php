<?php

namespace App\Controller\Controller;

use App\Entity\Group\Group;
use App\Factory\Comment\CommentFactory;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use App\Service\CurrentUserService;
use App\ValueObject\FlashValueObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/user/comment')]
class UserCommentController extends AbstractController
{

    public function __construct(
        private readonly CurrentUserService $currentUserService,
        private readonly CommentFactory     $commentFactory,
        private readonly UserRepository     $userRepository
    )
    {
    }

    #[Route(path: '/create', name: 'user_create_post')]
    public function create(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $comment = $this->commentFactory->createUserPost($currentUser, '');
        $commentForm = $this->createForm(CommentFormType::class, $comment);

        $commentForm->handleRequest($request);
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $this->userRepository->add($commentForm->getData(), true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, 'changes saved');

            return $this->redirectToRoute('profile', [
                'id' => $currentUser->getId(),
            ]);
        }

        return $this->render('comment/new.html.twig', [
            'commentForm' => $commentForm->createView(),
            'path' => $this->generateUrl('user_create_post', [
                'id' => $currentUser->getId(),
            ]),
        ]);
    }

}