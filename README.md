# Zend Expressive ContentBasedErrorResponseGenerator

[![Build Status](https://img.shields.io/travis/acelaya/ze-content-based-error-handler/master.svg?style=flat-square)](https://travis-ci.org/acelaya/ze-content-based-error-handler)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/acelaya/ze-content-based-error-handler.svg?style=flat-square)](https://scrutinizer-ci.com/g/acelaya/ze-content-based-error-handler/?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/acelaya/ze-content-based-error-handler.svg?style=flat-square)](https://scrutinizer-ci.com/g/acelaya/ze-content-based-error-handler/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/acelaya/ze-content-based-error-handler/v/stable?format=flat-square)](https://packagist.org/packages/acelaya/ze-content-based-error-handler)
[![Total Downloads](https://poser.pugx.org/acelaya/ze-content-based-error-handler/downloads?format=flat-square)](https://packagist.org/packages/acelaya/ze-content-based-error-handler)
[![License](https://poser.pugx.org/acelaya/ze-content-based-error-handler/license?format=flat-square)](LICENSE)

A Zend Expressive error response generator which allows to implement different strategies to render error responses based on the accepted content-types.

### Context

This package was created following this article https://blog.alejandrocelaya.com/2016/07/29/creating-a-content-based-error-handler-for-zend-expressive/.

On it, I demonstrate how to implement an strategy-based system which generates different error responses by taking into account the request's `Accept` header.

After writing the article I decided to create this package, so that everybody can install and use the provided solution in their own projects.

The package has then evolved to support expressive 2, which completely drops the concept of error handlers. Instead, from v2, this provides error response generators.

### Installation

Use composer to install this package

    composer require acelaya/ze-content-based-error-handler

### Usage

This package includes an error response generator, the `Acelaya\ExpressiveErrorHandler\ErrorHandler\ContentBasedErrorResponseGenerator`, that can be used to replace default Zend Expressive implementations.

It composes a plugin manager that fetches a concrete error response generator at runtime, based on the Request's `Accept` header. Thus, you can use the Expressive's `ErrorResponseGenerator` to dispatch **text/html** request errors, Stratiglity's `ErrorResponseGenerator` for **text/plain** errors, etc.

You can also provide your own implementations for other content-types, like **application/json** or **text/xml**. The ContentBasedErrorResponseGenerator will automatically use the proper implementation.

### Provided configuration

To get things easily working, a `ConfigProvider` is included, which automatically registers all the dependencies in the service container (including the `Zend\Expressive\Middleware\ErrorResponseGenerator` service).

It also preregisters error handlers for html and plain text requests (The `Zend\Expressive\Middleware\ErrorResponseGenerator` and the `Zend\Stratigility\Middleware\ErrorResponseGenerator` as mentioned before).

```php
<?php
use Acelaya\ExpressiveErrorHandler\ErrorHandler\Factory\PlainTextResponseGeneratorFactory;
use Zend\Expressive\Container\ErrorResponseGeneratorFactory;

return [

    'error_handler' => [
        'default_content_type' => 'text/html',

        'plugins' => [
            'factories' => [
                'text/plain' => PlainTextResponseGeneratorFactory::class,
                'text/html' => ErrorResponseGeneratorFactory::class,
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

If your are using [zend config aggregator](https://github.com/zendframework/zend-config-aggregator), you can just pass the class name to it like this:

```php
return (new Zend\ConfigAggregator\ConfigAggregator([
    Acelaya\ExpressiveErrorHandler\ConfigProvider::class,
    // [...]
    new Zend\ConfigAggregator\ZendConfigProvider('config/autoload/{{,*.}global,{,*.}local}.php'),
], 'data/cache/app_config.php'))->getMergedConfig();
```

Also, if you are using the [zend component installer](https://docs.zendframework.com/zend-component-installer/) package, it will ask you to register the ConfigProvider when installed.

### Override configuration

If you need to override any of the content types, its as easy as defining the same plugin with a different value.

For example, it is very likely that you want to use Expressive's `WhoopsErrorResponseGenerator` in development environments.

Just define a local configuration file with this content and all the html requests will use it from now on:

```php
<?php
use Zend\Expressive\Container\WhoopsErrorResponseGeneratorFactory;
use Zend\Expressive\Container\WhoopsFactory;
use Zend\Expressive\Container\WhoopsPageHandlerFactory;

return [

    'dependencies' => [
        'factories' => [
            'Zend\Expressive\Whoops' => WhoopsFactory::class,
            'Zend\Expressive\WhoopsPageHandler' => WhoopsPageHandlerFactory::class,
        ]
    ],

    'error_handler' => [
        'plugins' => [
            'factories' => [
                'text/html' => WhoopsErrorResponseGeneratorFactory::class,
            ],
        ],
    ],

];
```

You will probably need to define other error handlers for different content types. You can do it by using the same structure.

```php
<?php
use App\ErrorHandler\Factory\XmlErrorResponseGeneratorFactory;
use App\ErrorHandler\JsonErrorResponseGenerator;

return [

    'error_handler' => [
        'plugins' => [
            'invokables' => [
                'application/json' => JsonErrorResponseGenerator::class,
            ],
            'factories' => [
                'text/xml' => XmlErrorResponseGeneratorFactory::class,
            ],
            'aliases' => [
                'application/x-json' => 'application/json',
                'text/json' => 'application/json',
            ],
        ],
    ],

];
```

With this configuration, the `ContentBasedErrorResponseGenerator` will create the proper `JsonErrorResponseGenerator` or `XmlErrorResponseGenerator` at runtime, to dispatch json or xml errors.

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
