<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function about(): Response
    {
        return $this->render('main/monProfil.html.twig', [
            'titre' => 'Mon Profil',
        ]);
    }

    /**
     * @Route("/contact/", name="contact")
     */
    public function contact(): Response
    {
        return $this->render('main/contact.html.twig', [
            'titre' => 'Page contact',
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
}
