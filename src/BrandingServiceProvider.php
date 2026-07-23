<?php

declare(strict_types=1);

namespace Rimba\Branding;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Rimba\Base\Services\BitesServiceProvider;

class BrandingServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected string $viewsPath = __DIR__.'/../resources/views';

    protected function bootPackage(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn () => view('panel.branding')
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_REGISTER_FORM_AFTER,
            fn () => view('panel.branding')
        );

    }

    protected function registerPackage(): void
    {
        //
    }
}
