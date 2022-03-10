<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/participant", name="app_participant")
     */
    public function index(): Response
    {
        return $this->render('participant/index.html.twig', [
            'controller_name' => 'ParticipantController',
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="participant_modifier")
     */
    public function modifierParticipant(CampusRepository $repo, Request $req, EntityManagerInterface $em, Participant $p): Response
    {

        $form = $this->createForm(ParticipantType::class, $p);
        $form->handleRequest($req);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form["password"]->getData()=="") {
                $p->setPassword($p->getPassword());
            }
            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render(
            'main/monProfil.html.twig',
            [
                'titre' => 'Mon Profil',
                'participantForm' => $form->createView()
            ]
        );
    }
}
