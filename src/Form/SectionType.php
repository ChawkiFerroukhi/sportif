<?php

namespace App\Form;

use App\Entity\Section;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Vich\UploaderBundle\Form\Type\VichImageType;


class SectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description', TinymceType::class, [
                "attr" => [
                    "toolbar" => "undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | outdent indent",
                ]
            ])
            ->add('logoFile', VichImageType::class, [
                'required' => false,
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
            'data_class' => Section::class,
        ]);
    }
}
