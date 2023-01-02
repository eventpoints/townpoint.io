<?php

declare(strict_types = 1);

namespace App\Controller\Admin;

use App\Entity\Conversation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ConversationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Conversation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title'),
            AssociationField::new('messages')->setFormTypeOption('by_reference', false),
            AssociationField::new('users')->setFormTypeOption('by_reference', false),
            AssociationField::new('owner'),
        ];
    }
}
