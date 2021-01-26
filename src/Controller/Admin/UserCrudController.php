<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            'email', ArrayField::new('roles'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        // Cette ligne permet de "cacher" le fait de pouvoir créer un User dans le BO
        return $actions->remove(Crud::PAGE_INDEX, Action::NEW);
    }
}
