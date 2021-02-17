<?php

declare(strict_types=1);

namespace App\Repository\Repositories;

use App\Model\Models\User;
use App\Repository\GenericRepository;
use Phalcon\Mvc\Model\Row;

class BrandRepository extends GenericRepository
{
    /**
     * Attempt to match a brand name contained in a product name.
     *
     * @param string $productName
     * @return string
     */
    public function getBrandName(string $productName): string
    {
        $query = "
            SELECT
                brands.name
            FROM
                App\Model\Models\Brand brands
            WHERE
                :productName: LIKE CONCAT('%', brands.name, '%')
        ";

        $record = $this->manager->executeQuery($query, ['productName' => $productName])->getFirst();

        return $record instanceof Row ? $record->toArray()['name'] : '';
    }
}
