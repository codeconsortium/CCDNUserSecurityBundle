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

namespace CCDNUser\SecurityBundle\Component\Authorisation\Voter;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Symfony\Component\HttpFoundation\Request;

class ClientLoginVoter implements VoterInterface
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
	protected $limitBeforeHttp500;
	
	/**
	 *
	 * @access public
	 * @param $request
	 * @param $loginFailureTracker
	 * @param $enableShield
	 * @param $blockRoutes
	 * @param $blockForMinutes
	 * @param $limitBeforeHttp500
	 */
    public function __construct(ContainerInterface $container, $loginFailureTracker, $enableShield, $blockRoutes, $blockForMinutes, $limitBeforeHttp500)
    {
		$this->container = $container;
		$this->loginFailureTracker = $loginFailureTracker;
		$this->enableShield = $enableShield;
		$this->blockRoutes = $blockRoutes;
		$this->blockForMinutes = $blockForMinutes;
		$this->limitBeforeHttp500 = $limitBeforeHttp500;
    }

	/**
	 *
	 * @access public
	 * @param $attribute
 	 * @return bool
	 */
    public function supportsAttribute($attribute)
    {
        // we won't check against a user attribute, so we return true
        return true;
    }


	/**
	 *
	 * @access public
	 * @param $class
	 * @return bool
	 */
    public function supportsClass($class)
    {
        // our voter supports all type of token classes, so we return true
        return true;
    }

	/**
	 *
	 * @access public
	 * @param TokenInterface $token
	 * @param object $object
	 * @param array $attributes
	 * @return int
	 */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if ($this->enableShield) {
			$request = $this->container->get('request');
			
            $route = $request->get('_route');

            // Abort if the route is not a login route.
            if ( ! in_array($route, $this->blockRoutes)) {
                return VoterInterface::ACCESS_ABSTAIN;
            }

            // Set a limit on how far back we want to look at failed login attempts.
            $timeLimit = new \DateTime('-' . $this->blockForMinutes . ' minutes');

            // Get session and check if it has any entries of failed logins.
            $session = $request->getSession();

            $ipAddress = $request->getClientIp();

            // Get number of failed login attempts.
            $attempts = $this->loginFailureTracker->getAttempts($session, $ipAddress);

            if (count($attempts) > $this->limitBeforeHttp500) {
                return VoterInterface::ACCESS_DENIED;
            }
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }
}