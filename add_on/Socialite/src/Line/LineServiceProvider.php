<?php

namespace Add_on\Socialite\Line;

use Laravel\Socialite\SocialiteServiceProvider;
use Add_on\Socialite\Line\LineProvider;

class LineServiceProvider extends SocialiteServiceProvider
{
    public function boot(){
        \Socialite::extend('', function ($app) {
            $config = $this->app['config']['services.line'];
            return \Socialite::buildProvider(LineProvider::class, $config);
        });
    }
}