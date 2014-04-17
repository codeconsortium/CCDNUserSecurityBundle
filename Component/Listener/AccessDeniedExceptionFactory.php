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

use Symfony\Component\HttpKernel\Exception\HttpException;

class AccessDeniedExceptionFactory implements AccessDeniedExceptionFactoryInterface
{
    public function createAccessDeniedException()
    {
        return new HttpException(500, 'flood control - login blocked');
    }
}
