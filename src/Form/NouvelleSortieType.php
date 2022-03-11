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

class NouvelleSortieType extends AbstractType
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
            ->add('lieu', EntityType::class, ['class' => Lieu::class, 'choice_label' => 'nom' ])

            //Bouttons ENREGISTRER / 
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            //Boutton Publier
            ->add('publish', SubmitType::class, ['label' => 'Publier La Sortie'])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
