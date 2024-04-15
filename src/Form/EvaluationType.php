<?php

namespace App\Form;

use App\Entity\Cours;
use App\Entity\Evaluation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EvaluationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('cours', EntityType::class, [
                'class' => Cours::class,
                'choice_label' => 'nom_cours', // or any other property you want to display
            ])
            ->add('Submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary w-100 py-3']
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evaluation::class,
        ]);
    }
}
