<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    /**
     * @Route("/api/service", name="service")
     * @param ServiceRepository $serviceRepository
     * @return JsonResponse
     */
    public function index(ServiceRepository $serviceRepository): JsonResponse
    {
        return $this->json($serviceRepository->findAll(), 200, [],["groups" => "service"]);
    }
}
