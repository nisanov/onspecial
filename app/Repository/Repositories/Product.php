<?php

declare(strict_types=1);

namespace App\Repository\Repositories;

use App\Model\Models\User;
use App\Repository\Generic;
use Phalcon\Mvc\Model\Row;

class Product extends Generic
{
    /**
     * Get statistical information on the specified user.
     *
     * @param User $user
     * @return Row
     */
    public function getStatistics(User $user): Row
    {
        $query = "
            SELECT
                COUNT(products.id) products,
                SUM(IFNULL(productUsers.ignoring, 0) OR IFNULL(brandUsers.ignoring, 0)) ignoring,
                SUM(IFNULL(productUsers.favorite, 0)) favoriting
            FROM 
                App\Model\Models\Product products          
            LEFT JOIN 
                App\Model\Models\UserProduct productUsers ON productUsers.product_id = products.id AND productUsers.user_id = :userId:
            LEFT JOIN 
                App\Model\Models\UserBrand brandUsers ON brandUsers.brand_id = products.brand_id AND brandUsers.user_id = :userId:
            WHERE
                products.on_special
        ";

        return $this->manager->executeQuery($query, ['userId' => $user->getId()])->getFirst();
    }
}
