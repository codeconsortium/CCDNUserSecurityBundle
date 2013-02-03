Installing CCDNUser SecurityBundle 1.x
======================================

## Dependencies:

No Dependencies.

## Installation:

Installation takes only 5 steps:

1. Download and install dependencies via Composer.
2. Register bundles with AppKernel.php.
3. Update your app/config/config.yml.
4. Update your database schema.

### Step 1: Download and install dependencies via Composer.

Append the following to end of your applications composer.json file (found in the root of your Symfony2 installation):

``` js
// composer.json
{
    // ...
    "require": {
        // ...
        "codeconsortium/ccdn-user-security-bundle": "dev-master"
    }
}
```

NOTE: Please replace ``dev-master`` in the snippet above with the latest stable branch, for example ``2.0.*``.

Then, you can install the new dependencies by running Composer's ``update``
command from the directory where your ``composer.json`` file is located:

``` bash
$ php composer.phar update
```

### Step 2: Register bundles with AppKernel.php.

Now, Composer will automatically download all required files, and install them
for you. All that is left to do is to update your ``AppKernel.php`` file, and
register the new bundle:

``` php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
		new CCDNUser\SecurityBundle\CCDNUserSecurityBundle(),
		...
	);
}
```

### Step 3: Update your app/config/config.yml.

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

Add or remove routes as you see fit to the ignore list or list of routes to block when denied.

Use the ignore list for routes you do not want to track for the redirect path after a successful login.

### Step 4: Update your database schema.

Make sure to add the ForumBundle to doctrines mapping configuration:

```
# app/config/config.yml
# Doctrine Configuration
doctrine:
    orm:
        default_entity_manager: default
        auto_generate_proxy_classes: "%kernel.debug%"
        entity_managers:
            default:
                mappings:
                    CCDNUserSecurityBundle:
                        mapping:              true
                        type:                 yml
                        dir:                  "Resources/config/doctrine"
                        alias:                ~
                        prefix:               CCDNUser\SecurityBundle\Entity
                        is_bundle:            true
```

From your projects root Symfony directory on the command line run:

``` bash
$ php app/console doctrine:schema:update --dump-sql
```

Take the SQL that is output and update your database manually.

**Warning:**

> Please take care when updating your database, check the output SQL before applying it.

## Next Steps.

Installation should now be complete!

If you need further help/support, have suggestions or want to contribute please join the community at [Code Consortium](http://www.codeconsortium.com)

- [Return back to the docs index](index.md).
- [Configuration Reference](configuration_reference.md).
