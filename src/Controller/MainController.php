<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\NouvelleSortieType;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

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
     * @Route("/profile/creersortie/", name="creersortie")
     * 
     */

    public function creerSortie(Request $req, EntityManagerInterface $em, EtatRepository$repoEtat, ParticipantRepository $repoParticipant, UserInterface $user): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(NouvelleSortieType::class, $sortie);

        $form->handleRequest($req);
       
        if ($form->isSubmitted()) {

            // set etat à l'id 2 -> Sortie Ouverte
            $sortie->setEtat($repoEtat->find(2));
            //set l'id d'oragnisateur à l'id du current
            $sortie->setOrganisateur($user);

            $em->persist($sortie);
            $em->flush();
            return $this->redirectToRoute('home');
        }

        

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
