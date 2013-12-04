CCDNUser SecurityBundle README.
===============================


## Notes:  
  
This bundle is for the symfony framework and requires Symfony >=2.1 and PHP >=5.3.2
  
This project uses Doctrine >=2.1 and so does not require any specific database.
  

This file is part of the CCDNUser bundles(s)

&copy; CCDN &copy; [CodeConsortium](http://www.codeconsortium.com/)

Available on:
* [Github](http://www.github.com/codeconsortium/CCDNUserSecurityBundle)
* [Packagist](https://packagist.org/packages/codeconsortium/ccdn-user-security-bundle)

For the full copyright and license information, please view the [LICENSE](http://github.com/codeconsortium/CCDNUserSecurityBundle/blob/master/Resources/meta/LICENSE) file that was distributed with this source code.

[![Build Status](https://secure.travis-ci.org/codeconsortium/CCDNUserSecurityBundle.png)](https://travis-ci.org/codeconsortium/CCDNUserSecurityBundle) [![Latest Stable Version](https://poser.pugx.org/codeconsortium/ccdn-user-security-bundle/v/stable.png)](https://packagist.org/packages/codeconsortium/ccdn-user-security-bundle) [![Total Downloads](https://poser.pugx.org/codeconsortium/ccdn-user-security-bundle/downloads.png)](https://packagist.org/packages/codeconsortium/ccdn-user-security-bundle)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/bc552d3d-50ea-4287-8398-ed165db32f78/big.png)](https://insight.sensiolabs.com/projects/bc552d3d-50ea-4287-8398-ed165db32f78)
[![knpbundles.com](http://knpbundles.com/codeconsortium/CCDNUserSecurityBundle/badge-short)](http://knpbundles.com/codeconsortium/CCDNUserSecurityBundle) 

## Description:

Use this bundle to mitigate brute force dictionary attacks on your sites. Excessive failed logins will force users to recover their account, additional attempts
to circumvent that will block the user from specified webpages by returning an HTTP 500 response on all specified routes.

### You can use this bundle with any User Bundle you like.

> This bundle does *NOT*  provide user registration/login/logout etc features. This bundle is for brute force dictionary attack mitigation only. Use this bundle in conjunction with your preferred user bundle.

## Features.

SecurityBundle Provides the following features:

1. Prevent brute force attacks being carried out by limiting number of login attempts:
	1. When first limit is reached, redirect to an account recovery page.
	2. When secondary limit is reached, return an HTTP 500 status to block login pages etc.
3. All limits are configurable.
4. Routes to block are configurable.
5. Route for account recovery page is configurable.
6. Decoupled from UserBundle specifics. You can use this with any user bundle you like.
6. Redirect user to last page they were on upon successful login.
7. Redirect user to last page they were on upon successful logout.

## Documentation.

Documentation can be found in the `Resources/doc/index.md` file in this bundle:

[Read the Documentation](http://github.com/codeconsortium/CCDNUserSecurityBundle/blob/master/Resources/doc/index.md).

## Installation.

All the installation instructions are located in [documentation](http://github.com/codeconsortium/CCDNUserSecurityBundle/blob/master/Resources/doc/install.md).

## License.

This software is licensed under the MIT license. See the complete license file in the bundle:

	Resources/meta/LICENSE

[Read the License](http://github.com/codeconsortium/CCDNUserSecurityBundle/blob/master/Resources/meta/LICENSE).

## About.

[CCDNUser SecurityBundle](http://github.com/codeconsortium/CCDNUserSecurityBundle) is free software from [Code Consortium](http://www.codeconsortium.com). 
See also the list of [contributors](http://github.com/codeconsortium/CCDNUserSecurityBundle/contributors).

## Reporting an issue or feature request.

Issues and feature requests are tracked in the [Github issue tracker](http://github.com/codeconsortium/CCDNUserSecurityBundle/issues).

Discussions and debates on the project can be further discussed at [Code Consortium](http://www.codeconsortium.com).
