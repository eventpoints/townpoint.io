<?php

declare(strict_types = 1);

namespace App\Controller\Admin;

use App\Entity\Snippet;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SnippetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Snippet::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [IdField::new('id')->onlyOnIndex(), TextField::new('title'), TextEditorField::new('content')];
    }
}
