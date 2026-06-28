<x-layouts.admin title="Dashboard">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-500">Total Users</p>
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-500">Admin Users</p>
            <p class="mt-1 text-3xl font-bold text-indigo-600">{{ $stats['admin_users'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <p class="text-sm font-medium text-gray-500">Regular Users</p>
            <p class="mt-1 text-3xl font-bold text-gray-900">{{ $stats['total_users'] - $stats['admin_users'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-base font-semibold text-gray-900">Recent Users</h2>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                View all →
            </a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse ($stats['recent_users'] as $user)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        @foreach ($user->roles as $role)
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ $role->name === 'admin' ? 'bg-indigo-50 text-indigo-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                        <span class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                <p class="px-6 py-8 text-center text-sm text-gray-400">No users yet.</p>
            @endforelse
        </div>
    </div>
</x-layouts.admin>
