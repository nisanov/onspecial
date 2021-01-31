<?php

declare(strict_types=1);

namespace App\Repository\Repositories;

use App\Model\Models\User;
use App\Repository\GenericRepository;
use Phalcon\Mvc\Model\ResultsetInterface;

class SpecialRepository extends GenericRepository
{
    /**
     * Get the count of specials for the users configured profile and search term request.
     *
     * @param User $user
     * @param string $search
     * @return int
     */
    public function getSpecialsCount(User $user, string $search = ""): int
    {
        $placeholders = [
            'userId' => $user->id,
            'lastVisitedAt' => $user->last_visited_at,
            'search' => "%$search%"
        ];

        return (int)$this->manager->executeQuery(preg_replace("/SELECT.*FROM/s", "SELECT COUNT(1) count FROM", $this->getSpecialsQuery($user, $search)), $placeholders)->getFirst()->toArray()['count'];
    }

    /**
     * Get the specials query.
     *
     * @param User $user
     * @param string $search
     * @return string
     */
    public function getSpecialsQuery(User $user, string $search = ""): string
    {
        return "
            SELECT 
                products.id,
                stores.name store,
                products.name,
                products.thumb,
                products.image,
                products.uri,
                products.description,
                products.ingredients,
                specials.current_price,
                specials.previous_price,
                specials.ratio_price,
                specials.created_at,
                specials.created_at > :lastVisitedAt: is_new,
                brands.id brand_id,
                IFNULL(brands.name, '-') brand,
                IFNULL(productUsers.favorite, 0) favorite_product,
                IFNULL(productUsers.ignoring, 0) ignoring_product,
                IFNULL(brandUsers.ignoring, 0) ignoring_brand
            FROM 
                App\Model\Models\Product products
            INNER JOIN
                App\Model\Models\Store stores ON products.store_id = stores.id
            INNER JOIN
                App\Model\Models\Special specials ON products.id = specials.product_id AND specials.updated_at IS NULL
            LEFT JOIN
                App\Model\Models\Brand brands ON products.brand_id = brands.id
            LEFT JOIN 
                App\Model\Models\UserProduct productUsers ON productUsers.product_id = products.id AND productUsers.user_id = :userId:
            LEFT JOIN 
                App\Model\Models\UserBrand brandUsers ON brandUsers.brand_id = products.brand_id AND brandUsers.user_id = :userId:
            WHERE
                products.on_special
                AND specials.updated_at IS NULL
                AND (" . ($user->show_ignored ? 'TRUE' : 'FALSE') . " OR (NOT IFNULL(productUsers.ignoring, 0) AND NOT IFNULL(brandUsers.ignoring, 0)))
                AND (" . ($user->show_favorites ? 'TRUE' : 'FALSE') . " OR NOT IFNULL(productUsers.favorite, 0))
                AND (" . (empty($search) ? 'TRUE' : 'FALSE') . " OR products.name LIKE :search:)
            ORDER BY
                IFNULL(productUsers.favorite, 0) DESC, 
                IFNULL(productUsers.ignoring, 0) OR IFNULL(brandUsers.ignoring, 0) ASC,
                IF(specials.created_at > :lastVisitedAt:, 1, 0) DESC,
                IFNULL(brands.name, 'zzz') ASC, 
                products.name ASC
        ";
    }

    /**
     * Get the paginated resultset of specials for the users configured profile and search term request.
     *
     * @param User $user
     * @param string $offset
     * @param string $search
     * @return ResultsetInterface
     */
    public function getSpecialsResultset(User $user, string $offset = "0", string $search = ""): ResultsetInterface
    {
        $placeholders = [
            'userId' => $user->id,
            'lastVisitedAt' => $user->last_visited_at,
            'search' => "%$search%"
        ];

        return $this->manager->executeQuery($this->getSpecialsQuery($user, $search) . "LIMIT $offset, $user->specials_per_page", $placeholders);
    }
}
