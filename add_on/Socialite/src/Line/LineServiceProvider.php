<?php

namespace Add_on\Socialite\Line;

use Laravel\Socialite\SocialiteServiceProvider;
use Add_on\Socialite\Line\LineProvider;
use Socialite;

//Socialite::driver()処理を定義しているSocialiteManagerをインスタンス化しているSocialiteServiceProviderを継承
class LineServiceProvider extends SocialiteServiceProvider
{
    public function boot(){
        \Socialite::extend('line', function ($app) {
            $config = $this->app['config']['services.line'];
            //buildProviderメソッドはSocialiteManagerで定義
            return \Socialite::buildProvider(LineProvider::class, $config);
        });
    }
}