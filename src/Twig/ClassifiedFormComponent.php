<?php

declare(strict_types = 1);

namespace App\Twig;

use App\Entity\Market\Classified;
use App\Form\ClassifiedFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\LiveCollectionTrait;

#[AsLiveComponent('classified_form')]
class ClassifiedFormComponent extends AbstractController
{
    use LiveCollectionTrait;
    use DefaultActionTrait;

    #[LiveProp(fieldName: 'data')]
    public null|Classified $classified = null;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ClassifiedFormType::class, $this->classified);
    }
}
