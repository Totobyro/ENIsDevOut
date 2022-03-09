<?php

namespace App\Controller;

use App\Entity\Participant;
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

        $p->setPrenom($req->get('prenom'));
        $p->setNom($req->get('nom'));
        $p->setTelephone($req->get('tel'));
        $p->setEmail($req->get('email'));
        $p->setPassword(password_hash($req->get('password'), PASSWORD_DEFAULT));
        $p->setCampus($repo->findOneBy(array('nom'=>$req->get('campus'))));
        $em->flush();
        return $this->redirectToRoute('home', []);
    }
}
