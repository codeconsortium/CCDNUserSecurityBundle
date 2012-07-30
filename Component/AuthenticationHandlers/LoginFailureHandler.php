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

namespace CCDNUser\SecurityBundle\Component\AuthenticationHandlers;

use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
//use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
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
		
		if ($this->container->getParameter('ccdn_user_security.brute_force_login_shield.enable_protection'))
		{

			$session = $request->getSession();

			// Get our visitors IP address.
			$ipAddress = $request->getClientIp();

			// Get the attempted username.
			$token = $exception->getExtraInformation();
			
			if (preg_match('/user="(?:[a-zA-Z0-9_]*)"/', $token, $tokenUsername))
			{
				if ($tokenUsername[0] !== '' || $tokenUsername[0] !== null)
				{
					$username = substr($tokenUsername[0], 6, -1);
				} else {
					$username = '';
				}
			} else {
				$username = '';
			}

			// Make a note of the failed login.
			$this->container->get('ccdn_user_security.session.manager')->newRecord($ipAddress, $username);		

			// Set a limit on how far back we want to look at failed login attempts.
			$blockInMinutes = $this->container->getParameter('ccdn_user_security.brute_force_login_shield.block_in_minutes');
			
			$timeLimit = new \DateTime('-' . $blockInMinutes . ' minutes ago');

			// Get the failed login attempts matching our visitors IP.
			$attempts = $this->container->get('ccdn_user_security.session.repository')->findByIpAddress($ipAddress, $timeLimit);				
						
			$session->set('auth_failed', $attempts);
		}
		
	}
	
}