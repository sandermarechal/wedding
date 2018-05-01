<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Model\Code;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Verify users
 */
class CodeType extends AbstractType
{
    /**
     * Build the form
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', Type\TextType::class, [
                'label' => 'form.code.code',
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
                'data_class' => Code::class,
            ])
        ;
    }
}
