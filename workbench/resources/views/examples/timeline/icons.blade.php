<x-ui.timeline class="max-w-md">
    <x-ui.timeline-item time="2 min ago" title="Payment received" variant="success">
        <x-slot:icon><x-icons.credit-card /></x-slot:icon>
        $1,250.00 from Acme Inc.
    </x-ui.timeline-item>
    <x-ui.timeline-item time="1 hour ago" title="Invoice sent">
        <x-slot:icon><x-icons.mail /></x-slot:icon>
        Invoice #1042 was emailed to billing@acme.test.
    </x-ui.timeline-item>
    <x-ui.timeline-item time="Yesterday" title="Reminder scheduled" variant="warning">
        <x-slot:icon><x-icons.time-schedule /></x-slot:icon>
        A follow-up goes out in 7 days if unpaid.
    </x-ui.timeline-item>
</x-ui.timeline>
