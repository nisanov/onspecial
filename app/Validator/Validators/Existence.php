<?php

declare(strict_types=1);

namespace App\Validator\Validators;

use App\Model\Generic as GenericModel;
use App\Validator\Generic as GenericValidator;
use Exception;
use Phalcon\Helper\Str;
use Phalcon\Messages\Message;
use Phalcon\Validation;

class Existence extends GenericValidator
{
    /**
     * Execute the validation.
     *
     * @param Validation $validation
     * @param mixed $field
     * @return bool
     * @throws Exception
     */
    public function validate(Validation $validation, $field): bool
    {
        $value = $validation->getValue($field);
        $model = $this->getOption('model');
        if (!$model instanceof GenericModel) {
            throw new Exception("The specified model must be an instance of App\Model\Generic.");
        }

        $instance = $model->{"findFirstBy" . Str::camelize($field)}($value);
        if ($instance === null) {
            if ($this->template === null) {
                $this->template = ucwords(str_replace('_', ' ', $field)) . " must exist on record.";
            }
            $validation->appendMessage(new Message($this->template, $field, 'Existence'));

            return false;
        }

        return true;
    }
}
