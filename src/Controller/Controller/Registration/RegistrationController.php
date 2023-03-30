<?php

declare(strict_types=1);

namespace App\Controller\Controller\Registration;

use App\Entity\User;
use App\Enum\RegistrationWorkflowEnum;
use App\Form\Payment\CardFormType;
use App\Form\RegistrationFormType;
use App\Model\Card;
use App\Security\UserWebAuthenticator;
use App\Service\AvatarService;
use App\Service\Subscription\SubscriptionHelper;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Charge;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Subscription;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Workflow\WorkflowInterface;
use function App\Controller\Controller\trim;

class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly AvatarService          $avatarService,
        private readonly EntityManagerInterface $entityManager,
        private readonly WorkflowInterface      $registrationStateMachine,
    )
    {
    }

    #[Route('/register', name: 'register_step_one')]
    public function stepOne(Request $request, UserPasswordHasherInterface $userPasswordHasher): ?Response
    {
        $session = $request->getSession();
        if ($session->has('user_data')) {
            $session->remove('user_data');
        }

        $user = new User();
        if ($this->registrationStateMachine->can($user, RegistrationWorkflowEnum::TRANSITION_TO_COMPLETE->value)) {
            return $this->redirectToRoute('register_step_two');
        }

        $userForm = $this->createForm(RegistrationFormType::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $this->registrationStateMachine->apply($user, RegistrationWorkflowEnum::TRANSITION_TO_PAYMENT_FORM->value);

            $user->setPassword($userPasswordHasher->hashPassword($user, $userForm->get('plainPassword')->getData()));
            $avatar = $this->avatarService->createAvatar($user->getEmail());
            $user->setAvatar($avatar);
            $user->setHandle(trim((string)$userForm->get('handle')->getData()));
            $session->set('user_data', $user);

            return $this->redirectToRoute('register_step_two');
        }

        return $this->render('registration/register.html.twig', [
            'user' => $user,
            'userForm' => $userForm,
        ]);
    }

    #[Route('/register/step-two', name: 'register_step_two')]
    public function stepTwo(
        Request                    $request,
        UserAuthenticatorInterface $userAuthenticator,
        UserWebAuthenticator       $authenticator,
        WorkflowInterface          $registrationStateMachine,
        SubscriptionHelper $subscriptionHelper
    ): ?Response
    {
        $session = $request->getSession();
        /** @var User $user */
        $user = $session->get('user_data');

//        if (!$user instanceof User) {
//            return $this->redirectToRoute('register_step_one');
//        }


        if ($request->getMethod() === Request::METHOD_POST) {

            Stripe::setApiKey($_ENV["STRIPE_PRIVATE_KEY"]);
            Charge::create ([
                "amount" => 5 * 100,
                "currency" => "usd",
                "source" => $request->request->get('stripeToken'),
                "description" => "Binaryboxtuts Payment Test"
            ]);

            dd($request->request->get('stripeToken'));

            $user->setStripeToken($request->request->get('stripeToken'));

            // persist user details
            $registrationStateMachine->apply($user, RegistrationWorkflowEnum::TRANSITION_TO_COMPLETE->value);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $userAuthenticator->authenticateUser($user, $authenticator, $request);

        }

        return $this->render('payment/new.html.twig', [
            'stripe_public_key' => $_ENV["STRIPE_PUBLIC_KEY"],
            'user' => $user,
        ]);
    }
    
}
