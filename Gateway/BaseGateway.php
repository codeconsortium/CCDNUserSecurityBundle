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

namespace CCDNUser\SecurityBundle\Gateway;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\QueryBuilder;

use CCDNUser\SecurityBundle\Gateway\BaseGatewayInterface;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 * @abstract
 */
abstract class BaseGateway implements BaseGatewayInterface
{
	/**
	 *
	 * @access protected
	 * @var \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
	 */
	protected $doctrine;

	/**
	 *
	 * @access protected
	 * @var \Doctrine\ORM\EntityManager $em
	 */		
	protected $em;
	
	/**
	 *
	 * @access public
	 * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
	 */
	public function __construct(Registry $doctrine)
	{
		$this->doctrine = $doctrine;
		
		$this->em = $doctrine->getEntityManager();		
	}

	/**
	 *
	 * @access public
	 * @return \Doctrine\ORM\QueryBuilder
	 */	
	public function getQueryBuilder()
	{
		return $this->em->createQueryBuilder();
	}
	
	/**
	 *
	 * @access public
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @param Array $parameters
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */	
	public function one(QueryBuilder $qb, $parameters = array())
	{
		if (count($parameters)) {
			$qb->setParameters($parameters);
		}
		
		try {
			return $qb->getQuery()->getSingleResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
	}
	
	/**
	 *
	 * @access public
	 * @param \Doctrine\ORM\QueryBuilder $qb
	 * @param Array $parameters
	 * @return \Doctrine\Common\Collections\ArrayCollection
	 */	
	public function all(QueryBuilder $qb, $parameters = array())
	{
		if (count($parameters)) {
			$qb->setParameters($parameters);
		}
		
		try {
			return $qb->getQuery()->getResult();
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;
		}
	}
	
	/**
	 *
	 * @access protected
	 * @param $item
	 * @return \CCDNUser\SecurityBundle\Gateway\BaseGatewayInterface
	 */
	protected function persist($item)
	{
		$this->em->persist($item);
		
		return $this;
	}
	
	/**
	 *
	 * @access protected
	 * @param $item
	 * @return \CCDNUser\SecurityBundle\Gateway\BaseGatewayInterface
	 */
	protected function remove($item)
	{
		$this->em->remove($item);
		
		return $this;
	}
	
	/**
	 *
	 * @access public
	 * @return \CCDNUser\SecurityBundle\Gateway\BaseGatewayInterface
	 */
	public function flush()
	{
		$this->em->flush();
		
		return $this;
	}
}