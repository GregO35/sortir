<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\UserRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\QueryBuilder;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->setMethod('POST')
            ->add('username', TextType::class, ['label'=>"Pseudo :"])
            ->add('firstName', TextType::class, ['label'=>"Prénom :"])
            ->add('name', TextType::class,  ['label'=>"Nom :"])
            ->add('phone', TextType::class, ['label'=>"Téléphone :"])
            ->add('mail', TextType::class, ['label'=>"Email :"])
            //Affiche 2 champs pour le mot de passe et la confirmation; compare les 2 et valide ou non
            ->add('password', RepeatedType::class,[
                'type'=>PasswordType::class,
                'invalid_message'=>'Les 2 mots de passe doivent matcher',
                'options'=>['attr'=>['class'=>'password-field']],
                'empty_data' => '',
                'required'=> true,
                'first_options'=>['label'=>"Mot de passe :"],
                'second_options' => ['label'=>"Confirmation :"],
             ])
            ->add('photo_file', FileType::class, ['data_class' => null,'required' => false, 'label'=> 'Ma photo :'])
        ;

//            ->add('nom_site', EntityType::class, [
//                'label'=>"Ville de rattachement",
//                'multiple' => true,
//                'expanded' => false,
//                'class' => Sites::class,
//            ])

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
