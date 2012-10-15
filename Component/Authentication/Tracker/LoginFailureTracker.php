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

namespace CCDNUser\SecurityBundle\Component\Authentication\Tracker;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class LoginFailureTracker
{

    /**
     *
     * @access protected
     */
    protected $container;

    /**
     *
     * @access public
     * @param $router, $container
     */
    public function __construct($container)
    {

        $this->container = $container;
    }

    /**
     *
     * @access public
     * @param $session, $ipAddress
     * @return array
     */
    public function getAttempts($session, $ipAddress)
    {

        // Set a limit on how far back we want to look at failed login attempts.
        $blockInMinutes = $this->container->getParameter('ccdn_user_security.login_shield.block_for_minutes');

        $timeLimit = new \DateTime('-' . $blockInMinutes . ' minutes');

        // Only load from the db if the session is not found.
        if ($session->has('auth_failed')) {
            $attempts = $session->get('auth_failed');

            // Iterate over attempts and only reveal attempts that fall within the $timeLimit.
            $freshenedAttempts = array();

            $limit = $timeLimit->getTimestamp();

            foreach ($attempts as $attempt) {
                if (array_key_exists('s_loginAttemptDate', $attempt)) {
                    $date = $attempt['s_loginAttemptDate']->getTimestamp();

                    if ($date > $limit) {
                        $freshenedAttempts[] = $attempt;
                    }
                }
            }

            $attempts = $freshenedAttempts;

        } else {

            $attempts = $this->container->get('ccdn_user_security.session.repository')->findByIpAddress($ipAddress, $timeLimit);
        }

        return $attempts;
    }

    /**
     *
     * @access public
     * @param Session $session, string $ipAddress, string $username
     */
    public function addAttempt($session, $ipAddress, $username)
    {

        // Make a note of the failed login.
        $this->container->get('ccdn_user_security.session.manager')->newRecord($ipAddress, $username);

        // Set a limit on how far back we want to look at failed login attempts.
        $blockInMinutes = $this->container->getParameter('ccdn_user_security.login_shield.block_for_minutes');

        $timeLimit = new \DateTime('-' . $blockInMinutes . ' minutes');

        // Update attempts list, because the loginFailureHandler will likely never get called now.
        $attempts = $this->container->get('ccdn_user_security.session.repository')->findByIpAddress($ipAddress, $timeLimit);

        $session->set('auth_failed', $attempts);

    }

}
