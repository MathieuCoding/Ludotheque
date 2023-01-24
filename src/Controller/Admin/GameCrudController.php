<?php

namespace App\Controller\Admin;

use App\Entity\Game;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class GameCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Game::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('players'),
            IntegerField::new('time'),
            IntegerField::new('age'),
            DateField::new('released'),
            TextEditorField::new('description'),
            TextEditorField::new('content'),
            ImageField::new('picture')->setBasePath('uploads/')->setUploadDir('public/uploads/')->setUploadedFileNamePattern('[timestamp][slug]-[contenthash].[extension]')->setRequired(false),
            AssociationField::new('categories')
            // ChoiceField::new('categories')->allowMultipleChoices(),
            
        ];
    }
    
}
