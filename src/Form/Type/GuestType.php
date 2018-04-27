<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Guest;
use App\Entity\User;
use App\Model\Rsvp;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Guest form type
 */
class GuestType extends AbstractType
{
    /**
     * Build the form
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', Type\TextType::class, [
                'label' => 'form.guest.name',
            ])
            ->add('email', Type\EmailType::class, [
                'label' => 'form.guest.email',
                'required' => false,
            ])
            ->add('ceremony', Type\ChoiceType::class, [
                'label' => 'form.guest.ceremony',
                'choices' => Rsvp::getChoices(),
                'expanded' => true,
            ])
            ->add('party', Type\ChoiceType::class, [
                'label' => 'form.guest.party',
                'choices' => Rsvp::getChoices(),
                'expanded' => true,
            ])
        ;

        if ($options['admin']) {
            $builder->add('user', EntityType::class, [
                'label' => 'form.guest.user',
                'class' => User::class,
                'choice_label' => 'email',
                'required' => false,
            ]);
        }
    }

    /**
     * Define form options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'admin' => false,
                'data_class' => Guest::class,
                'empty_data' => function (FormInterface $form) {
                    return new Guest($form->get('name')->getData());
                }
            ])
        ;
    }
}
