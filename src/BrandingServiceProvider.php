<?php

declare(strict_types=1);

namespace Rimba\Branding;

use Rimba\Base\Services\BitesServiceProvider;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\Factory;


class BrandingServiceProvider extends BitesServiceProvider
{
    protected string $viewsPath = __DIR__ . '/../resources/views';

    protected function bootPackage(): void
    {
        $this->ensureBrandingExists();
        view()->share('brand', [
            ...$this->resolveBranding(),
            'wallpaper' => $this->resolveWallpaper(),
            'lobby' => $this->resolveLobby(),
            'favicon' => asset('branding/favicon.ico'),
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
        if (! is_dir(public_path('branding'))) { mkdir(public_path('branding'), 0755, true); }
        if (collect(glob(public_path('branding/wallpaper.*')))->isEmpty()) { copy(__DIR__.'/../resources/branding/wallpaper.png', public_path('branding/wallpaper.png')); }
        if (collect(glob(public_path('branding/lobby.*')))->isEmpty()) { copy(__DIR__.'/../resources/branding/lobby.png', public_path('branding/lobby.png')); }
        if (collect(glob(public_path('branding/favicon.*')))->isEmpty()) { copy(__DIR__.'/../resources/branding/favicon.png', public_path('branding/favicon.png')); }
        if (! file_exists(public_path('branding/text.json'))) { copy(__DIR__.'/../resources/branding/text.json', public_path('branding/text.json')); }
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
    protected function resolveLobby(): string
    {
        $lobby = collect(glob(public_path('branding/lobby.*')))->first();
        return asset('branding/'.basename($lobby));
    }
    protected function resolveBranding(): array
    {
        $file = public_path('branding/text.json');
        if (! file_exists($file)) {
            return [];
        }
        return json_decode(
            file_get_contents($file),
            true
        ) ?? [];
    }

}
