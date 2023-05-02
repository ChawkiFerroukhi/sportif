<?php

namespace App\Form;

use App\Entity\Cycle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class CycleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description',CKEditorType::class,[
                'attr' => [
                    'placeholder' => 'Description',
                ],
                'required' => true
            ])
            ->add('startdate',DateType::class, [ 
                'widget' => 'single_text',
                'format'=> 'dd/MM',
                'html5' => false
            ])
            ->add('enddate',DateType::class, [ 
                'widget' => 'single_text',
                'format'=> 'dd/MM',
                'html5' => false            
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cycle::class,
        ]);
    }
}
