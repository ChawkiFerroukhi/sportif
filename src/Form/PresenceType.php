<?php

namespace App\Form;

use App\Entity\Presence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


class PresenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adherantid', ChoiceType::class, [
                'choices' => $options['choices'],
                'choice_label' => 'nomprenom', // optional: specify the property to use as the label
            ])
            ->add('date',DateTimeType::class, [ 
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Presence::class,
            'choices' => []
        ]);
    }

}
