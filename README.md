# PHP Monolog Integration for Rouda.online Dashboard

This package provides an integration for the Monolog logging library to send logs from your PHP application to your Rouda.online dashboard. By using this package, you can easily monitor and analyze your application's logs in real-time through the Rouda.online interface.

## Features

- Seamless integration with Monolog
- Real-time log monitoring on Rouda.online dashboard
- Easy setup and configuration

## Installation

To install this package, you can use Composer:

```
composer install rouda/monolog-handler
```

To use this package as a logging channel in Laravel, you need to add the following code to the `logging.php` configuration file:

```
 'logentries' => [
     'driver'  => 'monolog',
     'handler' => rouda\monologHandler\ApiLogger::class,
     'with' => [
         'authToken' => 'string',
         'applicationId' => 'string',
         'applicationEnv' => 'string',
     ],
 ],
```

This configuration sets up a custom logging channel named 'logentries' using the Monolog library's SyslogUdpHandler.
Replace 'authToken', 'applicationId' and 'applicationEnv' with the appropriate details found in your rouda.online dashboard.
