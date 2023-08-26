<?php

namespace App\Form;

use App\Entity\Maladie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Eckinox\TinymceBundle\Form\Type\TinymceType;



class MaladieType extends AbstractType
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
            ->add('clubid', null, [
                'label' => 'Club',
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un club',
                'required' => false,
            ])    
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Maladie::class,
        ]);
    }
}
