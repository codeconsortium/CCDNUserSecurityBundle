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

namespace CCDNUser\SecurityBundle\Manager;

use CCDNUser\SecurityBundle\Entity\Session;

use CCDNUser\SecurityBundle\Manager\ManagerInterface;
use CCDNUser\SecurityBundle\Manager\BaseManager;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class SessionManager extends BaseManager implements ManagerInterface
{

    /**
     *
     * @access public
     * @param String $ipAddress, String $username
     * @return $this
     */
    public function newRecord($ipAddress, $username)
    {

        $session = new Session();

        $session->setIpAddress($ipAddress);
        $session->setLoginAttemptUsername($username);
        $session->setLoginAttemptDate(new \DateTime('now'));

        $this->persist($session);

        $this->flush();

        return $this;
    }

}
