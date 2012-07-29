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

namespace CCDNUser\SecurityBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 * 
 * @author Reece Fowell <reece@codeconsortium.com> 
 * @version 1.0
 */
class Configuration implements ConfigurationInterface
{
	
	
	
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ccdn_user_security');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
    	$rootNode;
			
		$this->addDoNotLogRouteSection($rootNode);
		$this->addBruteForceLoginPreventionSection($rootNode);
		
        return $treeBuilder;
    }

	

	/**
	 *
	 * @access private
	 * @param ArrayNodeDefinition $node
	 */
	private function addDoNotLogRouteSection(ArrayNodeDefinition $node)
	{
	
		$node
			->addDefaultsIfNotSet()
			->canBeUnset()
			->children()
				->arrayNode('do_not_log_route')
					->prototype('array')
						->addDefaultsIfNotSet()
						->children()
							->scalarNode('bundle')->end()
							->scalarNode('route')->isRequired()->end()
							->scalarNode('path')->defaultNull()->end()
						->end()
						//->defaultValue(array('defaults' => array(
						//	array('bundle' => 'fosuserbundle', 'route' => 'fos_user_security_login'),
						//	array('bundle' => 'fosuserbundle', 'route' => 'fos_user_security_check'),
						//	array('bundle' => 'fosuserbundle', 'route' => 'fos_user_security_logout'),
						//	array('bundle' => 'fosuserbundle', 'route' => 'fos_user_registration_register'),
						//	array('bundle' => 'fosuserbundle', 'route' => 'fos_user_registration_check_email'),
						//	array('bundle' => 'fosuserbundle', 'route' => 'fos_user_registration_confirm'),
						//	array('bundle' => 'fosuserbundle', 'route' => 'fos_user_registration_confirmed'),
						//)))->end()
					->end()
				->end()
			->end();

	}
	
	

	/**
	 *
	 * @access private
	 * @param ArrayNodeDefinition $node
	 */
	private function addBruteForceLoginPreventionSection(ArrayNodeDefinition $node)
	{
	
		$node
			->addDefaultsIfNotSet()
			->children()
				->arrayNode('brute_force_login_prevention')
					->addDefaultsIfNotSet()
					->children()
						->scalarNode('enable_protection')->defaultValue(true)->end()
						->scalarNode('login_attempts')->defaultValue(25)->end()
						->scalarNode('block_in_minutes')->defaultValue(10)->end()
					->end()
				->end()
			->end();
					
	}
	
}
