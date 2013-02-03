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
            bundle:               ~
            route:                ~ # Required
            path:                 ~
    login_shield:
        enable_shield:        true
        block_for_minutes:    10
        limit_failed_login_attempts:
            before_recover_account:  25
            before_return_http_500:  50
        primary_login_route:
            name:                 fos_user_security_login
            params:               []
        recover_account_route:
            name:                 fos_user_resetting_request
            params:               []
        block_routes_when_denied:  []
			
```

- [Return back to the docs index](index.md).
