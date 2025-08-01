<?php

namespace App\Shared\Support;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocalizationHelper
{
    /**
     * Idiomas suportados pela aplicação
     */
    public const SUPPORTED_LOCALES = [
        'pt_BR' => [
            'name' => 'Português (BR)',
            'flag' => '🇧🇷',
            'short' => 'pt_BR'
        ],
        'en' => [
            'name' => 'English',
            'flag' => '🇺🇸',
            'short' => 'en'
        ]
    ];

    /**
     * Idioma padrão da aplicação
     */
    public const DEFAULT_LOCALE = 'pt_BR';

    /**
     * Obter todos os idiomas suportados
     *
     * @return array
     */
    public static function getSupportedLocales(): array
    {
        return self::SUPPORTED_LOCALES;
    }

    /**
     * Verificar se o idioma é suportado
     *
     * @param string $locale
     * @return bool
     */
    public static function isSupported(string $locale): bool
    {
        return array_key_exists($locale, self::SUPPORTED_LOCALES);
    }

    /**
     * Obter informações do idioma atual
     *
     * @return array
     */
    public static function getCurrentLocaleInfo(): array
    {
        $currentLocale = App::getLocale();
        return self::SUPPORTED_LOCALES[$currentLocale] ?? self::SUPPORTED_LOCALES[self::DEFAULT_LOCALE];
    }

    /**
     * Definir o idioma da aplicação
     *
     * @param string $locale
     * @return bool
     */
    public static function setLocale(string $locale): bool
    {
        if (!self::isSupported($locale)) {
            return false;
        }

        App::setLocale($locale);
        Session::put('locale', $locale);

        return true;
    }

    /**
     * Obter URL com parâmetro de idioma
     *
     * @param string $locale
     * @param string|null $url
     * @return string
     */
    public static function getUrlWithLocale(string $locale, ?string $url = null): string
    {
        $url = $url ?: request()->url();
        $queryParams = request()->query();
        $queryParams['lang'] = $locale;

        return $url . '?' . http_build_query($queryParams);
    }

    /**
     * Formatar data baseada no idioma atual
     *
     * @param \Carbon\Carbon|\DateTime|string $date
     * @param string $format
     * @return string
     */
    public static function formatDate($date, string $format = null): string
    {
        if (!$date) {
            return '';
        }

        $carbon = \Carbon\Carbon::parse($date);
        $locale = App::getLocale();

        // Definir formatos baseados no idioma
        if (!$format) {
            $format = match ($locale) {
                'pt_BR' => 'd/m/Y H:i',
                'en' => 'm/d/Y H:i',
                default => 'd/m/Y H:i'
            };
        }

        // Definir nomes dos meses/dias em português
        if ($locale === 'pt_BR') {
            $carbon->locale('pt_BR');
        }

        return $carbon->format($format);
    }

    /**
     * Obter direção do texto baseada no idioma
     *
     * @param string|null $locale
     * @return string
     */
    public static function getTextDirection(?string $locale = null): string
    {
        $locale = $locale ?: App::getLocale();
        
        // Todos os idiomas suportados são LTR (Left-to-Right)
        return 'ltr';
    }

    /**
     * Obter timezone baseado no idioma/região
     *
     * @param string|null $locale
     * @return string
     */
    public static function getTimezone(?string $locale = null): string
    {
        $locale = $locale ?: App::getLocale();
        
        return match ($locale) {
            'pt_BR' => 'America/Sao_Paulo',
            'en' => 'UTC',
            default => 'UTC'
        };
    }
} 