@props([
    'sidebar' => false,
])

@php
    $officeSetting = null;

    if (\Illuminate\Support\Facades\Schema::hasTable('office_settings')) {
        $officeSetting = \App\Models\OfficeSetting::query()->first();
    }

    $brandName = $officeSetting?->office_name ?: config('app.name', 'Laravel');
    $defaultLogoPath = asset('assets/logo/logo.jpeg');
    $logoPath = $officeSetting?->logo_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($officeSetting->logo_path) : $defaultLogoPath;
@endphp

@if($sidebar)
    <flux:sidebar.brand :name="$brandName" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground overflow-hidden">
            @if($logoPath)
                <img src="{{ $logoPath }}" alt="Logo" class="size-8 object-cover" />
            @else
                <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
            @endif
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="$brandName" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground overflow-hidden">
            @if($logoPath)
                <img src="{{ $logoPath }}" alt="Logo" class="size-8 object-cover" />
            @else
                <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
            @endif
        </x-slot>
    </flux:brand>
@endif
