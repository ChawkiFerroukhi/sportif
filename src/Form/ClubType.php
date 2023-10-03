<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\Club;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;


class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('adresse')
            ->add('numTel')
            ->add('nbSections')
            ->add('dateFondation',DateType::class, [ 
                'widget' => 'single_text',
            ])
            ->add('logoFile', VichImageType::class, [
                'required' => false,
                'image_uri' => false,
                'delete_label' => false,
                'allow_delete' => false,
                'download_label' => false,
            ])
            ->add('color',ColorType::class,[
                'attr' => ['class' => 'colorpicker-default']
            ])

            ->add('color2',ColorType::class,[
                'attr' => ['class' => 'colorpicker-default']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}
