<?php

namespace App\Form;

use App\Entity\Teste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
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
            'data_class' => Teste::class,
            'choices' => []
        ]);
    }
}
