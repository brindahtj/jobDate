<?php

namespace App\Controller\Admin;

use App\Entity\HomeSetting;
use DateTime;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class HomeSettingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return HomeSetting::class;
    }


    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         IdField::new('id')->hideOnForm(),
    //         TextEditorField::new('message'),
    //         TextField::new('callToAction'),
    //         ImageField::new('image')->setBasePath('uploads/')->setUploadDir('public/uploads')->setUploadedFileNamePattern('[randomhash], [extension]')->setRequired(false),
    //         DateTimeField::new('createdAt')->hideOnForm()
    //     ];
    // }
}
