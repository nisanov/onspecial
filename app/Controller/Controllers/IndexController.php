<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\Traits\RequiresAuthentication;
use App\Controller\HtmlController;

class IndexController extends HtmlController
{
    use RequiresAuthentication;

    /**
     * Show the home page.
     *
     * @return void
     */
    public function indexAction(): void
    {
        $userId = $this->authentication->getUser()->getId();

        $this->view->setVar('statistics', $this->modelsManager->executeQuery("
            SELECT
                COUNT(products.id) products,
                SUM(IFNULL(productUsers.ignoring, 0)) ignoring
            FROM 
                App\Model\Models\Product products
            LEFT JOIN 
                App\Model\Models\UserProduct productUsers ON productUsers.product_id = products.id AND productUsers.user_id = :userId:
            WHERE
                products.on_special
        ", [
            'userId' => $userId,
        ])->getFirst());

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
                brands.name brand,
                IFNULL(productUsers.favorite, 0) favorite,
                IFNULL(productUsers.ignoring, 0) ignoring
            FROM 
                App\Model\Models\Product products
            LEFT JOIN 
                App\Model\Models\UserProduct productUsers ON productUsers.product_id = products.id AND productUsers.user_id = :userId:
            INNER JOIN
                App\Model\Models\Store stores ON products.store_id = stores.id
            INNER JOIN
                App\Model\Models\Special specials ON products.id = specials.product_id
            INNER JOIN
                App\Model\Models\Brand brands ON products.brand_id = brands.id
            WHERE
                products.on_special
                AND specials.updated_at IS NULL
                AND NOT IFNULL(productUsers.ignoring, 0)
            ORDER BY
                IFNULL(productUsers.favorite, 0) DESC, 
                brands.name ASC, 
                products.name ASC
            LIMIT 500
        ", [
            'userId' => $userId,
        ]));
    }
}
