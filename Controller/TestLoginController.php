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

namespace CCDNUser\SecurityBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\EventDispatcher\Event;

/**
 *
 * This controller is only for testing the POST request circumvention of login
 * protection and is not used during prod environment. Routes are only imported
 * during the test env for Behat under Tests/Functional/app/config
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
class TestLoginController extends ContainerAware
{
	public function circumventAction()
	{
        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');
		
		return $this->container->get('templating')->renderResponse('CCDNUserSecurityBundle::login.html.twig', array('csrf_token' => $csrfToken));
	}
}