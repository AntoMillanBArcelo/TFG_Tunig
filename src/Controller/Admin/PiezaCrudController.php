<?php

namespace App\Controller\Admin;

use App\Entity\Pieza;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class PiezaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pieza::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ChoiceField::new('nombre')
                ->setChoices([
                    'Inducción' => 'inducción',
                    'ECU' => 'ECU',
                    'Sistema de Combustible' => 'Sistema de Combustible',
                    'Escape' => 'escape',
                    'Nitro' => 'nitro',
                    'Suspensión' => 'suspension',
                    'Frenos' => 'frenos',
                    'Embrague' => 'embrague',
                    'Diferencial' => 'diferencial',
                ])
                ->setLabel('Nombre'),
            TextField::new('descripcion'),
            IntegerField::new('velocidadPunta'),
            ChoiceField::new('categoria')
                ->setChoices([
                    'Deportivo' => 'deportivo',
                    'Pro' => 'pro',
                    'Súper' => 'súper',
                    'Élite' => 'élite',
                ])
                ->setLabel('Categoría'),
        ];
    }
}
