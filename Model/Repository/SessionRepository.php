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

namespace CCDNUser\SecurityBundle\Model\Repository;

/**
 *
 * SessionRepository
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
class SessionRepository extends BaseRepository implements RepositoryInterface
{
    /**
     *
     * @access public
     * @param  string                                       $ipAddress
     * @param  string                                       $timeLimit
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllByIpAddressAndLoginAttemptDate($ipAddress, $timeLimit)
    {
        $qb = $this->createSelectQuery(array('s'));

        $params = array('1' => $ipAddress, '2' => $timeLimit);

        $qb
            ->where(
                $qb->expr()->andx(
                    $qb->expr()->eq('s.ipAddress', '?1'),
                    $qb->expr()->gt('s.loginAttemptDate', '?2')
                )
            )
        ;

        return $this->gateway->findSessions($qb, $params);
    }
}
