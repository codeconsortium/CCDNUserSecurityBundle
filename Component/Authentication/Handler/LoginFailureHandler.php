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
use Symfony\Bundle\FrameworkBundle\Routing\Router;

use CCDNUser\SecurityBundle\Component\Authentication\Tracker\LoginFailureTracker;

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
class LoginFailureHandler implements AuthenticationFailureHandlerInterface
{
    /**
     *
     * @access protected
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     */
    protected $router;

    /**
     *
     * @access protected
     * @var \CCDNUser\SecurityBundle\Component\Authentication\Tracker\LoginFailureTracker $loginFailureTracker
     */
    protected $loginFailureTracker;

    /**
     *
     * @access protected
     * @var array $routeLogin
     */
    protected $routeLogin;

    /**
     *
     * @access public
     * @param  \Symfony\Bundle\FrameworkBundle\Routing\Router                                $router
     * @param  \CCDNUser\SecurityBundle\Component\Authentication\Tracker\LoginFailureTracker $loginFailureTracker
     * @param  array                                                                         $routeLogin
     */
    public function __construct(Router $router, LoginFailureTracker $loginFailureTracker, $routeLogin)
    {
        $this->router = $router;
        $this->loginFailureTracker = $loginFailureTracker;
        $this->routeLogin = $routeLogin;
    }

    /**
     *
     * @access public
     * @param  \Symfony\Component\HttpFoundation\Request                                                     $request
     * @param  \Symfony\Component\Security\Core\Exception\AuthenticationException                            $exception
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // Get the visitors IP address and attempted username.
        $ipAddress = $request->getClientIp();
        if ($request->request->has('_username')) {
            $username = $request->request->get('_username');
        } else {
            $username = '';
        }

        // Make a note of the failed login.
        $this->loginFailureTracker->addAttempt($ipAddress, $username);
        $request->getSession()->set(SecurityContext::AUTHENTICATION_ERROR, $exception);

		// Send response back to browser depending on wether this is XML request or not.
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
        } else {
            $response = new RedirectResponse(
                $this->router->generate(
                    $this->routeLogin['name'],
                    $this->routeLogin['params']
                )
            );
        }
		
        return $response;
    }
}
