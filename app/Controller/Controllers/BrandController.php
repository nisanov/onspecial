<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\JsonController;
use App\Controller\Traits\RequiresAuthentication;
use App\Exception\Exceptions\ModelNotFound;
use App\Model\Models\Brand;
use App\Model\Models\UserBrand;
use App\Repository\Repositories\Product as ProductRepository;

class BrandController extends JsonController
{
    use RequiresAuthentication;

    /**
     * Change the state of a user to brand ignore relationship.
     * 
     * @return array
     */
    public function ignoreAction(int $brandId): array
    {
        $brand = Brand::findFirst($brandId);
        if (!$brand instanceof Brand) {
            throw new ModelNotFound("The specified brand #[{$brandId}] does not exist");
        }

        $state = ['ignoring' => (bool)($this->request->getJsonRawBody()->state ?? true)];

        $userBrand = UserBrand::findFirst([
            'user_id = :userId: AND brand_id = :brandId:',
            'bind' => [
                'userId' => $this->authentication->getUser()->getId(),
                'brandId' => $brand->getId(),
            ]
        ]);

        if ($userBrand === null) {
            $userBrand = new UserBrand;
            $userBrand->setUserId($this->authentication->getUser()->getId());
            $userBrand->setBrandId($brand->getId());
        }

        $userBrand->assign($state);
        $userBrand->save();

        return [
            'message' => $state['ignoring'] === true
                ? $brand->getName() . " has been marked as an ignored brand"
                : $brand->getName() . " is no longer marked as an ignored brand",
            'ignoring' => (new ProductRepository)->getStatistics($userBrand->getUser())->ignoring,
        ];
    }
}
