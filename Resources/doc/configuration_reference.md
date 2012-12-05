CCDNUser SecurityBundle Configuration Reference.
================================================

All available configuration options are listed below with their default values.

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
		#	params: 
        recover_account_route:
            name: fos_user_resetting_request
		# 	params:
        block_routes_when_denied:
        #    - fos_user_security_login
        #    - fos_user_security_check
        #    - fos_user_security_logout
			
```

- [Return back to the docs index](index.md).
