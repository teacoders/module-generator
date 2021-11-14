<p align="center"><a href="https://teacoders.in" target="_blank"><img src="https://teacoders.in/images/logo.png" width="400"></a></p>

# Module Generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/teacoders/module-generator.svg?style=flat-square)](https://packagist.org/packages/teacoders/module-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/teacoders/module-generator.svg?style=flat-square)](https://packagist.org/packages/teacoders/module-generator)

## About Teacoders

We craft web applications & open source packages in the Laravel ecosystem.

## Installation

You can install the package via composer:

```bash
composer require teacoders/module-generator
```
Run the command below to publish the package config file

```bash
php artisan vendor:publish --provider="TeaCoders\ModuleGenerator\ModuleServiceProvider"
```
Run the command below to generate all the required files for the module(model, controller, migration, request, view, route)

```
php artisan make:all <module name> (Product or ServiceCategory)
```

after that it will ask  "Do you want to add columns in migration ? (yes/no) [no]":

if you enter yes/y then it will ask comma(,) seperated column names (name,avatar)

if you hit enter then it will ask data types for these columns, press enter after selecting data types

after that it will ask that "Do you want to create request class?" if you enter yes/y then it will create request class and validations for your migration columns, if you enter no/n then it will add validations in controller.

Run the command below to generate view 

```
php artisan make:view <view name>
```
Run the given command to generate specific view file inside the view directory 
```
php artisan make:view <view directory name> --file=filename
```
Run the given command to delete whole module(model, controller, migration, request, view, route)

```
php artisan delete:all <module name> (Product)

```

Run the below command to delete specific file from module

```
php artisan delete:all <module name> -c|--controller, -m|--model, -r|--request, -t|--table, -b|--blade
```

Run the below command to delete view

```
php artisan delete:view <view name>
```
Run the below command to delete specific view file inside the view directory

```
php artisan delete:view <view name> --file=filename
```
Run the command below to generate trait

```
php artisan make:trait <trait1 trait2> // you can also create multiple traits
```
Run the command below to delete trait

```
php artisan delete:trait <trait1 trait2> // you can also delete multiple traits
```

If you want to generate more view files then change in `config/module-generator.php`
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

### Security

If you discover any security related issues, please email support@teacoders.in instead of using the issue tracker.

## Credits

- [Moolchand Sharma](https://github.com/technical-ms)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.