[![Build Status](https://travis-ci.org/umobi/laravel-settings.svg?branch=master)](https://travis-ci.org/umobi/laravel-settings)

# Persistent Settings Manager for Laravel

 * Simple key-value storage
 * Support multi-level array (dot delimited keys) structure.
 * Localization supported.

## Installation

1. Install package

    ```bash
    composer require umobi/laravel-settings
    ```

1. Edit config/app.php (Skip this step if you are using laravel 5.5+)

    service provider:

    ```php
    Umobi\Setting\SettingServiceProvider::class,
    ```

    class aliases:

    ```php
    'Setting' => Umobi\Setting\SettingFacade::class,
    ```

1. Create settings table

    ```bash
    php artisan vendor:publish --tag=settings
    php artisan migrate
    ```

## Usage

```php
Setting::get('name', 'Computer');
// get setting value with key 'name'
// return 'Computer' if the key does not exists

Setting::lang('zh-TW')->get('name', 'Computer');
// get setting value with key and language

Setting::set('name', 'Computer');
// set setting value by key

Setting::lang('zh-TW')->set('name', 'Computer');
// set setting value by key and language

Setting::has('name');
// check the key exists, return boolean

Setting::lang('zh-TW')->has('name');
// check the key exists by language, return boolean

Setting::forget('name');
// delete the setting by key

Setting::lang('zh-TW')->forget('name');
// delete the setting by key and language
```

## Dealing with array

```php
Setting::get('item');
// return null;

Setting::set('item', ['USB' => '8G', 'RAM' => '4G']);
Setting::get('item');
// return array(
//     'USB' => '8G',
//     'RAM' => '4G',
// );

Setting::get('item.USB');
// return '8G';
``
