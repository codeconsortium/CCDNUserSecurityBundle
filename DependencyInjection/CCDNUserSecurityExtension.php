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

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
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
class CCDNUserSecurityExtension extends Extension
{
    /**
     *
     * @access public
     * @return string
     */
    public function getAlias()
    {
        return 'ccdn_user_security';
    }

    /**
     *
     * @access public
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Class file namespaces.
        $this->getEntitySection($container, $config);
        $this->getGatewaySection($container, $config);
        $this->getRepositorySection($container, $config);
        $this->getManagerSection($container, $config);
        $this->getModelSection($container, $config);
        $this->getComponentSection($container, $config);

        // Configuration stuff.
        $this->getRouteRefererSection($container, $config);
        $this->getLoginShieldSection($container, $config);

        // Load Service definitions.
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		
        $loader->load('services.yml');
        $loader->load('services/components.yml');
        $loader->load('services/model-gateway.yml');
        $loader->load('services/model-repository.yml');
        $loader->load('services/model-manager.yml');
        $loader->load('services/model.yml');
    }

    /**
     *
     * @access private
     * @param  array                                                                  $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder                $container
     * @return \CCDNUser\SecurityBundle\DependencyInjection\CCDNUserSecurityExtension
     */
    private function getEntitySection(ContainerBuilder $container, $config)
    {
        $container->setParameter('ccdn_user_security.entity.user.class', $config['entity']['user']['class']);
        $container->setParameter('ccdn_user_security.entity.session.class', $config['entity']['session']['class']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                                  $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder                $container
     * @return \CCDNUser\SecurityBundle\DependencyInjection\CCDNUserSecurityExtension
     */
    private function getGatewaySection(ContainerBuilder $container, $config)
    {
        $container->setParameter('ccdn_user_security.gateway.session.class', $config['gateway']['session']['class']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                                  $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder                $container
     * @return \CCDNUser\SecurityBundle\DependencyInjection\CCDNUserSecurityExtension
     */
    private function getRepositorySection(ContainerBuilder $container, $config)
    {
        $container->setParameter('ccdn_user_security.repository.session.class', $config['repository']['session']['class']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                                  $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder                $container
     * @return \CCDNUser\SecurityBundle\DependencyInjection\CCDNUserSecurityExtension
     */
    private function getManagerSection(ContainerBuilder $container, $config)
    {
        $container->setParameter('ccdn_user_security.manager.session.class', $config['manager']['session']['class']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                                  $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder                $container
     * @return \CCDNUser\SecurityBundle\DependencyInjection\CCDNUserSecurityExtension
     */
    private function getModelSection(ContainerBuilder $container, $config)
    {
        $container->setParameter('ccdn_user_security.model.session.class', $config['model']['session']['class']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                                  $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder                $container
     * @return \CCDNUser\SecurityBundle\DependencyInjection\CCDNUserSecurityExtension
     */
    private function getComponentSection(ContainerBuilder $container, $config)
    {
        $container->setParameter('ccdn_user_security.component.authentication.handler.login_failure_handler.class', $config['component']['authentication']['handler']['login_failure_handler']['class']);
        $container->setParameter('ccdn_user_security.component.authentication.handler.login_success_handler.class', $config['component']['authentication']['handler']['login_success_handler']['class']);
        $container->setParameter('ccdn_user_security.component.authentication.handler.logout_success_handler.class', $config['component']['authentication']['handler']['logout_success_handler']['class']);
        $container->setParameter('ccdn_user_security.component.authentication.tracker.login_failure_tracker.class', $config['component']['authentication']['tracker']['login_failure_tracker']['class']);

        $container->setParameter('ccdn_user_security.component.authorisation.voter.client_login_voter.class', $config['component']['authorisation']['voter']['client_login_voter']['class']);

        $container->setParameter('ccdn_user_security.component.listener.route_referer_listener.class', $config['component']['listener']['route_referer_listener']['class']);
        $container->setParameter('ccdn_user_security.component.listener.blocking_login_listener.class', $config['component']['listener']['blocking_login_listener']['class']);

        $container->setParameter('ccdn_user_security.component.route_referer_ignore.chain.class', $config['component']['route_referer_ignore']['chain']['class']);

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                                  $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder                $container
     * @return \CCDNUser\SecurityBundle\DependencyInjection\CCDNUserSecurityExtension
     */
    private function getRouteRefererSection(ContainerBuilder $container, $config)
    {
        $defaults = array(
            array('bundle' => 'fosuserbundle', 'route' => 'fos_user_security_login'),
            array('bundle' => 'fosuserbundle', 'route' => 'fos_user_security_check'),
            array('bundle' => 'fosuserbundle', 'route' => 'fos_user_security_logout'),
            array('bundle' => 'fosuserbundle', 'route' => 'fos_user_registration_register'),
            array('bundle' => 'fosuserbundle', 'route' => 'fos_user_registration_check_email'),
            array('bundle' => 'fosuserbundle', 'route' => 'fos_user_registration_confirm'),
            array('bundle' => 'fosuserbundle', 'route' => 'fos_user_registration_confirmed'),
            array('bundle' => 'fosuserbundle', 'route' => 'fos_user_resetting_request'),
            array('bundle' => 'fosuserbundle', 'route' => 'fos_user_resetting_send_email'),
            array('bundle' => 'fosuserbundle', 'route' => 'fos_user_resetting_check_email'),
            array('bundle' => 'fosuserbundle', 'route' => 'fos_user_resetting_reset'),
            array('bundle' => 'fosuserbundle', 'route' => 'fos_user_change_password'),
        );

        $container->setParameter('ccdn_user_security.route_referer.route_ignore_list', array_merge($config['route_referer']['route_ignore_list'], $defaults));

        return $this;
    }

    /**
     *
     * @access private
     * @param  array                                                                  $config
     * @param  \Symfony\Component\DependencyInjection\ContainerBuilder                $container
     * @return \CCDNUser\SecurityBundle\DependencyInjection\CCDNUserSecurityExtension
     */
    private function getLoginShieldSection(ContainerBuilder $container, $config)
    {
        $container->setParameter('ccdn_user_security.login_shield.enable_shield', $config['login_shield']['enable_shield']);
        $container->setParameter('ccdn_user_security.login_shield.block_for_minutes', $config['login_shield']['block_for_minutes']);
        $container->setParameter('ccdn_user_security.login_shield.limit_failed_login_attempts.before_recover_account', $config['login_shield']['limit_failed_login_attempts']['before_recover_account']);
        $container->setParameter('ccdn_user_security.login_shield.limit_failed_login_attempts.before_return_http_500', $config['login_shield']['limit_failed_login_attempts']['before_return_http_500']);

        $container->setParameter('ccdn_user_security.login_shield.primary_login_route.name', $config['login_shield']['primary_login_route']['name']);
        $container->setParameter('ccdn_user_security.login_shield.primary_login_route.params', $config['login_shield']['primary_login_route']['params']);

        $container->setParameter('ccdn_user_security.login_shield.recover_account_route.name', $config['login_shield']['recover_account_route']['name']);
        $container->setParameter('ccdn_user_security.login_shield.recover_account_route.params', $config['login_shield']['recover_account_route']['params']);

        $blockRoutesWhenDeniedDefaults = array(
            'fos_user_security_login',
            'fos_user_security_check',
            'fos_user_security_logout',
        );

        $container->setParameter('ccdn_user_security.login_shield.block_routes_when_denied', array_merge($config['login_shield']['block_routes_when_denied'], $blockRoutesWhenDeniedDefaults));

        return $this;
    }
}
