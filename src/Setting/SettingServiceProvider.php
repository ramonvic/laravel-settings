<?php

namespace Unisharp\Setting;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Class SettingServiceProvider.
 */
class SettingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $filename = '2015_08_06_184708_create_settings_table.php';

        $this->publishes([
            __DIR__.'/../../database/migrations/'.$filename => base_path('/database/migrations/'.$filename),
        ], 'settings');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $method = version_compare(Application::VERSION, '5.2', '>=') ? 'singleton' : 'bindShared';
        $this->app->$method('Setting', Setting::class);
        $this->app->$method(SettingStorageContract::class, EloquentStorage::class);
    }
}
