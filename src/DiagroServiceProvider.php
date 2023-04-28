<?php
namespace Laravel\Diagro;

use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Foundation\Http\Kernel;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Diagro\Console\Commands\DiagroMigration;
use Laravel\Diagro\Console\Commands\DiagroModel;
use Laravel\Diagro\Console\Commands\DiagroRequest;

/**
 * Bridge between package and laravel backend application.
 *
 * @package Diagro\Backend
 */
class DiagroServiceProvider extends ServiceProvider
{


    public function register()
    {
        $this->app->singleton('diagro.creator', function ($app) {
            return new MigrationCreator($app['files'], __DIR__ . '/../stubs');
        });
    }


    /**
     * Boot me up Scotty!
     */
    public function boot(Kernel $kernel)
    {
        //drop invalid keys
        Validator::excludeUnvalidatedArrayKeys();

        //always use https in production, ALWAYS! It's the future
        URL::forceScheme('https');

        //commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                DiagroRequest::class,
                DiagroModel::class,
                DiagroMigration::class,
            ]);
        }
    }


}