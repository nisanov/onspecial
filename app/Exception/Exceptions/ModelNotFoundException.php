<?php

declare(strict_types=1);

namespace App\Exception\Exceptions;

use App\Exception\Generic;
use Throwable;

class ModelNotFoundException extends Generic
{
    /**
     * Constuct the model not found exception instance.
     *
     * @param string $message
     * @param int $code
     * @param Throwable $previous
     */
    public function __construct(string $message)
    {
        parent::__construct($message, 404);
    }
}
