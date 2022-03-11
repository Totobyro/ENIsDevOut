<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom')
            ->add('nom')
            ->add('email')
            ->add('telephone')
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas, veuillez écrire le même',
                'required' => false,
                'mapped' => false,
                'options' => ['attr' => ['class' => 'password-field']],
                'first_options'  => array('label' => 'Mot De Passe'),
                'second_options' => array('label' => 'Confirmation Mot De Passe'),
            ))
            ->add('campus', EntityType::class, ['class' => Campus::class, 'choice_label' => 'nom'])


            ->add('brochure', FileType::class, [
                'label' => 'Brochure (PDF file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            // 'application/pdf',
                            // 'application/x-pdf',
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                            'image/webp',
                        ],
                        'mimeTypesMessage' => 'Svp prenez un format png / jpeg ou webp',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
