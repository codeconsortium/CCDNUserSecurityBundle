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

namespace CCDNUser\SecurityBundle\Model\Model;

use CCDNUser\SecurityBundle\Model\Manager\ManagerInterface;
use CCDNUser\SecurityBundle\Model\Repository\RepositoryInterface;

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
 * @abstract
 */
interface ModelInterface
{
    /**
     *
     * @access public
     * @param \CCDNUser\SecurityBundle\Model\Repository\RepositoryInterface $repository
     * @param \CCDNUser\SecurityBundle\Model\Manager\ManagerInterface       $manager
     */
    public function __construct(RepositoryInterface $repository, ManagerInterface $manager);

    /**
     *
     * @access public
     * @return \CCDNUser\SecurityBundle\Model\Repository\RepositoryInterface
     */
    public function getRepository();

    /**
     *
     * @access public
     * @return \CCDNUser\SecurityBundle\Model\Manager\ManagerInterface
     */
    public function getManager();
}
