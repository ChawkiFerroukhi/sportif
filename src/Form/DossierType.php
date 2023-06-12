<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\Dossier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;


class DossierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('documentFile', VichImageType::class, [
                'required' => true,
                'image_uri' => false,
                'delete_label' => false,
                'allow_delete' => false,
                'download_label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dossier::class,
        ]);
    }
}
