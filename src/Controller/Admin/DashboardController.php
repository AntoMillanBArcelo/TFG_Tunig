<?php

namespace App\Controller\Admin;
use App\Entity\User;
use App\Entity\Coche;
use App\Entity\Horario;
use App\Entity\Motor;
use App\Entity\Pieza;
use App\Entity\Carrera;
use App\Entity\Prestacion;
use App\Entity\Reserva;
use App\Entity\TramoHorario;
use App\Entity\Circuito;
use App\Entity\ECU;
use App\Entity\Induccion;
use App\Entity\Nitro;
use App\Entity\SistemaDeCombustible;
use App\Entity\Turbocompresor;
use App\Repository\NitroRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use IntlChar;

class DashboardController extends AbstractDashboardController
{
  
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) 
        {
            return $this->redirectToRoute('app_login'); 
        }
        return $this->render('admin/easyadmin.html.twig');
    }
    


    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('TFG Tunig');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Inicio');
        yield MenuItem::linkToUrl('Inicio', 'fa-solid fa-home', '/#menu');      

        yield MenuItem::section('Carreras');
        yield MenuItem::linkToCrud('Horario', 'fa-solid fa-calendar-days', Horario::class);
        yield MenuItem::linkToCrud('Reserva', 'fa-solid fa-user-graduate', Reserva::class);
        yield MenuItem::linkToCrud('Tramo horario', 'fa-solid fa-book', TramoHorario::class);
        yield MenuItem::linkToCrud('Circuito', 'fa-solid fa-user-group', Circuito::class);
        yield MenuItem::linkToCrud('Carrera', 'fa-solid fa-user-group', Carrera::class);

        yield MenuItem::section('Carreras');
        yield MenuItem::linkToCrud('Motores', 'fa-solid fa-calendar', Motor::class);
        yield MenuItem::linkToCrud('Piezas', 'fa-solid fa-user-tie', Pieza::class);
      
    
        yield MenuItem::section('Usuario');
        yield MenuItem::linkToCrud('Usuarios', 'fa-solid fa-user', User::class);

        yield MenuItem::section('Piezas');
        yield MenuItem::linkToCrud('ECU', 'fa-solid fa-user', ECU::class);
        yield MenuItem::linkToCrud('Inducción', 'fa-solid fa-user', Induccion::class);
        yield MenuItem::linkToCrud('Nitro', 'fa-solid fa-user', Nitro::class);
        yield MenuItem::linkToCrud('Sistema de combustible', 'fa-solid fa-user', SistemaDeCombustible::class);
        yield MenuItem::linkToCrud('Turbocompresor', 'fa-solid fa-user', Turbocompresor::class);
       
    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
