<?php

namespace App\Twig\Component;

use App\Form\Form\UserAccountFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(name: 'account_basics_component')]
class AccountBasicsComponent extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    public function __construct(
        private readonly Security $security
    ) {
    }

    protected function instantiateForm(): FormInterface
    {
        $currentUser = $this->security->getUser();
        return $this->createForm(UserAccountFormType::class, $currentUser);
    }
}
