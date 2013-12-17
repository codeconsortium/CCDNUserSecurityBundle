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

namespace CCDNUser\SecurityBundle\Model\Component\Manager;

use CCDNUser\SecurityBundle\Model\Component\Manager\ManagerInterface;
use CCDNUser\SecurityBundle\Model\Component\Manager\BaseManager;

use CCDNUser\SecurityBundle\Entity\Session;

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
class SessionManager extends BaseManager implements ManagerInterface
{
    /**
     *
     * @access public
     * @param  string                                                          $ipAddress
     * @param  string                                                          $username
     * @return \CCDNUser\SecurityBundle\Model\Component\Manager\SessionManager
     */
    public function newRecord($ipAddress, $username)
    {
        $session = new Session();

        $session->setIpAddress($ipAddress);
        $session->setLoginAttemptUsername($username);
        $session->setLoginAttemptDate(new \DateTime('now'));

        $this
            ->persist($session)
            ->flush()
        ;

        return $this;
    }
}
