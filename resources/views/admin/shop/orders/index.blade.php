<x-layouts.admin title="Orders">
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($orders as $order)
                    <tr>
                        <td class="px-4 py-3 text-xs font-mono text-gray-500">{{ substr($order->id, 0, 8) }}…</td>
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $order->user->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">${{ number_format($order->total, 2) }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset {{ $order->status->color() }}">
                                {{ $order->status->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $order->created_at->format('M j, Y') }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <a href="{{ route('admin.shop.orders.show', $order) }}"
                               class="text-indigo-600 hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-400">No orders yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $orders->links() }}</div>
    </div>
</x-layouts.admin>
