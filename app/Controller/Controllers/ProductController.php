<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\JsonController;
use App\Controller\Traits\RequiresAuthentication;
use App\Exception\Exceptions\ModelNotFound;
use App\Model\Models\Product;
use App\Model\Models\UserProduct;

class ProductController extends JsonController
{
    use RequiresAuthentication;

    /**
     * Change the state of a user to product favorite relationship.
     * 
     * @return array
     */
    public function favoriteAction(int $productId): array
    {
        $product = Product::findFirst($productId);
        if (!$product instanceof Product) {
            throw new ModelNotFound("The specified product #[{$productId}] does not exist");
        }

        $state = ['favorite' => (bool)($this->request->getJsonRawBody()->state ?? true)];
        if ($state['favorite'] === true) {
            $state['ignoring'] = false;
        }

        $userProduct = UserProduct::findFirst([
            'user_id = :userId: AND product_id = :productId:',
            'bind' => [
                'userId' => $this->authentication->getUser()->getId(),
                'productId' => $product->getId(),
            ]
        ]);

        if ($userProduct === null) {
            $userProduct = new UserProduct;
            $userProduct->setUserId($this->authentication->getUser()->getId());
            $userProduct->setProductId($product->getId());
        }

        $userProduct->assign($state);
        $userProduct->save();

        return [
            'message' => $state['favorite'] === true
                ? $product->getName() . " has been marked as a favorite product"
                : $product->getName() . " is no longer marked as a favorite product"
        ];
    }

    /**
     * Change the state of a user to product ignore relationship.
     * 
     * @return array
     */
    public function ignoreAction(int $productId): array
    {
        $product = Product::findFirst($productId);
        if (!$product instanceof Product) {
            throw new ModelNotFound("The specified product #[{$productId}] does not exist");
        }

        $state = ['ignoring' => (bool)($this->request->getJsonRawBody()->state ?? true)];
        if ($state['ignoring'] === true) {
            $state['favorite'] = false;
        }

        $userProduct = UserProduct::findFirst([
            'user_id = :userId: AND product_id = :productId:',
            'bind' => [
                'userId' => $this->authentication->getUser()->getId(),
                'productId' => $product->getId(),
            ]
        ]);

        if ($userProduct === null) {
            $userProduct = new UserProduct;
            $userProduct->setUserId($this->authentication->getUser()->getId());
            $userProduct->setProductId($product->getId());
        }

        $userProduct->assign($state);
        $userProduct->save();

        return [
            'message' => $state['ignoring'] === true
                ? $product->getName() . " has been marked as an ignored product"
                : $product->getName() . " is no longer marked as an ignored product"
        ];
    }
}
