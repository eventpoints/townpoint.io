<?php

declare(strict_types = 1);

namespace App\Controller\Controller;

use App\Entity\User;
use App\Form\UserAccountFormType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\ViewRepository;
use App\Service\CurrentUserService;
use App\Service\ImageUploadService;
use App\Service\InteractorService;
use App\Service\ProfileViewService;
use App\ValueObject\FlashValueObject;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ImageUploadService $imageUploadService,
        private readonly ProfileViewService $profileViewService,
        private readonly ViewRepository $viewRepository,
        private readonly PostRepository $postRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly CurrentUserService $currentUserService
    ) {
    }

    #[Route(path: '/insights', name: 'insights')]
    public function insight(): Response
    {
        $currentUser = $this->getUser();
        assert($currentUser instanceof User && $currentUser instanceof UserInterface);
        $views = $this->viewRepository->findByCurrentUser($currentUser);

        return $this->render('user/insights.html.twig', [
            'views' => $views,
        ]);
    }

    #[Route(path: '/dashboard', name: 'dashboard')]
    public function dashboard(): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());

        $qr = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data('hi')
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(500)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->validateResult(false)
            ->build();

        return $this->render('user/dashboard.html.twig', [
            'qr' => $qr->getDataUri(),
        ]);
    }

    #[Route(path: '/account', name: 'account')]
    public function account(Request $request): Response
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        $userForm = $this->createForm(UserAccountFormType::class, $currentUser);

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            /** @var UploadedFile $file */
            $file = $userForm->get('avatar')
                ->getData();

            if ($file instanceof UploadedFile) {
                $base64Image = $this->imageUploadService->processAvatar($file);
                $currentUser->setAvatar($base64Image->getEncoded());
            }

            $this->userRepository->add($currentUser, true);
            $this->addFlash(FlashValueObject::TYPE_SUCCESS, FlashValueObject::MESSAGE_SUCCESS_SAVED);

            return $this->redirectToRoute('account');
        }

        return $this->render('user/account.html.twig', [
            'userForm' => $userForm->createView(),
        ]);
    }

    #[Route(path: '/profile/{id}', name: 'profile')]
    public function profile(User $user, Request $request): Response
    {
        $this->profileViewService->view($user);
        $currentUser = $this->currentUserService->getCurrentUser($this->getUser());
        //        $this->interactorService->check($currentUser, $user);

        $posts = $this->postRepository->findPostsByUser($user);

        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }

    #[Route(path: '/menu/profile', name: 'profile_menu')]
    public function profileMenu(): Response
    {
        return $this->render('user/_profile-post-menu.html.twig');
    }
}
