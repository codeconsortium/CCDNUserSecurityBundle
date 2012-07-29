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

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

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

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
		
		$this->getDoNotLogRouteSection($container, $config);
		$this->getBruteForceLoginPreventionSection($container, $config);
		
    }
	
	
	
	/**
	 *
	 * @access private
	 * @param $container, $config
	 */
	private function getDoNotLogRouteSection($container, $config)
	{
			
		$defaults = array(
			array('bundle' => 'fosuserbundle', 'route' => 'fos_user_security_login'),
			array('bundle' => 'fosuserbundle', 'route' => 'fos_user_security_check'),
			array('bundle' => 'fosuserbundle', 'route' => 'fos_user_security_logout'),
			array('bundle' => 'fosuserbundle', 'route' => 'fos_user_registration_register'),
			array('bundle' => 'fosuserbundle', 'route' => 'fos_user_registration_check_email'),
			array('bundle' => 'fosuserbundle', 'route' => 'fos_user_registration_confirm'),
			array('bundle' => 'fosuserbundle', 'route' => 'fos_user_registration_confirmed'),
		);
		
		$container->setParameter('ccdn_user_security.do_not_log_route', array_merge($config['do_not_log_route'], $defaults));
		
	}
	
	
	
	/**
	 *
	 * @access private
	 * @param $container, $config
	 */
	private function getBruteForceLoginPreventionSection($container, $config)
	{
		
		$container->setParameter('ccdn_user_security.brute_force_login_prevention.enable_protection', $config['brute_force_login_prevention']['enable_protection']);
		$container->setParameter('ccdn_user_security.brute_force_login_prevention.login_attempts', $config['brute_force_login_prevention']['login_attempts']);
		$container->setParameter('ccdn_user_security.brute_force_login_prevention.block_in_minutes', $config['brute_force_login_prevention']['block_in_minutes']);

	}
}
