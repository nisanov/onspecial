<?php

declare(strict_types=1);

return [
    App\Provider\Providers\ConfigProvider::class,
    App\Provider\Providers\LoggerProvider::class,
    App\Provider\Providers\WhoopsProvider::class,
    App\Provider\Providers\ProfilerProvider::class,
    App\Provider\Providers\UrlProvider::class,
    App\Provider\Providers\ViewProvider::class,
    App\Provider\Providers\DbProvider::class,
    App\Provider\Providers\ModelsMetadataProvider::class,
    App\Provider\Providers\SessionProvider::class,
    App\Provider\Providers\CookiesProvider::class,
    App\Provider\Providers\CacheProvider::class,
    App\Provider\Providers\FlashSessionProvider::class,
    App\Provider\Providers\AuthenticationProvider::class,
    App\Provider\Providers\RedirectorProvider::class,
    App\Provider\Providers\RouterProvider::class,
    App\Provider\Providers\DispatcherProvider::class,
    App\Provider\Providers\MailerProvider::class,
];
