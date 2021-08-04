<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\HtmlController;
use App\Controller\Traits\RequiresAuthentication;
use App\Repository\Repositories\BirthdayRepository;
use Phalcon\Cache\Exception\InvalidArgumentException;
use Phalcon\Db\Exception as DbException;

class BirthdayController extends HtmlController
{
    use RequiresAuthentication;

    /**
     * Show the birthdays page.
     *
     * @return void
     * @throws DbException|InvalidArgumentException
     */
    public function indexAction(): void
    {
        $user = $this->authentication->getUser();
        $birthdays = (new BirthdayRepository)->getBirthdays($user);

        $this->view->setVar('user', $user);
        $this->view->setVar('birthdays', $birthdays);
    }
}
