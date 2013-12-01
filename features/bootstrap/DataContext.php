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

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\HttpKernel\KernelInterface;

use CCDNUser\SecurityBundle\Tests\Functional\src\Entity\User;
use CCDNUser\SecurityBundle\Entity\Profile;

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
class DataContext extends BehatContext implements KernelAwareInterface
{
    /**
     *
     * Kernel.
     *
     * @var KernelInterface
     */
    protected $kernel;

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
     * Get entity manager.
     *
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     *
     * Returns Container instance.
     *
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->kernel->getContainer();
    }

    /**
     *
     * Get service by id.
     *
     * @param string $id
     *
     * @return object
     */
    protected function getService($id)
    {
        return $this->getContainer()->get($id);
    }

    protected $users = array();

    /**
     *
     * @Given /^there are following users defined:$/
     */
    public function thereAreFollowingUsersDefined(TableNode $table)
    {
        foreach ($table->getHash() as $data) {
			$username = isset($data['name']) ? $data['name'] : sha1(uniqid(mt_rand(), true));
			
            $this->users[$username] = $this->thereIsUser(
                $username,
                isset($data['email']) ? $data['email'] : sha1(uniqid(mt_rand(), true)),
                isset($data['password']) ? $data['password'] : 'password',
                isset($data['role']) ? $data['role'] : 'ROLE_USER',
                isset($data['enabled']) ? $data['enabled'] : true
            );
        }
		
		$this->getEntityManager()->flush();
    }

    public function thereIsUser($username, $email, $password, $role = 'ROLE_USER', $enabled = true)
    {
        $user = new User();

        $user->setUsername($username);
        $user->setEmail($email);
        $user->setEnabled($enabled);
        $user->setPlainPassword($password);

        if (null !== $role) {
            $user->addRole($role);
        }
		
        $this->getEntityManager()->persist($user);

        return $user;
    }

	protected $profiles = array();

    /**
     *
     * @Given /^there are following profiles defined:$/
     */
    public function thereAreFollowingProfilesDefined(TableNode $table)
    {
        foreach ($table->getHash() as $data) {
			$username = isset($data['user']) ? $data['user'] : sha1(uniqid(mt_rand(), true));
			
			if (isset($this->users[$username])) {
	            $this->profiles[$username] = $this->thereIsProfile(
	                $this->users[$username],
					isset($data['country']) ? $data['country'] : null,
					isset($data['city']) ? $data['city'] : null,
					isset($data['real_name']) ? $data['real_name'] : null,
					isset($data['birthday']) ? new \Datetime($data['birthday']) : null,
					isset($data['company']) ? $data['company'] : null,
					isset($data['position']) ? $data['position'] : null,
					isset($data['bio']) ? $data['bio'] : null,
					isset($data['signature']) ? $data['signature'] : null,
	                isset($data['msn']) ? $data['msn'] : null,
	                isset($data['aim']) ? $data['aim'] : null,
	                isset($data['yahoo']) ? $data['yahoo'] : null,
	                isset($data['icq']) ? $data['icq'] : true
	            );
			}
        }
		
		$this->getEntityManager()->flush();
    }

    public function thereIsProfile(User $user, $country, $city, $realName, \Datetime $birthday, $company, $position, $bio, $signature, $msn, $aim, $yahoo, $icq)
    {
        $profile = new Profile();

        $profile->setUser($user);
        $profile->setLocationCountry($country);
        $profile->setLocationCity($city);
        $profile->setRealName($realName);
		$profile->setBirthDate($birthday);
		$profile->setCompany($company);
		$profile->setPosition($position);
		$profile->setBio($bio);
		$profile->setSignature($signature);
		$profile->setMsn($msn);
		$profile->setMsnPublic(true);
		$profile->setAim($aim);
		$profile->setAimPublic(true);
		$profile->setYahoo($yahoo);
		$profile->setYahooPublic(true);
		$profile->setIcq($icq);
		$profile->setIcqPublic(true);
		
        $this->getEntityManager()->persist($profile);

        return $user;
    }
}
