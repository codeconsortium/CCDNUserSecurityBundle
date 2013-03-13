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

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{

    /**
     *
     * @access public
     */
    public function __construct()
    {

    }

    /**
     *
     * @access public
     * @param Request $request, TokenInterface $token
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $session = $request->getSession();

        if ($session->has('referer')) {
            if ($session->get('referer') !== null
            && $session->get('referer') !== '')
            {
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

        return $response;
    }

}
