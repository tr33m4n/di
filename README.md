# di
A very simple DI container and auto-wiring implementation

## Basic Usage
### Configuration
Creating and getting classes using the container can be done without any configuration, however configuring the container will allow for class preferences to be set as well as class parameters. For example:
```php
return [
    Container\GetPreference::CONFIG_KEY => [
        \Some\Interface\To\Implement::class => \Some\Class\To\Bind::class
    ],
    Container\GetParameters::CONFIG_KEY => [
        \Some\Class\To\Bind::class => [
            'testParam' => 'something',
            'anotherClass' => \Some\Class\Argument::class 
        ]
    ]
];
```
### `get`
The simplest usage is without providing any configuration. All constructor arguments of class `\Some\Class\To\Get` when called with `get` will be auto-wired:
```php
<?php

use tr33m4n\Di\Container;
use tr33m4n\Di\Container\GetParameters;
use tr33m4n\Di\Container\GetPreference;

// Manually initialising the container
$container = new Container(new GetParameters(), new GetPreference());

// `get` class from container (class constructor arguments will auto-wire). The instantiated class will be cached for subsequent calls
$myInitialisedClass = $container->get(\Some\Class\To\Get::class);
```
### `create`
New instances of a class can be created outside the configured container using the `create` method:
```php
<?php

use tr33m4n\Di\Container;
use tr33m4n\Di\Container\GetParameters;
use tr33m4n\Di\Container\GetPreference;

// Manually initialising the container
$container = new Container(new GetParameters(), new GetPreference());

// Create a new instance of a class which is not cached, with all constructor arguments auto-wired
$myInitialisedClass = $container->create(\Some\Class\To\Create::class);

// Create a new instance of a class which is not cached, with all but the `testParam` arguments auto-wired. The `testParam` will be initialised with `something`
$myInitialisedClassWithParameters = $container->create(\Some\Class\To\Create::class, ['testParam' => 'something']);
```
## Todo
- [ ] Flesh out configuration instructions
- [ ] Add helper function instructions to README
- [ ] Fix parameter iteration
