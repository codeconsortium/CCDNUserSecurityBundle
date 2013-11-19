<?php

namespace CCDNUser\SecurityBundle\Tests\Functional\src\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use CCDNUser\SecurityBundle\Entity\Security;

class User extends BaseUser
{
    public function __construct()
    {
        parent::__construct();
		
        // your own logic
    }
}
