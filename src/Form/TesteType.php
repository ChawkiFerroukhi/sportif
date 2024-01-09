<?php

namespace App\Form;

use App\Entity\Teste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TesteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('equipeid', ChoiceType::class, [
                'choices' => $options['choices'],
                'choice_label' => 'nom', // optional: specify the property to use as the label
            ])
            ->add('date',DateType::class, [ 
                'widget' => 'single_text',
            ])
            ->add('nom')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'effectué' => 'effectué',
                    'non-effectué' => 'non-effectué',
                ],
            ])
            ->add('description', TinymceType::class, [
                "attr" => [
                    "toolbar" => "undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | outdent indent",
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Teste::class,
            'choices' => []
        ]);
    }
}
