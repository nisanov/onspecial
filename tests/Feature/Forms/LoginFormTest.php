<?php

declare(strict_types=1);

namespace Tests\Feature\Forms;

use App\Form\Forms\LoginForm;
use Tests\Feature\FeatureTestCase;

class LoginFormTest extends FeatureTestCase
{
    /**
     * Provide data for the login form validations test.
     */
    public function provideDataForValidationsTest(): array
    {
        return [
            'all invalid' => [
                [
                    'email' => "",
                    'password' => "",
                ], 4, false
            ],
            'invalid email' => [
                [
                    'email' => "person@@email.com",
                    'password' => "password",
                ], 1, false
            ],
            'all valid' => [
                [
                    'email' => "person@email.com",
                    'password' => "password",
                ], 0, true
            ],
        ];
    }

    /**
     * @dataProvider provideDataForValidationsTest
     */
    public function testValidations(array $data, int $errorCount, bool $isValid): void
    {
        $form = new LoginForm;

        $this->assertSame($form->isValid($data), $isValid);
        $this->assertCount($errorCount, $form->getMessages());
    }
}
