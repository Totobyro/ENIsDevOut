<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
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
        $this->addEtats();
        $this->addVilles();
        $this->addLieux();
        $this->addCampus();
        $this->addUsers();
        $this->addSorties();
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
            $user->setTelephone("0102030405");
            $user->setRoles(array('ROLE_ADMIN'))->setEmail($this->faker->email)
                ->setPassword($this->hasher->hashPassword($user, '123456'))->setActif(true)->setCampus($this->faker->randomElement($tabCampus));
            $this->manager->persist($user);
        }

        $this->manager->flush();


        //$users = $this->manager->getRepository(User::class)->findAll();

    }

    public function addCampus()
    {

        $campus = new Campus();

        for ($i = 0; $i < 4; $i++) {
            $campus = new Campus();
            $campus->setNom($this->faker->region);
            $this->manager->persist($campus);
        }

        $this->manager->flush();
    }

    public function addVilles()
    {

        for ($i = 0; $i < 3; $i++) {
            $ville = new Ville();
            $ville->setNom($this->faker->city);
            $ville->setCodePostal($this->faker->departmentNumber);
            $this->manager->persist($ville);
        }
        $this->manager->flush();
    }

    public function addLieux()
    {
        $tabVilles = $this->manager->getRepository(Ville::class)->findAll();

        for ($i = 0; $i < 7; $i++) {
            $lieu = new Lieu();
            $lieu->setNom($this->faker->city);
            $lieu->setRue($this->faker->streetName);
            $lieu->setVille($this->faker->randomElement($tabVilles));
            $this->manager->persist($lieu);
        }
        $this->manager->flush();
    }

    public function addEtats()
    {
        $etat = new Etat();
        $etat->setLibelle("Crée");
        $this->manager->persist($etat);
        $etat = new Etat();
        $etat->setLibelle("Ouverte");
        $this->manager->persist($etat);
        $etat = new Etat();
        $etat->setLibelle("Cloturée");
        $this->manager->persist($etat);
        $etat = new Etat();
        $etat->setLibelle("Activité en cours");
        $this->manager->persist($etat);
        $etat = new Etat();
        $etat->setLibelle("Passée");
        $this->manager->persist($etat);
        $etat = new Etat();
        $etat->setLibelle("Annulée");
        $this->manager->persist($etat);
        $etat = new Etat();
        $etat->setLibelle("Historisée");
        $this->manager->persist($etat);
        $this->manager->flush();
    }

    public function addSorties()
    {
        $tabLieux = $this->manager->getRepository(Lieu::class)->findAll();
        $tabEtats = $this->manager->getRepository(Etat::class)->findAll();
        $tabCampus = $this->manager->getRepository(Campus::class)->findAll();
        $tabParticipants = $this->manager->getRepository(Participant::class)->findAll();

        for ($i = 0; $i < 7; $i++) {
            $sortie = new Sortie();
            $sortie->setNom($this->faker->word);
            $sortie->setDateHeureDebut($this->faker->dateTimeBetween($startDate = '- 10 days', $endDate = '+ 10 days', $timezone = 'Europe/Paris'));
            $sortie->setDuree($this->faker->numberBetween($min = 0, $max = 120));
            do {
                $sortie->setDateLimiteInscription($this->faker->dateTimeBetween($startDate = '- 10 days', $endDate = '+ 10 days', $timezone = 'Europe/Paris'));
            } while ($sortie->getDateLimiteInscription() > $sortie->getDateHeureDebut());
            $sortie->setNbInscriptionsMax($this->faker->numberBetween($min = 0, $max = 40));
            $sortie->setInfosSortie($this->faker->sentence($nbWords = 10, $variableNbWords = true));
            $sortie->setLieu($this->faker->randomElement($tabLieux));
            $sortie->setEtat($this->faker->randomElement($tabEtats));
            $sortie->setCampus($this->faker->randomElement($tabCampus));
            $sortie->setOrganisateur($this->faker->randomElement($tabParticipants));
            $sortie->addParticipant($sortie->getOrganisateur());
            $this->manager->persist($sortie);
        }
        $this->manager->flush();
    }
}
