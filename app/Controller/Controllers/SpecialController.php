<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\HtmlController;
use App\Controller\Traits\RequiresAuthentication;
use App\Repository\Repositories\ProductRepository as ProductRepository;
use App\Repository\Repositories\SpecialRepository;
use Phalcon\Cache\Exception\InvalidArgumentException;
use Phalcon\Db\Exception as DbException;

class SpecialController extends HtmlController
{
    use RequiresAuthentication;

    /**
     * Show the home page.
     *
     * @param int $number
     * @return void
     * @throws DbException|InvalidArgumentException
     */
    public function pageAction(int $number = 1): void
    {
        $user = $this->authentication->getUser();
        $search = $this->request->getQuery('search', 'trim', "");

        $repository = new SpecialRepository;

        $statistics = (new ProductRepository)->getStatistics($user);
        $statistics['listed'] = $repository->getSpecialsCount($user, $search);
        $statistics['pages'] = (int)ceil($statistics['listed'] / $user->specials_per_page);
        $statistics['page'] = ($number > $statistics['pages']) ? $statistics['pages'] : $number;
        $statistics['offset'] = ($statistics['page'] - 1) * $user->specials_per_page;

        $this->view->setVar('user', $user);
        $this->view->setVar('statistics', $statistics);
        $this->view->setVar('products', $repository->getSpecialsResultset($user, (string)$statistics['offset'], $search));
    }
}
