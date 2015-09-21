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

namespace CCDNUser\SecurityBundle\Component\Authorisation\Voter;

use CCDNUser\SecurityBundle\Component\Authorisation\SecurityManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
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
class ClientLoginVoter implements VoterInterface
{
    /**
     *
     * @access protected
     * @var \CCDNUser\SecurityBundle\Component\Authorisation\SecurityManagerInterface $securityManager
     */
    protected $securityManager;

    /**
     *
     * @access public
     * @param \CCDNUser\SecurityBundle\Component\Authorisation\SecurityManagerInterface $securityManager
     */
    public function __construct(SecurityManagerInterface $securityManager)
    {
        $this->securityManager = $securityManager;
    }

    /**
     *
     * @access public
     * @param $attribute
     * @return bool
     */
    public function supportsAttribute($attribute)
    {
        // we won't check against a user attribute, so we return true
        return true;
    }

    /**
     *
     * @access public
     * @param $class
     * @return bool
     */
    public function supportsClass($class)
    {
        // our voter supports all type of token classes, so we return true
        return true;
    }

    /**
     *
     * @access public
     * @param  \Symfony\Component\Security\Core\Authentication\Token\TokenInterface $token
     * @param  object                                                               $object
     * @param  array                                                                $attributes
     * @return int
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $securityManager = $this->securityManager; // Avoid the silly cryptic error 'T_PAAMAYIM_NEKUDOTAYIM'
        $result = $securityManager->vote();

        if ($result == $securityManager::ACCESS_ALLOWED) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if ($result == $securityManager::ACCESS_DENIED_DEFER) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if ($result == $securityManager::ACCESS_DENIED_BLOCK) {
            return VoterInterface::ACCESS_DENIED;
        }
    }
}
