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
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
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
        $this
            ->addEntitySection($rootNode)
            ->addRepositorySection($rootNode)
            ->addGatewaySection($rootNode)
            ->addManagerSection($rootNode)
            ->addComponentSection($rootNode)
        ;

        // Configuration stuff.
        $this
            ->addRouteRefererSection($rootNode)
            ->addLoginShieldSection($rootNode)
        ;

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
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('entity')
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
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
                                ->scalarNode('class')->defaultValue('CCDNUser\SecurityBundle\Repository\SessionRepository')->end()
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
                                ->scalarNode('class')->defaultValue('CCDNUser\SecurityBundle\Gateway\SessionGateway')->end()
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
                                ->scalarNode('class')->defaultValue('CCDNUser\SecurityBundle\Manager\SessionManager')->end()
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
                    ->addDefaultsIfNotSet()
                    ->canBeUnset()
                    ->children()
                        ->arrayNode('route_ignore_list')
                            ->prototype('array')
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
                        ->scalarNode('enable_shield')->defaultValue(true)->end()
                        ->scalarNode('block_for_minutes')->defaultValue(10)->end()
                        ->arrayNode('limit_failed_login_attempts')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('before_recover_account')->defaultValue(25)->end()
                                ->scalarNode('before_return_http_500')->defaultValue(50)->end()
                            ->end()
                        ->end()
                        ->arrayNode('primary_login_route')
                            ->addDefaultsIfNotSet()
                            ->canBeUnset()
                            ->children()
                                ->scalarNode('name')->defaultValue('fos_user_security_login')->end()
                                ->arrayNode('params')
                                    ->prototype('scalar')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('recover_account_route')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('name')->defaultValue('fos_user_resetting_request')->end()
                                ->arrayNode('params')
                                    ->prototype('scalar')
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('block_routes_when_denied')
                            ->prototype('scalar')
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $this;
    }
}
