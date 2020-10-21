<?php

declare(strict_types=1);

namespace App\Validator\Validators;

use App\Model\Generic as GenericModel;
use App\Validator\Generic;
use Exception;
use Phalcon\Validation;
use Phalcon\Helper\Str;
use Phalcon\Messages\Message;

class Existence extends Generic
{
    /**
     * Execute the validation.
     *
     * @param Validation $validator
     * @param string $attribute
     * @return bool
     */
    public function validate(Validation $validator, $attribute): bool
    {
        $value = $validator->getValue($attribute);
        $model = $this->getOption('model');
        if (!$model instanceof GenericModel) {
            throw new Exception("The specified model must be an instance of App\Model\Generic.");
        }

        $instance = $model->{"findFirstBy" . Str::camelize($attribute)}($value);
        if ($instance === null) {
            if ($this->template === null) {
                $this->template = ucwords(str_replace('_', ' ', $attribute)) . " must exist on record.";
            }
            $validator->appendMessage(new Message($this->template, $attribute, 'Existence'));

            return false;
        }

        return true;
    }
}
