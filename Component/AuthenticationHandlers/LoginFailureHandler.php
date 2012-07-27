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

	}
	
}