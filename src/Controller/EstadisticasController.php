<?php

namespace App\Controller;

use App\Repository\InscripcionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EstadisticasController extends AbstractController
{
    #[Route(path: '/estadisticas', name: 'app_estadisticas')]
    public function index(InscripcionRepository $inscripcionRepository): Response
    {
        
        $inscripciones = $inscripcionRepository->findAll();

       
        $datosGrafica = [];
        foreach ($inscripciones as $inscripcion) {
            $datosGrafica[] = [
                'fecha' => $inscripcion->getCarrera()->getFecha()->format('d-m-Y'),
                'posicion' => $inscripcion->getPosicion(), 
            ];
        }

     
        $datosGraficaUsuarios = [];
        foreach ($inscripciones as $inscripcion) {
            $usuario = $inscripcion->getUser()->getEmail(); 
            $datosGraficaUsuarios[] = [
                'usuario' => $usuario,
                'fecha' => $inscripcion->getCarrera()->getFecha()->format('Y-m-d'), 
                'posicion' => $inscripcion->getPosicion(), 
            ];
        }

        
        $posicionesAltas = $inscripcionRepository->findHighestPositionsByRace();

        return $this->render('estadisticas/index.html.twig', [
            'datos_grafica' => $datosGrafica,
            'datos_grafica_usuarios' => $datosGraficaUsuarios,
            'posiciones_altas' => $posicionesAltas, 
        ]);
    }
}
