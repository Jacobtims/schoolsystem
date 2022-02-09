<?php

namespace App\Providers;

use App\Models\Setting;
use Cache;
use Illuminate\Support\ServiceProvider;
use Schema;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (Schema::hasTable('settings')) {
            $settings = Cache::remember('settings', 24 * 60, function () {
                return Setting::all(['name', 'value'])->keyBy('name')->transform(function ($setting) {
                    return $setting->value;
                })->toArray();
            });

            config()->set('settings', $settings);
        }
    }
}
