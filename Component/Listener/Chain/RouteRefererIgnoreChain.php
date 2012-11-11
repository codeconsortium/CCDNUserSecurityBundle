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

namespace CCDNUser\SecurityBundle\Component\Listener\Chain;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class RouteRefererIgnoreChain
{

    /**
     *
	 * @access private
     */
    private $chain;

	/**
	 *
	 * @access public
	 */
    public function __construct()
    {
        $this->chain = array();
    }

    /**
     *
 	 * @access public
	 * @param array $ignore
     */
    public function addRoutesToIgnore($list)
    {
		$this->chain[] = $list;
    }

    /**
     *
 	 * @access public
	 * @return mixed[]
     */
    public function getRoutes()
    {
		$ignore = array();
		
		foreach($this->chain as $object)
		{
			$ignore = array_merge($ignore, $object->getRoutes());
		}
		
		return $ignore;
    }

}
