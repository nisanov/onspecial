<?php

declare(strict_types=1);

namespace App\Exception;

use Phalcon\Exception;
use Throwable;

abstract class Generic extends Exception
{
    /**
     * The http exception status code.
     *
     * @var int
     */
    protected int $statusCode = 500;

    /**
     * Construct the generic exception instance.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message, int $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->statusCode = $code;
    }

    /**
     * Set the http exception status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
