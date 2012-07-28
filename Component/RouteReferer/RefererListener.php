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
	
		// Abort if we are dealing with some symfony2 internal requests.
		if ($event->getRequestType() !== \Symfony\Component\HttpKernel\HttpKernel::MASTER_REQUEST) {
			return;
		}

		// Get the route from the request object.
		$request = $event->getRequest();
		
		$route = $request->get('_route');

		// Get the list of routes we must ignore.
		$logIgnore = $this->container->getParameter('ccdn_user_security.do_not_log_route');
			
		// Abort if the route is ignorable.
		foreach($logIgnore as $ignore) {
			if ($route == $ignore['route']) { return; }
		}
		
		// Check for any internal routes.
		if ($route[0] == '_') { return; }
		
		// Get the session and assign it the url we are at presently.
		$session = $request->getSession();
		
		$session->set('referer', $request->getBasePath() . $request->getPathInfo());			
		//echo $request->getBasePath() . $request->getPathInfo(); die();

    }

}