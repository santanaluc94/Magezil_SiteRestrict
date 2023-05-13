# Site Restrict

## Description

Custom module to redirect non-logged-in customers to the login page. With access to the forgot password and customer registration pages (whether these paths are selected in the admin settings).

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
> **Magento 2 Tested up to:** 2.4.6
> **Requires PHP:** 8.1

---

## Admin

### Settings

**Enabled Module:** Enable/Disable module site restriction functionality.
**Available Paths:** Select the paths that are available to non-logged-in customers.

![ScreenShot](https://github.com/santanaluc94/Magezil_SiteRestrict/blob/doc/config.png)
