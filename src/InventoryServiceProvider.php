<?php

namespace Stevebauman\Inventory;

use Illuminate\Support\ServiceProvider;

/**
 * Class InventoryServiceProvider.
 *
 * @package Stevebauman\Inventory
 * @version 1.9.1
 */
class InventoryServiceProvider extends ServiceProvider
{
    /**
     * Inventory version.
     *
     * @var string
     */
    const VERSION = '1.9.1';

    /**
     * Stores the package configuration separator
     * for Laravel 5 compatibility.
     *
     * @var string
     */
    public static $packageConfigSeparator = '::';

    /**
     * The laravel version number. This is
     * used for the install commands.
     *
     * @var int
     */
    public static $laravelVersion = 8;

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the service provider.
     */
    public function boot()
    {
        /*
         * If the package method exists, we're using Laravel 4, if not, we're on 5
         */
        if (method_exists($this, 'package')) {
            $this->package('stevebauman/inventory', 'stevebauman/inventory', __DIR__.'/..');
        } else {
            /*
             * Set the proper configuration separator since
             * retrieving configuration values in packages
             * changed from '::' to '.'
             */
            $this::$packageConfigSeparator = '.';

            /*
             * Set the local inventory laravel version for easy checking
             */
            $this::$laravelVersion = 5;

            /*
             * Load the inventory translations from the inventory lang folder
             */
            $this->loadTranslationsFrom(__DIR__.'/lang', 'inventory');

            /*
             * Assign the configuration as publishable, and tag it as 'config'
             */
            $this->publishes([
                __DIR__.'/config/config.php' => config_path('inventory.php'),
            ], 'config');

            /*
             * Assign the migrations as publishable, and tag it as 'migrations'
             */
            $this->publishes([
                __DIR__.'/migrations/' => base_path('database/migrations'),
            ], 'migrations');
        }
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        // Load the inventory translations from the inventory lang folder
        $this->loadTranslationsFrom(__DIR__.'/Lang', 'inventory');

        // Assign the configuration as publishable, and tag it as 'config'
        $this->publishes([
            __DIR__.'/Config/config.php' => config_path('inventory.php'),
        ], 'config');

        // Assign the migrations as publishable, and tag it as 'migrations'
        $this->publishes([
            __DIR__.'/Migrations/' => base_path('database/migrations'),
        ], 'migrations');
    }

    /**
     * {@inheritdoc}
     */
    public function provides()
    {
        return ['inventory'];
    }
}
