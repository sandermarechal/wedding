<?php

namespace App\Grid\Extension;

use Prezent\Grid\BaseElementTypeExtension;
use Prezent\Grid\ElementView;
use Prezent\Grid\Extension\Core\Type\ActionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * IconTypeExtension
 *
 * @author Sander Marechal
 */
class IconTypeExtension extends BaseElementTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined('icon');
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(ElementView $view, array $options)
    {
        if (!empty($options['icon'])) {
            $view->vars['icon'] = sprintf('fas fa-%s', $options['icon']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return ActionType::class;
    }
}
