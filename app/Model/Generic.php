<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Traits\HasTimestamps;
use Phalcon\Mvc\Model as MvcModel;
use ReflectionClass;

abstract class Generic extends MvcModel
{
    /**
     * Initialize the model.
     *
     * @return void
     */
    public function initialize(): void
    {
        var_dump($this->keepSnapshots(true));
        $this->setSource($this->getDerivedTableName());
    }

    /**
     * Determine if this instance is newly created.
     *
     * @return bool
     */
    public function isNewlyCreated(): bool
    {
        return $this->hasChanged('id');
    }

    /**
     * Get the table name derived from the class name.
     *
     * @return string
     */
    public function getDerivedTableName(): string
    {
        $name = strtolower((new ReflectionClass($this))->getShortName());

        switch (substr($name, -1)) {
            case 'y':
                return substr($name, 0, -1) . 'ies';
            case 's':
                return $name . 'es';
        }

        return $name . 's';
    }

    /**
     * Inserts or updates a model instance. Returning true on success or false otherwise.
     *
     * @return bool
     */
    public function save(): bool
    {
        if (in_array(HasTimestamps::class, class_uses($this))) {
            $this->stamp();
        }

        return parent::save();
    }
}
