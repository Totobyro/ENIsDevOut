<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    // /**
    //  * @Route("/modifier/{id}", name="participant_modifier")
    //  */
    // public function modifierParticipant(CampusRepository $repo, Request $req, EntityManagerInterface $em, Participant $p): Response
    // {

    //     $form = $this->createForm(ParticipantType::class, $p);
    //     $form->handleRequest($req);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         if ($form["password"]->getData() == "") {
    //             $p->setPassword($p->getPassword());
    //         }
    //         $em->flush();
    //         return $this->redirectToRoute('home');
    //     }

    //     return $this->render(
    //         'main/monProfil.html.twig',
    //         [
    //             'titre' => 'Mon Profil',
    //             'participantForm' => $form->createView()
    //         ]
    //     );
    // }


    /**
     * @Route("/modifier/{id}", name="participant_modifier")
     */
    public function modifierParticipant(Request $req, SluggerInterface $slugger, EntityManagerInterface $em, Participant $participant): Response
    {

        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $participant->setBrochureFilename($newFilename);
            }

            if ($form["password"]->getData() == "") {
                $participant->setPassword($participant->getPassword());
            }

            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render(
            'main/monProfil.html.twig',
            [
                'titre' => 'Mon Profil',
                'participantForm' => $form->createView(),
                'participant' => $participant,

            ]
        );
    }
}
