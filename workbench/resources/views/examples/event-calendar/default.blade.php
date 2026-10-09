@php
    $today = now()->startOfDay();

    $events = [
        [
            'id' => 1,
            'title' => 'Team standup',
            'start' => $today->copy()->setTime(9, 30)->toIso8601String(),
            'end' => $today->copy()->setTime(9, 45)->toIso8601String(),
            'allDay' => false,
            'color' => 'sky',
            'description' => 'Daily sync with the product team.',
            'location' => 'Google Meet',
        ],
        [
            'id' => 2,
            'title' => 'Design review',
            'start' => $today->copy()->addDays(2)->setTime(14, 0)->toIso8601String(),
            'end' => $today->copy()->addDays(2)->setTime(15, 0)->toIso8601String(),
            'allDay' => false,
            'color' => 'indigo',
            'description' => 'Review the new dashboard mockups.',
            'location' => 'Room 2B',
        ],
        [
            'id' => 3,
            'title' => 'Release day',
            'start' => $today->copy()->addDays(5)->toIso8601String(),
            'end' => $today->copy()->addDays(5)->endOfDay()->toIso8601String(),
            'allDay' => true,
            'color' => 'emerald',
            'description' => 'Ship v1.0.',
            'location' => 'Everywhere',
        ],
        [
            'id' => 4,
            'title' => 'Dentist',
            'start' => $today->copy()->subDays(3)->setTime(11, 0)->toIso8601String(),
            'end' => $today->copy()->subDays(3)->setTime(12, 0)->toIso8601String(),
            'allDay' => false,
            'color' => 'rose',
        ],
    ];
@endphp

<x-ui.event-calendar :events="$events" />
