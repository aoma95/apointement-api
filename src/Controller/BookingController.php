<?php

namespace App\Controller;


use App\Entity\Booking;
use App\Entity\User;
use App\Repository\BookingRepository;
use App\Repository\ClientRepository;
use Carbon\Carbon;
use PhpParser\Node\Expr\New_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookingController extends AbstractController
{
    /**
     * @Route("/api/booking", name="bookings", methods="GET")
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
     * @param null $month
     * @return JsonResponse
     */
    public function bookingUser(BookingRepository $bookingsRepository, User $user, $month = null): JsonResponse
    {
        $meetings = $bookingsRepository->findByUser($user);

        $today = Carbon::now()->subDay();
        // Ajout de mois supplémentaires
        if (! is_null($month)) {
            $today->addMonths($month);
        }
        $today->setHour(0);
        $today->setMinute(0);
        $today->setSecond(0);
        // Horaires de début et de fin d'une journée
        $startDay = 8;
        $endDay = 18;
        // Horaires de la pause déjeuner
        $startPause = 12;
        $endPause = 14;

        // RDV disponibles
        $available = array();

        for ($i = 0; $i < 30; $i++) {
            $currentDay = $today->addDay();
            $currentMeetings = array();

            for ($j = $startDay; $j < $endDay; $j++) {
                $canTakeMeeting = true;

                foreach ($meetings as $meeting) {
                    // Récupération du jour du RDV
                    $dateMeeting = Carbon::make($meeting->getStartDate())->format('Y-m-d');
                    // Récupération des heures de début et de fin du RDV
                    $startHourMeeting = Carbon::make($meeting->getStartDate())->format('H');
                    $endHourMeeting = Carbon::make($meeting->getEndDate())->format('H');
                    // Vérification d'un RDV au même moment
                    if ($dateMeeting === $currentDay->format('Y-m-d') && $startHourMeeting == $currentDay->setHour($j)->format('H') && $endHourMeeting == $currentDay->setHour($j + 1)->format('H')) {
                        $canTakeMeeting = false;
                        break;
                    }
                }

                // Ajout du créneau disponible si pas de RDV au même moment
                if ($canTakeMeeting && ! ($currentDay->setHour($j)->format('H') >= $startPause && $currentDay->setHour($j+1)->format('H') <= $endPause)) {
                    array_push($currentMeetings, array(
                        'start' => $currentDay->setHour($j)->format('H'),
                        'end' => $currentDay->setHour($j+1)->format('H')
                    ));
                }
            }

            array_push($available, array(
                $currentDay->toDateString() => $currentMeetings
            ));
        }

        return $this->json($available, 200, []);
    }

    /**
     * @Route("/api/booking", name="add_bookings", methods="POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function addBooking(Request $request): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);
        $clientRepository = New ClientRepository();
        $clientRepository
        /**$booking = new Booking();
        $parameters = json_decode($request->getContent(), true);
        $booking->setClient($parameters["client"])
            ->setPro($parameters["name"])
            ->setStartDate($parameters["name"])
            ->setEndDate($parameters["name"])
        ;
        $em = $this->getDoctrine()->getManager();
        $em->persist($booking);
        $em->flush();
         * */
        return $this->json(['message'=>$parameters["client"]], 200);
    }
}
