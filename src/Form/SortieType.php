<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Lieu;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut')
            ->add('dateLimiteInscription')
            ->add('nbInscriptionsMax')
            ->add('duree')
            ->add('infosSortie')
            ->add('lieu', EntityType::class, ['class' => Lieu::class, 'choice_label' => 'nom'])

            // Le campus doit être récupéré par rapport au User
            ->add('campus', EntityType::class, ['class' => Campus::class, 'choice_label' => 'nom'])

            //Bouttons ENREGISTRER / 
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            //Boutton Publier
            ->add('publish', SubmitType::class, ['label' => 'Publier la Sortie'])
            //Boutton Supprimer la sortie
            ->add('delete', SubmitType::class, ['label' => 'Supprimer la Sortie'])

            // ->add('latitude')
            // ->add('longitude')
            // ->add('etat')
            // ->add('campus')
            // ->add('organisateur')
            // ->add('participants')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
