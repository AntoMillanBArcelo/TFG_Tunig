<?php
// src/Controller/Admin/CarreraCrudController.php

namespace App\Controller\Admin;

use App\Entity\Carrera;
use App\Entity\Horario;
use App\Entity\Circuito;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class CarreraCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Carrera::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('fecha', 'Fecha de la Carrera'),
            AssociationField::new('horario', 'Horario')->setRequired(true),
            AssociationField::new('circuito', 'Circuito')->setRequired(true),
        ];
    }
    
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Carrera) {
            throw new \InvalidArgumentException('La entidad debe ser una Carrera.');
        }

      
        $horario = $entityInstance->getHorario();
        $fecha = $entityInstance->getFecha();

        if ($horario === null) {
            $this->addFlash('error', 'Debe seleccionar un horario válido.');
            return;
        }

        if (!$this->validarCarrera($fecha, $horario)) {
      
            $this->addFlash('error', 'La carrera no puede ser programada en el horario seleccionado.');
            return;
        }

      
        parent::persistEntity($entityManager, $entityInstance);
    }

    private function validarCarrera(\DateTimeInterface $fecha, Horario $horario): bool
    {
        // No permitir carreras los lunes
        if ($fecha->format('N') === '1') {
            return false;
        }

        $descripcionHorario = $horario->getDescripcion();
        
        if ($descripcionHorario === null) {
            return false; 
        }

        $hora = (int) $fecha->format('H');

        if ($descripcionHorario === 'Invierno' && ($hora < 6 || $hora > 12)) {
            return false; // Invierno: solo por la mañana (6:00 a 12:00)
        }

        if ($descripcionHorario === 'Verano' && ($hora < 18 || $hora >= 24)) {
            return false; // Verano: solo por la noche (18:00 a 23:59)
        }

        return true;
    }
}
