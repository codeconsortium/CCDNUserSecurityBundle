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

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\SecurityContext;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\QueryBuilder;

use CCDNUser\SecurityBundle\Manager\BaseManagerInterface;
use CCDNUser\SecurityBundle\Gateway\BaseGatewayInterface;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
interface BaseManagerInterface
{
	/**
	 *
	 * @access public
	 * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
	 * @param \CCDNUser\SecurityBundle\Gateway\BaseGatewayInterface $gateway
	 */
    public function __construct(Registry $doctrine, BaseGatewayInterface $gateway);

	/**
	 *
	 * @access public
	 * @return \CCDNUser\SecurityBundle\Gateway\BaseGatewayInterface
	 */
	public function getGateway();

	/**
	 *
	 * @access public
	 * @return \Doctrine\ORM\QueryBuilder
	 */	
	public function getQueryBuilder();
	
	/**
	 *
	 * @access public
	 * @param string $column = null
	 * @param Array $aliases = null
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */	
	public function createCountQuery($column = null, Array $aliases = null);
		
	/**
	 *
	 * @access public
	 * @param Array $aliases = null
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */	
	public function createSelectQuery(Array $aliases = null);
	
	/**
	 *
	 * @access public
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */	
	public function one(QueryBuilder $qb);
	
	/**
	 *
	 * @access public
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @return \Doctrine\ORM\QueryBuilder
	 */	
	public function all(QueryBuilder $qb);
	
	/**
	 *
	 * @access public
	 * @param $entity
	 * @return \CCDNUser\SecurityBundle\Manager\BaseManagerInterface
	 */
    public function persist($entity);

	/**
	 *
	 * @access public
	 * @param $entity
	 * @return \CCDNUser\SecurityBundle\Manager\BaseManagerInterface
	 */
    public function remove($entity);

	/**
	 *
	 * @access public
	 * @return \CCDNUser\SecurityBundle\Manager\BaseManagerInterface
	 */
    public function flush();

	/**
	 *
	 * @access public
	 * @param $entity
	 * @return \CCDNUser\SecurityBundle\Manager\BaseManagerInterface
	 */
    public function refresh($entity);
}