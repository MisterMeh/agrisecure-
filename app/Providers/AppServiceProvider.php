<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Masbug\Flysystem\GoogleDriveAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Storage::extend('google', function ($app, $config) {
            $client = new \Google_Client();
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($config['refreshToken']);

            $service = new \Google_Service_Drive($client);

            $options = [];
            if (isset($config['folderId']) && $config['folderId']) {
                $options['teamDriveId'] = $config['folderId'];
            }

            $adapter = new GoogleDriveAdapter($service, $config['folderId'] ?? null);

            return new \League\Flysystem\Filesystem($adapter);
        });
    }
}
