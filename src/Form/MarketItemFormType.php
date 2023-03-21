<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Image;
use App\Entity\Market\Item;
use App\Entity\User;
use App\Exception\ShouldNotHappenException;
use App\Service\ImageUploadService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\Dropzone\Form\DropzoneType;

class MarketItemFormType extends AbstractType
{
    public function __construct(
        private readonly Security            $security,
        private readonly TranslatorInterface $translator,
        private readonly ImageUploadService  $imageUploadService
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new ShouldNotHappenException('user is needed to create a market item');
        }

        $builder
            ->add('images', DropzoneType::class, [
                'mapped' => false,
                'multiple' => true
            ])
            ->add('title', TextType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('condition', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    $this->translator->trans('new') => 'new',
                    $this->translator->trans('new-other') => 'new-other',
                    $this->translator->trans('used') => 'used',
                    $this->translator->trans('seller-refurbished') => 'used',
                    $this->translator->trans('spare-parts-or-not-working') => 'spare-parts-or-not-working',
                ],
                'autocomplete' => true,
            ])
            ->add('description', TextareaType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('price', MoneyType::class, [
                'label' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'currency' => $user->getCurrency(),
            ])->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                /** @var Item $item */
                $item = $event->getData();
                $files = $form->get('images')->getData();

                foreach ($files as $file) {
                    $image = new Image();
                    $content = $this->imageUploadService->processStatementPhoto($file);
                    $image->setContent($content->getEncoded());
                    $item->addImage($image);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
