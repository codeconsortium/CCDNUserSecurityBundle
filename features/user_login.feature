Feature: Check Blocking Functionalities
	In order to determine forced account recovery and page blocking	as an User.

    Background:
        And there are following users defined:
          | name  | email          | password | enabled  | role      |
          | user1 | user1@foo.com  | root     | 1        | ROLE_USER |
		  | user2 | user2@foo.com  | root     | 1        | ROLE_USER |
		  | user3 | user3@foo.com  | root     | 1        | ROLE_USER |

	Scenario: I login successfully
        Given I am on "/login"
		And I fill in "username" with "user1@foo.com"
		And I fill in "password" with "root"
		And I press "_submit"
		And I should be logged in
        And I logout

	Scenario: I fail to login and am forced to recover my account
        Given I am on "/login"
		And I fill in "username" with "user1@foo.com"
		And I fill in "password" with "wrongpass"
		And I press "_submit"
		And I should not be logged in
		And I fill in "username" with "user1@foo.com"
		And I fill in "password" with "wrongpass"
		And I press "_submit"
		And I should not be logged in
		And I should be on "/resetting/request"

	Scenario: I fail to login too many times and am blocked from the account pages
        Given I am on "/login"
		And I circumvent login with "user1@foo.com" and "wrongpass"
		And I should not be logged in
		And I circumvent login with "user1@foo.com" and "wrongpass"
		And I should not be logged in
		And I should be blocked
