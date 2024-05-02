<?php

namespace App\Form;

use App\Entity\Events;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Callback;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('event_name', null, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9\s]+$/',
                        'message' => 'The name should only contain letters, numbers, and spaces.',
                    ]),
                ],
            ])
            ->add('description', null, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 1000]),
                ],
            ])
            ->add('created_at', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(),
                    new Callback([$this, 'validateDateIsHigherThanTomorrow']),
                ],
            ])
            ->add('event_photo', FileType::class, [
                'label' => 'Event Image',
                'mapped' => false, 
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Please upload an image.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
        ]);
    }


    public function validateDateIsHigherThanTomorrow($value, $context)
    {
        $today = new \DateTime();
        $tomorrow = (new \DateTime())->modify('+1 day');

        if ($value < $tomorrow) {
            $context
                ->buildViolation('The date should be at least tomorrow or higher.')
                ->addViolation();
        }
    }
}
