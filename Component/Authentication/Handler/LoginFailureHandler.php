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

namespace CCDNUser\SecurityBundle\Component\Authentication\Handler;

use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class LoginFailureHandler implements AuthenticationFailureHandlerInterface
{

    /**
     *
     * @access protected
     */
    protected $container;

    /**
     *
     * @access protected
     */
    protected $router;

    /**
     *
     * @access public
     * @param Router $router
     */
    public function __construct($container, Router $router)
    {

        $this->container = $container;

        $this->router = $router;
    }

    /**
     *
     * @access public
     * @param Request $request, TokenInterface $token
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {

        if ($this->container->getParameter('ccdn_user_security.login_shield.enable_shield')) {

            // Get the attempted username.
            $token = $exception->getExtraInformation();

            if (preg_match('/user="(?:[a-zA-Z0-9_]*)"/', $token, $tokenUsername)) {
                if ($tokenUsername[0] !== '' || $tokenUsername[0] !== null) {

                    if (preg_match('/([a-zA-Z0-9_]*)/', $tokenUsername[0])) {
                        $username = substr($tokenUsername[0], 6, -1);
                    } else {
                        $username = '';
                    }
                } else {
                    $username = '';
                }
            } else {
                $username = '';
            }

            // Get our visitors Session & IP address.
            $session = $request->getSession();

            $ipAddress = $request->getClientIp();

            // Make a note of the failed login.
            $tracker = $this->container->get('ccdn_user_security.component.authentication.tracker.login_failure_tracker');

            $tracker->addAttempt($session, $ipAddress, $username);
        }

        return new RedirectResponse($this->container->get('router')->generate(
			$this->container->getParameter('ccdn_user_security.login_shield.primary_login_route.name'),
			$this->container->getParameter('ccdn_user_security.login_shield.primary_login_route.params')));
    }

}
