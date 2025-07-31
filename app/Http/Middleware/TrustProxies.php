<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Illuminate\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    protected $proxies = '*';
    protected $headers = [
        'X_FORWARDED_FOR',
        'X_FORWARDED_HOST',
        'X_FORWARDED_PORT',
        'X_FORWARDED_PROTO',
    ];
}
