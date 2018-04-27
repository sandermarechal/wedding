<?php

declare(strict_types=1);

namespace App\Model;

/**
 * Rsvp
 *
 * @author Sander Marechal
 */
class Rsvp
{
    const YES = 'yes';
    const MAYBE = 'maybe';
    const NO = 'no';

    /**
     * Get form choices
     *
     * @return void
     */
    public static function getChoices()
    {
        return [
            'rsvp.YES' => self::YES,
            'rsvp.MAYBE' => self::MAYBE,
            'rsvp.NO' => self::NO,
        ];
    }
}
