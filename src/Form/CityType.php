<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Place;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', EntityType::class, [
                'label'=>"Ville :",
                'multiple' => false,
                'expanded' => false,
                'class' => City::class,
                'placeholder' =>''
            ])
        ;

        /*$formModifier = function (FormInterface $form, City $city = null) {
            $places = null === $city ? [] : $city->getPlaces();



            $form->add('places', EntityType::class, [
                'class' => Place::class,
                'multiple' => true,
                'expanded' => false,
                'placeholder' => '',
                'choices' => $places,
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                // this would be your entity, i.e. SportMeetup
                $city = $event->getData();

                $formModifier($event->getForm(), $city);
            }
        );

        $builder->get('name')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $city = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $city);
            }
        );*/

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event){
                $form = $event->getForm();

                $city = $event->getData();

                $places = null === $city ? [] : $city->getPlaces();

                //dd($places);

                $form->add('places', EntityType::class,[
                   'label' => 'Lieux',
                    'class' => Place::class,
                    'choices' => $places,
                    'placeholder' => '',
                    'mapped' => false
                ]);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => City::class
        ]);
    }
}
