<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\HtmlController;

class IndexController extends HtmlController
{
    /**
     * Forward to the specials page.
     *
     * @return void
     */
    public function indexAction(): void
    {
        // Forward flow to another action
        $this->dispatcher->forward(
            [
                'controller' => 'specials',
                'action'     => 'page',
            ]
        );
    }
}
