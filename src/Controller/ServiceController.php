<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/api/service/user/{id}", name="all_service_user" , methods="get")
     * @param ServiceRepository $serviceRepository
     * @param int $id
     * @return JsonResponse
     */
    public function findUserByService(ServiceRepository $serviceRepository, int $id): JsonResponse
    {
        return $this->json($serviceRepository->find($id)->getUsers(), 200, [],["groups" => "user"]);
    }
}
