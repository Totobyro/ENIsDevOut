<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\NouvelleSortieType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("profile/sortie")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/", name="app_sortie_index", methods={"GET"})
     */
    public function index(SortieRepository $sortieRepository): Response
    {
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="afficher_sortie")
     */
    public function afficher_sortie(Sortie $sortie): Response
    {
        return $this->render('sortie/afficher.html.twig', [
            'sortie' => $sortie,
        ]);
    }


    /**
     * @Route("/modifier/{id}", name="modifier_sortie")
     */
    public function modifier_sortie(Request $request, Sortie $sortie, SortieRepository $sortieRepository): Response
    {
        // return $this->render('sortie/modifier.html.twig', [
        //     'sortie' => $sortie,
        // ]);

        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortieRepository->add($sortie);
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/modifier.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/new", name="app_sortie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SortieRepository $sortieRepository): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortieRepository->add($sortie);
            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }
}
