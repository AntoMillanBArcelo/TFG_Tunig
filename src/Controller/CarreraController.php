<?php
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
        $fechaCarrera = $request->request->get('fecha');
        $fecha = new DateTime($fechaCarrera);

        $temporada = $this->determinarTemporada($fecha);
        $horario = $horarioRepository->findOneBy(['descripcion' => $temporada]);

        if (!$this->validarCarrera($fecha, $horario, $tramoHorarioRepository)) {
            $this->addFlash('error', 'No puedes crear una carrera en este día u horario.');
            return $this->redirectToRoute('formulario_carrera');
        }

        $carrera = new Carrera();
        $carrera->setFecha($fecha);

        $entityManager->persist($carrera);
        $entityManager->flush();

        $this->addFlash('success', 'Carrera creada con éxito.');
        return $this->redirectToRoute('lista_carreras');
    }

    private function validarCarrera(DateTime $fecha, Horario $horario, TramoHorarioRepository $tramoHorarioRepository): bool
    {
        // Verificar si la carrera es un lunes
        if ($fecha->format('N') === '1') 
        {
            return false; 
        }

        $horaCarrera = $fecha->format('H:i:s');

        $tramosHorarios = $tramoHorarioRepository->findBy(['horario' => $horario]);

        foreach ($tramosHorarios as $tramo) 
        {
            $horaInicio = $tramo->getInicio()->format('H:i:s');
            $horaFin = $tramo->getFin()->format('H:i:s');

            if ($horaCarrera >= $horaInicio && $horaCarrera <= $horaFin) {
                return true;
            }
        }

        return false;
    }

    private function determinarTemporada(DateTime $fecha): string
    {
        $mes = (int) $fecha->format('m');

        // EL invierno es de noviembre a marzo y verano de abril a octubre
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
         
            $usuario = $this->getUser();     
            $carreras = $carreraRepository->findBy(['circuito' => $id]);
            $response = [];
        
            foreach ($carreras as $carrera) 
            {
                $yaInscrito = $inscripcionRepository->findOneBy([
                    'user' => $usuario,
                    'carrera' => $carrera,
                ]) !== null;
               
                $response[] = [
                    'id' => $carrera->getId(),
                    'fecha' => $carrera->getFecha()->format('Y-m-d H:i:s'),
                    'horario' => $carrera->getHorario()->getDescripcion(),
                    'yaInscrito' => $yaInscrito, 
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
        if ($request->isMethod('POST')) {
            $this->denyAccessUnlessGranted('ROLE_ADMIN');

            $posiciones = $request->request->all('posiciones');

            foreach ($posiciones as $inscripcionId => $posicion) {
                $inscripcion = $inscripcionRepository->find($inscripcionId);
                if ($inscripcion) {
                    $inscripcion->setPosicion($posicion);
                    $entityManager->persist($inscripcion);
                }
            }
            $entityManager->flush();

            return $this->redirectToRoute('asignar_posiciones', ['id' => $id]);
        }

        $inscripciones = $inscripcionRepository->findBy(['carrera' => $id]);

        return $this->render('carrera/detalles.html.twig', [
            'inscripciones' => $inscripciones,
            'carrera_id' => $id,
        ]);
    } 
}
