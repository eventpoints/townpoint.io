<?php

declare(strict_types = 1);

namespace App\Form;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectUserAddressFormType extends AbstractType
{
    public function __construct(
        private readonly Security $security
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentUser = $this->security->getUser();
        if (! $currentUser instanceof User) {
            throw new Exception('Must be type of User');
        }
        $builder
            ->add('address', EntityType::class, [
                'class' => Address::class,
                'query_builder' => function (EntityRepository $er) use ($currentUser): QueryBuilder {
                    $qb = $er->createQueryBuilder('a');
                    $qb->innerJoin('a.owner', 'o');
                    $qb->andWhere(
                        $qb->expr()
                            ->eq('o.id', ':user')
                    )->setParameter('user', $currentUser->getId(), 'uuid')
                        ->orderBy('a.createdAt', 'ASC');

                    return $qb;
                },
                'choice_label' => function (Address $address): string {
                    return ($address->getTitle() ?? '') . ' - ' . $address->getLineOne() . ', ' . $address->getLineTwo() . ',' . $address->getTownOrCity() . ',' . $address->getPostCode() . ',' . $address->getCountry();
                },
                'autocomplete' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
