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

namespace CCDNUser\SecurityBundle\Component\Authorisation;

use Symfony\Component\DependencyInjection\ContainerInterface;
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
class SecurityManager
{
    /**
     *
     * @access protected
     * @var \Symfony\Component\HttpFoundation\Request $request
     */
    protected $request;
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
     * @access protected
     * @var array $forceAccountRecovery
     */
    protected $forceAccountRecovery;

    /**
     *
     * @access protected
     * @var array $blockPages
     */
    protected $blockPages;

	const ACCESS_ALLOWED = 0;
	const ACCESS_DENIED_DEFER = 1;
	const ACCESS_DENIED_BLOCK = 2;

    /**
     *
     * @access public
     * @param  \Symfony\Component\DependencyInjection\ContainerInterface                     $container
     * @param  \Symfony\Bundle\FrameworkBundle\Routing\Router                                $router
     * @param  \CCDNUser\SecurityBundle\Component\Authentication\Tracker\LoginFailureTracker $loginFailureTracker
     * @param  array                                                                         $routeLogin
     * @param  array                                                                         $forceAccountRecovery
     * @param  array                                                                         $blockPages
     */
    public function __construct(ContainerInterface $container, LoginFailureTracker $loginFailureTracker, $routeLogin, $forceAccountRecovery, $blockPages)
    {
		$this->request = $container->get('request');
		$this->loginFailureTracker = $loginFailureTracker;
		$this->routeLogin = $routeLogin;
		$this->forceAccountRecovery = $forceAccountRecovery;
		$this->blockPages = $blockPages;
    }

    /**
     * If you have failed to login too many times, a log of this will be present
     * in your session and the databse (incase session is dropped the record remains).
     *
     * @access public
     * @return int
     */
    public function vote()
    {
		if ($this->forceAccountRecovery['enabled'] || $this->blockPages['enabled']) {
            $route = $this->request->get('_route');
			$ipAddress = $this->request->getClientIp();

			$this->blockPages['routes'][] = $this->routeLogin['name'];
			if ($this->blockPages['enabled'] && in_array($route, $this->blockPages['routes'])) {
	            // Get number of failed login attempts.
	            $attempts = $this->loginFailureTracker->getAttempts($ipAddress, $this->blockPages['duration_in_minutes']);

	            if (count($attempts) >= $this->blockPages['after_attempts']) {
					// You have too many failed login attempts, login access is temporarily blocked.
					return self::ACCESS_DENIED_BLOCK;
				}
			}
			
			$this->forceAccountRecovery['routes'][] = $this->routeLogin['name'];
			if ($this->forceAccountRecovery['enabled'] && in_array($route, $this->forceAccountRecovery['routes'])) {
	            // Get number of failed login attempts.
	            $attempts = $this->loginFailureTracker->getAttempts($ipAddress, $this->forceAccountRecovery['duration_in_minutes']);

	            if (count($attempts) >= $this->forceAccountRecovery['after_attempts']) {
					// You have too many failed login attempts, login access is temporarily blocked, go recover your account.
                    return self::ACCESS_DENIED_DEFER;
				}
			}
		}
		
        return self::ACCESS_ALLOWED;
    }
}
