<?php

declare(strict_types=1);

namespace Rimba\Branding;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\Factory;
use Rimba\Base\Services\BitesServiceProvider;

class BrandingServiceProvider extends BitesServiceProvider
{
    protected string $viewsPath = __DIR__.'/../resources/views';

    protected function bootPackage(): void
    {
        $this->ensureBrandingExists();
        view()->share('brand', [
            'wallpaper' => $this->resolveWallpaper(),
            'slogan' => $this->resolveSlogan(),
            'favicon' => asset('branding/favicon.ico'),
            // 'logo'      => asset('branding/logo.png'),
        ]);
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
            fn (): Factory|\Illuminate\Contracts\View\View => view('bites::panel.branding')
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::AUTH_REGISTER_FORM_AFTER,
            fn (): Factory|\Illuminate\Contracts\View\View => view('bites::panel.branding')
        );

    }

    protected function registerPackage(): void
    {
        //
    }

    protected function ensureBrandingExists(): void
    {
        if (! is_dir(public_path('branding'))) {
            mkdir(public_path('branding'), 0755, true);
        }

        if (collect(glob(public_path('branding/wallpaper.*')))->isEmpty()) {
            copy(__DIR__.'/../resources/branding/wallpaper.png', public_path('branding/wallpaper.png'));
        }

        if (collect(glob(public_path('branding/favicon.*')))->isEmpty()) {
            copy(__DIR__.'/../resources/branding/favicon.png', public_path('branding/favicon.png'));
        }

        if (! file_exists(public_path('branding/slogan.txt'))) {
            copy(__DIR__.'/../resources/branding/slogan.txt', public_path('branding/slogan.txt'));
        }

        // if (collect(glob(public_path('branding/logo.*')))->isEmpty()) {
        //     copy(__DIR__ . '/../resources/branding/logo.png', public_path('branding/logo.png'));
        // }
    }

    protected function copyIfMissing(string $source, string $targetPattern, string $target): void
    {
        if (
            collect(glob($targetPattern))->isEmpty()
            && file_exists($source)
        ) {
            copy($source, $target);
        }
    }

    protected function resolveWallpaper(): string
    {
        $wallpaper = collect(glob(public_path('branding/wallpaper.*')))->first();

        return asset('branding/'.basename($wallpaper));
    }

    protected function resolveSlogan(): string
    {
        return trim(file_get_contents(public_path('branding/slogan.txt')));
    }
}
