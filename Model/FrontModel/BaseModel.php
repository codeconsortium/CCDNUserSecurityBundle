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

use CCDNUser\SecurityBundle\Model\Component\Manager\ManagerInterface;
use CCDNUser\SecurityBundle\Model\Component\Repository\RepositoryInterface;

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
abstract class BaseModel
{
    /**
     *
     * @access protected
     * @var \CCDNUser\SecurityBundle\Model\Component\Repository\RepositoryInterface
     */
    protected $repository;

    /**
     *
     * @access protected
     * @var \CCDNUser\SecurityBundle\Model\Component\Manager\ManagerInterface
     */
    protected $manager;

    /**
     *
     * @access public
     * @param \CCDNUser\SecurityBundle\Model\Component\Repository\RepositoryInterface $repository
     * @param \CCDNUser\SecurityBundle\Model\Component\Manager\ManagerInterface       $manager
     */
    public function __construct(RepositoryInterface $repository, ManagerInterface $manager)
    {
        $repository->setModel($this);
        $this->repository = $repository;

        $manager->setModel($this);
        $this->manager = $manager;
    }

    /**
     *
     * @access public
     * @return \CCDNUser\SecurityBundle\Model\Component\Repository\RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     *
     * @access public
     * @return \CCDNUser\SecurityBundle\Model\Component\Manager\ManagerInterface
     */
    public function getManager()
    {
        return $this->manager;
    }
}
