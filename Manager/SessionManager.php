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

use CCDNUser\SecurityBundle\Manager\BaseManagerInterface;
use CCDNUser\SecurityBundle\Manager\BaseManager;

use CCDNUser\SecurityBundle\Entity\Session;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class SessionManager extends BaseManager implements BaseManagerInterface
{
    /**
     *
     * @access public
     * @param string $ipAddress
	 * @param string $timeLimit
     * @return \CCDNUser\SecurityBundle\Manager\SessionManager
     */
	public function findAllByIpAddressAndLoginAttemptDate($ipAddress, $timeLimit)
	{
        $qb = $this->createSelectQuery(array('s'));

		$params = array('1' => $ipAddress, '2' => $timeLimit);
		
		$qb
            ->where($qb->expr()->andx(
                $qb->expr()->eq('s.ipAddress', '?1'),
                $qb->expr()->gt('s.loginAttemptDate', '?2'))
			)
		;

		return $this->gateway->findSessions($qb, $params);
	}
	
    /**
     *
     * @access public
     * @param string $ipAddress, string $username
     * @return self
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