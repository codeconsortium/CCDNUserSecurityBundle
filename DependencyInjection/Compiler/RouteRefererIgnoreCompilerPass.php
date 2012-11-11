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

namespace CCDNUser\SecurityBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 *
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class RouteRefererIgnoreCompilerPass implements CompilerPassInterface
{

    /**
     *
     * @access public
 	 * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('ccdn_user_security.component.route_referer_ignore.chain')) {
            return;
        }

        $definition = $container->getDefinition('ccdn_user_security.component.route_referer_ignore.chain');

        foreach ($container->findTaggedServiceIds('ccdn_user_security.route_referer_ignore') as $id => $attributes) {
            $definition->addMethodCall('addRoutesToIgnore', array(new Reference($id)));
        }
    }

}
