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
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
    protected $loginFailureTracker;
	
    /**
     *
     * @access protected
     */
    protected $enableShield;
	
    /**
     *
     * @access protected
     */
    protected $loginRoute;
	
    /**
     *
     * @access protected
     */
    protected $loginRouteParams;
	
    /**
     *
     * @access protected
     */
    protected $router;
	
    /**
     *
     * @access public
     * @param $router
	 * @param $loginFailureTracker
	 * @param $enableShield
	 * @param $loginRoute
	 * @param $loginRouteParams
     */
    public function __construct($router, $loginFailureTracker, $enableShield, $loginRoute, $loginRouteParams)
    {
		$this->router = $router;
		$this->loginFailureTracker = $loginFailureTracker;
		$this->enableShield = $enableShield;
		$this->loginRoute = $loginRoute;
		$this->loginRouteParams = $loginRouteParams;
    }

    /**
     *
     * @access public
     * @param Request $request, TokenInterface $token
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($this->enableShield) {
            // Get the attempted username.
			if ($request->request->has('_username')) {
				$username = $request->request->get('_username');				
			} else {
				$username = '';
			}
			
            // Get our visitors Session & IP address.
            $session = $request->getSession();

            $ipAddress = $request->getClientIp();

            // Make a note of the failed login.
            $this->loginFailureTracker->addAttempt($session, $ipAddress, $username);

            $session->set(SecurityContext::AUTHENTICATION_ERROR, $exception);
        }

        if ($request->isXmlHttpRequest() || $request->request->get('_format') === 'json') {
            $response = new Response(
				json_encode(
					array(
						'status' => 'failed',
						'errors' => array($exception->getMessage())
					)
				)
			);
			
            $response->headers->set('Content-Type', 'application/json');
			
            return $response;
        } else {
            return new RedirectResponse(
				$this->router->generate(
	                $this->loginRoute,
	                $this->loginRouteParams
				)
			);
        }
    }
}