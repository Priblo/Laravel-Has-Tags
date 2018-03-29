# Laravel Has Tags
##### Performance centric model tags trait
[![Build Status](https://travis-ci.org/Priblo/Laravel-Has-Tags.svg?branch=master)](https://travis-ci.org/Priblo/Laravel-Has-Tags)


## Trait Usage

Add the trait to any Eloqent model you need to become taggable:

```php
Priblo\LaravelHasTags\Traits\HasTags;
```

Usage:

```php
$Post = new Post();

// Tag Model
// (Duplicates are automatically handled)
$Post->tag(['tag1', 'tag2', 'tag3', 'tag1']);
// Tag Model with type
$Post->tag(['tag1', 'tag2', 'tag3', 'tag1'], 'hashtag');

// Count ALL tags attached to the model
$Post->tags->count()
// Count ONLY tags with type
$Post->tagsWithType('hashtag')->count()

// Retag model
// Will remove previous tags and add the new ones
// Type is optional
$Post->reTag(['tag5', 'tag6'],'hashtag');
// Removes ALL tags without type from Model
$Post->untag()
// Removes ONLY tags with the specified type from Model
$Post->untag('hashtag')
// Removes ALL tags from model
$Post->untagALL()

// Retrieves all tagged models with the specified tag and type (optional)
$posts = Post::withAnyTag(['tag1'],'hashtag')->get();

// Retrieves all related models according to Tag and type (optional)
// Returns a collection of Priblo\LaravelHasTags\Models\Related
$relatedCollection = Post::getRelatedByTag('tag1', 'hashtag));
```

**Note on types**

Every tag has a type, which if unspecified will be NULL, a null type is still a type, which means that using the ```untag()``` method will remove ONLY tags with ```type === NULL```.
This is a design choice.

#### 

## Config

### Cache
**Caching requires a driver which supports tags**. File and Database won't work, redis is suggested. Please make sure to either disable caching in the config or use the array driver for local development.

In the *has-tags.php* config file you can enable/disable caching and set the cache expiration time.

**Caching is enabled by default**

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
php artisan vendor:publish --provider="Priblo\LaravelHasTags\LaravelServiceProvider" --tag="config"
```

Then migrate:

```
php artisan migrate
```

### Why another tagging trait?
At [Priblo](https://www.priblo.com) we couldn't find a suitable tagging trait for Laravel. Each one fell short for some reason or another. Mainly in the performance department.
Tags are an important part of Priblo and we needed to get them right. Opting for the [Decorator pattern](https://en.wikipedia.org/wiki/Decorator_pattern) we put an emphasis on caching and performance.
We considered contributing to other projects, but none were in line with our design philosophy. So here it is our implementation, hoping it will be useful to someone else.
