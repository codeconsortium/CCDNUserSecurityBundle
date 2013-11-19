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

namespace CCDNUser\SecurityBundle\Tests\Manager;

use CCDNUser\SecurityBundle\Tests\TestBase;

/**
 *
 * @category CCDNUser
 * @package  SecurityBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 1.0
 * @link     https://github.com/codeconsortium/CCDNUserSecurityBundle
 *
 */
class SessionManagerTest extends TestBase
{
	public function testNewRecord()
	{
		$users = $this->addFixturesForUsers();
		
		$ipAddress = '127.0.0.1';
		$this->getSessionModel()->newRecord($ipAddress, 'tom');
		
		$timeLimit = new \DateTime('-' . 1 . ' minutes');
		$sessions = $this->getSessionModel()->findAllByIpAddressAndLoginAttemptDate($ipAddress, $timeLimit);

		$this->assertCount(1, $sessions);
		$this->assertInstanceOf('CCDNUser\SecurityBundle\Entity\Session', $sessions[0]);
	}
}