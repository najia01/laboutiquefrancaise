<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
            
        ;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
           TextField::new('Firstname')->setLabel('Prénom'),
           TextField::new('LastName')->setLabel('Nom'),
           DateField::new('lastLoginAt')->setLabel('Dernière connexion')->onlyOnIndex(),
           ChoiceField::new('roles')->setLabel('Permission')->setHelp('Vous pouvez choisir le rôle de cet utilisateur')->setChoices([
            'ROLE_USER'=> 'ROLE_USER',
            'ROLE_ADMIN'=> 'ROLE_ADMIN',
           ])->allowMultipleChoices(),
           TextField::new('Email')->setLabel('Email')->onlyOnIndex(),
        ];
    }
    
}
