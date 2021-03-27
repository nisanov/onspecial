<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\JsonController;
use App\Controller\Traits\RequiresAuthentication;
use App\Exception\Exceptions\ModelNotFoundException;
use Phalcon\Cache\Exception\InvalidArgumentException;
use Phalcon\Db\Exception as DbException;

class ProductController extends JsonController
{
    use RequiresAuthentication;

    /**
     * Change the state of a user to product favorite relationship.
     *
     * @param int $productId
     * @return array
     * @throws ModelNotFoundException|DbException|InvalidArgumentException
     */
    public function favoriteAction(int $productId): array
    {
        $user = $this->authentication->getUser();
        $state = (bool)($this->request->getJsonRawBody()->state ?? true);
        $product = $this->toggle->setUser($user)->favoriteProduct($productId, $state);

        return [
            'message' => $state === true
                ? "{$product->name} has been marked as a favorite product"
                : "{$product->name} is no longer marked as a favorite product"
        ];
    }

    /**
     * Change the state of a user to product ignore relationship.
     *
     * @param int $productId
     * @return array
     * @throws ModelNotFoundException|DbException|InvalidArgumentException
     */
    public function ignoreAction(int $productId): array
    {
        $user = $this->authentication->getUser();
        $state = (bool)($this->request->getJsonRawBody()->state ?? true);
        $product = $this->toggle->setUser($user)->ignoreProduct($productId, $state);

        return [
            'message' => $state === true
                ? "{$product->name} has been marked as an ignored product"
                : "{$product->name} is no longer marked as an ignored product"
        ];
    }
}
