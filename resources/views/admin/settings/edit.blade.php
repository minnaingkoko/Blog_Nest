<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('General Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Settings Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6">
                    @csrf

                    <!-- Site Name -->
                    <div class="mb-6">
                        <label class="block font-medium text-gray-700 mb-2">Site Name</label>
                        <input type="text" name="site_name" value="{{ $settings->site_name }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                               placeholder="Enter site name" required>
                    </div>

                    <!-- Logo Path -->
                    <div class="mb-6">
                        <label class="block font-medium text-gray-700 mb-2">Logo Path</label>
                        <input type="text" name="logo_path" value="{{ $settings->logo_path }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                               placeholder="Enter logo file path">
                        <p class="text-sm text-gray-500 mt-1">Optional. Path to your logo file in storage.</p>
                    </div>

                    <!-- Owner Email -->
                    <div class="mb-6">
                        <label class="block font-medium text-gray-700 mb-2">Owner Email</label>
                        <input type="email" name="owner_email" value="{{ $settings->owner_email }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                               placeholder="Enter owner email" required>
                        <p class="text-sm text-gray-500 mt-1">Used for admin notifications.</p>
                    </div>

                    <!-- Color Theme -->
                    <div class="mb-6">
                        <label class="block font-medium text-gray-700 mb-2">Color Theme</label>
                        <select name="color_theme" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500">
                            <option value="light" {{ $settings->color_theme === 'light' ? 'selected' : '' }}>Light</option>
                            <option value="dark" {{ $settings->color_theme === 'dark' ? 'selected' : '' }}>Dark</option>
                        </select>
                        <p class="text-sm text-gray-500 mt-1">Choose the default color theme for the site.</p>
                    </div>

                    <!-- Social Links -->
                    <div class="mb-6">
                        <label class="block font-medium text-gray-700 mb-2">Social Links</label>
                        <textarea name="social_links" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                                  placeholder='{"facebook":"https://facebook.com","twitter":"https://twitter.com"}'>{!! $settings->social_links ? json_encode($settings->social_links, JSON_PRETTY_PRINT) : '' !!}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Enter as JSON. Example: {"facebook":"https://facebook.com","twitter":"https://twitter.com"}</p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>