<?php

declare(strict_types=1);

namespace App\Controller\Controllers;

use App\Controller\Traits\RequiresAuthentication;
use App\Controller\HtmlController;
use App\Form\Forms\ProfileForm;
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
     * @return ResponseInterface|null
     */
    public function indexAction(): void
    {
        $this->view->setVar('form', new ProfileForm($this->authentication->getUser()));
    }

    /**
     * Process the profile form.
     *
     * @return ResponseInterface
     */
    public function updateAction(): ResponseInterface
    {
        if (!$this->request->isPost()) {
            return $this->redirector->toLocation('profile')->withError("Invalid Request Type");
        }

        $user = $this->authentication->getUser();
        $password = $user->getPassword();

        $form = new ProfileForm($user);
        if ($form->isValid($this->request->getPost())) {

            if ($form->getValue('password') !== "") {
                $password = $this->security->hash($form->getValue('password'));
            }

            $user->setPassword($password);

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
     * @param string $segment
     * @param bool $state
     * @return ResponseInterface
     */
    public function showAction(string $attribute, bool $state): ResponseInterface
    {
        $user = $this->authentication->getUser();

        switch ($attribute) {
            case 'seen':
                $user->isShowingSeen($state);
                $message = "Configured to " . ($state ? "show" : "hide") . " seen specials";
                break;
            case 'favorites':
                $user->isShowingFavorites($state);
                $message = "Configured to " . ($state ? "show" : "hide") . " favorite products";
                break;
            case 'ignored':
                $user->isShowingIgnored($state);
                $message = "Configured to " . ($state ? "show" : "hide") . " ignored products/brands";
                break;
            default:
                return $this->redirector->toRequest('specials/page/1')->withError("The attribute specified is unknown: {$attribute}");
        }

        $user->save();

        return $this->redirector->toRequest('specials/page/1')->withSuccess($message);
    }
}
