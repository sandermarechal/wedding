<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Reset password form
 *
 * @author Sander Marechal
 */
class ResetPasswordVerifyType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', Type\RepeatedType::class, [
                'type' => Type\PasswordType::class,
                'invalid_message' => 'form.reset_password_verify.mismatch',
                'first_options' => [
                    'label' => 'form.reset_password_verify.password',
                ],
                'second_options' => [
                    'label' => 'form.reset_password_verify.repeat',
                ],
            ])
        ;
    }
}
