<?php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CocheRepository;
use App\Repository\ECURepository;
use App\Repository\InduccionRepository;
use App\Repository\SistemaDeCombustibleRepository;
use App\Repository\TurbocompresorRepository;
use App\Repository\NitroRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ApiComponentController extends AbstractController
{
    private $logger;

    // Inyección de dependencias en el constructor, incluyendo LoggerInterface
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/api/install_component', name: 'api_install_component', methods: ['POST'])]
    public function installComponent(
        Request $request,
        CocheRepository $cocheRepository,
        ECURepository $ecuRepository,
        InduccionRepository $induccionRepository,
        SistemaDeCombustibleRepository $sistemaDeCombustibleRepository,
        TurbocompresorRepository $turbocompresorRepository,
        NitroRepository $nitroRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        $em->getConnection()->beginTransaction();

        try {
            $data = json_decode($request->getContent(), true);
            
            $cocheId = $data['coche_id'] ?? null;
            $componentType = $data['component_type'] ?? null;
            $componentId = $data['component_id'] ?? null;

            if (!$cocheId || !$componentType || !$componentId) {
                throw new \Exception('Faltan parámetros necesarios');
            }

            $coche = $cocheRepository->find($cocheId);
            if (!$coche) {
                throw new \Exception('Coche no encontrado');
            }

            switch ($componentType) {
                case 'ecu':
                    $component = $ecuRepository->find($componentId);
                    if (!$component) {
                        throw new \Exception('ECU no encontrada');
                    }
                    $coche->setEcu($component);
                    break;
                case 'induccion':
                    $component = $induccionRepository->find($componentId);
                    if (!$component) {
                        throw new \Exception('Inducción no encontrada');
                    }
                    $coche->setInduccion($component);
                    break;
                case 'sistema_de_combustible':
                    $component = $sistemaDeCombustibleRepository->find($componentId);
                    if (!$component) {
                        throw new \Exception('Sistema de combustible no encontrado');
                    }
                    $coche->setSistemaDeCombustible($component);
                    break;
                case 'turbocompresor':
                    $component = $turbocompresorRepository->find($componentId);
                    if (!$component) {
                        throw new \Exception('Turbocompresor no encontrado');
                    }
                    $coche->setTurbocompresor($component);
                    break;
                case 'nitro':
                    $component = $nitroRepository->find($componentId);
                    if (!$component) {
                        throw new \Exception('Nitro no encontrado');
                    }
                    $coche->setNitro($component);
                    break;
                default:
                    throw new \Exception('Tipo de componente no válido');
            }

            $em->persist($coche);
            $em->flush();
            $em->getConnection()->commit();

            return $this->json([
                'success' => true,
                'message' => ucfirst($componentType) . ' instalado correctamente',
            ], JsonResponse::HTTP_OK);

        } catch (\Exception $e) {
            $em->getConnection()->rollBack();
            $this->logger->error('Error instalando el componente: ' . $e->getMessage());
            return $this->json(['error' => 'Error interno del servidor: ' . $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
