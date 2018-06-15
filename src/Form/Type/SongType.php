<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Song;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Sander Marechal
 */
class SongType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('performer', Type\TextType::class, [
                'label' => 'form.song.performer',
                'required' => false,
                'empty_data' => '',
            ])
            ->add('title', Type\TextType::class, [
                'label' => 'form.song.title',
                'required' => false,
                'empty_data' => '',
            ])
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Song::class,
        ]);
    }
}
