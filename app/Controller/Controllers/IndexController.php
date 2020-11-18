<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\WebController;
use App\Model\Models\User;
use Phalcon\Http\ResponseInterface;

class IndexController extends WebController
{
    /**
     * Show the home page.
     *
     * @return ResponseInterface|null
     */
    public function indexAction(): ?ResponseInterface
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->response->redirect('authentication');
        }

        $products = $this->modelsManager->executeQuery("
            SELECT 
                products.id,
                products.name,
                products.thumb,
                products.uri,
                specials.current_price,
                specials.previous_price,
                brands.name brand
            FROM 
                App\Model\Models\Product products
            INNER JOIN
                App\Model\Models\Special specials ON products.id = specials.product_id
            INNER JOIN
                App\Model\Models\Brand brands ON products.brand_id = brands.id
            WHERE
                products.on_special
                AND specials.updated_at IS NULL
            ORDER BY
                brands.name, products.name
        ");

        $this->view->setVar('products', $products);

        return null;
    }
}
