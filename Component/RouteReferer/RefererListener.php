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

namespace CCDNUser\SecurityBundle\Component\RouteReferer;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class RefererListener
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
	 * @param $router, $container
	 */
	public function __construct($container, $router)
	{

		$this->container = $container;		

		$this->router = $router;
	}



	/**
	 *
	 * @access public
	 * @param GetResponseEvent $event
	 */
    public function onKernelRequest(GetResponseEvent $event)
    {

		if ($event->getRequestType() !== \Symfony\Component\HttpKernel\HttpKernel::MASTER_REQUEST) {
			return;
		}

		$request = $event->getRequest();
		$route = $request->get('_route');

		//echo $request->getSession()->get('referer'); die();

		if ($route !== 'fos_user_security_login'
		&& $route !== 'fos_user_security_check'
		&& $route !== 'fos_user_security_logout'
		&& $route !== 'fos_user_registration_register'
		&& $route !== 'fos_user_registration_check_email'
		&& $route !== 'fos_user_registration_confirm'
		&& $route !== 'fos_user_registration_confirmed'		
		&& $route !== 'cc_message_action_bulk'
		&& $route[0] !== '_') // last one checks incase of some of SF2 internal routes
		{
			$session = $request->getSession();
			
			//echo $request->getBasePath() . $request->getPathInfo(); die();
			
			$session->set('referer', $request->getBasePath() . $request->getPathInfo());			
			
			
		}
    }

}