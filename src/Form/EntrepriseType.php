<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Validator\Constraints as Assert;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('pays')
            ->add('localisation')
            ->add('website')
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
                $groups = ['Entreprise','registration'];
                if ($options['include_password']) {
                    $groups[] = 'Default';
                }
                return $groups;
            },
            
        ]);
    }
}
