# Site Restrict

## Description

Adobe Commerce custom module to redirect non-logged-in customers to the login page. With access to the forgot password and customer registration pages (whether these paths are selected in the admin settings).

---

## Installation

To download the module by composer, execute this code bellow:

```sh
composer require magezil/module-site-restrict
```

After installing the module it is necessary to execute the following commands:

```sh
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:clean
bin/magento cache:flush
```

### System Requirements

> **Magento 2 requires at least:** 2.4.X
> 
> **Magento 2 Tested up to:** 2.4.6
> 
> **Requires PHP:** 8.1

---

## Admin

### Settings

To configure the module, go to the Magento admin area and follow the steps below:

> **Stores** > **Configuration** > **Magezil** > **Site Restrict** > **General**

**Enabled Module:** Enable/Disable module site restriction functionality.
**Available Paths:** Select the paths that are available to non-logged-in customers.

> Note: The list of controllers should be specified as a comma-separated string, without leading or trailing spaces.

![ScreenShot](https://github.com/santanaluc94/Magezil_SiteRestrict/raw/master/docs/config.png)

## Site

### Feature

The module includes a controller validation observer that runs whenever a controller is loaded. This observer checks if the current controller is in the list of valid controllers defined in the module's configuration. If the controller is not in the list, the observer redirects the user to login page.