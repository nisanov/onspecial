<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\Traits\RequiresAuthentication;
use App\Controller\HtmlController;
use App\Repository\Repositories\Product as ProductRepository;
use stdClass;

class SpecialsController extends HtmlController
{
    use RequiresAuthentication;

    /**
     * Show the home page.
     *
     * @return void
     */
    public function pageAction(int $number = 1): void
    {
        $user = $this->authentication->getUser();
        $offset = $user->getSpecialsPerPage();

        $show = new stdClass;
        $show->seen = $user->isShowingSeen();
        $show->favorites = $user->isShowingFavorites();
        $show->ignored = $user->isShowingIgnored();

        $this->view->setVar('show', $show);

        $statistics = (new ProductRepository)->getStatistics($user);
        $statistics->listed = $statistics->products - ($user->isShowingIgnored() ? 0 : $statistics->ignoring);
        $statistics->pages = (int)ceil($statistics->listed / $offset);
        $statistics->page = ($number > $statistics->pages) ? $statistics->pages : $number;
        $statistics->offset = ($statistics->page - 1) * $offset;

        $this->view->setVar('statistics', $statistics);

        $this->view->setVar('products', $this->modelsManager->executeQuery("
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
                AND (" . ($user->isShowingIgnored() ? 'TRUE' : 'FALSE') . " OR (NOT IFNULL(productUsers.ignoring, 0) AND NOT IFNULL(brandUsers.ignoring, 0)))
                AND (" . ($user->isShowingFavorites() ? 'TRUE' : 'FALSE') . " OR NOT IFNULL(productUsers.favorite, 0))
            ORDER BY
                IFNULL(productUsers.favorite, 0) DESC, 
                IFNULL(productUsers.ignoring, 0) OR IFNULL(brandUsers.ignoring, 0) ASC,
                IF(specials.created_at > :lastVisitedAt:, specials.created_at, 0) DESC,
                IFNULL(brands.name, 'zzz') ASC, 
                products.name ASC
            LIMIT {$statistics->offset}, {$offset}
        ", [
            'userId' => $user->getId(),
            'lastVisitedAt' => $user->getLastVisitedAt(),
        ]));
    }
}
