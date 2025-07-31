@php
use App\Shared\Support\LocalizationHelper;
$currentLocale = app()->getLocale();
$currentInfo = LocalizationHelper::getCurrentLocaleInfo();
$supportedLocales = LocalizationHelper::getSupportedLocales();
@endphp

<div class="relative inline-block text-left">
    <div>
        <button type="button" class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors duration-200" id="language-menu-button" aria-expanded="false" aria-haspopup="true">
            {{ $currentInfo['flag'] }} {{ $currentInfo['name'] }}
            <svg class="-mr-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <div class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="language-menu-button" tabindex="-1" id="language-menu">
        <div class="py-1" role="none">
            @foreach($supportedLocales as $locale => $info)
                <a href="{{ LocalizationHelper::getUrlWithLocale($locale) }}" 
                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-200 {{ $currentLocale === $locale ? 'bg-gray-100 text-gray-900 font-medium' : '' }}" 
                   role="menuitem" tabindex="-1">
                    <span class="mr-2">{{ $info['flag'] }}</span>
                    {{ $info['name'] }}
                    @if($currentLocale === $locale)
                        <svg class="ml-auto h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const button = document.getElementById('language-menu-button');
    const menu = document.getElementById('language-menu');
    
    if (button && menu) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            menu.classList.toggle('hidden');
            button.setAttribute('aria-expanded', menu.classList.contains('hidden') ? 'false' : 'true');
        });
        
        // Fechar menu ao clicar fora
        document.addEventListener('click', function(e) {
            if (!button.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
                button.setAttribute('aria-expanded', 'false');
            }
        });
        
        // Fechar menu com ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                menu.classList.add('hidden');
                button.setAttribute('aria-expanded', 'false');
                button.focus();
            }
        });
    }
});
</script> 