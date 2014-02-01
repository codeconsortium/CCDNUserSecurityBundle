Installing CCDNUser SecurityBundle.
===================================

## Dependencies:

> Note you will need a User Bundle so that you can map the UserInterface to your own User entity. You can use whatecer User Bundle you prefer. FOSUserBundle is highly rated.

## Installation:

Installation takes only 5 steps:

1. Download and install dependencies via Composer.
2. Register bundles with AppKernel.php.
3. Update your app/config/config.yml.
4. enable handlers
5. Update your database schema.

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
        // ...
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
    entity:
        user:
            class: Acme\YourUserBundle\Entity\User # Required
    route_referer:
        enabled: true
        route_ignore_list:
            - fos_user_security_login
            - fos_user_security_check
            - fos_user_security_logout
            - fos_user_registration_register
            - fos_user_registration_check_email
            - fos_user_registration_confirm
            - fos_user_registration_confirmed
            - fos_user_resetting_request
            - fos_user_resetting_send_email
            - fos_user_resetting_check_email
            - fos_user_resetting_reset
            - fos_user_change_password
    login_shield:
        route_login:
            name:                  fos_user_security_login
            params:                []
        force_account_recovery:    # Specify all routes to block after attempt limit is reached, and account recovery route to force browser redirect.
            enabled:               true
            after_attempts:        2
            duration_in_minutes:   1
            route_recover_account:
                name:              fos_user_resetting_request
                params:            []
            routes:
                - fos_user_security_login
                - fos_user_security_check
                - fos_user_security_logout
        block_pages:               # Specify all routes to block after attempt limit is reached.
            enabled:               true
            after_attempts:        4
            duration_in_minutes:   2
            routes:
                - fos_user_security_login
                - fos_user_security_check
                - fos_user_security_logout
                - fos_user_registration_register
                - fos_user_registration_check_email
                - fos_user_registration_confirm
                - fos_user_registration_confirmed
                - fos_user_resetting_request
                - fos_user_resetting_send_email

```

> Routes added are for FOSUserBundle and are added as an example only, choose the correct routes for the user bundle of your choice. If however you are using FOSUserBundle then the routes shown above should work for you.

Replace Acme\YourUserBundle\Entity\User with the user class of your chosen user bundle.

Add or remove routes as you see fit to the ignore list or list of routes to block when denied.

Use the ignore list for routes you do not want to track for the redirect path after a successful login.

>Please note that for either 'force_account_recovery' or 'block_pages' to function, you need to specify the 'route_login' config, also you must specify the route for the account recovery page.
>Once you have enabled either 'force_account_recovery' or 'block_pages', you must specify the routes that you want blocked once the number of attempts has been reached.
>In order that the forced account recovery process works, the limit must be set lower than the block_pages process, otherwise page blocking will supersede this and prevent it from working and the route must be provided for the account recovery page.

### Step 4: enable handlers

You have to enable your login-/logout-handlers via app/config/security.yml:

```
security:
    firewalls:
        main:
            form_login:
                provider:       fos_userbundle
                login_path:     /login
                use_forward:    false
                check_path:     /login_check
                success_handler: ccdn_user_security.component.authentication.handler.login_success_handler
                failure_handler: ccdn_user_security.component.authentication.handler.login_failure_handler
                failure_path:   null
            logout:
                path:   /logout
                success_handler: ccdn_user_security.component.authentication.handler.logout_success_handler
```


### Step 5: Update your database schema.

Make sure to add the SecurityBundle to doctrines mapping configuration:

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
