<?php

namespace App\Providers;

use App\DominoGame\Game;
use App\DominoGame\Resources\DominoTable;
use Illuminate\Support\ServiceProvider;
use LaravelZero\Framework\Application;

class GameServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DominoTable::class, function ($app) {
            return new DominoTable();
        });

        $this->app->singleton(Game::class, function (Application $app) {
            return new Game(
                $app->get(DominoTable::class)
            );
        });
    }
}
