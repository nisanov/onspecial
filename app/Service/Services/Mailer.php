<?php

declare(strict_types=1);

namespace App\Service\Services;

use Exception;
use Phalcon\Mvc\View;
use Swift_Mailer;
use Swift_Message;
use Swift_Transport;

class Mailer extends Swift_Mailer
{
    /**
     * View to generate email templates
     * 
     * @var View
     */
    protected View $view;

    /**
     * Create a new Mailer using $transport for delivery.
     * 
     * @param View $view
     * @param Swift_Transport $transport
     * @return void
     */
    public function __construct(View $view, Swift_Transport $transport)
    {
        $this->view = $view;

        parent::__construct($transport);
    }

    /**
     * Mail 
     * 
     * @var array
     */
    public const TEMPLATE = [
        'registration' => [
            'subject' => 'New Registration - Activation Instructions',
            'from' => 'hello@onspecial.info',
        ],
        'recovery' => [
            'subject' => 'Account Recovery - Recovery Instructions',
            'from' => 'hello@onspecial.info',
        ],
    ];

    /**
     * Undocumented function
     *
     * @param array $recipient
     * @param string $template
     * @param array $params
     * @return void
     */
    public function sendTo(array $recipient, string $template, array $params = [])
    {
        if (!isset(self::TEMPLATE[$template])) {
            throw new Exception("The specified template is not configured: {$template}");
        }

        $message = $this->view->getRender('email', $template, $params, function (View $view) {
            $view->setRenderLevel(View::LEVEL_LAYOUT);
        });

        $message = new Swift_Message(self::TEMPLATE[$template]['subject'], $message);
        $message->setTo($recipient);
        $message->setFrom(self::TEMPLATE[$template]['from']);
        $message->setContentType('text/html');

        return parent::send($message);
    }
}
