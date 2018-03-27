# Laravel Has Tags
##### Performance centric model tags trait
[![Build Status](https://travis-ci.org/Priblo/Laravel-Has-Tags.svg?branch=master)](https://travis-ci.org/Priblo/Laravel-Has-Tags)

## Install

_Requires: Laravel >=5.5 and PHP 7.1_

**Composer**

```
    composer require priblo/laravel-has-tags
```

**Laravel**

This package supports Auto Discovery.

If you have disabled it, you can install this package by adding to the 'providers' array in *./config/app.php*

```php
Priblo\LaravelHasTags\LaravelServiceProvider::class,
```


Then run:

```
php artisan vendor:publish --provider="Priblo\LaravelHasTags\LaravelServiceProvider" --tag="migrations"
```

Then migrate:

```
php artisan migrate
```

### Why another tagging trait?
At [Priblo](https://www.priblo.com) we couldn't find a suitable tagging trait for Laravel. Each one fell short for some reason or another. Mainly in the performance department.
Tags are an important part of Priblo and we needed to get them right. Opting for the [Decorator pattern](https://en.wikipedia.org/wiki/Decorator_pattern) we put an emphasis on caching and performance.
We considered contributing to other projects, but none were in line with our design philosophy. So here it is our implementation, hoping it will be useful to someone else.
