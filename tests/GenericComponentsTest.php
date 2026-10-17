<?php

use Illuminate\Support\Facades\Blade;

it('renders the stat card with a trend', function () {
    expect(Blade::render('<x-ui.stat-card title="Revenue" value="$10" change="+5%" trend="up" description="this week" />'))
        ->toContain('data-slot="stat-card"', '$10', '+5%', 'data-trend="up"', 'this week');
});

it('renders the plan card with renewal, usage and footer', function () {
    $html = Blade::render('<x-ui.plan-card name="Pro" price="$29" interval="month" renews="2026-11-01" :usage="68"><x-slot:footer><button>Upgrade</button></x-slot:footer></x-ui.plan-card>');

    expect($html)->toContain('Pro', '$29', '/ month', 'Renews on November 1, 2026', 'aria-valuenow="68"', 'Upgrade');
});

it('only ever renders the last four digits of a payment method', function () {
    $html = Blade::render('<x-ui.payment-method brand="mastercard" last4="5555 4444 3333 1111" expires="11/27" default />');

    expect($html)->toContain('Mastercard', '1111', 'Expires 11/27', 'Default')->not->toContain('5555');
});

it('renders the media item with a +N tile and a labelled action', function () {
    $html = Blade::render('<x-ui.media-item title="Hero" href="/review" :max="2" :images="[\'/a.jpg\', \'/b.jpg\', \'/c.jpg\']" />');

    expect($html)->toContain('/a.jpg', '/b.jpg', '+1', 'href="/review"', ': Hero')->not->toContain('/c.jpg');
});

it('renders the reaction button as a pressable toggle', function () {
    $html = Blade::render('<x-ui.reaction-button pressed :count="3" name="liked" />');

    expect($html)->toContain('aria-pressed="true"', 'data-slot="reaction-button-count"', 'name="liked"', 'value="1"');
});

it('renders the quadrant picker as a slider with corner labels and inputs', function () {
    $html = Blade::render('<x-ui.quadrant-picker name="tone" :x="2" :y="-0.5" :labels="[\'A\', \'B\', \'C\', \'D\']" />');

    expect($html)->toContain('role="slider"', 'aria-valuetext="x 1, y -0.5"', 'name="tone[x]"', 'name="tone[y]"', '>A<', '>D<');
});

it('renders the theme switcher as a radio group', function () {
    $html = Blade::render('<x-ui.theme-switcher value="dark" name="appearance" />');

    expect($html)->toContain('role="radiogroup"', 'data-value="dark"', 'aria-checked="true"', 'name="appearance"');
});

it('renders the segmented progress with filled segments', function () {
    $html = Blade::render('<x-ui.segmented-progress :value="50" :segments="10" show-value />');

    expect($html)->toContain('role="progressbar"', 'aria-valuenow="50"', '50%')
        ->and(substr_count($html, 'data-filled="true"'))->toBe(5);
});
