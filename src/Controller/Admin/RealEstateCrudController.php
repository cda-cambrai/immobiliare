<?php

namespace App\Controller\Admin;

use App\Entity\RealEstate;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class RealEstateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RealEstate::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            'title', 'surface',
            MoneyField::new('price')->setCurrency('EUR'),
            'rooms',
            'sold',
            ImageField::new('image')->setBasePath('img/uploads')->setUploadDir('public/img/uploads/'),
        ];
    }
}
