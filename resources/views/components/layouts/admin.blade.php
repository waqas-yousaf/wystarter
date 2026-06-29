<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <x-seo :title="(($title ?? 'Dashboard') . ' — ' . config('app.name') . ' Admin')" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @stack('head')
</head>
<body class="min-h-screen bg-gray-100">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-8">
                    <span class="text-lg font-bold text-gray-900">
                        {{ config('app.name') }} <span class="text-indigo-600">Admin</span>
                    </span>
                    <div class="flex gap-4">
                        <a href="{{ route('admin.main') }}"
                           class="text-sm font-medium {{ request()->routeIs('admin.main') ? 'text-indigo-600' : 'text-gray-600 hover:text-gray-900' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'text-indigo-600' : 'text-gray-600 hover:text-gray-900' }}">
                            Users
                        </a>
                        <a href="{{ route('admin.blog-posts.index') }}"
                           class="text-sm font-medium {{ request()->routeIs('admin.blog-posts.*') ? 'text-indigo-600' : 'text-gray-600 hover:text-gray-900' }}">
                            Blog
                        </a>
                        <a href="{{ route('admin.pages.index') }}"
                           class="text-sm font-medium {{ request()->routeIs('admin.pages.*') ? 'text-indigo-600' : 'text-gray-600 hover:text-gray-900' }}">
                            Pages
                        </a>
                        <a href="{{ route('admin.settings.index') }}"
                           class="text-sm font-medium {{ request()->routeIs('admin.settings.*') ? 'text-indigo-600' : 'text-gray-600 hover:text-gray-900' }}">
                            Settings
                        </a>
                        @feature('ecommerce_enabled')
                            <a href="{{ route('admin.shop.products.index') }}"
                               class="text-sm font-medium {{ request()->routeIs('admin.shop.*') ? 'text-indigo-600' : 'text-gray-600 hover:text-gray-900' }}">
                                Shop
                            </a>
                        @endfeature
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="text-sm text-gray-600 hover:text-gray-900 font-medium cursor-pointer">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
                {{ session('error') }}
            </div>
        @endif

        @isset($title)
            <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ $title }}</h1>
        @endisset

        {{ $slot }}
    </main>
    @stack('scripts')
</body>
</html>
