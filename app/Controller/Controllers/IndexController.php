<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\HtmlController;
use App\Service\Services\Captcha;

class IndexController extends HtmlController
{
    /**
     * Forward to the specials page.
     *
     * @return void
     */
    public function indexAction(): void
    {
        $this->dispatcher->forward([
            'controller' => 'special',
            'action' => 'page',
        ]);
    }

    /**
     * Generate and respond with a captcha image.
     *
     * @param Captcha $captcha
     * @return void
     */
    public function captchaAction(Captcha $captcha): void
    {
        $image = $captcha->getImage();

        $this->response->setHeader('Content-type', 'image/png');

        imagepng($image);
        imagedestroy($image);
    }
}
