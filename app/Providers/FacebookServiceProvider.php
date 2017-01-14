<?php

namespace App\Providers;

use App\Services\Facebook;
use Illuminate\Support\ServiceProvider;

class FacebookServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;
    
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() : void
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() : void
    {
        $this->app->singleton(Facebook::class, function ($app) {
            $fb = new Facebook([
                'app_id' => env('FACEBOOK_APP_ID'),
                'app_secret' => env('FACEBOOK_APP_SECRET'),
                'default_graph_version' => 'v2.8',
            ]);
            
            $fb->setDefaultAccessToken(
                env('FACEBOOK_APP_ID') . '|' . env('FACEBOOK_APP_SECRET')
            );
            
            return $fb;
        });
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() : array
    {
        return [Facebook::class];
    }
}
