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

namespace CCDNUser\SecurityBundle\features\bootstrap;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use CCDNUser\SecurityBundle\features\bootstrap\WebUser;

/**
 *
 * Features context.
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
class FeatureContext extends RawMinkContext implements KernelAwareInterface
{
    /**
     *
     * Kernel.
     *
     * @var KernelInterface
     */
    private $kernel;

    /**
     *
     * Parameters.
     *
     * @var array
     */
    private $parameters;

    /**
     *
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
        $this->parameters = $parameters;

        // Web user context.
        $this->useContext('web-user', new WebUser());
    }

    /**
     *
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     *
     * @BeforeScenario
     */
    public function purgeDatabase()
    {
        $entityManager = $this->kernel->getContainer()->get('doctrine.orm.entity_manager');

        $purger = new ORMPurger($entityManager);
        $purger->purge();
    }

    /**
     *
     * @Given /^I am logged in as "([^"]*)"$/
     */
    public function iAmLoggedInAs($user)
    {
        $session = $this->getMainContext()->getSession();
        $session->setBasicAuth($user . '@foo.com', 'root');
    }

    /**
     * @Given /^I logout$/
     */
    public function iLogout()
    {
		$this->kernel->getContainer()->get('security.context')->getToken()->eraseCredentials();
    }

    /**
     * @Given /^I should be logged in$/
     */
    public function iShouldBeLoggedIn()
    {
		WebTestCase::assertTrue($this->kernel->getContainer()->get('security.context')->getToken()->getUser() instanceof \Symfony\Component\Security\Core\User\UserInterface);
    }

    /**
     * @Given /^I should not be logged in$/
     */
    public function iShouldNotBeLoggedIn()
    {
		WebTestCase::assertFalse($this->kernel->getContainer()->get('security.context')->getToken()->getUser() instanceof \Symfony\Component\Security\Core\User\UserInterface);
    }

    /**
     * @Given /^I circumvent login with "([^"]*)" and "([^"]*)"$/
     */
    public function iCircumventLoginWithAnd($username, $password)
    {
		$this->getMainContext()->getSession()->visit('/circumvent_login');
        $this->getMainContext()->getSession()->getPage()->fillField('_username', $username);
        $this->getMainContext()->getSession()->getPage()->fillField('_password', $password);
        $this->getMainContext()->getSession()->getPage()->pressButton('Login');
    }

    /**
     * @Given /^I should be blocked$/
     */
    public function iShouldBeBlocked()
    {
		WebTestCase::assertSame(500, $this->getMainContext()->getSession()->getStatusCode());
    }
}
