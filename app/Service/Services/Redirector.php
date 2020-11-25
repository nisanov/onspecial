<?php

declare(strict_types=1);

namespace App\Service\Services;

use App\Service\Generic;
use Phalcon\Http\ResponseInterface;

class Redirector extends Generic
{
    /**
     * Set session flash success message and redirect.
     *
     * @param string $location
     * @param string $message
     * @return ResponseInterface
     */
    public function withSuccess(string $location, string $message): ResponseInterface
    {
        $this->flashSession->success($message);

        return $this->response->redirect($location);
    }

    /**
     * Set session flash error message and redirect.
     *
     * @param string $location
     * @param string $message
     * @return ResponseInterface
     */
    public function withError(string $location, string $message): ResponseInterface
    {
        $this->flashSession->error($message);

        return $this->response->redirect($location);
    }

    /**
     * Clear session flash nothing and redirect.
     *
     * @param string $location
     * @return ResponseInterface
     */
    public function withNothing(string $location): ResponseInterface
    {
        $this->flashSession->clear();

        return $this->response->redirect($location);
    }
}
