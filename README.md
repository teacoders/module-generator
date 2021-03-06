# Module Generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/teacoders/module-generator.svg?style=flat-square)](https://packagist.org/packages/teacoders/module-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/teacoders/module-generator.svg?style=flat-square)](https://packagist.org/packages/teacoders/module-generator)

## Installation

You can install the package via composer:

```bash
composer require teacoders/module-generator
```
Run the command below to publish the package config file config/modulegenerator.php

```bash
php artisan vendor:publish //select publish module generator config file
```
Run the command below to generate all(controller,model,migration,view,route)

```
php artisan make:all <module name>
```
Run the command below to generate view 

```
php artisan make:view <directory name>
```
Run the command to generate specific view file inside the directory 
```
php artisan make:view <directory name> --file=filename
```
Run the below command to delete view

```
php artisan delete:view <directory name>
```
Run the below command to delete specific view file inside the directory

```
php artisan delete:view <directory name> --file=filename
```
Run the command below to generate trait

```
php artisan make:trait <traitname>
```
Run the command below to delete trait

```
php artisan delete:trait <traitname>
```

If you want to generate more view files then change in `config/modulegenerator.php`
```
return [
    'files' => [
        'create',
        'edit',
        'index',
        'show', 
        // add more file names
    ]
];
```
## Documentation

You can find the documentation on the [TeaCoders website](https://teacoders.in).

### Security

If you discover any security related issues, please email support@teacoders.in instead of using the issue tracker.

## Credits

- [Moolchand Sharma](https://github.com/technical-ms)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
