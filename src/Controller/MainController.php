<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Sortie;
use App\Form\FiltreType;
use App\Form\NouvelleSortieType;
use App\Model\Filtre;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;


class MainController extends AbstractController
{
    /**
     * @Route("/profile/", name="home")
     */
    public function home(Request $req, SortieRepository $repo, UserInterface $user, EtatRepository $repoEtat, EntityManagerInterface $em): Response
    {
        $filtre = new Filtre();
        $formFiltre = $this->createForm(FiltreType::class, $filtre);
        $formFiltre->handleRequest($req);

        $sorties = $repo->findByFilters($formFiltre, $user);


        return $this->render(
            'main/home.html.twig',
            [
                'tableauSortie' => $sorties,
                'filtre' => $formFiltre->createView(),
            ]
        );
    }

    /**
     * @Route("/admin/campus/", name="campus")
     */
    public function campus(CampusRepository $campus): Response
    {
        return $this->render('main/campus.html.twig', [
            'titre' => 'Page campus',
            'campus' => $campus->findAll(),
        ]);
    }

    /**
     * @Route("/admin/ville/", name="ville")
     */
    public function ville(VilleRepository $ville): Response
    {
        return $this->render('main/ville.html.twig', [
            'titre' => 'Page ville',
            'ville' => $ville->findAll(),
        ]);
    }

    /**
     * @Route("/coucou/", name="coucou")
     */
    public function coucou(): Response
    {
        return $this->render('main/coucou.html.twig', [
            'titre' => 'Page coucou',
        ]);
    }
}
