<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Domains\Auth\Providers\AuthServiceProvider::class,
    App\Domains\Customer\Providers\CustomerServiceProvider::class,
    App\Domains\Project\Providers\ProjectServiceProvider::class,
    App\Domains\Public\Providers\PublicServiceProvider::class,
    App\Domains\Update\Providers\UpdateServiceProvider::class,
    App\Domains\User\Providers\UserServiceProvider::class,
];
