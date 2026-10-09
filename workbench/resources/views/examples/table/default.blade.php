@php
    $invoices = [
        ['id' => 'INV001', 'status' => 'Paid', 'method' => 'Credit Card', 'amount' => '$250.00'],
        ['id' => 'INV002', 'status' => 'Pending', 'method' => 'PayPal', 'amount' => '$150.00'],
        ['id' => 'INV003', 'status' => 'Unpaid', 'method' => 'Bank Transfer', 'amount' => '$350.00'],
    ];
@endphp

<x-ui.table>
    <caption class="mt-4 text-sm text-muted-foreground">A list of your recent invoices.</caption>
    <x-ui.table-header>
        <x-ui.table-row>
            <x-ui.table-head class="w-[100px]">Invoice</x-ui.table-head>
            <x-ui.table-head>Status</x-ui.table-head>
            <x-ui.table-head>Method</x-ui.table-head>
            <x-ui.table-head class="text-right">Amount</x-ui.table-head>
        </x-ui.table-row>
    </x-ui.table-header>
    <x-ui.table-body>
        @foreach ($invoices as $invoice)
            <x-ui.table-row>
                <x-ui.table-cell class="font-medium">{{ $invoice['id'] }}</x-ui.table-cell>
                <x-ui.table-cell>
                    <x-ui.badge variant="outline">{{ $invoice['status'] }}</x-ui.badge>
                </x-ui.table-cell>
                <x-ui.table-cell>{{ $invoice['method'] }}</x-ui.table-cell>
                <x-ui.table-cell class="text-right">{{ $invoice['amount'] }}</x-ui.table-cell>
            </x-ui.table-row>
        @endforeach
    </x-ui.table-body>
</x-ui.table>
