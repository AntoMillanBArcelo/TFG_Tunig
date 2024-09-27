<?php
// src/Controller/CarreraController.php

// src/Controller/CarreraController.php

namespace App\Controller;

use App\Entity\Carrera;
use App\Repository\HorarioRepository;
use App\Repository\TramoHorarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use App\Entity\Horario;
use App\Repository\CarreraRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Inscripcion;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\InscripcionRepository;
class CarreraController extends AbstractController
{
    public function createCarrera(
        Request $request,
        HorarioRepository $horarioRepository,
        TramoHorarioRepository $tramoHorarioRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $fechaCarrera = $request->request->get('fecha'); // La fecha de la carrera desde el formulario
        $fecha = new DateTime($fechaCarrera);

        // Determinar si es invierno o verano
        $temporada = $this->determinarTemporada($fecha);
        $horario = $horarioRepository->findOneBy(['descripcion' => $temporada]);

        // Validar si la fecha es lunes o si está fuera del horario permitido
        if (!$this->validarCarrera($fecha, $horario, $tramoHorarioRepository)) {
            $this->addFlash('error', 'No puedes crear una carrera en este día u horario.');
            return $this->redirectToRoute('formulario_carrera');
        }

        // Crear la carrera
        $carrera = new Carrera();
        $carrera->setFecha($fecha);

        // Persistir y guardar la carrera
        $entityManager->persist($carrera);
        $entityManager->flush();

        $this->addFlash('success', 'Carrera creada con éxito.');
        return $this->redirectToRoute('lista_carreras');
    }

    private function validarCarrera(DateTime $fecha, Horario $horario, TramoHorarioRepository $tramoHorarioRepository): bool
    {
        // Verificar si la carrera es un lunes
        if ($fecha->format('N') === '1') {
            return false; // Es lunes, no se puede correr
        }

        // Verificar si la hora está dentro del horario permitido
        $horaCarrera = $fecha->format('H:i:s');

        $tramosHorarios = $tramoHorarioRepository->findBy(['horario' => $horario]);

        foreach ($tramosHorarios as $tramo) {
            $horaInicio = $tramo->getInicio()->format('H:i:s');
            $horaFin = $tramo->getFin()->format('H:i:s');

            if ($horaCarrera >= $horaInicio && $horaCarrera <= $horaFin) {
                return true; // Está dentro del horario permitido
            }
        }

        return false; // No está dentro del horario permitido
    }

    private function determinarTemporada(DateTime $fecha): string
    {
        $mes = (int) $fecha->format('m');

        // En este ejemplo, consideramos invierno de noviembre a marzo y verano de abril a octubre
        if ($mes >= 11 || $mes <= 3) {
            return 'invierno';
        } else {
            return 'verano';
        }
    }

    #[Route('/carreras/circuito/{id}', name: 'carreras_por_circuito')]
public function carrerasPorCircuito(
    int $id, 
    CarreraRepository $carreraRepository, 
    InscripcionRepository $inscripcionRepository
): JsonResponse {
    // Obtener el usuario actual
    $usuario = $this->getUser();
    
    // Obtener las carreras asociadas al circuito
    $carreras = $carreraRepository->findBy(['circuito' => $id]);
    $response = [];

    // Recorrer cada carrera para construir la respuesta
    foreach ($carreras as $carrera) {
        // Verificar si el usuario ya está inscrito en la carrera
        $yaInscrito = $inscripcionRepository->findOneBy([
            'user' => $usuario,
            'carrera' => $carrera,
        ]) !== null;

        // Añadir la información de la carrera y si el usuario ya está inscrito
        $response[] = [
            'id' => $carrera->getId(),
            'fecha' => $carrera->getFecha()->format('Y-m-d H:i:s'),
            'horario' => $carrera->getHorario()->getDescripcion(),
            'yaInscrito' => $yaInscrito, // Indica si el usuario ya está inscrito
        ];
    }

    return new JsonResponse($response);
}


    #[Route('/carrera/{id}/inscribirse', name: 'inscribirse_carrera')]
    public function inscribirse(int $id, CarreraRepository $carreraRepository, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        $carrera = $carreraRepository->find($id);

        if ($carrera && $user) {
            $inscripcion = new Inscripcion();
            $inscripcion->setUser($user);
            $inscripcion->setCarrera($carrera);
            $inscripcion->setFechaInscripcion(new \DateTime());

            $entityManager->persist($inscripcion);
            $entityManager->flush();

            return new JsonResponse(['message' => 'Inscripción exitosa']);
        }

        return new JsonResponse(['error' => 'Error en la inscripción'], 400);
    }

    #[Route('/carrera/{id}/asignar-posiciones', name: 'asignar_posiciones')]
public function asignarPosiciones(
    Request $request,
    int $id,
    InscripcionRepository $inscripcionRepository,
    EntityManagerInterface $entityManager
): Response {
    // Verificar si el formulario fue enviado (método POST)
    if ($request->isMethod('POST')) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Obtener las posiciones desde el formulario
        $posiciones = $request->request->all('posiciones');

        foreach ($posiciones as $inscripcionId => $posicion) {
            $inscripcion = $inscripcionRepository->find($inscripcionId);
            if ($inscripcion) {
                $inscripcion->setPosicion($posicion);
                $entityManager->persist($inscripcion);
            }
        }
        $entityManager->flush();

        // Redirigir a la misma página solo después de procesar el formulario
        return $this->redirectToRoute('asignar_posiciones', ['id' => $id]);
    }

    // Obtener las inscripciones para la carrera
    $inscripciones = $inscripcionRepository->findBy(['carrera' => $id]);

    // Renderizar la plantilla sin redirección
    return $this->render('carrera/detalles.html.twig', [
        'inscripciones' => $inscripciones,
        'carrera_id' => $id,
    ]);
}

  
    

    

}
