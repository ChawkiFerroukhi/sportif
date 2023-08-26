<?php

namespace App\Form;

use App\Entity\Note;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
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
            ->add('observation', TinymceType::class, [
                "attr" => [
                    "toolbar" => "undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | outdent indent",
                ]
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
