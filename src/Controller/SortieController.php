<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\AnnulerSortieType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

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
    public function afficher_sortie(Sortie $sortie, ParticipantRepository $participantRepository): Response
    {
        return $this->render('sortie/afficher.html.twig', [
            'sortie' => $sortie,
            'participants' => $participantRepository->findAll()
        ]);
    }


    /**
     * @Route("/modifier/{id}", name="modifier_sortie")
     */
    public function modifier_sortie(Request $request, EntityManagerInterface $em, Sortie $sortie, EtatRepository $repoEtat, UserInterface $user, SortieRepository $sortieRepository): Response
    {

        $form = $this->createForm(SortieType::class, $sortie);

        $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $sortieRepository->add($sortie);
        //     return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        // }

        if ($form->get('save')->isClicked() && $repoEtat->findOneBy(['libelle' => 'Crée']) && new DateTime() < $sortie->getDateHeureDebut()) {

            // set etat à l'id 1 -> Sortie crée (Enregistrée)
            $sortie->setEtat($repoEtat->findOneBy(['libelle' => 'Crée']));
            //set l'id d'oragnisateur à l'id du current
            $sortie->setOrganisateur($user);

            $em->flush();

            return $this->redirectToRoute('home');
        }
        if ($form->get('delete')->isClicked() && $repoEtat->findOneBy(['libelle' => 'Crée']) && new DateTime() < $sortie->getDateHeureDebut()) {

            $sortieRepository->remove($sortie);

            return $this->redirectToRoute('home');
        } else {
            if ($form->get('publish')->isClicked()) {
                // set etat à l'id 2 -> Sortie crée (Publiée)
                $sortie->setEtat($repoEtat->findOneBy(['libelle' => 'Ouverte']));
                //set l'id d'oragnisateur à l'id du current
                $sortie->setOrganisateur($user);

                $em->persist($sortie);
                $em->flush();

                return $this->redirectToRoute('home');
            }
        }

        return $this->renderForm('sortie/modifier.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/new", name="app_sortie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SortieRepository $sortieRepository, UserInterface $user): Response
    {
        $sortie = new Sortie();
        $sortie->addParticipant($user);
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
     * @Route("/inscrire/{id}", name="inscrire")
     */
    public function inscrire(Sortie $sortie, UserInterface $user, EntityManagerInterface $em, EtatRepository $repoEtat): Response
    {

        if ($sortie->getEtat() == $repoEtat->findOneBy(['libelle' => 'Ouverte']) && $sortie->getParticipants()->count() < $sortie->getNbInscriptionsMax() && new DateTime() < $sortie->getDateHeureDebut()) {
            $sortie->addParticipant($user);
            if ($sortie->getParticipants()->count() >= $sortie->getNbInscriptionsMax()) {
                $sortie->setEtat($repoEtat->findOneBy(['libelle' => 'Cloturée']));
            }
            $em->persist($sortie);
            $em->flush();
        } else {
            $this->addFlash('danger', "Tu ne peux pas t'inscrire, la sortie n'est plus ouverte aux inscriptions");
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/desinscrire/{id}", name="desinscrire")
     */
    public function desinscire(Sortie $sortie, UserInterface $user, EntityManagerInterface $em, EtatRepository $repoEtat): Response
    {
        if (new DateTime() < $sortie->getDateHeureDebut()) {
            $sortie->removeParticipant($user);
            $sortie->setEtat($repoEtat->findOneBy(['libelle' => 'Ouverte']));
            $em->flush();
        } else {
            $this->addFlash('danger', "Tu ne peux plus te désinscrire");
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/annulerSortie/{id}", name="annulerSortie")
     */
    public function annuler_sortie(Request $request, EntityManagerInterface $em, Sortie $sortie, EtatRepository $repoEtat): Response
    {

        $formulaireAnnuler = $this->createForm(AnnulerSortieType::class, $sortie);

        $formulaireAnnuler->handleRequest($request);

        if ($formulaireAnnuler->isSubmitted() && $repoEtat->findOneBy(['libelle' => 'Crée']) && new DateTime() < $sortie->getDateHeureDebut()) {

            $sortie->setEtat($repoEtat->findOneBy(['libelle' => 'Annulée']));

            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute('home');
        } else {
            $this->addFlash('danger', "Tu ne peux plus modifier ta sortie");
        }

        return $this->renderForm('sortie/annulerSortie.html.twig', [
            'sortie' => $sortie,
            'formulaireAnnuler' => $formulaireAnnuler,
        ]);
    }
}
