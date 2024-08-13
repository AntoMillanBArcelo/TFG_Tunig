<?php
// src/Controller/PerfilController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CocheRepository;
use App\Repository\CircuitoRepository;
use App\Repository\MotorRepository;
use App\Repository\PiezaRepository;
use Symfony\Component\HttpFoundation\Request;
class PerfilController extends AbstractController
{
    #[Route('/perfil', name: 'app_perfil')]
    public function index(CocheRepository $carRepository, CircuitoRepository $raceRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to view this page.');
        }

        $cars = $carRepository->findBy(['usuario' => $user]);

        return $this->render('perfil/index.html.twig', [
            'user' => $user,
            'cars' => $cars,
        ]);
    }

    #[Route('/coche/{id}', name: 'app_car_details')]
public function carDetails(
    int $id, 
    CocheRepository $carRepository, 
    MotorRepository $motorRepository, 
    PiezaRepository $piezaRepository, 
    Request $request
): Response {
    $car = $carRepository->find($id);

    if (!$car) {
        throw $this->createNotFoundException('El coche no existe.');
    }

    $selectedType = $request->query->get('type', 'motor');
    $selectedId = $request->query->get('id');

    $items = $selectedType === 'motor' ? $motorRepository->findAll() : $piezaRepository->findAll();

    $selectedItem = $selectedType === 'motor' 
        ? ($selectedId ? $motorRepository->find($selectedId) : null)
        : ($selectedId ? $piezaRepository->find($selectedId) : null);

    return $this->render('perfil/car_details.html.twig', [
        'car' => $car,
        'items' => $items,
        'selectedType' => $selectedType,
        'selectedMotor' => $selectedType === 'motor' ? $selectedItem : null,
        'selectedPieza' => $selectedType === 'pieza' ? $selectedItem : null,
    ]);
}



}
