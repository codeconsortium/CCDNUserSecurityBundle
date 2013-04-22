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

use Symfony\Component\HttpFoundation\Session\Session;
use CCDNUser\SecurityBundle\Manager\SessionManager;

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
class LoginFailureTracker
{
    /**
     *
     * @access protected
     * @var \CCDNUser\SecurityBundle\Manager\SessionManager $sessionManager
     */
    protected $sessionManager;

    /**
     *
     * @access protected
     * @var int $blockForMinutes
     */
    protected $blockForMinutes;

    /**
     *
     * @access public
     * @param \CCDNUser\SecurityBundle\Manager\SessionManager $sessionManager
     * @param int                                             $blockForMinutes
     */
    public function __construct(SessionManager $sessionManager, $blockForMinutes)
    {
        $this->sessionManager = $sessionManager;
        $this->blockForMinutes = $blockForMinutes;
    }

    /**
     *
     * @access public
     * @param  \Symfony\Component\HttpFoundation\Session\Session $session
     * @param  string                                            $ipAddress
     * @return array
     */
    public function getAttempts(Session $session, $ipAddress)
    {
        // Set a limit on how far back we want to look at failed login attempts.
        $timeLimit = new \DateTime('-' . $this->blockForMinutes . ' minutes');

        // Only load from the db if the session is not found.
        if ($session->has('auth_failed')) {
            $attempts = $session->get('auth_failed');

            // Iterate over attempts and only reveal attempts that fall within the $timeLimit.
            $freshenedAttempts = array();

            $limit = $timeLimit->getTimestamp();

            foreach ($attempts as $attempt) {
                $date = $attempt->getLoginAttemptDate()->getTimestamp();

                if ($date > $limit) {
                    $freshenedAttempts[] = $attempt;
                }
            }

            $attempts = $freshenedAttempts;
        } else {
            $attempts = $this->sessionManager->findAllByIpAddressAndLoginAttemptDate($ipAddress, $timeLimit);
        }

        return $attempts;
    }

    /**
     *
     * @access public
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param string                                            $ipAddress
     * @param string                                            $username
     */
    public function addAttempt(Session $session, $ipAddress, $username)
    {
        // Make a note of the failed login.
        $this->sessionManager->newRecord($ipAddress, $username);

        // Set a limit on how far back we want to look at failed login attempts.
        $timeLimit = new \DateTime('-' . $this->blockForMinutes . ' minutes');

        // Update attempts list, because the loginFailureHandler will likely never get called now.
        $attempts = $this->sessionManager->findAllByIpAddressAndLoginAttemptDate($ipAddress, $timeLimit);

        $session->set('auth_failed', $attempts);
    }
}
