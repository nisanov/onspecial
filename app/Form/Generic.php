<?php

declare(strict_types=1);

namespace App\Form;

use App\Model\Generic as GenericModel;
use Exception;
use Phalcon\Forms\Form;

abstract class Generic extends Form
{
    /**
     * Whether this form has rendered a CSRF token element.
     * 
     * @var bool
     */
    protected bool $hasCsrfToken = false;

    /**
     * Determine whether the specified data is valid for this form configuration.
     *
     * @param array $data
     * @param GenericModel $entity
     * @return bool
     */
    public function isValid($data = null, $entity = null): bool
    {
        if ($this->hasCsrfToken && !$this->security->checkToken()) {
            throw new Exception("The CSRF token validation failed.");
        }

        $isValid = parent::isValid($data, $entity);
        if ($isValid) {
            $this->session->remove($this->getClassNameData());
            $this->session->remove($this->getClassNameMessages());
        } else {
            $this->session->set($this->getClassNameData(), $data);
            foreach ($data as $attribute => &$value) {
                if ($this->hasMessagesFor($attribute)) {
                    $value = $this->getMessagesFor($attribute)->current()->getMessage();
                } else {
                    unset($data[$attribute]);
                }
            }
            $this->session->set($this->getClassNameMessages(), $data);
        }

        return $isValid;
    }

    /**
     * Set the session stored data and messages for this form.
     *
     * @param array $data
     * @param array $messages
     * @return void
     */
    public function setSessionStoredData(array $data, array $messages): void
    {
        $this->session->set($this->getClassNameData(), $data);
        $this->session->set($this->getClassNameMessages(), $messages);
    }

    /**
     * Attempt to get the key specified session stored value for this form.
     *
     * @param string $key
     * @param string $default
     * @return string
     */
    public function getSessionStoredValue(string $key, string $default = ""): string
    {
        $stored = $this->session->get($this->getClassNameData()) ?? [];
        if (array_key_exists($key, $stored)) {
            return $stored[$key];
        }

        return $default;
    }

    /**
     * Attempt to get the key specified session stored message for this form.
     *
     * @param string $key
     * @return string|null
     */
    public function getSessionStoredMessage(string $key): ?string
    {
        $stored = $this->session->get($this->getClassNameMessages()) ?? [];
        if (array_key_exists($key, $stored)) {
            return $stored[$key];
        }

        return null;
    }

    /**
     * Render a hidden CSRF token element.
     * 
     * @return void
     */
    public function renderCsrfToken(): void
    {
        $this->hasCsrfToken = true;

        $security = $this->getDI()->getShared('security');

        $tokenKey = $security->getTokenKey();
        $tokenValue = $security->getToken();

        echo "<input type='hidden' name='$tokenKey' value='$tokenValue' />";
    }

    /**
     * Get the class name data session key.
     * 
     * @return string
     */
    private function getClassNameData(): string
    {
        return get_class($this) . "\Data";
    }

    /**
     * Get the class name messages session key.
     * 
     * @return string
     */
    private function getClassNameMessages(): string
    {
        return get_class($this) . "\Messages";
    }
}
