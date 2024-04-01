<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Validator\Constraints as Assert;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('classe')
            ->add('niveau')
            ->add('cin')
            ->add('genre', ChoiceType::class, [
                'choices' => [
                    'Male' => 'male',
                    'Female' => 'female',
                    'Autre' => 'other',
                ],
                'label' => 'Genre',
            ])
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text', // Use a single input for entering the date
                'format' => 'yyyy-MM-dd', // Adjust the format as needed
                'label' => 'Date de Naissance',
                'attr' => ['class' => 'form-control'], // Bootstrap class
                'input' => 'datetime_immutable', // Ensure the form returns a DateTimeImmutable object
            ])
            
            ->add('email');

            if ($options['include_password']) {
                $builder->add('password', PasswordType::class, [
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Le mot de passe ne peut pas être vide.',
                            'groups' => ['Default']
                        ]),
                        new Assert\Regex([
                            'pattern' => "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/",
                            'message' => "Le mot de passe doit contenir au moins 8 caractères, dont au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.",
                            'groups' => ['Default']
                        ])
                    ]
                ]);
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'include_password' => true,
            'validation_groups' => function (Options $options) {
                $groups = ['Student','registration'];
                if ($options['include_password']) {
                    $groups[] = 'Default';
                }
                return $groups;
            },
            
        ]);
    }
}
