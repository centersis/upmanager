<?php

namespace App\Domains\System\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class LocaleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $this->determineLocale($request);
        
        // Normalize pt-BR to pt_BR for Laravel's pluralization to work correctly
        if ($locale === 'pt-BR') {
            $locale = 'pt_BR';
        }
        
        App::setLocale($locale);
        
        return $next($request);
    }
    
    private function determineLocale(Request $request): string
    {
        $supportedLocales = ['en', 'pt_BR'];
        
        // 1. Check if user is authenticated and has locale preference
        if ($request->user() && $request->user()->locale) {
            $userLocale = $request->user()->locale;
            if (in_array($userLocale, $supportedLocales)) {
                return $userLocale;
            }
        }
        
        // 2. Check query string parameter
        if ($queryLocale = $request->query('lang')) {
            if (in_array($queryLocale, $supportedLocales)) {
                // Set cookie for future visits
                Cookie::queue('locale', $queryLocale, 60 * 24 * 365); // 1 year
                return $queryLocale;
            }
        }
        
        // 3. Check session
        if ($sessionLocale = $request->session()->get('locale')) {
            if (in_array($sessionLocale, $supportedLocales)) {
                return $sessionLocale;
            }
        }
        
        // 4. Check cookie (for unauthenticated users)
        if ($cookieLocale = $request->cookie('locale')) {
            if (in_array($cookieLocale, $supportedLocales)) {
                return $cookieLocale;
            }
        }
        
        // 5. Check Accept-Language header
        $preferredLanguage = $request->getPreferredLanguage(['en', 'pt_BR', 'pt']);
        if ($preferredLanguage === 'pt' || $preferredLanguage === 'pt_BR') {
            return 'pt_BR';
        }
        
        // 6. Default fallback
        return config('app.locale', 'pt_BR');
    }
} 