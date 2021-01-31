<?php

declare(strict_types=1);

namespace App\Formatter\Formatters;

use Carbon\Carbon;
use Phalcon\Logger;
use Phalcon\Logger\Formatter\AbstractFormatter;
use Phalcon\Logger\Item;

class LineFormatter extends AbstractFormatter
{
    /**
     * Log level strings.
     *
     * @var array
     */
    const TYPES = [
        Logger::EMERGENCY => 'EMERGENCY',
        Logger::CRITICAL => 'CRITICAL',
        Logger::ALERT => 'ALERT',
        Logger::ERROR => 'ERROR',
        Logger::WARNING => 'WARNING',
        Logger::NOTICE => 'NOTICE',
        Logger::INFO => 'INFO',
        Logger::DEBUG => 'DEBUG',
        Logger::CUSTOM => 'CUSTOM',
    ];

    /**
     * Format the logger item into a log line.
     *
     * @param Item $item
     * @return string
     */
    public function format(Item $item): string
    {
        return sprintf("[%s][%s] %s %s", Carbon::now()->toISOString(true), self::TYPES[$item->getType()], $item->getMessage(), json_encode($item->getContext()));
    }
}
