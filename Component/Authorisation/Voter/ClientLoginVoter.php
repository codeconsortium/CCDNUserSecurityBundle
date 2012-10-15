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
	 * @access public
	 * @param ContainerInterface $container, array $blackListedIp
	 */
    public function __construct(ContainerInterface $container, array $blacklistedIp = array())
    {
        $this->container     = $container;
        $this->blacklistedIp = $blacklistedIp;
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
	 * @param TokenInterface $token, object $object, array $attributes
	 * @return int
	 */
    public function vote(TokenInterface $token, $object, array $attributes)
    {

        if ($this->container->getParameter('ccdn_user_security.login_shield.enable_shield')) {
            $request = $this->container->get('request');

            $route = $request->get('_route');

            $blockRoutes = $this->container->getParameter('ccdn_user_security.login_shield.block_routes_when_denied');

            // Abort if the route is not a login route.
            if ( ! in_array($route, $blockRoutes)) {
                return VoterInterface::ACCESS_ABSTAIN;
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

            $attemptLimitReturnHttp500 = $this->container->getParameter('ccdn_user_security.login_shield.limit_failed_login_attempts.before_return_http_500');

            if (count($attempts) > $attemptLimitReturnHttp500) {
                return VoterInterface::ACCESS_DENIED;
            }
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }

}
