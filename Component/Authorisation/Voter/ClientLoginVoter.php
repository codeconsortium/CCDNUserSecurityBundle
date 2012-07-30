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

class ClientLoginVoter implements VoterInterface
{
	
	
	
    public function __construct(ContainerInterface $container, array $blacklistedIp = array())
    {

        $this->container     = $container;

        $this->blacklistedIp = $blacklistedIp;

    }



    public function supportsAttribute($attribute)
    {

        // we won't check against a user attribute, so we return true
        return true;
    }



    public function supportsClass($class)
    {

        // our voter supports all type of token classes, so we return true
        return true;
    }



    function vote(TokenInterface $token, $object, array $attributes)
    {
        $request = $this->container->get('request');
		
		$route = $request->get('_route');
		
		$loginRoutes = $this->container->getParameter('ccdn_user_security.login_shield.login_routes');
				
		// Abort if the route is not a login route.
		if ( ! in_array($route, $loginRoutes)) {
			return;
		}

		// Set a limit on how far back we want to look at failed login attempts.
		$blockInMinutes = $this->container->getParameter('ccdn_user_security.login_shield.minutes_blocked_for');
		
		$timeLimit = new \DateTime('-' . $blockInMinutes . ' minutes');
		
		// Get session and check if it has any entries of failed logins.
		$session = $request->getSession();
		
		// Only load from the db if the session is not found.
		if ($session->has('auth_failed')) {
			$attempts = $session->get('auth_failed');
			
			// Iterate over attempts and only reveal attempts that fall within the $timeLimit.
			
		} else {
			$ipAddress = $request->getClientIp();
			
			$attempts = $this->container->get('ccdn_user_security.session.repository')->findByIpAddress($ipAddress, $timeLimit);				
		}

		$attemptLimitRecover = $this->container->getParameter('ccdn_user_security.login_shield.login_fail_limit_recover_account');
		$attemptLimitReturn500 = $this->container->getParameter('ccdn_user_security.login_shield.login_fail_limit_http_500');
		
		if (count($attempts) > $attemptLimitRecover)
		{
			if (count($attempts) > $attemptLimitReturn500)
			{
				return VoterInterface::ACCESS_DENIED;
			}
			
			return VoterInterface::ACCESS_DENIED;
		}
		
        return VoterInterface::ACCESS_ABSTAIN;
    }

}