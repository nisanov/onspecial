<?php

declare(strict_types=1);

namespace App\Exception\Exceptions;

use App\Exception\Generic;

class ModelNotFoundException extends Generic
{
    /**
     * Construct the model not found exception instance.
     *
     * @param string $message
     * @return void
     */
    public function __construct(string $message)
    {
        parent::__construct($message, 404);
    }
}
