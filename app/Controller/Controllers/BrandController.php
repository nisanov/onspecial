<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\JsonController;
use App\Controller\Traits\RequiresAuthentication;
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
        $user = $this->authentication->getUser();
        $state = (bool)($this->request->getJsonRawBody()->state ?? true);
        $brand = $this->toggle->setUser($user)->ignoreBrand($brandId, $state);

        return [
            'message' => $state === true
                ? "{$brand->name} has been marked as an ignored brand"
                : "{$brand->name} is no longer marked as an ignored brand",
            'ignoring' => (new ProductRepository)->getStatistics($user)->ignoring,
        ];
    }
}
