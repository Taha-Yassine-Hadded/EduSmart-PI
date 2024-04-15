<?php

namespace App\Form;

use App\Entity\Option;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('valeur')
            ->add('isCorrect', ChoiceType::class, [
                'label' => 'Is Correct',
                'choices' => [
                    'No' => '0',
                    'Yes' => '1',
                ],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('Submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary w-100 py-3']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Option::class,
        ]);
    }
}
