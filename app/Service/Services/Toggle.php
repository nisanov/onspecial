<?php

declare(strict_types=1);

namespace App\Service\Services;

use App\Exception\Exceptions\ModelNotFoundException;
use App\Model\Models\Brand;
use App\Model\Models\Product;
use App\Model\Models\User;
use App\Model\Models\UserBrand;
use App\Model\Models\UserProduct;
use App\Service\Generic;
use InvalidArgumentException;
use Phalcon\Db\Exception as DbException;

class Toggle extends Generic
{
    /**
     * The applicable user.
     *
     * @var User
     */
    protected User $user;

    /**
     * Set the user to be toggled.
     *
     * @param User $user
     * @return self
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Set a user's product favorite state.
     *
     * @param int $productId
     * @param bool $state
     * @return Product
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function favoriteProduct(int $productId, bool $state): Product
    {
        $product = $this->getProduct($productId);

        $userProduct = UserProduct::findFirst([
            'user_id = :userId: AND product_id = :productId:',
            'bind' => [
                'userId' => $this->user->id,
                'productId' => $product->id,
            ]
        ]);

        if ($userProduct === null) {
            $userProduct = new UserProduct;
            $userProduct->user_id = $this->user->id;
            $userProduct->product_id = $product->id;
        }

        $userProduct->assign(['favorite' => $state]);
        $userProduct->save();

        if ($userProduct->favorite) {
            $this->ignoreProduct($userProduct->product->brand_id, false);
        }

        return $product;
    }

    /**
     * Set a user's product ignore state.
     *
     * @param int $productId
     * @param bool $state
     * @return Product
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function ignoreProduct(int $productId, bool $state): Product
    {
        $product = $this->getProduct($productId);

        $userProduct = UserProduct::findFirst([
            'user_id = :userId: AND product_id = :productId:',
            'bind' => [
                'userId' => $this->user->id,
                'productId' => $product->id,
            ]
        ]);

        if ($userProduct === null) {
            $userProduct = new UserProduct;
            $userProduct->user_id = $this->user->id;
            $userProduct->product_id = $product->id;
        }

        $userProduct->assign(['ignoring' => $state]);
        $userProduct->save();

        if (!$userProduct->ignoring) {
            $this->ignoreBrand($userProduct->product->brand_id, false);
        }

        return $product;
    }

    /**
     * Get a product instance based on the specified product identifier.
     *
     * @param int $productId
     * @return Product
     * @throws ModelNotFoundException
     */
    private function getProduct(int $productId): Product
    {
        if (!$this->user instanceof User) {
            throw new InvalidArgumentException("The user must be initialised before performing a request.");
        }

        $product = Product::findFirst($productId);
        if (!$product instanceof Product) {
            throw new ModelNotFoundException("The specified product #[$productId] does not exist");
        }

        return $product;
    }

    /**
     * Set a user's brand ignore state.
     *
     * @param int $brandId
     * @param bool $state
     * @return Brand
     * @throws InvalidArgumentException when the user has not been initialised.
     * @throws ModelNotFoundException|DbException when the specified brand is not found.
     */
    public function ignoreBrand(int $brandId, bool $state): Brand
    {
        if (!$this->user instanceof User) {
            throw new InvalidArgumentException("The user must be initialised before performing a request.");
        }

        $brand = Brand::findFirst($brandId);
        if (!$brand instanceof Brand) {
            throw new ModelNotFoundException("The specified brand #[$brandId] does not exist");
        }

        $userBrand = UserBrand::findFirst([
            'user_id = :userId: AND brand_id = :brandId:',
            'bind' => [
                'userId' => $this->user->id,
                'brandId' => $brand->id,
            ]
        ]);

        if ($userBrand === null) {
            $userBrand = new UserBrand;
            $userBrand->user_id = $this->user->id;
            $userBrand->brand_id = $brand->id;
        }

        $userBrand->assign(['ignoring' => $state]);
        $userBrand->save();

        return $brand;
    }
}
