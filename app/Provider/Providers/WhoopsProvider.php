<?php

declare(strict_types=1);

namespace App\Provider\Providers;

use App\Provider\Generic;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PlainTextHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Whoops\Util\Misc;

class WhoopsProvider extends Generic
{
    /**
     * Identifies this class instance in the service container.
     * 
     * @var string
     */
    const IDENTIFIER = 'whoops';

    /**
     * Register this service provider.
     *
     * @return void
     */
    public function register(): void
    {
        if (php_sapi_name() === 'cli') {
            $handler = new PlainTextHandler;
        } else {
            $handler = new PrettyPageHandler;
            $handler->setPageTitle("Whoops! There was a problem.");
            $handler->addDataTable('Additional Information', [
                'unique_id' => uniqid(),
            ]);
        }

        $run = new Run;
        $run->pushHandler($handler);

        if (Misc::isAjaxRequest()) {
            $run->pushHandler(new JsonResponseHandler);
        }

        $run->register();
    }
}
