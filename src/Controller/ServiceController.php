<?php

namespace App\Controller;

use App\Entity\Location;
use App\Entity\Service;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    /**
     * @Route("/api/service", name="service", methods={"get"})
     * @param ServiceRepository $serviceRepository
     * @return JsonResponse
     */
    public function index(ServiceRepository $serviceRepository): JsonResponse
    {
        return $this->json($serviceRepository->findAll(), 200, [],["groups" => "service"]);
    }

    /**
     * @Route("/api/service/{id}/user", name="all_service_user" , methods="get")
     * @param ServiceRepository $serviceRepository
     * @param int $id
     * @return JsonResponse
     */
    public function findUserByService(ServiceRepository $serviceRepository, int $id): JsonResponse
    {
        return $this->json($serviceRepository->find($id)->getUsers(), 200, [],["groups" => "user"]);
    }

    /**
     * @Route("/api/service/{idService}/location", name="all_service_location" , methods="get")
     * @param ServiceRepository $serviceRepository
     * @param Service $idService
     * @return JsonResponse
     */
    public function findLocationByService(ServiceRepository $serviceRepository, Service $idService): JsonResponse
    {
        return $this->json($serviceRepository->find($idService)->getLocation(), 200, [],["groups" => "location"]);
    }

    /**
     * @Route("/api/service/{idService}/location/{idLocation}/user", name="all_service_location_user" , methods="get")
     * @param ServiceRepository $serviceRepository
     * @param Service $idService
     * @param Location $idLocation
     * @return JsonResponse
     */
    public function findUserByLocationByService(ServiceRepository $serviceRepository, Service $idService, Location $idLocation): JsonResponse
    {
        return $this->json($serviceRepository->findUserByLocation($idLocation,$idService), 200, [],["groups" => "user"]);
    }

    /**
     * @Route("/api/service", name="add_service" , methods={"post"})
     * @param Request $request
     * @param Service $service
     * @return JsonResponse
     */
    public function addService(Request $request): JsonResponse
    {
        $service = new Service();
        $service->setName($request->request->get('name'));
        $service->setDescribes($request->request->get('describes'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($service);
        $em->flush();

        return $this->json(['message'=>'add',], 200);
    }
}
