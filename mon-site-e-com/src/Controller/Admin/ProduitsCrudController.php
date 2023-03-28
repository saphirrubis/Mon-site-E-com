<?php

namespace App\Controller\Admin;

use App\Entity\Produits;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProduitsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produits::class;
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            SlugField::new('slug')->setTargetFieldName('nom'),
            AssociationField::new('category'),
            TextareaField::new('description'),
            BooleanField::new('online'),
            ImageField::new('attachement')->setBasePath('/uploads/attachements')->onlyOnIndex(),
            TextField::new('attachementFile')->setFormType(VichImageType::class)->onlyWhenCreating(),
            MoneyField::new('prix')->setCurrency('EUR'),
            DateField::new('createdAt'),
        ];
    }
    
}
