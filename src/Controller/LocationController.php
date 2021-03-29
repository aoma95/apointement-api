<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends AbstractController
{
    /**
     * @Route("/api/location", name="all_location", methods={"GET"})
     * @param LocationRepository $location
     * @return Response
     */
    public function index(LocationRepository $location): Response
    {
        return $this->json($location->findAll(), 200, [],["groups" => "location"]);
    }

    /**
     * @Route("/api/location/{location}", name="update_location", methods={"PUT"})
     * @param Location $location
     * @param Request $request
     * @return Response
     */
    public function updateLocation(Location $location, Request $request): Response
    {
        $parameters = json_decode($request->getContent(), true);
        $location->setCityName($parameters["cityName"]);
        $em = $this->getDoctrine()->getManager();
        $em->persist($location);
        $em->flush();

        return $this->json(['message'=>'Update location',], 200);
    }

    /**
     * @Route("/api/location/{location}", name="delete_location", methods={"DELETE"})
     * @param Location $location
     * @param Request $request
     * @return Response
     */
    public function deleteLocation(Location $location, Request $request): Response
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($location);
        $em->flush();

        return $this->json(['message'=>'Update location',], 200);
    }

    /**
     * @Route("/api/location", name="add_location", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function addLocation(Request $request): Response
    {
        $location = new Location();
        $parameters = json_decode($request->getContent(), true);
        $location->setCityName($parameters["cityName"]);
        $em = $this->getDoctrine()->getManager();
        $em->persist($location);
        $em->flush();

        return $this->json(['message'=>'Add location',], 200);
    }
}
