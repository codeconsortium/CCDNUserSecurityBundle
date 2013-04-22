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

namespace CCDNUser\SecurityBundle\Entity;

class Session
{
    protected $id;

    /**
     * @var string $ipAddress
     */
    protected $ipAddress;

    /**
     * @var \Datetime $loginAttemptDate
     */
    protected $loginAttemptDate;

    /**
     * @var string $loginAttemptUsername
     */
    protected $loginAttemptUsername;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * Get ipAddress
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set loginAttemptDate
     *
     * @param integer $loginAttemptDate
     */
    public function setLoginAttemptDate($loginAttemptDate)
    {
        $this->loginAttemptDate = $loginAttemptDate;
    }

    /**
     * Get loginAttemptDate
     *
     * @return integer
     */
    public function getLoginAttemptDate()
    {
        return $this->loginAttemptDate;
    }

    /**
     * Set loginUsername
     *
     * @param string $loginUsername
     */
    public function setLoginAttemptUsername($loginAttemptUsername)
    {
        $this->loginAttemptUsername = $loginAttemptUsername;
    }

    /**
     * Get loginUsername
     *
     * @return string
     */
    public function getLoginAttemptUsername()
    {
        return $this->loginAttemptUsername;
    }
}
