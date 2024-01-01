<?php

namespace VariableSign\Sms;

use Illuminate\Support\ServiceProvider;
use VariableSign\Sms\Channels\SmsChannel;
use Illuminate\Support\Facades\Notification;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'sms');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'sms');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/sms.php' => config_path('sms.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/sms'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/sms'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/sms'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }

        // Bind the main class to use with the facade
        $this->app->bind('sms', function () {
            return new Sms;
        });

        if (config('sms.channel_name')) {
            Notification::extend(config('sms.channel_name'), function ($app) {
                return new SmsChannel();
            });
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/sms.php', 'sms');
    }
}
