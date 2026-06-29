<x-layouts.admin title="System Settings">
    <div class="space-y-6">

        {{-- Brand Assets + Google Analytics (main form) --}}
        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                {{-- Brand Assets --}}
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-6 space-y-6">
                    <h2 class="text-base font-semibold text-gray-900">Brand Assets</h2>

                    {{-- Logo --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Logo <span class="text-gray-400 text-xs font-normal">saved as logo.png</span>
                        </label>
                        @if ($hasLogo)
                            <img src="{{ asset('images/logo.png') }}?t={{ time() }}" alt="Logo"
                                 class="mb-2 h-12 w-auto rounded border border-gray-200 bg-gray-50 object-contain p-1">
                        @endif
                        <input type="file" name="logo" accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-3 file:rounded file:border-0 file:bg-indigo-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('logo')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Logo Light --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Logo (Light) <span class="text-gray-400 text-xs font-normal">saved as logo-light.png</span>
                        </label>
                        @if ($hasLogoLight)
                            <img src="{{ asset('images/logo-light.png') }}?t={{ time() }}" alt="Logo Light"
                                 class="mb-2 h-12 w-auto rounded border border-gray-200 bg-gray-800 object-contain p-1">
                        @endif
                        <input type="file" name="logo_light" accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-3 file:rounded file:border-0 file:bg-indigo-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('logo_light')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Favicon --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Favicon <span class="text-gray-400 text-xs font-normal">saved as favicon.png</span>
                        </label>
                        @if ($hasFavicon)
                            <img src="{{ asset('images/favicon.png') }}?t={{ time() }}" alt="Favicon"
                                 class="mb-2 h-8 w-8 rounded border border-gray-200 bg-gray-50 object-contain p-0.5">
                        @endif
                        <input type="file" name="favicon" accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-3 file:rounded file:border-0 file:bg-indigo-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-indigo-700 hover:file:bg-indigo-100">
                        @error('favicon')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-5">

                    {{-- Save button --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <h3 class="mb-3 text-sm font-semibold text-gray-900">Save Changes</h3>
                        <button type="submit"
                                class="w-full rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                            Save Settings
                        </button>
                    </div>

                    {{-- Tools --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-4 space-y-3">
                        <h3 class="text-sm font-semibold text-gray-900">Tools</h3>

                        <form method="POST" action="{{ route('admin.settings.clear-cache') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Clear Cache
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.settings.backup') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Download DB Backup
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Google Analytics --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="mb-1 text-base font-semibold text-gray-900">Google Analytics</h2>
                <p class="mb-4 text-xs text-gray-500">Paste your full GA script tag. It will be injected into the frontend.</p>
                <textarea id="google_analytics_code" name="google_analytics_code" rows="5"
                          placeholder="<script async src=&quot;https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXX&quot;></script>"
                          class="block w-full rounded-lg border border-gray-300 px-3 py-2 font-mono text-xs shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">{{ old('google_analytics_code', $googleAnalyticsCode) }}</textarea>
                @error('google_analytics_code')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </form>
    </div>
</x-layouts.admin>
