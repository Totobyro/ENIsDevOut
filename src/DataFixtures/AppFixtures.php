<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private ObjectManager $manager;
    private UserPasswordHasherInterface $hasher;
    private Generator $faker;



    public function load(ObjectManager $manager): void
    {

        $this->manager = $manager;
        $this->addCampus();
        $this->addUsers();
    }

    public function __construct(UserPasswordHasherInterface $passwordHasher)

    {
        $this->faker = Factory::create('fr_FR');
        $this->hasher = $passwordHasher;
    }

    public function addUSers()
    {

        $tabCampus = $this->manager->getRepository(Campus::class)->findAll();
        for ($i = 0; $i < 10; $i++) {

            $user = new Participant();
            $user->setNom($this->faker->lastName);
            $user->setPrenom($this->faker->firstname);
            $user->setTelephone("012030405");
            $user->setRoles(array('ROLE_USER'))->setEmail($this->faker->email)
                ->setPassword($this->hasher->hashPassword($user, '123
                '))->setActif(true)->setCampus(array_rand($tabCampus));
            $this->manager->persist($user);
        }

        $this->manager->flush();


        //$users = $this->manager->getRepository(User::class)->findAll();

    }

    public function addCampus()
    {

        for ($i = 0; $i < 4; $i++) {
            $campus = new Campus();
            $campus->setNom($this->faker->city);
            $this->manager->persist($campus);
        }
    }
}
