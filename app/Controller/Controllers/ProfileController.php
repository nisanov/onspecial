<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\HtmlController;
use App\Controller\Traits\RequiresAuthentication;
use App\Form\Forms\ProfileForm;
use Exception;
use Phalcon\Cache\Exception\InvalidArgumentException;
use Phalcon\Db\Exception as DbException;
use Phalcon\Http\ResponseInterface;

class ProfileController extends HtmlController
{
    use RequiresAuthentication;

    /**
     * Initialize this controller.
     *
     * @return void
     */
    public function initialize(): void
    {
        $this->view->setTemplateAfter('common');
    }

    /**
     * Show the profile form.
     *
     * @return void
     * @throws DbException|InvalidArgumentException
     */
    public function indexAction(): void
    {
        $this->view->setVar('form', new ProfileForm($this->authentication->getUser()));
    }

    /**
     * Process the profile form.
     *
     * @return ResponseInterface
     * @throws DbException
     * @throws Exception
     */
    public function updateAction(): ResponseInterface
    {
        if (!$this->request->isPost()) {
            return $this->redirector->toLocation('profile')->withError("Invalid Request Type");
        }

        $user = $this->authentication->getUser();
        $password = $user->password;

        $form = new ProfileForm($user);
        if ($form->isValid($this->request->getPost())) {

            if ($form->getValue('password') !== "") {
                $password = $this->security->hash($form->getValue('password'));
            }

            $user->password = $password;

            if (!empty($user->getChangedFields())) {
                $user->save();
            }

            return !empty($user->getUpdatedFields())
                ? $this->redirector->toLocation('profile')->withSuccess("Updated Profile")
                : $this->redirector->toLocation('profile')->withWarning("Unchanged Profile");
        }

        return $this->redirector->toLocation('profile')->withSuccess("Invalid Profile");
    }

    /**
     * Set the show state on the specified attribute.
     *
     * @param string $attribute
     * @param bool $state
     * @return ResponseInterface
     * @throws Exception
     */
    public function showAction(string $attribute, bool $state): ResponseInterface
    {
        $user = $this->authentication->getUser();
        $action = $state ? "show" : "hide";

        switch ($attribute) {
            case 'seen':
                $user->show_seen = (int)$state;
                $message = "Configured to $action seen specials";
                break;
            case 'favorites':
                $user->show_favorites = (int)$state;
                $message = "Configured to $action favorite products";
                break;
            case 'ignored':
                $user->show_ignored = (int)$state;
                $message = "Configured to $action ignored products/brands";
                break;
            default:
                return $this->redirector->toRequest('special/page/1')->withError("The attribute specified is unknown: $attribute");
        }

        $user->save();

        return $this->redirector->toRequest('special/page/1')->withSuccess($message);
    }
}
