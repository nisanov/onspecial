<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\HtmlController;
use App\Service\Services\Captcha;

class CaptchaController extends HtmlController
{
    /**
     * Generate and respond with a captcha image.
     *
     * @return bool
     */
    public function indexAction(): bool
    {
        $captcha = new Captcha;

        $this->session->set('captcha', $captcha->getString());
        $this->response->setContentType('image/png');

        $image = imagescale($captcha->getImage(), 144);

        imagepng($image);
        imagedestroy($image);

        return false;
    }
}
