<?php

namespace App\Form;

use App\Entity\Place;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', EntityType::class, [
                'label'=>"Lieu",
                'multiple' => false,
                'expanded' => true,
                'class' => Place::class
            ])

            ->add('latitude', TextType::class,[
                'label' => 'Latitude'
            ])
            ->add('longitude', TextType::class,[
                'label' => 'Longitude'
            ])
            /*
            ->add('city')
            ->add('street')
            */
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
