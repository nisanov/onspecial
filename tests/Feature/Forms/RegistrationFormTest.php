<?php

declare(strict_types=1);

namespace Tests\Feature\Forms;

use App\Form\Forms\RegistrationForm;
use App\Model\Models\User;
use Tests\Feature\FeatureTestCase;

class RegistrationFormTest extends FeatureTestCase
{
    /**
     * Provide data for the login form validations test.
     */
    public function provideDataForValidationsTest(): array
    {
        return [
            'all invalid' => [
                [
                    'name' => "",
                    'email' => "",
                    'password' => "",
                    'password_confirm' => "",
                ], 6, false
            ],
            'non matching password' => [
                [
                    'name' => "A. Person",
                    'email' => "person@email.com",
                    'password' => "password1",
                    'password_confirm' => "password2",
                ], 1, false
            ],
            'invalid email' => [
                [
                    'name' => "A. Person",
                    'email' => "person@@email.com",
                    'password' => "password",
                    'password_confirm' => "password",
                ], 1, false
            ],
            'all valid' => [
                [
                    'name' => "A. Person",
                    'email' => "person@email.com",
                    'password' => "password",
                    'password_confirm' => "password",
                ], 0, true
            ],
        ];
    }

    /**
     * @dataProvider provideDataForValidationsTest
     */
    public function testValidations(array $data, int $errorCount, bool $isValid): void
    {
        $form = new RegistrationForm(new User);

        $this->assertSame($form->isValid($data), $isValid);
        $this->assertCount($errorCount, $form->getMessages());
    }
}
