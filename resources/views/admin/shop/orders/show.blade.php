<x-layouts.admin title="Order Details">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                    <h2 class="text-sm font-semibold text-gray-900">Items</h2>
                </div>
                <table class="min-w-full divide-y divide-gray-100">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Product</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Qty</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500">Price</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($order->items as $item)
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">{{ $item->name_snapshot }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-600">${{ number_format($item->price_snapshot * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Shipping Address</h3>
                    @foreach ($order->shipping_address as $key => $value)
                        <p class="text-sm text-gray-600">{{ $value }}</p>
                    @endforeach
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Billing Address</h3>
                    @foreach ($order->billing_address as $key => $value)
                        <p class="text-sm text-gray-600">{{ $value }}</p>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-5">
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Order Summary</h3>
                <dl class="space-y-1 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">Subtotal</dt><dd>${{ number_format($order->subtotal, 2) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Shipping</dt><dd>${{ number_format($order->shipping, 2) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Tax</dt><dd>${{ number_format($order->tax, 2) }}</dd></div>
                    <div class="flex justify-between font-semibold border-t border-gray-100 pt-2 mt-2"><dt>Total</dt><dd>${{ number_format($order->total, 2) }}</dd></div>
                </dl>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <h3 class="text-sm font-semibold text-gray-900 mb-3">Update Status</h3>
                <form method="POST" action="{{ route('admin.shop.orders.update', $order) }}">
                    @csrf @method('PUT')
                    <select name="status"
                            class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 mb-3">
                        @foreach (\App\Enums\OrderStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected($order->status === $status)>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="w-full rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                        Update Status
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4 text-xs text-gray-500 space-y-1">
                <p>Customer: <span class="font-medium text-gray-700">{{ $order->user->name }}</span></p>
                <p>Placed: {{ $order->created_at->format('M j, Y H:i') }}</p>
            </div>
        </div>
    </div>
</x-layouts.admin>
