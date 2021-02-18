<?php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Entity\Client;
use App\Entity\Location;
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
        $arrayEntityLocation =[];
        $arrayNameService = [
            "Déppannage",
            "Livraison",
            "Plombier",
            "Electriciens",
            "Accompagnateur de tourisme équestre",
            "Administrateur de bases de données",
            "Administrateur de biens immobiliers",
            "Agent de développement local",
            "Aide-comptable",
            "Analyste de crédit",
            "Animateur de club de vacances",
            "Approvisionneur",
            "Arboriculteur",
            "Gestionnaire Copropriété",
            "Ingénieur Aéronautique",
            "Ingénieur d'exploitation gaz",
            "Ingénieur environnement",
            "Ingénieur production",
            "Gestionnaire paie et administration du personnel",
            "Expert bilan carbone",
            "Eleveur canin ou félin",
            "Documentaliste",
            "Directeur marketing",
            "Directeur de zoo",
            "Conseiller en insertion professionnelle",
            "Chef de projet Web",
            "Réalisateur",
            "Rédacteur",
            "Sommelier",
            "Souscripteur en assurances",
            "Urbaniste"
            ];
        for($i=0;$i <=100;$i++)
        {
            $location = new Location();
            array_push($arrayEntityLocation,$location->setCityName($faker->unique()->city));
            $manager->persist($location);
        }
        foreach ($arrayNameService as $nameservice){
            $service= new Service();
            $service
                ->setName($nameservice)
                ->setDescribes($faker->text("100"))
            ;

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
        ;
        $manager->persist($user);
        #User test
        $user2 = new User();
        $user2
            ->setEmail("user@user.fr")
            ->setPassword($this->passwordEncoder->encodePassword($user2, "user"))
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setCity($arrayEntityLocation[random_int(0,sizeof($arrayEntityLocation)-1)])
            ->setService($arrayEntityService[random_int(0,sizeof($arrayEntityService)-1)])
            ->getService()->addLocation($user2->getCity())
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
                ->setCity($arrayEntityLocation[random_int(0,sizeof($arrayEntityLocation)-1)])
                ->setService($arrayEntityService[random_int(0,sizeof($arrayEntityService)-1)])
                ->getService()->addLocation($userGen->getCity())
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
