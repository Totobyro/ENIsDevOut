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

        // foreach ($sorties as $sort) {
        //     $dateFin=new DateTimeImmutable();
        //     $dateFin->createFromMutable($sort->getDateHeureDebut());
        //     dd($sort->getDateHeureDebut());
        //     if ($sort->getDateHeureDebut() > new DateTime() && $dateFin->add(new DateInterval('PT'.$sort->getDuree().'M')) > new DateTime()) {
        //         $sort->setEtat($repoEtat->findOneBy(['libelle' => 'Activité en cours']));
        //     }elseif ($dateFin->add(new DateInterval('PT'.$sort->getDuree().'M')) < new DateTime() && ($sort->getEtat() != $repoEtat->findOneBy(['libelle' => 'Annulée'])) && ($sort->getEtat() != $repoEtat->findOneBy(['libelle' => 'Historisée']))){
        //         $sort->setEtat($repoEtat->findOneBy(['libelle' => 'Passée']));
        //     }elseif ($sort->getDateLimiteInscription() > new DateTime() && ($sort->getEtat() != $repoEtat->findOneBy(['libelle' => 'Ouverte'])) && ($sort->getEtat() != $repoEtat->findOneBy(['libelle' => 'Cloturée']))) {
        //         $sort->setEtat($repoEtat->findOneBy(['libelle' => 'Ouverte']));
        //     }
        //     $em->persist($sort);

        // }
        // $em->flush();
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

    /**
     * @Route("/profile/creersortie/", name="creersortie")
     * 
     */
    public function creerSortie(Request $req, EntityManagerInterface $em, EtatRepository $repoEtat, UserInterface $user, CampusRepository $repoCampus): Response
    {
        $sortie = new Sortie();
        $sortie->addParticipant($user);
        $form = $this->createForm(NouvelleSortieType::class, $sortie);

        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('save')->isClicked()) {

                // set etat à l'id 1 -> Sortie crée (Enregistrée)
                // ->find(1) est à changé si la bdd est re-generée
                $sortie->setEtat($repoEtat->findOneBy(['libelle' => 'Crée']));
                //set l'id d'oragnisateur à l'id du current
                $sortie->setOrganisateur($user);
                //set l'id du campus à celui du User
                $sortie->setCampus($repoCampus->find($user->getCampus()->getId()));

                $em->persist($sortie);
                $em->flush();

                return $this->redirectToRoute('home');
            } else {
                if ($form->get('publish')->isClicked()) {
                    // set etat à l'id 2 -> Sortie crée (Publiée)
                    $sortie->setEtat($repoEtat->findOneBy(['libelle' => 'Ouverte']));
                    //set l'id d'oragnisateur à l'id du current
                    $sortie->setOrganisateur($user);
                    //set l'id du campus à celui du User
                    $sortie->setCampus($repoCampus->find($user->getCampus()->getId()));

                    $em->persist($sortie);
                    $em->flush();

                    return $this->redirectToRoute('home');
                }
            }
        }
        return $this->render(
            'main/creersortie.html.twig',
            ['formulaire' => $form->createView(),]
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
