<?php

namespace App\Form;

use App\Entity\Campus;
use App\Model\Filtre;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiltreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $time=new DateTime();
        $time->setDate(2023,1,1);
        
        $builder
            ->add('recherche')
            ->add('dateDebut' ,DateType::class, [
                'widget' => 'choice',
                'data' => new \DateTime("now"),
            ])
            ->add('dateFin',DateType::class, [
                'widget' => 'choice',
                'data' => $time,
            ])
            ->add('sortieOrganisateur',CheckboxType::class, [
                'label'    => 'Sortie Organisateur',
                'required' => false,
            ])
            ->add('sortieInscrit', CheckboxType::class, [
                'label'    => 'Sorties Inscrits',
                'required' => false,
            ])
            ->add('sortieNonInscrit', CheckboxType::class, [
                'label'    => 'Sorties Non Inscrits',
                'required' => false,
            ])
            ->add('sortiePasse', CheckboxType::class, [
                'label'    => 'Sorties Passées',
                'required' => false,
            ])
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
