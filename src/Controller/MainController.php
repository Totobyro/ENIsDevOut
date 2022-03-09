<?php

namespace App\Controller;

use App\Entity\Sortie;
<<<<<<< Updated upstream
use App\Form\NouvelleSortieType;
=======
>>>>>>> Stashed changes
use App\Repository\CampusRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(SortieRepository $repo): Response
    {
        $tableauSortie = $repo->findAll();

        return $this->render('main/home.html.twig', [
            'tableauSortie' => $tableauSortie,
        ]);
    }

    /**
     * @Route("/monProfil/", name="monProfil")
     */
    public function about(CampusRepository $repo): Response
    {
        $tabCampus = $repo->findAll();

        return $this->render('main/monProfil.html.twig', [
            'titre' => 'Mon Profil',
            'campus' => $tabCampus,
        ]);
    }

    /**
     * @Route("/campus/", name="campus")
     */
    public function campus(): Response
    {
        return $this->render('main/campus.html.twig', [
            'titre' => 'Page campus',
        ]);
    }

    /**
     * @Route("/ville/", name="ville")
     */
    public function ville(): Response
    {
        return $this->render('main/ville.html.twig', [
            'titre' => 'Page ville',
        ]);
    }

    /**
<<<<<<< Updated upstream
     * @Route("/creersortie/", name="creersortie")
     * 
     */

    public function creerSortie(Sortie $s, Request $req): Response
    {

        $form = $this->createForm(NouvelleSortieType::class, $s);
        $form->handleRequest($req);

        return $this->render('main/creersortie.html.twig',
         [ 'formulaire'=> $form->createView()]);
    }

=======
     * @Route("/{id}", name="app_sortie_show", methods={"GET"})
     */
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }
>>>>>>> Stashed changes
}
