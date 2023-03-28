<?php

namespace App\Controller\Admin;

use App\Entity\Transporter;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class TransporterCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Transporter::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            TextareaField::new('content'),
            MoneyField::new('prix')->setCurrency('EUR'),
        ];
    }
    
}
