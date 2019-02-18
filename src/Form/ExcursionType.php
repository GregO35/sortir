<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Excursion;
use App\Entity\Place;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
                'label' => 'Nom*'
            ])
            ->add('startDate', DateTimeType::class,[
                'label' => 'Date de début*'
            ])
            ->add('endDate', DateTimeType::class,[
                'label' => 'Date de clôture*'
            ])
            ->add('registrationNumberMax', NumberType::class,[
                'label' => 'Nombre maximum de participant*'
            ])
            ->add('description', TextType::class,[
                'label' => 'Description'
            ])
            ->add('duration', NumberType::class,[
                'label' => 'Durée de la sortie (en minutes)'
            ])
            ->add('urlPicture')
/*
            ->add('name', EntityType::class, [
                'label'=>"Lieu",
                'multiple' => false,
                'expanded' => true,
                'class' => Place::class
            ])
            ->add('latitude', TextType::class,[
                'label' => 'Latitude',
                'class'=> Place::class
            ])
            ->add('longitude', TextType::class,[
                'label' => 'Longitude',
                'class'=> Place::class
            ])
*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Excursion::class,
        ]);
    }
}
