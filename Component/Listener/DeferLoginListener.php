<?php

namespace CCDNUser\SecurityBundle\Component\Listener;

use CCDNUser\SecurityBundle\Component\Authorisation\SecurityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;

class DeferLoginListener
{
    /**
     *
     * @access protected
     * @var \Symfony\Component\Routing\RouterInterface $router
     */
    protected $router;

    /**
     *
     * @access protected
     * @var array $forceAccountRecovery
     */
    protected $forceAccountRecovery;

    /**
     *
     * @access protected
     * @var \CCDNUser\SecurityBundle\Component\Authorisation\SecurityManagerInterface $securityManager
     */
    protected $securityManager;

    /**
     *
     * @access public
     * @param \Symfony\Component\Routing\RouterInterface                                $router
     * @param \CCDNUser\SecurityBundle\Component\Authorisation\SecurityManagerInterface $securityManager
     * @param array                                                                     $forceAccountRecovery
     *
     */
    public function __construct(RouterInterface $router, SecurityManagerInterface $securityManager, array $forceAccountRecovery)
    {
        $this->router               = $router;
        $this->securityManager      = $securityManager;
        $this->forceAccountRecovery = $forceAccountRecovery;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        $securityManager = $this->securityManager; // Avoid the silly cryptic error 'T_PAAMAYIM_NEKUDOTAYIM'
        $result = $securityManager->vote();

        if ($result === $securityManager::ACCESS_DENIED_DEFER) {
            $event->stopPropagation();

            $redirectUrl = $this->router->generate(
                $this->forceAccountRecovery['route_recover_account']['name'],
                $this->forceAccountRecovery['route_recover_account']['params']
            );

            $event->setResponse(new RedirectResponse($redirectUrl));
        }
    }
}
