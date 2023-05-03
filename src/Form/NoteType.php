<?php

namespace App\Form;

use App\Entity\Note;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class NoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note')
            ->add('objectifid', ChoiceType::class, [
                'choices' => $options['choices_obj'],
                'choice_label' => 'nom', // optional: specify the property to use as the label
            ])
            ->add('adherantid', ChoiceType::class, [
                'choices' => $options['choices_adh'],
                'choice_label' => 'nom', // optional: specify the property to use as the label
            ])
            ->add('observation',CKEditorType::class,[
                'attr' => [
                    'placeholder' => 'Observation',
                ],
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
            'choices_obj' => [],
            'choices_adh' => [],
        ]);
    }
}
