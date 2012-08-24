Installing CCDNUser SecurityBundle 1.0
======================================

## Dependencies:

No Dependencies.

## Installation:

Installation takes only 9 steps:

1. Download and install the dependencies.
2. Register bundles with autoload.php.
3. Register bundles with AppKernel.php.  
4. Run vendors install script.
5. Update your app/config/routing.yml. 
6. Update your app/config/config.yml. 
7: Update your database schema.
8. Symlink assets to your public web directory.
9. Warmup the cache.

### Step 1: Download and install the dependencies.

Append the following to end of your deps file (found in the root of your Symfony2 installation):

``` ini
[CCDNUser_SecurityBundle]
	git=http://github.com/codeconsortium/CCDNUserSecurityBundle.git
	target=/bundles/CCDNUser/SecurityBundle
    version=v1.0
```

### Step 2: Register bundles with autoload.php.

Add the following to the registerNamespaces array in the method by appending it to the end of the array.

``` php
// app/autoload.php
$loader->registerNamespaces(array(
    'CCDNUser'        => __DIR__.'/../vendor/bundles',
	**...**
);
```

### Step 3: Register bundles with AppKernel.php.  

In your AppKernel.php add the following bundles to the registerBundles method array:  

``` php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
	    new CCDNUser\SecurityBundle\CCDNUserSecurityBundle(),    
		**...**
	);
}
``` 

### Step 4: Run vendors install script.

From your projects root Symfony directory on the command line run:

``` bash
$ php bin/vendors install
```

### Step 5: Update your app/config/routing.yml. 

In your app/config/routing.yml add:  

``` yml
CCDNUserSecurityBundle:
    resource: "@CCDNUserSecurityBundle/Resources/config/routing.yml"
    prefix: /
```

### Step 6: Update your app/config/config.yml. 

In your app/config/config.yml add:    

``` yml
#
# for CCDNUser SecurityBundle
#
ccdn_user_security:
    route_referer:
        route_ignore_list:
            - { bundle: 'fosuserbundle', route: 'fos_user_security_login' }
            - { bundle: 'fosuserbundle', route: 'fos_user_security_check' }
            - { bundle: 'fosuserbundle', route: 'fos_user_security_logout' }
            - { bundle: 'fosuserbundle', route: 'fos_user_registration_register' }
            - { bundle: 'fosuserbundle', route: 'fos_user_registration_check_email' }
            - { bundle: 'fosuserbundle', route: 'fos_user_registration_confirm' }
            - { bundle: 'fosuserbundle', route: 'fos_user_registration_confirmed' }
            - { bundle: 'fosuserbundle', route: 'fos_user_resetting_request' }
            - { bundle: 'fosuserbundle', route: 'fos_user_resetting_send_email' }
            - { bundle: 'fosuserbundle', route: 'fos_user_resetting_check_email' }
            - { bundle: 'fosuserbundle', route: 'fos_user_resetting_reset' }
            - { bundle: 'fosuserbundle', route: 'fos_user_change_password' }
    login_shield:
        enable_shield: true
        block_for_minutes: 10
        limit_failed_login_attempts:
            before_recover_account: 5
            before_return_http_500: 10
        primary_login_route:
            name: fos_user_security_login
        recover_account_route:
            name: fos_user_resetting_request
        block_routes_when_denied:
            - fos_user_security_login
            - fos_user_security_check
            - fos_user_security_logout

```   

See [Configuration Reference](configuration_reference.md) for more information.

### Step 7: Update your database schema.

From your projects root Symfony directory on the command line run:

``` bash
$ php app/console doctrine:schema:update --dump-sql
```

Take the SQL that is output and update your database manually.

**Warning:**

> Please take care when updating your database, check the output SQL before applying it.

### Step 8: Symlink assets to your public web directory.

From your projects root Symfony directory on the command line run:

``` bash
$ php app/console assets:install --symlink web/
```

### Step 9: Warmup the cache.

From your projects root Symfony directory on the command line run:

``` bash
$ php app/console cache:warmup
```

## Next Steps.

Installation should now be complete!

If you need further help/support, have suggestions or want to contribute please join the community at [Code Consortium](http://www.codeconsortium.com)

- [Return back to the docs index](index.md).
- [Configuration Reference](configuration_reference.md).
