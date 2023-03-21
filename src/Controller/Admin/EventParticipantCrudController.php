<?php

declare(strict_types = 1);

namespace App\Controller\Admin;

use App\Entity\Event\EventParticipant;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

class EventParticipantCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EventParticipant::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            AssociationField::new('owner'),
            AssociationField::new('event'),
            DateTimeField::new('createdAt'),
        ];
    }
}
