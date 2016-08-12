# Zend Expressive ContentBasedErrorHandler

A Zend Expressive error handler which allows to implement different strategies based on the accepted content-types.

### Context

This package has been created following this article https://blog.alejandrocelaya.com/2016/07/29/creating-a-content-based-error-handler-for-zend-expressive/.

On it, I demonstrate how to implement an strategy-based system which generates different error responses by taking into account the request's `Accept` header.

After writing the article I have decided to create this package, so that everybody can install and use the solution on their own projects.

### Installation

Use composer to install this package

    composer require acelaya/ze-content-based-error-handler

### Usage

This package includes an error handler, the `Acelaya\Expressive\ErrorHandler\ContentBasedErrorHandler`, that can be used to replace default Zend Expressive implementations.

It composes a plugin manager that fetches a concrete error handler at runtime, based on the Request's `Accept` header. Thus, you can use the Expressive's `TemplatedErrorHandler` to dispatch **text/html** request errors, Stratiglity's `FinalHandler` for **text/plain** errors, etc.

You can also provide your own implementations for other content-types, like **application/json** or **text/xml**. The ContentBasedErrorHandler will automatically use the proper implementation.

### Provided configuration

To get things easily working, a `ConfigProvider` is included, which automatically registers all the dependencies for the service container (including the `Zend\Expressive\ErroHandler` service).

It also registers error handlers for html and plain text requests.

### Override configuration

### Log errors
