<?php 
namespace Amir\Notifications\Providers;
use Illuminate\Support\ServiceProvider;

class FirebaseNotificationService extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/fcm.php', 'fcm'
        );
        
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/fcm.php' => config_path('fcm.php'),
        ], 'nagy-notify');
    }
}

