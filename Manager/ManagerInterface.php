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

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
interface ManagerInterface
{

	/**
	 *
	 * @access public
	 * @param $doctrine
	 */
    public function __construct($doctrine);

	/**
	 *
	 * @access public
	 * @param $entity
	 * @return $this
	 */
    public function persist($entity);

	/**
	 *
	 * @access public
	 * @param $entity
	 * @return $this
	 */
    public function remove($entity);

	/**
	 *
	 * @access public
	 * @return $this
	 */
    public function flush();

	/**
	 *
	 * @access public
	 * @param $entity
	 * @return $this
	 */
    public function refresh($entity);

}
