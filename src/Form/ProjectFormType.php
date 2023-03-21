<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\PhoneNumber;
use App\Entity\Project;
use App\Entity\User;
use App\Enum\ProjectEnum;
use App\Repository\ProjectRepository;
use App\Service\CurrentUserService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProjectFormType extends AbstractType
{

    public function __construct(
        private readonly ProjectRepository  $projectRepository,
        private readonly CurrentUserService $currentUserService,
        private readonly Security           $security,
        private readonly TranslatorInterface $translator
    )
    {
    }

    /**
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentUser = $this->currentUserService->getCurrentUser($this->security->getUser());
        $builder
            ->add('title', TextType::class, [
                'label' => 'I will have..',
                'help' => 'something that has a defined end',
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ]);

        if (count($this->projectRepository->findProjectsByUser($currentUser, 'SPOT_LIGHT')) === 0) {
            $builder->add('type', ChoiceType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'choices' => [
                    ProjectEnum::spotlight->name => ProjectEnum::spotlight->value,
                    ProjectEnum::starlight->name => ProjectEnum::starlight->value,
                ],
                'autocomplete' => true
            ]);
        } else {
            $builder->add('type', ChoiceType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'choices' => [
                    ProjectEnum::starlight->name => ProjectEnum::starlight->value,
                ],
                'autocomplete' => true
            ]);
        }

        $builder->add('endAt', DateTimeType::class, [
            'label' => $this->translator->trans('deadline'),
            'input' => 'datetime_immutable',
            'widget' => 'single_text',
            'row_attr' => [
                'class' => 'form-floating mb-3',
            ],
        ])->add('description', TextareaType::class, [
            'label' => 'project details',
            'help' => 'include how people can help you',
            'attr' => [
                'data-controller' => 'textarea-autogrow',
            ],
            'row_attr' => [
                'class' => 'form-floating mb-3',
            ],
        ])->add('project', EntityType::class, [
            'row_attr' => [
                'class' => 'form-floating mb-3',
            ],
            'class' => Project::class,
            'query_builder' => function (EntityRepository $er) use ($currentUser){
                $qb = $er->createQueryBuilder('project');
                $qb->andWhere($qb->expr()->eq('project.owner', ':user' ))->setParameter('user', $currentUser->getId(), 'uuid');
                $qb->andWhere($qb->expr()->eq('project.type', ':type'))->setParameter('type', 'STAR_LIGHT');
                $qb->orderBy('project.createdAt', 'ASC');
                return $qb;
            },
            'choice_label' => 'title',
            'autocomplete' => true,
            'multiple' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
