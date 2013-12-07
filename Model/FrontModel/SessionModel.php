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

namespace CCDNUser\SecurityBundle\Model\FrontModel;

use CCDNUser\SecurityBundle\Model\FrontModel\BaseModel;
use CCDNUser\SecurityBundle\Model\FrontModel\ModelInterface;

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
class SessionModel extends BaseModel implements ModelInterface
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
		return $this->getRepository()->findAllByIpAddressAndLoginAttemptDate($ipAddress, $timeLimit);
	}

    /**
     *
     * @access public
     * @param  string                                            $ipAddress
	 * @param  string                                            $username
     * @return \CCDNUser\SecurityBundle\Model\FrontModel\SessionModel
     */
    public function newRecord($ipAddress, $username)
	{
		return $this->getManager()->newRecord($ipAddress, $username);
	}
}
