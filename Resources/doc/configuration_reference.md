CCDNUser SecurityBundle Configuration Reference.
================================================

All available configuration options are listed below with their default values.

``` yml
ccdn_user_security:
	do_not_log_route:
		~
	login_shield:
		enable_protection: true
		login_fail_limit_recover_account: 25 # 25 attempts before we do not show login form.
		login_fail_limit_http_500: 40
		minutes_blocked_for: 10 # time in minutes we will prevent login for.
		login_routes:
			~

```

- [Return back to the docs index](index.md).
