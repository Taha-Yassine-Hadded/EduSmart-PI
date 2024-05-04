<?php

namespace App\Form;

use App\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_cours')
            ->add('nom_module')
            ->add('niveau')
            ->add('coursURLpdf', FileType::class, [
                'label' => 'Cours PDF',
                'mapped' => false, // This field is not mapped to any property of the entity
                'required' => false, // It's not mandatory to upload a file
            ])
            ->add('Submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary w-100 py-3']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
