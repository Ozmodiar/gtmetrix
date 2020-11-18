# GTmetrix
This module provides a way to see basic GTmetrix statistics for your website on the Drupal status report page.
The result will be shown as Error, Warning or OK depending on the configured percentages for PageSpeed and YSlow.
The test itself will be run on cron and cached for a specific amount of time that is configurable in the settings.
All test runs are logged to Watchdog.

## Requirements
You need an account on [GTmetrix](https://www.gtmetrix.com) in order to generate an API key.

## Dependencies
This module uses Phil Cook's GTmetrix PHP library: [https://github.com/philcook/php-gtmetrix](https://github.com/philcook/php-gtmetrix)
