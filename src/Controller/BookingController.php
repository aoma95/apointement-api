<?php

namespace App\Controller;


use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/api/booking", name="booking", methods="get")
     * @param BookingRepository $bookingsRepository
     * @return JsonResponse
     */
    public function index(BookingRepository $bookingsRepository): JsonResponse
    {
        return $this->json($bookingsRepository->findAll(), 200, [],["groups" => "booking"]);
    }
}
