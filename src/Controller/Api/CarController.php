<?php
// src/Controller/Api/CarController.php

namespace App\Controller\Api;

use App\Entity\Coche;
use App\Repository\CocheRepository;
use App\Repository\UserRepository;
use App\Repository\MotorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class CarController extends AbstractController
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    #[Route('/api/cars', name: 'api_cars_list', methods: ['GET'])]
    public function list(CocheRepository $cocheRepository, SerializerInterface $serializer): JsonResponse
    {
        $coches = $cocheRepository->findAll();
        $data = $serializer->serialize($coches, 'json', ['groups' => 'car:read']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/cars/{id}', name: 'api_car_show', methods: ['GET'])]
    public function show(int $id, CocheRepository $cocheRepository, SerializerInterface $serializer): JsonResponse
    {
        $coche = $cocheRepository->find($id);

        if (!$coche) {
            return new JsonResponse(['message' => 'Car not found'], Response::HTTP_NOT_FOUND);
        }

        $data = $serializer->serialize($coche, 'json', ['groups' => 'car:read']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/cars', name: 'api_car_create', methods: ['POST'])]
public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepository): JsonResponse
{
    $em->getConnection()->beginTransaction();

    try {
        $data = json_decode($request->getContent(), true);

     

        $coche = new Coche();
      
        $coche->setModelo($data['modelo']);
        $coche->setAño($data['año']);
        $coche->setColor($data['color']);
        $coche->setModelo3d($data['modelo3d'] ?? null);
       

        // Obtener el usuario desde el repositorio
        $usuario = $userRepository->find($data['usuario_id']);
        if (!$usuario) {
            throw new \Exception('Usuario no encontrado');
        }
        $coche->setUsuario($usuario);

        $em->persist($coche);
        $em->flush();

        $em->getConnection()->commit();

        return $this->json([
            'id' => $coche->getId(),
            
            'modelo' => $coche->getModelo(),
            'año' => $coche->getAño(),
            'color' => $coche->getColor(),
            'modelo3d' => $coche->getModelo3d(),
          
            'usuario' => [
                'id' => $usuario->getId(),
                'email' => $usuario->getEmail()
            ]
        ], Response::HTTP_CREATED);
    } catch (\Exception $e) {
        $em->getConnection()->rollBack();
        $this->logger->error('Error guardando el coche: ' . $e->getMessage());
        return $this->json(['error' => 'Error interno del servidor: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

    



    #[Route('/api/cars/{id}', name: 'api_car_update', methods: ['PUT'])]
    public function update(int $id, Request $request, CocheRepository $cocheRepository, SerializerInterface $serializer): JsonResponse
    {
        $coche = $cocheRepository->find($id);

        if (!$coche) {
            return new JsonResponse(['message' => 'Car not found'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->getContent();
        $coche = $serializer->deserialize($data, Coche::class, 'json', ['object_to_populate' => $coche]);
        $cocheRepository->save($coche);

        $data = $serializer->serialize($coche, 'json', ['groups' => 'car:read']);
        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/api/cars/{id}', name: 'api_car_delete', methods: ['DELETE'])]
    public function delete(int $id, CocheRepository $cocheRepository): JsonResponse
    {
        $coche = $cocheRepository->find($id);

        if (!$coche) {
            return new JsonResponse(['message' => 'Car not found'], Response::HTTP_NOT_FOUND);
        }

        $cocheRepository->remove($coche);

        return new JsonResponse(['message' => 'Car deleted successfully'], Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/install_motor', name: 'api_install_motor', methods: ['POST'])]
    public function installMotor(
        Request $request, 
        EntityManagerInterface $em, 
        CocheRepository $cocheRepository, 
        MotorRepository $motorRepository,
        LoggerInterface $logger
    ): JsonResponse {
        $em->getConnection()->beginTransaction();
    
        try {
            // Decodificar el contenido del JSON
            $data = json_decode($request->getContent(), true);
    
            // Validar que se envían todos los parámetros requeridos
            if (empty($data['cocheId']) || empty($data['motorId'])) {
                throw new \Exception('Faltan parámetros necesarios');
            }
    
            // Buscar coche y motor
            $coche = $cocheRepository->find($data['cocheId']);
            $motor = $motorRepository->find($data['motorId']);
    
            // Validar que ambos existan
            if (!$coche) {
                throw new \Exception('Coche no encontrado');
            }
            if (!$motor) {
                throw new \Exception('Motor no encontrado');
            }
    
            // Asignar el motor al coche
            $coche->setMotor($motor);
    
            // Guardar cambios en la base de datos
            $em->persist($coche);
            $em->flush();
            $em->getConnection()->commit();
    
            // Responder con los detalles del coche actualizado
            return $this->json([
                'id' => $coche->getId(),
                'modelo' => $coche->getModelo(),
                'año' => $coche->getAño(),
                'color' => $coche->getColor(),
                'motor' => [
                    'id' => $motor->getId(),
                    'caballos' => $motor->getCaballos(),
                    'par_maximo' => $motor->getParMaximo(),
                    'cuarto_milla' => $motor->getCuartoMilla()
                ]
            ], Response::HTTP_OK);
    
        } catch (\Exception $e) {
            $em->getConnection()->rollBack();
            $logger->error('Error instalando el motor: ' . $e->getMessage());
            return $this->json(['error' => 'Error interno del servidor: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
