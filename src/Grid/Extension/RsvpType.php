<?php

declare(strict_types=1);

namespace App\Grid\Extension;

use App\Model\Rsvp;
use Prezent\Grid\BaseElementType;
use Prezent\Grid\ElementView;
use Prezent\Grid\Extension\Core\Type\ColumnType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * RSVP column type
 *
 * @author Sander Marechal
 */
class RsvpType extends BaseElementType
{
    /**
     * {@inheritDoc}
     */
    public function bindView(ElementView $view, $item)
    {
        switch ($view->vars['value']) {
            case Rsvp::YES:
                $view->vars['value'] = 'check';
                break;
            case Rsvp::MAYBE:
                $view->vars['value'] = 'question';
                break;
            case Rsvp::NO:
                $view->vars['value'] = 'times';
                break;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return ColumnType::class;
    }
}
