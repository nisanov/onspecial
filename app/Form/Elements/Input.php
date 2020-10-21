<?php

declare(strict_types=1);

namespace App\Form\Elements;

use Phalcon\Forms\Element\AbstractElement;

class Input extends AbstractElement
{
    /**
     * Get the HTML render of this element.
     *
     * @param array $attributes
     * @return string
     */
    public function render(array $attributes = []): string
    {
        $classes = ['input'];
        $attributes += ['id' => $this->name, 'name' => $this->name] + $this->getAttributes();

        $message = $this->getUserOption('message');
        if ($message !== null) {
            $classes[] = 'invalid';
        }

        if (strlen((string)($attributes['value'] ?? $this->value)) > 0) {
            $classes[] = 'activated';
        }

        array_walk($attributes, function (&$item, $attribute) {
            if ($item !== null) {
                $item = $attribute . '="' . $item . '"';
            }
        });

        $markup = [
            '<div class="' . implode(' ', $classes) . '">',
            '<label for="' . $this->name . '">' . $this->label . '</label>',
            '<input ' . trim(implode(' ', $attributes)) . '>',
            '<span class="underline"></span>',
            '</div>',
            '<div class="message">' . $message . '</div>',
        ];

        return implode('', $markup);
    }
}
