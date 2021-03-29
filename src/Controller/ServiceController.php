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
     * @Route("/api/service", name="service", methods={"GET"})
     * @param ServiceRepository $serviceRepository
     * @return JsonResponse
     */
    public function index(ServiceRepository $serviceRepository): JsonResponse
    {
        return $this->json($serviceRepository->findAll(), 200, [],["groups" => "service"]);
    }

    /**
     * @Route("/api/service/{id}/user", name="all_service_user" , methods="GET")
     * @param ServiceRepository $serviceRepository
     * @param int $id
     * @return JsonResponse
     */
    public function findUserByService(ServiceRepository $serviceRepository, int $id): JsonResponse
    {
        return $this->json($serviceRepository->find($id)->getUsers(), 200, [],["groups" => "user"]);
    }

    /**
     * @Route("/api/service/{idService}/location", name="all_service_location" , methods="GET")
     * @param ServiceRepository $serviceRepository
     * @param Service $idService
     * @return JsonResponse
     */
    public function findLocationByService(ServiceRepository $serviceRepository, Service $idService): JsonResponse
    {
        return $this->json($serviceRepository->find($idService)->getLocation(), 200, [],["groups" => "location"]);
    }

    /**
     * @Route("/api/service/{idService}/location/{idLocation}/user", name="all_service_location_user" , methods="GET")
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
     * @Route("/api/service", name="add_service" , methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function addService(Request $request): JsonResponse
    {
        $service = new Service();
        $parameters = json_decode($request->getContent(), true);
        $service->setName($parameters["name"]);
        $service->setDescribes($parameters["describes"]);
        $em = $this->getDoctrine()->getManager();
        $em->persist($service);
        $em->flush();

        return $this->json(['message'=>'Add service',], 200);
    }

    /**
     * @Route("/api/service/{idService}", name="update_service" , methods={"PUT"})
     * @param Service $idService
     * @param Request $request
     * @return JsonResponse
     */
    public function updateService(Service $idService,Request $request): JsonResponse
    {

        $parameters = json_decode($request->getContent(), true);
        $idService->setName($parameters["name"]);
        $idService->setDescribes($parameters["describes"]);
        $em = $this->getDoctrine()->getManager();
        $em->persist($idService);
        $em->flush();

        return $this->json(['message'=>'Update service',], 200);
    }

    /**
     * @Route("/api/service/{idService}", name="delete_service" , methods={"DELETE"})
     * @param Service $idService
     * @return JsonResponse
     */
    public function deleteService(Service $idService): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($idService);
        $em->flush();

        return $this->json(['message'=>'Delete service',], 200);
    }
}
