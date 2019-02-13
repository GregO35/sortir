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
                'label' => 'pseudo'
            ])
            ->add('firstName', TextType::class,[
                'label' => 'prénom'
            ])
            ->add('name', TextType::class,[
                'label' => 'nom'
            ])
            ->add('phone', NumberType::class,[
                'label' => 'Téléphone'
            ])
            ->add('mail', EmailType::class,[
                'label' => 'mail'
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
