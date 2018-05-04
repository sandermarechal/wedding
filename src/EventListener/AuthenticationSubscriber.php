<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * AuthenticationSubscriber
 *
 * @author Sander Marechal
 */
class AuthenticationSubscriber implements EventSubscriberInterface
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * Constructor
     */
    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        UrlGeneratorInterface $router
    ) {
        $this->authorizationChecker = $authorizationChecker;
        $this->router = $router;
    }

    /**
     * Test for ROLE_VERIFIED
     */
    public function onException(GetResponseForExceptionEvent $event)
    {
        if (!($event->getException() instanceof AccessDeniedHttpException)) {
            return;
        }

        if (!$this->authorizationChecker->isGranted('ROLE_USER')) {
            die('not a user');
            return; // Not logged in
        }


        if ($this->authorizationChecker->isGranted('ROLE_VERIFIED')) {
            return; // Already verified, do nothing
        }

        $request = $event->getRequest();

        if ($request->getMethod() !== 'GET') {
            return;
        }

        $request->getSession()->set('verify_url', $request->getRequestUri());
        $event->setResponse(new RedirectResponse($this->router->generate('app_user_verifycode')));
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'onException',
        ];
    }
}
