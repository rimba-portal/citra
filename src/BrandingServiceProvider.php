<?php

declare(strict_types=1);

namespace Rimba\Branding;

use Rimba\Base\Services\BitesServiceProvider;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;


class BrandingServiceProvider extends BitesServiceProvider
{
    protected string $viewsPath = __DIR__ . '/../resources/views';

    protected function bootPackage(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn() => view('bites::panel.branding')
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_REGISTER_FORM_AFTER,
            fn() => view('bites::panel.branding')
        );

    }
    protected function registerPackage(): void
    {
        //
    }

}
