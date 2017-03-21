# Zend Expressive ContentBasedErrorResponseGenerator

[![Build Status](https://travis-ci.org/acelaya/ze-content-based-error-handler.svg?branch=master)](https://travis-ci.org/acelaya/ze-content-based-error-handler)
[![Code Coverage](https://scrutinizer-ci.com/g/acelaya/ze-content-based-error-handler/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/acelaya/ze-content-based-error-handler/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/acelaya/ze-content-based-error-handler/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/acelaya/ze-content-based-error-handler/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/acelaya/ze-content-based-error-handler/v/stable.png)](https://packagist.org/packages/acelaya/ze-content-based-error-handler)
[![Total Downloads](https://poser.pugx.org/acelaya/ze-content-based-error-handler/downloads.png)](https://packagist.org/packages/acelaya/ze-content-based-error-handler)
[![License](https://poser.pugx.org/acelaya/ze-content-based-error-handler/license.png)](https://packagist.org/packages/acelaya/ze-content-based-error-handler)

A Zend Expressive error handler which allows to implement different strategies to render error responses based on the accepted content-types.

### Context

This package has been created following this article https://blog.alejandrocelaya.com/2016/07/29/creating-a-content-based-error-handler-for-zend-expressive/.

On it, I demonstrate how to implement an strategy-based system which generates different error responses by taking into account the request's `Accept` header.

After writing the article I have decided to create this package, so that everybody can install and use the provided solution in their own projects.

### Installation

Use composer to install this package

    composer require acelaya/ze-content-based-error-handler

### Usage

This package includes an error handler, the `Acelaya\ExpressiveErrorHandler\ErrorHandler\ContentBasedErrorResponseGenerator`, that can be used to replace default Zend Expressive implementations.

It composes a plugin manager that fetches a concrete error handler at runtime, based on the Request's `Accept` header. Thus, you can use the Expressive's `TemplatedErrorHandler` to dispatch **text/html** request errors, Stratiglity's `FinalHandler` for **text/plain** errors, etc.

You can also provide your own implementations for other content-types, like **application/json** or **text/xml**. The ContentBasedErrorResponseGenerator will automatically use the proper implementation.

### Provided configuration

To get things easily working, a `ConfigProvider` is included, which automatically registers all the dependencies in the service container (including the `Zend\Expressive\ErroHandler` service).

It also preregisters error handlers for html and plain text requests (The `TemplatedErrorHandler` and the `FinalHandler` as mentioned before).

```php
<?php
return [

    'error_handler' => [
        'default_content_type' => 'text/html',

        'plugins' => [
            'invokables' => [
                'text/plain' => FinalHandler::class,
            ],
            'factories' => [
                'text/html' => TemplatedErrorHandlerFactory::class,
            ],
            'aliases' => [
                'application/xhtml+xml' => 'text/html',
            ],
        ],
    ],

];
```

> The **plugins** block is the one consumed by the plugin manager. For more information on how plugin managers work, read [this](https://docs.zendframework.com/zend-servicemanager/plugin-managers/).

In order to use the built-in ConfigProvider, create a config file with this contents:

```php
<?php
return (new Acelaya\ExpressiveErrorHandler\ConfigProvider())->__invoke();
```

If your are using the Expressive's ConfigManager ([mtymek/expressive-config-manager](https://github.com/mtymek/expressive-config-manager)), you can just pass the class name to it like this:

```php
return (new Zend\Expressive\ConfigManager([
    Acelaya\ExpressiveErrorHandler\ConfigProvider::class,
    // [...]
    new ZendConfigProvider('config/autoload/{{,*.}global,{,*.}local}.php'),
], 'data/cache/app_config.php'))->getMergedConfig();
```

### Override configuration

If you need to override any of the content types, its as easy as defining the same plugin with a different value.

For example, it is very likely that you want to use Expressive's `WhoopsErrorHandler` in development environments.

Just define a local configuration file with this content and all the html requests will use it from now on:

```php
<?php
return [

    'error_handler' => [
        'plugins' => [
            'factories' => [
                'text/html' => WhoopsErrorHandlerFactory::class,
            ],
        ],
    ],

];
```

You will probably need to define other error handlers for different content types. You can do it by using the same structure.

```php
<?php
return [

    'error_handler' => [
        'plugins' => [
            'invokables' => [
                'application/json' => JsonErrorHandler::class,
            ],
            'factories' => [
                'text/xml' => XmlErrorHandlerFactory::class,
            ],
            'aliases' => [
                'application/x-json' => 'application/json',
                'text/json' => 'application/json',
            ],
        ],
    ],

];
```

With this configuration, the `ContentBasedErrorResponseGenerator` will create the proper `JsonErroHandler` or `XmlErroHandler` at runtime, to dispatch json or xml errors.

Similarly, you could need to override the default content type by setting the `default_content_type` property.

```php
<?php
return [

    'error_handler' => [
        'default_content_type' => 'application/json',
    ],

];
```

This way, when no `Accept` header was provided from the client or none of the accepted content types is registered, the **application/json** content type will be used, instead of **text/html**, which is the default behavior.

### Log errors

This package allows you to provided a psr-3 logger to the `ContentBasedErrorResponseGenerator`, in order to get errors logged.

By default a `Psr\Log\NullLogger` is used, so no errors will be logged, but if a logger is registered under the `Psr\Log\LoggerInterface` service name, it will be injected in the `ContentBasedErrorResponseGenerator` when created.

The logged message can be customized too. The `ContentBasedErrorResponseGenerator` expects an object implementing `Acelaya\ExpressiveErrorHandler\Log\LogMessageBuilderInterface` to be injected on it.

A base implementation is provided, the `Acelaya\ExpressiveErrorHandler\Log\BasicLogMessageBuilder`, which basically logs the message "Error occurred while dispatching request" and appends the error on a new line.

You can easily override that by creating your own service implementing the interface, and registering it with the `Acelaya\ExpressiveErrorHandler\Log\LogMessageBuilderInterface` name.
