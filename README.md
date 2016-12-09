# laravel-qwatcher

A full-lifecycle queue jobs watcher for Laravel 5.1+

[![Build Status](https://travis-ci.org/maqe/laravel-qwatcher.svg?branch=master)](https://travis-ci.org/maqe/laravel-qwatcher) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/maqe/laravel-qwatcher/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/maqe/laravel-qwatcher/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/maqe/laravel-qwatcher/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/maqe/laravel-qwatcher/?branch=master)

## Installation

Add package dependency to your project's `composer.json` file:

```json
"require": {
    "maqe/laravel-qwatcher": "dev-master"
}
```

Run composer update:

```bash
composer update maqe/laravel-qwatcher
```

Add package's service provider to your project's `config/app.php`:

```php
'providers' => array(
    Maqe\Qwatcher\QwatcherServiceProvider::class,
),
```

Add package's class aliases to your project's `config/app.php`:

```php
'aliases' => array(
    'Qwatcher'  => Maqe\Qwatcher\Facades\Qwatch::class,
),
```

You can publish the migration with:
```bash
php artisan vendor:publish --provider="Maqe\Qwatcher\QwatcherServiceProvider" --tag="migrations"
```

After the migration has been published you can create the media-table by running the migrations:

```bash
php artisan migrate
```


## Usage

### In your PHP project
Once Qwatcher is included in your project you may add it to any class by simply using the trait.

For example:

```php
use Maqe\Qwatcher\Traits\WatchableDispatchesJobs;

class Example {
    use WatchableDispatchesJobs;

    public function someMethod() {
        // WatchableDispatchesJobs trait allowed you to add additional info as an optional
        $this->dispatch(new Jobs(), array('key_addition_info1' => 'value_addition_info1'));
    }
}
```
## License
laravel-qwatcher is released under the MIT License.
