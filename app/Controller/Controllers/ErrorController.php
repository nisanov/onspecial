<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\HtmlController;

class ErrorController extends HtmlController
{
    /**
     * Configured in the router provider register method to 
     * handle a 404 page not found responses.
     *
     * @return void
     */
    public function pageNotFoundAction(): void
    {
        return;
    }
}
