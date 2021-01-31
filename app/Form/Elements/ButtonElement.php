<?php

declare(strict_types=1);

namespace App\Form\Elements;

use Phalcon\Forms\Element\AbstractElement;

class ButtonElement extends AbstractElement
{
    /**
     * Get the HTML render of this element.
     *
     * @param array $attributes
     * @return string
     */
    public function render(array $attributes = []): string
    {
        $attributes += ['type' => 'submit', 'class' => 'btn primary'] + $this->getAttributes();

        array_walk($attributes, function (&$item, $attribute) {
            if ($item !== null) {
                $item = $attribute . '="' . $item . '"';
            }
        });

        return '<button ' . trim(implode(' ', $attributes)) . '>' . $this->name . '</button>';
    }
}
