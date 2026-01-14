<?php

namespace TomatoPHP\FilamentLogger;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use TomatoPHP\FilamentLogger\EventServiceProvider;
use TomatoPHP\FilamentLogger\Services\Benchmark;

class FilamentLoggerServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-logger';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile('filament-logger')
            ->hasMigrations()
            ->hasTranslations()
            ->hasCommand(\TomatoPHP\FilamentLogger\Console\FilamentLoggerInstall::class);
    }

    public function packageRegistered(): void
    {
        Benchmark::start(config('filament-logger.request.benchmark', 'application'));

        $this->app->bind('filament-logger', function () {
            return new \TomatoPHP\FilamentLogger\Services\LoggerServices();
        });
    }

    public function packageBooted(): void
    {
        $this->app->register(EventServiceProvider::class);
    }
}
