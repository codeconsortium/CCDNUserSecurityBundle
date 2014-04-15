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

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
class LogoutSuccessHandler implements LogoutSuccessHandlerInterface
{
    /**
     *
     * @access protected
     * @var \Symfony\Component\Routing\RouterInterface $router
     */
    protected $router;

    /**
     *
     * @param array $routeReferer
     */
    protected $routeReferer;

    /**
     *
     * @param array $routeLogin
     */
    protected $routeLogin;

    /**
     *
     * @access public
     * @param \Symfony\Component\Routing\RouterInterface $router
     * @param array                                      $routeReferer
     * @param array                                      $routeLogin
     */
    public function __construct(RouterInterface $router, $routeReferer, $routeLogin)
    {
        $this->router = $router;
        $this->routeReferer = $routeReferer;
        $this->routeLogin = $routeLogin;
    }

    /**
     *
     * @access public
     * @param  \Symfony\Component\HttpFoundation\Request                                                     $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function onLogoutSuccess(Request $request)
    {
        if ($this->routeReferer['enabled']) {
            $session = $request->getSession();

            if ($session->has('referer')) {
                if ($session->get('referer') !== null && $session->get('referer') !== '') {
                    $response = new RedirectResponse($session->get('referer'));
                } else {
                    $response = new RedirectResponse($request->getBasePath() . '/');
                }
            } else {
                // if no referer then go to homepage
                $response = new RedirectResponse($request->getBasePath() . '/');
            }

            if ($request->isXmlHttpRequest() || $request->request->get('_format') === 'json') {
                $response = new Response(json_encode(array('status' => 'success')));
                $response->headers->set('Content-Type', 'application/json');
            }
        } else {
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
        }

        return $response;
    }
}
