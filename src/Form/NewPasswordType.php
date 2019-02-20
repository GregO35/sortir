<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('password', RepeatedType::class,[
            'type'=>PasswordType::class,
            'invalid_message'=>'Les 2 mots de passe doivent matcher',
            'options'=>['attr'=>['class'=>'password-field']],
            'required'=> true,
            'first_options'=>['label'=>"Mot de passe :"],
            'second_options' => ['label'=>"Confirmation :"],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
