<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\NouvelleSortieType;
use App\Repository\CampusRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


// /**
//  * @Route("/profile")
//  */
class MainController extends AbstractController
{
    /**
     * @Route("/profile/", name="home")
     */
    public function home(SortieRepository $repo): Response
    {
        $tableauSortie = $repo->findAll();

        return $this->render('main/home.html.twig', [
            'tableauSortie' => $tableauSortie,
        ]);
    }

    /**
     * @Route("/profile/monProfil/", name="monProfil")
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
     * @Route("/admin/campus/", name="campus")
     */
    public function campus(): Response
    {
        return $this->render('main/campus.html.twig', [
            'titre' => 'Page campus',
        ]);
    }

    /**
     * @Route("/admin/ville/", name="ville")
     */
    public function ville(): Response
    {
        return $this->render('main/ville.html.twig', [
            'titre' => 'Page ville',
        ]);
    }

    /**
     * @Route("/creersortie/", name="creersortie")
     * 
     */

    public function creerSortie(Request $req): Response
    {

        $form = $this->createForm(NouvelleSortieType::class);
        $form->handleRequest($req);

        return $this->render(
            'main/creersortie.html.twig',
            ['formulaire' => $form->createView()]
        );
    }

    /**
     * @Route("/{id}", name="app_sortie_show", methods={"GET"})
     */
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }
}
