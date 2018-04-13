<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

/**
 * Registration form type
 */
class RegistrationType extends AbstractType
{
    /**
     * Build the form
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', Type\TextType::class, [
                'label' => 'form.user.name',
            ])
            ->add('email', Type\EmailType::class, [
                'label' => 'form.user.email',
            ])
            ->add('plainPassword', Type\RepeatedType::class, [
                'type' => Type\PasswordType::class,
                'first_options' => [
                    'label' => 'form.user.password',
                ],
                'second_options' => [
                    'label' => 'form.user.password_repeat',
                ],
            ])
        ;
    }

    /**
     * Define form options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => User::class,
                'validation_groups' => ['Default', 'registration'],
            ])
        ;
    }
}
