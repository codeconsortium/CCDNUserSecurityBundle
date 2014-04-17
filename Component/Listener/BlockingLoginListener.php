<?php

/*
 * This file is part of the CCDNUser SecurityBundle
 *
 * (c) CCDN (c) CodeConsortium <http://www.codeconsortium.com/>
 *
 * Available on github <http://www.github.com/codeconsortium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCDNUser\SecurityBundle\Component\Listener;

use CCDNUser\SecurityBundle\Component\Authorisation\SecurityManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

/**
 *
 * @category CCDNUser
 * @package  SecurityBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNUserSecurityBundle
 *
 */
class BlockingLoginListener
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
     * @var \CCDNUser\SecurityBundle\Component\Authorisation\SecurityManager $securityManager
     */
    protected $securityManager;

    /**
     * @var AccessDeniedExceptionFactoryInterface
     */
    protected $exceptionFactory;

    /**
     *
     * @access public
     * @param \Symfony\Component\Routing\RouterInterface                                              $router
     * @param \CCDNUser\SecurityBundle\Component\Authorisation\SecurityManager                        $loginFailureTracker
     * @param \CCDNUser\SecurityBundle\Component\Listener\AccessDeniedExceptionFactoryInterface $exceptionFactory
     * @param array                                                                                   $forceAccountRecovery
     */
    public function __construct(RouterInterface $router, SecurityManager $securityManager, AccessDeniedExceptionFactoryInterface $exceptionFactory, $forceAccountRecovery)
    {
        $this->securityManager = $securityManager;
        $this->router = $router;
        $this->forceAccountRecovery = $forceAccountRecovery;
        $this->exceptionFactory = $exceptionFactory;
    }

    /**
     *
     * If you have failed to login too many times,
     * a log of this will be present in the databse.
     *
     * @access public
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->getRequestType() !== \Symfony\Component\HttpKernel\HttpKernel::MASTER_REQUEST) {
            return;
        }

        $securityManager = $this->securityManager; // Avoid the silly cryptic error 'T_PAAMAYIM_NEKUDOTAYIM'
        $result = $securityManager->vote();

        if ($result == $securityManager::ACCESS_ALLOWED) {
            return;
        }

        if ($result == $securityManager::ACCESS_DENIED_DEFER) {
            $event->stopPropagation();

            $redirectUrl = $this->router->generate(
                $this->forceAccountRecovery['route_recover_account']['name'],
                $this->forceAccountRecovery['route_recover_account']['params']
            );

            $event->setResponse(new RedirectResponse($redirectUrl));
        }

        if ($result == $securityManager::ACCESS_DENIED_BLOCK) {
            $event->stopPropagation();

            throw $this->exceptionFactory->createAccessDeniedException();
        }
    }
}
