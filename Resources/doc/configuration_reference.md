CCDNUser SecurityBundle Configuration Reference.
================================================

All available configuration options are listed below with their default values.

``` yml
#
# for CCDNUser SecurityBundle
#
ccdn_user_security:
    entity:
        user:
            class: Acme\YourUserBundle\Entity\User # Required
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

Please note that for either 'force_account_recovery' or 'block_pages' to function, you need to specify the 'route_login' config, also you must specify the route for the account recovery page.

Once you have enabled either 'force_account_recovery' or 'block_pages', you must specify the routes that you want blocked once the number of attempts has been reached.

In order that the forced account recovery process works, the limit must be set lower than the block_pages process, otherwise page blocking will supersede this and prevent it from working.

Replace Acme\YourUserBundle\Entity\User with the user class of your chosen user bundle.

- [Return back to the docs index](index.md).
