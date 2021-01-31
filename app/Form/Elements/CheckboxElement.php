<?php

declare(strict_types=1);

namespace App\Form\Elements;

use Phalcon\Forms\Element\AbstractElement;

class CheckboxElement extends AbstractElement
{
    /**
     * Checkbox checked "yes" state.
     *
     * @var string
     */
    public const YES = 'yes';

    /**
     * Checkbox checked "no" state.
     *
     * @var string
     */
    public const NO = 'no';

    /**
     * Get the HTML render of this element.
     *
     * @param array $attributes
     * @return string
     */
    public function render(array $attributes = []): string
    {
        $attributes += ['id' => $this->name, 'name' => $this->name, 'type' => "checkbox", 'value' => self::YES] + $this->getAttributes();

        array_walk($attributes, function (&$item, $attribute) {
            if ($item !== null) {
                $item = $attribute . '="' . $item . '"';
            }
        });

        $markup = [
            '<div class="checkbox">',
            '<label class="container">',
            '<input type="hidden" id="' . $this->name . '_default" name="' . $this->name . '" value="' . self::NO . '" />',
            '<input ' . trim(implode(' ', $attributes)) . '>',
            '<span class="widget"></span>',
            '</label>',
            '<label class="label" for="' . $this->name . '">' . $this->label . '</label>',
            '</div>',
        ];

        return implode('', $markup);
    }
}
