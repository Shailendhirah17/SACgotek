<?php

namespace MacsiDigital\Zoom;

use Illuminate\Support\ServiceProvider;

class ZoomServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('zoom', function () {
            return new Zoom();
        });
    }

    public function boot()
    {
    }
}
