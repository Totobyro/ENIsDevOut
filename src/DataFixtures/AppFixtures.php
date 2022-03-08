<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\CampusRepository;
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
        $campus = new Campus();
        $campus->setNom("ENI Ecole " . $this->faker->country);
        $manager->persist($campus);
        $manager->flush();
        //$this->addUsers();
    }

    public function __construct(UserPasswordHasherInterface $passwordHasher)

    {
        $this->faker = Factory::create('fr_FR');
        $this->hasher = $passwordHasher;
    }

    public function addUSers()
    {

        $campus = $this->manager->getRepository(Campus::class)->find(8);
        //$campus = $this->manager->getRepository(Campus::class)->findAll();


        // $user = new Participant();
        // $user->setNom("Baptiste")->setPrenom('Guillon')->setTelephone('0600000000')->setRoles(array('ROLE_ADMIN'))->setEmail("baba@gmail.com")
        //     ->setPassword($this->hasher->hashPassword($user, '123456'))
        //     ->setActif(true)->setCampus($campus);
        // $this->manager->persist($user);

        for ($i = 0; $i < 10; $i++) {

            $user = new Participant();
            $user->setNom($this->faker->lastName);
            $user->setPrenom($this->faker->firstname);
            $user->setTelephone("012030405");
            $user->setRoles(array('ROLE_ADMIN'))->setEmail($this->faker->email)
                ->setPassword($this->hasher->hashPassword($user, '123
                '))->setActif(true)->setCampus($campus);;

            $this->manager->persist($user);
        }

        $this->manager->flush();


        //$users = $this->manager->getRepository(User::class)->findAll();

    }

    public function addCampus()
    {
    }
}
