<?php

declare(strict_types=1);

namespace App\Service\Services;

use App\Service\Generic;
use Phalcon\Http\ResponseInterface;

class Redirector extends Generic
{
    /**
     * The location 
     */
    protected string $location;

    /**
     * Set the redirection back to the request.
     *
     * @param string $default
     * @return self
     */
    public function toRequest(string $default = ''): self
    {
        $this->location = $this->session->get('requested', $default);

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $location
     * @return self
     */
    public function toLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Set session flash success message and redirect.
     *
     * @param string $message
     * @return ResponseInterface
     */
    public function withSuccess(string $message): ResponseInterface
    {
        $this->flashSession->success($message);

        return $this->response->redirect($this->location);
    }

    /**
     * Set session flash warning message and redirect.
     *
     * @param string $message
     * @return ResponseInterface
     */
    public function withWarning(string $message): ResponseInterface
    {
        $this->flashSession->warning($message);

        return $this->response->redirect($this->location);
    }

    /**
     * Set session flash error message and redirect.
     *
     * @param string $message
     * @return ResponseInterface
     */
    public function withError(string $message): ResponseInterface
    {
        $this->flashSession->error($message);

        return $this->response->redirect($this->location);
    }

    /**
     * Clear session flash nothing and redirect.
     *
     * @return ResponseInterface
     */
    public function withNothing(): ResponseInterface
    {
        $this->flashSession->clear();

        return $this->response->redirect($this->location);
    }
}
