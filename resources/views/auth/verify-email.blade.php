<x-guest-layout>
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-sm border border-gray-100">
        <!-- Welcome Header -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Welcome to BlogNest!</h1>
            <p class="text-gray-600">Your personal blogging sanctuary</p>
        </div>

        <!-- Verification Message -->
        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-blue-500 mt-0.5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="text-sm text-gray-700">
                        {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking the link we just emailed to you.') }}
                    </p>
                    @if (session('status') == 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-green-600">
                            {{ __('A new verification link has been sent to your email.') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="mb-8">
            <h3 class="font-medium text-gray-900 mb-2">What's next?</h3>
            <ul class="list-disc pl-5 space-y-1 text-sm text-gray-600">
                <li>Check your inbox for the verification email</li>
                <li>Explore BlogNest's features after verification</li>
                <li>Start publishing your first post</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-between gap-4">
            <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                @csrf
                <x-primary-button class="w-full justify-center">
                    <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <x-secondary-button class="w-full justify-center">
                    {{ __('Log Out') }}
                </x-secondary-button>
            </form>
        </div>

        <!-- Support Info -->
        <div class="mt-8 pt-6 border-t border-gray-100 text-center text-sm text-gray-500">
            <p>Didn't receive the email? Check your spam folder or <a href="#" class="text-blue-600 hover:text-blue-500">contact support</a>.</p>
        </div>
    </div>
</x-guest-layout>