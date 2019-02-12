<?php

namespace App\Form;

use App\Entity\Excursion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExcursionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'nom'
            ])
            ->add('startDate', DateType::class,[
                'label' => 'Date de début'
            ])
            ->add('duration', NumberType::class,[
                'label' => 'Durée de la sortie'
            ])
            ->add('endDate', DateType::class,[
                'label' => 'Date de fin'
            ])
            ->add('registrationNumberMax', NumberType::class,[
                'label' => 'Nombre maximum de participant'
            ])
            ->add('description', TextType::class,[
                'label' => 'Description'
            ])
            ->add('urlPicture')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Excursion::class,
        ]);
    }
}
