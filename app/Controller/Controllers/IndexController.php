<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\WebController;
use App\Model\Models\User;
use Phalcon\Http\ResponseInterface;

class IndexController extends WebController
{
    /**
     * Show the home page.
     *
     * @return ResponseInterface|null
     */
    public function indexAction(): ?ResponseInterface
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->response->redirect('authentication');
        }

        return null;
    }
}
