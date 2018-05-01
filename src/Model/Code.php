<?php

declare(strict_types=1);

namespace App\Model;

/**
 * Invitation code
 *
 * @author Sander Marechal
 */
class Code
{
    /**
     * @var string
     */
    public $code = '';

    /**
     * Check current code
     */
    public function isValid(): bool
    {
        return strtolower($this->code) === strtolower(getenv('VERIFICATION_CODE'));
    }
}
