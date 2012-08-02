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

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class BlockingLoginListener
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
	 * If you have failed to login too many times, a log of this will be present
	 * in your session and the databse (incase session is dropped the record remains).
	 *
	 * @access public
	 * @param GetResponseEvent $event
	 */
    public function onKernelRequest(GetResponseEvent $event)
    {
	
		if ($this->container->getParameter('ccdn_user_security.login_shield.enable_shield'))
		{
			// Abort if we are dealing with some symfony2 internal requests.
			if ($event->getRequestType() !== \Symfony\Component\HttpKernel\HttpKernel::MASTER_REQUEST) {
				return;
			}

			// Get the route from the request object.
	        $request = $this->container->get('request');

			$route = $request->get('_route');

			$blockRoutes = $this->container->getParameter('ccdn_user_security.login_shield.block_routes_when_denied');
		
			// Abort if the route is not a login route.
			if ( ! in_array($route, $blockRoutes)) {
				return;
			}

			// Set a limit on how far back we want to look at failed login attempts.
			$blockInMinutes = $this->container->getParameter('ccdn_user_security.login_shield.block_for_minutes');

			$timeLimit = new \DateTime('-' . $blockInMinutes . ' minutes');

			// Get session and check if it has any entries of failed logins.
			$session = $request->getSession();

			$ipAddress = $request->getClientIp();

			// Get number of failed login attempts.
			$tracker = $this->container->get('ccdn_user_security.component.authentication.tracker.login_failure_tracker');
			
			$attempts = $tracker->getAttempts($session, $ipAddress);

			$attemptLimitRecoverAccount = $this->container->getParameter('ccdn_user_security.login_shield.limit_failed_login_attempts.before_recover_account');
			$attemptLimitReturnHttp500 = $this->container->getParameter('ccdn_user_security.login_shield.limit_failed_login_attempts.before_return_http_500');

			if (count($attempts) > ($attemptLimitRecoverAccount -1))
			{	
				// Prepare a redirect
				$recoverAccountRouteName = $this->container->getParameter('ccdn_user_security.login_shield.recover_account_route.name');
				$recoverAccountRouteParams = $this->container->getParameter('ccdn_user_security.login_shield.recover_account_route.params');
				
				// Only continue incrementing if on the account recovery page
				// because the counter won't increase from the loginFailureHandler.
				$loginRouteName = $this->container->getParameter('ccdn_user_security.login_shield.primary_login_route.name');
				
				if ($route == $loginRouteName)	
				{
					$tracker->addAttempt($session, $ipAddress, '');

					$attempts = $tracker->getAttempts($session, $ipAddress);								
				}
				
				// Block the page when continuing to bypass the block.
				if (count($attempts) < ($attemptLimitReturnHttp500 + 1))
				{			
				
					$event->setResponse(new RedirectResponse($this->container->get('router')->generate($recoverAccountRouteName, $recoverAccountRouteParams)));
					
					return;
				}
				
				// In severe cases, block for a while.
				//	$this->container->get('kernel')->shutdown();
				
				throw new HttpException(500, 'flood control - login blocked');
			}
		}

		return;
    }

}