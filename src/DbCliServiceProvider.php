<?php

namespace Roycedev\DbCli;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Roycedev\DbCli\Console\MakeDbTableCommand;
use Roycedev\DbCli\Console\MakeMigrationCommand;

class DbCliServiceProvider extends ServiceProvider
{
    /**
     * Run service provider boot operations.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeMigrationCommand::class,
            ]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([MakeMigrationCommand::class]);
    }
}
