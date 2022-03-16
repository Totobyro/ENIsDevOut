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
     * @Route("/modifier/{id}", name="participant_modifier")
     */
    public function modifierParticipant(Request $req, SluggerInterface $slugger, EntityManagerInterface $em, Participant $participant): Response
    {

        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochure')->getData();

            // cette condition est nécessaire car le champ 'brochure' n'est pas obligatoire
            // donc le fichier PDF doit être traité uniquement lorsqu'un fichier est téléchargé
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // ceci est nécessaire pour inclure en toute sécurité le nom du fichier dans l'URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Déplacer le fichier dans le répertoire où sont stockées les brochures
                try {
                    $brochureFile->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... gérer l'exception si quelque chose se passe pendant le téléchargement du fichier
                }

                // met à jour la propriété 'brochureFilename' pour stocker le nom du fichier PDF
                // au lieu de son contenu
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

    /**
     * @Route("/afficherProfil/{id}", name="afficher_profil")
     */
    public function afficher_profil(Participant $p): Response
    {

        return $this->render('participant/afficherParticipant.html.twig', [
            'participant' => $p,
        ]);
    }
}
