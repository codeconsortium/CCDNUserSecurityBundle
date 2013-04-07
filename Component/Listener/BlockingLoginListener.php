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

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
    protected $router;
	
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
    protected $blockRoutes;
	
    /**
     *
     * @access protected
     */
    protected $blockForMinutes;
	
    /**
     *
     * @access protected
     */
    protected $limitBeforeRecover;
	
    /**
     *
     * @access protected
     */
    protected $limitBeforeHttp500;
	
    /**
     *
     * @access protected
     */
    protected $recoverRoute;
	
    /**
     *
     * @access protected
     */
    protected $recoverRouteParams;
	
    /**
     *
     * @access protected
     */
    protected $loginRoute;
	
    /**
     *
     * @access public
     * @param $container, $router
     */
    public function __construct($router, $loginFailureTracker, $enableShield, $blockRoutes, $blockForMinutes, $limitBeforeRecoverAccount, $limitBeforeHttp500, $recoverRoute, $recoverRouteParams, $loginRoute)
    {
        $this->router = $router;
		$this->loginFailureTracker = $loginFailureTracker;
		$this->enableShield = $enableShield;
		$this->blockRoutes = $blockRoutes;
		$this->blockForMinutes = $blockForMinutes;
		$this->limitBeforeRecoverAccount = $limitBeforeRecoverAccount;
		$this->limitBeforeHttp500 = $limitBeforeHttp500;
		$this->recoverRoute = $recoverRoute;
		$this->recoverRouteParams = $recoverRouteParams;
		$this->loginRoute = $loginRoute;		
    }

    /**
     * If you have failed to login too many times, a log of this will be present
     * in your session and the databse (incase session is dropped the record remains).
     *
     * @access public
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($this->enableShield) {
            // Abort if we are dealing with some symfony2 internal requests.
            if ($event->getRequestType() !== \Symfony\Component\HttpKernel\HttpKernel::MASTER_REQUEST) {
                return;
            }

            // Get the route from the request object.
			$request = $event->getRequest();

            $route = $request->get('_route');

            // Abort if the route is not a login route.
            if ( ! in_array($route, $this->blockRoutes)) {
                return;
            }

            // Set a limit on how far back we want to look at failed login attempts.
            $timeLimit = new \DateTime('-' . $this->blockForMinutes . ' minutes');

            // Get session and check if it has any entries of failed logins.
            $session = $request->getSession();

            $ipAddress = $request->getClientIp();

            // Get number of failed login attempts.
            $attempts = $this->loginFailureTracker->getAttempts($session, $ipAddress);

            if (count($attempts) > ($this->limitBeforeRecoverAccount -1)) {
                // Only continue incrementing if on the account recovery page
                // because the counter won't increase from the loginFailureHandler.
                if ($route == $this->loginRoute) {
                    $this->loginFailureTracker->addAttempt($session, $ipAddress, '');

                    $attempts = $this->loginFailureTracker->getAttempts($session, $ipAddress);
                }

                // Block the page when continuing to bypass the block.
                if (count($attempts) < ($this->limitBeforeHttp500 + 1)) {

                    $event->setResponse(new RedirectResponse($this->router->generate($this->recoverRouteName, $this->recoverRouteParams)));

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