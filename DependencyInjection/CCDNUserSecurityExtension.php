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
 * @author Reece Fowell <reece@codeconsortium.com>
 * @version 1.0
 */
class CCDNUserSecurityExtension extends Extension
{

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return 'ccdn_user_security';
    }

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->getRouteRefererSection($container, $config);
        $this->getLoginShieldSection($container, $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     *
     * @access private
     * @param $container, $config
     */
    private function getRouteRefererSection($container, $config)
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

    }

    /**
     *
     * @access private
     * @param $container, $config
     */
    private function getLoginShieldSection($container, $config)
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

    }

}
