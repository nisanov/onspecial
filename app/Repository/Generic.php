<?php

declare(strict_types=1);

namespace App\Repository;

use Phalcon\Di;
use Phalcon\Mvc\Model\Manager;

abstract class Generic
{
    /**
     * The models manager.
     * 
     * @var Manager
     */
    protected Manager $manager;

    /**
     * Construct the class instance.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->manager = Di::getDefault()->get(Manager::class);
    }
}
