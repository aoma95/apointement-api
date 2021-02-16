<?php

namespace App\DataFixtures;

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
        $arrayEntityService =[];
        $nameServie = ["Déppannage","Livraison","Plombier","Admin"];
        foreach ($nameServie as $value){
            $service= new Service();
            $service->setName($value);
            array_push($arrayEntityService,$service);
            $manager->persist($service);
//            echo "${$arrayEntityService[1]}";
        }


        $faker = Factory::create('fr_FR');
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
        $user2 = new User();
        $user2->setEmail("user@user.fr")
            ->setPassword($this->passwordEncoder->encodePassword($user2, "user"))
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setCity($faker->city)
            ->setService($arrayEntityService[1])
        ;

        $manager->persist($user2);
        $manager->flush();
    }
}
