<x-layouts.guest title="Verify your email">
    <div class="text-center mb-6">
        <p class="text-sm text-gray-600">
            Thanks for signing up! Before getting started, please verify your email address by clicking
            the link we just emailed to you. If you didn't receive the email, we'll gladly send another.
        </p>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit"
                class="w-full rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
            Resend verification email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit"
                class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
            Log out
        </button>
    </form>
</x-layouts.guest>
