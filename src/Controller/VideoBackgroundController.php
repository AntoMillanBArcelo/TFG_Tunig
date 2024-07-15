<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class VideoBackgroundController extends AbstractController
{
    #[Route('/', name: 'app_video_background')]
    public function index(): Response
    {
        return $this->render('video_background/index.html.twig', [
            'controller_name' => 'VideoBackgroundController',
        ]);
    }
}
