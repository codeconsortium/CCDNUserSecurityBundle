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

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
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
class Configuration implements ConfigurationInterface
{
    /**
     *
     * @access public
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ccdn_user_security');

        // Class file namespaces.
        $this->addEntitySection($rootNode);
        $this->addGatewaySection($rootNode);
        $this->addRepositorySection($rootNode);
        $this->addManagerSection($rootNode);
        $this->addModelSection($rootNode);
        $this->addComponentSection($rootNode);

        // Configuration stuff.
        $this->addRouteRefererSection($rootNode);
        $this->addLoginShieldSection($rootNode);

        return $treeBuilder;
    }

    /**
     *
     * @access private
     * @param  \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     * @return \CCDNUser\SecurityBundle\DependencyInjection\Configuration
     */
    private function addEntitySection(ArrayNodeDefinition $node)
    {
        $node
            ->isRequired()
            ->cannotBeEmpty()
            ->children()
                ->arrayNode('entity')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->children()
                        ->arrayNode('user')
		                    ->isRequired()
		                    ->cannotBeEmpty()
                            ->children()
                                ->scalarNode('class')
				                    ->isRequired()
				                    ->cannotBeEmpty()
								->end()
                            ->end()
                        ->end()
                        ->arrayNode('session')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNUser\SecurityBundle\Entity\Session')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     * @return \CCDNUser\SecurityBundle\DependencyInjection\Configuration
     */
    private function addGatewaySection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('gateway')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('session')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNUser\SecurityBundle\Model\Gateway\SessionGateway')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     * @return \CCDNUser\SecurityBundle\DependencyInjection\Configuration
     */
    private function addRepositorySection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('repository')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('session')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNUser\SecurityBundle\Model\Repository\SessionRepository')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     * @return \CCDNUser\SecurityBundle\DependencyInjection\Configuration
     */
    private function addManagerSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('manager')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('session')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNUser\SecurityBundle\Model\Manager\SessionManager')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     * @return \CCDNUser\SecurityBundle\DependencyInjection\Configuration
     */
    private function addModelSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('model')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('session')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('class')->defaultValue('CCDNUser\SecurityBundle\Model\Model\SessionModel')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     * @return \CCDNUser\SecurityBundle\DependencyInjection\Configuration
     */
    private function addComponentSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('component')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('authentication')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->arrayNode('handler')
                                    ->addDefaultsIfNotSet()
                                    ->canBeUnset()
                                    ->children()
                                        ->arrayNode('login_failure_handler')
                                            ->addDefaultsIfNotSet()
                                            ->canBeUnset()
                                            ->children()
                                                ->scalarNode('class')->defaultValue('CCDNUser\SecurityBundle\Component\Authentication\Handler\LoginFailureHandler')->end()
                                            ->end()
                                        ->end()
                                        ->arrayNode('login_success_handler')
                                            ->addDefaultsIfNotSet()
                                            ->canBeUnset()
                                            ->children()
                                                ->scalarNode('class')->defaultValue('CCDNUser\SecurityBundle\Component\Authentication\Handler\LoginSuccessHandler')->end()
                                            ->end()
                                        ->end()
                                        ->arrayNode('logout_success_handler')
                                            ->addDefaultsIfNotSet()
                                            ->canBeUnset()
                                            ->children()
                                                ->scalarNode('class')->defaultValue('CCDNUser\SecurityBundle\Component\Authentication\Handler\LogoutSuccessHandler')->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('tracker')
                                    ->addDefaultsIfNotSet()
                                    ->canBeUnset()
                                    ->children()
                                        ->arrayNode('login_failure_tracker')
                                            ->addDefaultsIfNotSet()
                                            ->canBeUnset()
                                            ->children()
                                                ->scalarNode('class')->defaultValue('CCDNUser\SecurityBundle\Component\Authentication\Tracker\LoginFailureTracker')->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('authorisation')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->arrayNode('voter')
                                    ->addDefaultsIfNotSet()
                                    ->canBeUnset()
                                    ->children()
                                        ->arrayNode('client_login_voter')
                                            ->addDefaultsIfNotSet()
                                            ->canBeUnset()
                                            ->children()
                                                ->scalarNode('class')->defaultValue('CCDNUser\SecurityBundle\Component\Authorisation\Voter\ClientLoginVoter')->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('listener')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->arrayNode('route_referer_listener')
                                    ->addDefaultsIfNotSet()
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('class')->defaultValue('CCDNUser\SecurityBundle\Component\Listener\RouteRefererListener')->end()
                                    ->end()
                                ->end()
                                ->arrayNode('blocking_login_listener')
                                    ->addDefaultsIfNotSet()
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('class')->defaultValue('CCDNUser\SecurityBundle\Component\Listener\BlockingLoginListener')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('route_referer_ignore')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->arrayNode('chain')
                                    ->addDefaultsIfNotSet()
                                    ->canBeUnset()
                                    ->children()
                                        ->scalarNode('class')->defaultValue('CCDNUser\SecurityBundle\Component\Listener\Chain\RouteRefererIgnoreChain')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     * @return \CCDNUser\SecurityBundle\DependencyInjection\Configuration
     */
    private function addRouteRefererSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->canBeUnset()
            ->children()
                ->arrayNode('route_referer')
                    ->children()
						->booleanNode('enabled')->defaultFalse()->end()
                        ->arrayNode('route_ignore_list')
                            ->prototype('scalar')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }

    /**
     *
     * @access private
     * @param  \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     * @return \CCDNUser\SecurityBundle\DependencyInjection\Configuration
     */
    private function addLoginShieldSection(ArrayNodeDefinition $node)
    {
        $node
            ->addDefaultsIfNotSet()
            ->canBeUnset()
            ->children()
                ->arrayNode('login_shield')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
				        ->arrayNode('route_login')
							->children()
				            	->scalarNode('name')->end()
				            	->arrayNode('params')
									->prototype('scalar')
									->end()
								->end()
							->end()
						->end()
						
						->arrayNode('force_account_recovery')
							->children()
								->booleanNode('enabled')->defaultFalse()->end()
								->scalarNode('after_attempts')->defaultValue(15)->end()
		                        ->scalarNode('duration_in_minutes')->defaultValue(10)->end()
								->arrayNode('route_recover_account')
									->children()
						            	->scalarNode('name')->end()
						            	->arrayNode('params')
											->prototype('scalar')
											->end()
										->end()
									->end()
								->end()
				            	->arrayNode('routes')
									->prototype('scalar')
									->end()
								->end()
							->end()
						->end()
						
						->arrayNode('block_pages')
							->children()
								->booleanNode('enabled')->defaultFalse()->end()
								->scalarNode('after_attempts')->defaultValue(15)->end()
								->scalarNode('duration_in_minutes')->defaultValue(10)->end()
				            	->arrayNode('routes')
									->prototype('scalar')
									->end()
								->end()
							->end()
						->end()

                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }
}
