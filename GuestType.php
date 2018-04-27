<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * User form type
 */
class UserType extends AbstractType
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
            ->add('locale', Type\ChoiceType::class, [
                'label' => 'form.user.email',
                'choices' => [
                    'Nederlands' => 'nl',
                    'Spaans' => 'es',
                ]
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
                'empty_data' => function (FormInterface $form) {
                    return new User($form->get('locale')->getData());
                }
            ])
        ;
    }
}
