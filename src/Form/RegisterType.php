<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class,[
                'label' => 'Pseudo'
            ])
            ->add('firstName', TextType::class,[
                'label' => 'Prénom'
            ])
            ->add('name', TextType::class,[
                'label' => 'Nom'
            ])
            ->add('phone', NumberType::class,[
                'label' => 'Téléphone'
            ])
            ->add('mail', EmailType::class,[
                'label' => 'Mail'
            ])
            ->add('password', PasswordType::class,[
                'label' => 'Mot de passe'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
