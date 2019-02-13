<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\QueryBuilder;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('GET')
            ->add('username', TextType::class, ['label'=>"Pseudo"])
            ->add('firstName', TextType::class, ['label'=>"Prénom"])
            ->add('name', TextType::class,  ['label'=>"Nom"])
            ->add('phone', TextType::class, ['label'=>"Téléphone"])
            ->add('mail', TextType::class, ['label'=>"Email"])

/*          ->add('administrator')
            ->add('roles')
            ->add('actif')*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
