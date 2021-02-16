<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Entity\Client;
use App\Entity\Service;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        #Array Entity
        $arrayEntityService =[];
        $arrayEntityClient = [];
        $arrayEntityUser = [];
        $arrayNameService = ["Déppannage","Livraison","Plombier","Admin"];
        foreach ($arrayNameService as $nameservice){
            $service= new Service();
            $service->setName($nameservice);
            array_push($arrayEntityService,$service);
            $manager->persist($service);
        }
        #User admin
        $user = new User();
        $user
            ->setEmail("admin@admin.fr")
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword($this->passwordEncoder->encodePassword($user, "admin"))
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setCity($faker->city)
            ->setService($arrayEntityService[3])
        ;
        $manager->persist($user);
        #User test
        $user2 = new User();
        $user2
            ->setEmail("user@user.fr")
            ->setPassword($this->passwordEncoder->encodePassword($user2, "user"))
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setCity($faker->city)
            ->setService($arrayEntityService[1])
        ;
        $manager->persist($user2);
        #User gen et Client gen
        for($i=0;$i <=100;$i++)
        {
            $userGen = new User();
            $userGen
                ->setEmail($faker->email)
                ->setPassword($this->passwordEncoder->encodePassword($userGen, "user"))
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setCity($faker->city)
                ->setService($arrayEntityService[random_int(0,3)])
            ;
            array_push($arrayEntityUser,$userGen);
            $manager->persist($userGen);
            $client = new Client();
            $client
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setEmail($faker->email)
                ->setPhone($faker->phoneNumber)
                ;
            array_push($arrayEntityClient,$client);
            $manager->persist($client);
        }
        for($i=0;$i <=100;$i++)
        {

            $booking = new Booking();
            $startDate = $faker->dateTimeBetween($startDate = '-1 years', $endDate = '+1 years', $timezone = "Europe/Paris");
            $endDate  = $faker->dateTimeBetween($startDate->format('Y-m-d H:i:s').' +1 hours', $startDate->format('Y-m-d H:i:s').' +1 hours');
            $booking
                ->setClient($arrayEntityClient[random_int(0,sizeof($arrayEntityClient)-1)])
                ->setPro($arrayEntityUser[random_int(0,sizeof($arrayEntityUser)-1)])
                ->setStartDate($startDate)
                ->setEndDate($endDate)
            ;
            $manager->persist($booking);
        }


        $manager->flush();
    }
}
