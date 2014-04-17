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

namespace CCDNUser\SecurityBundle\Component\Listener;

interface AccessDeniedExceptionFactoryInterface
{
    /**
     * Create exception thrown when a ip is blocked
     *
     * @return \Exception
     */
    public function createAccessDeniedException();
}
