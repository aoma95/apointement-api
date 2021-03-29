<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/api/booking", name="booking", methods="GET")
     * @param BookingRepository $bookingsRepository
     * @return JsonResponse
     */
    public function index(BookingRepository $bookingsRepository): JsonResponse
    {
        return $this->json($bookingsRepository->findAll(), 200, [],["groups" => "booking"]);
    }

    /**
     * @Route("/api/booking/{user}", name="booking", methods="GET")
     * @param BookingRepository $bookingsRepository
     * @param User $user
     * @return JsonResponse
     */
    public function bookingUser(BookingRepository $bookingsRepository, User $user): JsonResponse
    {
        return $this->json($bookingsRepository->findByUser($user), 200, [],["groups" => "booking"]);
    }
}
