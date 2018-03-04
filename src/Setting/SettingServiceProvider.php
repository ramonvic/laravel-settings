<?php

namespace Umobi\Setting;

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

        if (! class_exists('CreateSettingsTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../../database/migrations/create_settings_table.php.stub' => database_path("/migrations/{$timestamp}_create_settings_table.php"),
            ], 'settings');
        }

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Setting', Setting::class);
        $this->app->singleton(SettingStorageContract::class, EloquentStorage::class);
    }
}
