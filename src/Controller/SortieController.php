<?php

namespace App\Controller;

use App\Entity\Sortie;
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
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/", name="app_sortie_index", methods={"GET"})
     */
    public function index(SortieRepository $sortieRepository): Response
    {
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
            
    }
    
    /**
     * @Route("/sortie/{id}", name="sortie")
     */
    public function sortie(Sortie $sort): Response
    {
        return $this->render('sortie/modifier.html.twig', [
            'titre' => 'Page campus',
            'sortie'=> $sort,
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

    /**
     * @Route("/{id}", name="app_sortie_show", methods={"GET"})
     */
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_sortie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Sortie $sortie, SortieRepository $sortieRepository): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortieRepository->add($sortie);
            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_sortie_delete", methods={"POST"})
     */
    public function delete(Request $request, Sortie $sortie, SortieRepository $sortieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $sortieRepository->remove($sortie);
        }

        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }
    /** 
    * @Route("/modifierSortie/{id}", name="sortie_modifier")
     */
    public function modifierSortie(LieuRepository $repoLieu,EtatRepository $repoEtat,CampusRepository $repoCampus, Request $req, EntityManagerInterface $em, Sortie $s): Response
    {

        dd($s->getDateHeureDebut());
        $s->setNom($req->get('nom'));
        $s->setDateHeureDebut($req->get('heureDebut'));
        $s->setDuree($req->get('duree'));
        $s->setDateLimiteInscription($req->get('dateLimite'));
        $s->setNbInscriptionsMax($req->get('heureDebut'));
        $s->setInfosSortie($req->get('infos'));
        $s->setLieu($repoLieu->findOneBy(array('nom' => $req->get('lieu'))));
        $s->setEtat($repoEtat->findOneBy(array('libelle' => $req->get('etat'))));
        $s->setCampus($repoCampus->findOneBy(array('nom' => $req->get('campus'))));
        $em->flush();
        return $this->redirectToRoute('home', []);
        }
}
