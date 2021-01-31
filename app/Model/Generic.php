<?php

declare(strict_types=1);

namespace App\Model;

use App\Model\Traits\HasTimestamps;
use Phalcon\Db\Exception;
use Phalcon\Mvc\Model as MvcModel;
use ReflectionClass;

abstract class Generic extends MvcModel
{
    /**
     * Initialize the model with initial instance.
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->keepSnapshots(true);
        $this->setSource($this->getDerivedTableName());
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
     * Construct the model with every instance.
     *
     * @return void
     */
    public function onConstruct(): void
    {
        $data = $this->toArray();

        $this->setOldSnapshotData($data);
        $this->setSnapshotData($data);
    }

    /**
     * Inserts or updates a model instance. Returning true on success or false otherwise.
     *
     * @return bool
     * @throws Exception when a failure occurs during the database save.
     */
    public function save(): bool
    {
        if (in_array(HasTimestamps::class, class_uses($this)) && method_exists($this, 'stamp')) {
            $this->stamp();
        }

        if (!parent::save()) {
            throw new Exception("Failed to save " . get_class($this) . ": " . $this->getMessages()[0]->getMessage());
        }

        return true;
    }

    /**
     * Handles the after fetch event.
     *
     * @return void
     */
    public function afterFetch(): void
    {
        $data = $this->toArray();

        $this->setOldSnapshotData($data);
        $this->setSnapshotData($data);
    }
}
