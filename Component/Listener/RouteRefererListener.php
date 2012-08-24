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

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class RouteRefererListener
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
     * Log all routes (except login/logout/registration etc) so that you can be
     * Redirected back to your original location once you login successfully.
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
        $logIgnore = $this->container->getParameter('ccdn_user_security.route_referer.route_ignore_list');

        // Abort if the route is ignorable.
        foreach ($logIgnore as $ignore) {
            if ($route == $ignore['route']) { return; }
        }

        // Check for any internal routes.
        if ($route[0] == '_') { return; }

        // Get the session and assign it the url we are at presently.
        $session = $request->getSession();

		$script = ($request->getScriptName() == $request->getBasePath() . '/app_dev.php') ? $request->getScriptName() : $request->getBasePath();

        $session->set('referer', $script . $request->getPathInfo());

        return;
    }

}
