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
 * @category CCDNUser
 * @package  SecurityBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNUserSecurityBundle
 *
 */
class RouteRefererIgnoreChain
{
    /**
     *
     * @access private
     * @var array $chain
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
     * @param array $list
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

        foreach ($this->chain as $object) {
            $ignore = array_merge($ignore, $object->getRoutes());
        }

        return $ignore;
    }
}
