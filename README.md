# Zend Framework 1.x Skeleton

## Installation:

1. Install [Composer](http://getcomposer.org)

	`$ curl -sS https://getcomposer.org/installer | php`

2. Install vendors

	`$ php composer.phar install`

3. Rename the configuration file `application/configs/application.php-new` to `application.php`. (Just remove the ending `-new`)


## Start Developing:

### 1. Code Organization:

For large projects, code is organized by modules. The modules directory is located at `application/modules`, there is a default module called 'site', you could start from there to develop.

### 2. Routes:

**This skeleton doesn't follow the Zend Framework's default route settings. It removed the default routes.** (Easy, just used the build-in method, doesn't modify any framework code.) So you can't access your site via :module/:controller/:action convention. You must add routings manually.

In folder `application/configs`, open `routes.php`to add your project's routings. For more information, check the [manual](http://framework.zend.com/manual/1.12/en/zend.controller.router.html)

### 3. Configuration:

Open `application/configs/application.php`, set what you want.

Enjoy!
