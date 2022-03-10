<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Filtre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('recherche')
            ->add('dateDebut')
            ->add('dateFin')
            ->add('sortieOrganisateur')
            ->add('sortieInscrit')
            ->add('sortieNonInscrit')
            ->add('sortiePasse')
            ->add('campus',EntityType::class, ['class'=> Campus::class, 'choice_label'=>'nom'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filtre::class,
        ]);
    }
}
