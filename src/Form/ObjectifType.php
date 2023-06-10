<?php

namespace App\Form;

use App\Entity\Objectif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;



class ObjectifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description',CKEditorType::class,[
                'attr' => [
                    'placeholder' => 'Description',
                ],
                'required' => false
            ])        
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Objectif::class,
        ]);
    }
}
