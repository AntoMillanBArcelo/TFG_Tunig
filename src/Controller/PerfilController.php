<?php
// src/Controller/PerfilController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CocheRepository;
use App\Repository\CircuitoRepository;
use App\Repository\MotorRepository;
use App\Repository\ECURepository;
use App\Repository\NitroRepository;
use App\Repository\SistemaDeCombustibleRepository;
use App\Repository\InduccionRepository;
use App\Repository\TurbocompresorRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CarreraRepository;
use App\Repository\InscripcionRepository;
class PerfilController extends AbstractController
{
    #[Route('/perfil', name: 'app_perfil')]
    public function index(CocheRepository $carRepository, InscripcionRepository $inscripcionRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to view this page.');
        }

        $cars = $carRepository->findBy(['usuario' => $user]);
        $inscripciones = $inscripcionRepository->findBy(['user' => $user]);

        return $this->render('perfil/index.html.twig', [
            'user' => $user,
            'cars' => $cars,
            'inscripciones' => $inscripciones, 
        ]);
    }

    #[Route('/coche/{id}', name: 'app_car_details')]
    public function carDetails(
        int $id, 
        CocheRepository $carRepository, 
        MotorRepository $motorRepository, 
        ECURepository $ecuRepository, 
        SistemaDeCombustibleRepository $sistemaDeCombustibleRepository, 
        InduccionRepository $induccionRepository, 
        TurbocompresorRepository $turbocompresorRepository, 
        NitroRepository $nitroRepository, 
        Request $request
    ): Response {
        $car = $carRepository->find($id);

        if (!$car) {
            throw $this->createNotFoundException('El coche no existe.');
        }

        $selectedType = $request->query->get('type', 'motor');
        $selectedId = $request->query->get('id');

        switch ($selectedType) {
            case 'motor':
                $items = $motorRepository->findAll();
                $selectedItem = $selectedId ? $motorRepository->find($selectedId) : null;
                break;
            case 'ecu':
                $items = $ecuRepository->findAll();
                $selectedItem = $selectedId ? $ecuRepository->find($selectedId) : null;
                break;
            case 'sistema_de_combustible':
                $items = $sistemaDeCombustibleRepository->findAll();
                $selectedItem = $selectedId ? $sistemaDeCombustibleRepository->find($selectedId) : null;
                break;
            case 'induccion':
                $items = $induccionRepository->findAll();
                $selectedItem = $selectedId ? $induccionRepository->find($selectedId) : null;
                break;
            case 'turbocompresor':
                $items = $turbocompresorRepository->findAll();
                $selectedItem = $selectedId ? $turbocompresorRepository->find($selectedId) : null;
                break;
            case 'nitro':
                $items = $nitroRepository->findAll();
                $selectedItem = $selectedId ? $nitroRepository->find($selectedId) : null;
                break;
            default:
                $items = [];
                $selectedItem = null;
                break;
        }

        return $this->render('perfil/car_details.html.twig', [
            'car' => $car,
            'items' => $items,
            'selectedType' => $selectedType,
            'selectedItem' => $selectedItem,
            'path' => $car->getModelo3d(),
            
        ]);
    }

}