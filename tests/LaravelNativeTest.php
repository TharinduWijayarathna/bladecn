<?php

use BladeCN\BladeCN\Support\FormField;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Fluent;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

/*
 * Laravel-native features: model-bound form fields (old() / model / errors),
 * the flash toaster, confirm-delete and the Livewire-safe controls.
 */

function withOldInput(array $input): void
{
    $session = app('session')->driver();
    $session->put('_old_input', $input);
    request()->setLaravelSession($session);
}

function withErrors(array $messages, string $bag = 'default'): void
{
    view()->share('errors', (new ViewErrorBag)->put($bag, new MessageBag($messages)));
}

// --- FormField helpers ---------------------------------------------------------

it('maps dot names and array names both ways', function () {
    expect(FormField::key('user[email]'))->toBe('user.email')
        ->and(FormField::key('user.email'))->toBe('user.email')
        ->and(FormField::key('tags[]'))->toBe('tags')
        ->and(FormField::key('items[0][name]'))->toBe('items.0.name')
        ->and(FormField::htmlName('user.email'))->toBe('user[email]')
        ->and(FormField::htmlName('items.0.name'))->toBe('items[0][name]')
        ->and(FormField::htmlName('user[email]'))->toBe('user[email]')
        ->and(FormField::htmlName('email'))->toBe('email');
});

it('marks matching options as selected', function () {
    $html = FormField::selectOptions('<option value="a" selected>A</option><option value="b">B</option><option>C</option>', 'b');

    expect($html)->toBe('<option value="a">A</option><option value="b" selected>B</option><option>C</option>')
        ->and(FormField::selectOptions('<option value="1">1</option><option value="2">2</option>', [1, 2]))
        ->toBe('<option value="1" selected>1</option><option value="2" selected>2</option>');
});

// --- Backward compatibility -----------------------------------------------------

it('renders fields without the new props exactly as before', function () {
    // Snapshots of the markup on main for typical existing usage.
    expect(Blade::render('<x-ui.input id="email" type="email" name="email" :value="old(\'email\')" required />'))
        ->toContain('id="email" name="email" required="required" data-slot="input" />')
        ->not->toContain('value=')
        ->not->toContain('aria-invalid="')
        ->not->toContain('field-error');

    expect(Blade::render('<x-ui.textarea name="bio">hello</x-ui.textarea>'))->toContain('name="bio">hello</textarea>');
    expect(Blade::render('<x-ui.checkbox name="remember" />'))->not->toContain('checked');
    expect(Blade::render('<x-ui.checkbox name="remember" checked />'))->toContain('<input type="checkbox" checked');
    expect(Blade::render('<x-ui.native-select name="s"><option value="a">A</option></x-ui.native-select>'))->toContain('<option value="a">A</option>');
    expect(Blade::render('<x-ui.switch name="n" />'))->toContain('x-data="{ checked: false }" x-modelable="checked"')->not->toContain('wire:ignore');
});

it('does not print a second error next to existing input-error usage', function () {
    withErrors(['email' => ['The email field is required.']]);

    $html = Blade::render('<x-ui.input name="email" /><x-ui.input-error :message="$errors->first(\'email\')" />');

    expect(substr_count($html, 'The email field is required.'))->toBe(1)
        ->and($html)->not->toContain('aria-invalid="');
});

// --- Model binding -----------------------------------------------------------------

it('fills an input from the model and maps dot names', function () {
    $user = new Fluent(['email' => 'ada@example.com', 'profile' => ['city' => 'London']]);

    $html = Blade::render('<x-ui.input name="email" :model="$user" /><x-ui.input name="profile.city" :model="$user" />', ['user' => $user]);

    expect($html)->toContain('name="email" value="ada@example.com"')
        ->toContain('name="profile[city]" value="London"');
});

it('prefers old input over the model and the value', function () {
    withOldInput(['email' => 'old@example.com', 'profile' => ['city' => 'Paris']]);
    $user = ['email' => 'ada@example.com', 'profile' => ['city' => 'London']];

    $html = Blade::render(
        '<x-ui.input name="email" :model="$user" /><x-ui.input name="profile[city]" value="x" /><x-ui.input name="nickname" value="kept" />',
        ['user' => $user],
    );

    expect($html)->toContain('value="old@example.com"')
        ->toContain('name="profile[city]" value="Paris"')
        ->toContain('value="kept"');
});

it('never refills password inputs', function () {
    withOldInput(['password' => 'secret']);

    expect(Blade::render('<x-ui.input type="password" name="password" />'))->not->toContain('secret');
});

it('shows the first validation error with aria attributes for a bound field', function () {
    withErrors(['user.email' => ['The email must be valid.', 'Second message.']]);

    $html = Blade::render('<x-ui.input id="email" name="user.email" :model="[]" required />');

    expect($html)->toContain('aria-invalid="true"', 'aria-describedby="email-error"', 'required="required"', 'name="user[email]"')
        ->toContain('data-slot="field-error"', 'id="email-error"', 'The email must be valid.')
        ->not->toContain('Second message.');
});

it('turns on errors without a model through bind, and off through :bind="false"', function () {
    withErrors(['title' => ['Required.']]);

    expect(Blade::render('<x-ui.input name="title" bind />'))->toContain('aria-invalid="true"', 'id="field-title-error"', 'Required.');
    expect(Blade::render('<x-ui.input name="title" :model="[]" :bind="false" />'))->not->toContain('Required.')->not->toContain('aria-invalid="');
});

it('reads errors from a named error bag', function () {
    withErrors(['email' => ['Login failed.']], 'login');

    expect(Blade::render('<x-ui.input name="email" bind error-bag="login" />'))->toContain('Login failed.');
    expect(Blade::render('<x-ui.input name="email" bind />'))->not->toContain('Login failed.');
});

it('binds a textarea from old input and the model', function () {
    $post = new Fluent(['body' => 'From model']);

    expect(Blade::render('<x-ui.textarea name="body" :model="$post">slot</x-ui.textarea>', ['post' => $post]))->toContain('>From model</textarea>');

    withOldInput(['body' => 'From <old>']);

    expect(Blade::render('<x-ui.textarea name="body" :model="$post" />', ['post' => $post]))->toContain('>From &lt;old&gt;</textarea>');
});

it('selects options in select and native-select from the model or old input', function () {
    $post = ['status' => 'published', 'tags' => ['a', 'c']];
    $options = '<option value="draft" selected>Draft</option><option value="published">Published</option>';

    $html = Blade::render("<x-ui.native-select name=\"status\" :model=\"\$post\">{$options}</x-ui.native-select>", ['post' => $post]);
    expect($html)->toContain('<option value="draft">Draft</option>', '<option value="published" selected>Published</option>');

    $html = Blade::render('<x-ui.select name="tags[]" multiple :model="$post"><option value="a">A</option><option value="b">B</option><option value="c">C</option></x-ui.select>', ['post' => $post]);
    expect($html)->toContain('<option value="a" selected>', '<option value="b">', '<option value="c" selected>');

    withOldInput(['status' => 'draft']);
    $html = Blade::render("<x-ui.native-select name=\"status\" :model=\"\$post\">{$options}</x-ui.native-select>", ['post' => $post]);
    expect($html)->toContain('<option value="draft" selected>Draft</option>', '<option value="published">Published</option>');
});

it('checks checkboxes and switches from the model, arrays and old input', function () {
    $user = ['newsletter' => true, 'roles' => ['admin'], 'marketing' => false];

    expect(Blade::render('<x-ui.checkbox name="newsletter" value="1" :model="$user" />', ['user' => $user]))->toContain('<input type="checkbox" checked');
    expect(Blade::render('<x-ui.checkbox name="marketing" value="1" :model="$user" />', ['user' => $user]))->not->toContain(' checked');
    expect(Blade::render('<x-ui.checkbox name="roles[]" value="admin" :model="$user" />', ['user' => $user]))->toContain(' checked');
    expect(Blade::render('<x-ui.checkbox name="roles[]" value="editor" :model="$user" />', ['user' => $user]))->not->toContain(' checked');
    expect(Blade::render('<x-ui.switch name="newsletter" :model="$user" />', ['user' => $user]))->toContain('x-data="{ checked: true }"', 'aria-checked="true"');

    // After a submit, a bound checkbox missing from the old input was unchecked.
    withOldInput(['other' => 'x']);
    expect(Blade::render('<x-ui.checkbox name="newsletter" value="1" :model="$user" />', ['user' => $user]))->not->toContain(' checked');
    expect(Blade::render('<x-ui.switch name="newsletter" :model="$user" />', ['user' => $user]))->toContain('x-data="{ checked: false }"');
    // ...but an unbound one keeps its `checked` default.
    expect(Blade::render('<x-ui.checkbox name="remember" checked />'))->toContain(' checked');
});

it('selects a radio group option from the model and shows its error', function () {
    withErrors(['plan' => ['Pick a plan.']]);

    $html = Blade::render('<x-ui.radio-group name="plan" :options="[\'free\', \'pro\']" :model="[\'plan\' => \'pro\']" />');

    expect($html)->toMatch('/value="pro"\s+checked/')
        ->toContain('aria-invalid="true"', 'Pick a plan.');
});

it('does not prefill fields bound with wire:model or x-model', function () {
    withOldInput(['email' => 'old@example.com']);

    expect(Blade::render('<x-ui.input name="email" wire:model="email" />'))->not->toContain('old@example.com');
    expect(Blade::render('<x-ui.input name="email" x-model="email" />'))->not->toContain('old@example.com');
});

// --- Flash toaster -----------------------------------------------------------------

it('turns flashed session messages into toasts', function () {
    session()->put(['success' => 'Profile updated.', 'error' => ['message' => 'Oops', 'description' => 'Try again']]);
    request()->setLaravelSession(app('session')->driver());

    $html = Blade::render('<x-ui.flash-toaster />');

    expect($html)->toContain('data-slot="toaster"', 'Profile updated.', '\u0022type\u0022:\u0022success\u0022', 'Oops', 'Try again', '\u0022type\u0022:\u0022error\u0022');
});

it('maps status to a success toast and keeps the session toast convention', function () {
    session()->put(['status' => 'Link sent.', 'toast' => ['message' => 'Saved', 'type' => 'info']]);
    request()->setLaravelSession(app('session')->driver());

    $html = Blade::render('<x-ui.flash-toaster />');

    expect($html)->toContain('Link sent.', 'Saved', '\u0022type\u0022:\u0022info\u0022');
});

it('optionally shows validation errors as toasts', function () {
    withErrors(['email' => ['Email is invalid.'], 'name' => ['Name is required.']]);

    expect(Blade::render('<x-ui.flash-toaster />'))->not->toContain('Email is invalid.');
    expect(Blade::render('<x-ui.flash-toaster validation />'))->toContain('Email is invalid.', 'Name is required.');
    expect(Blade::render('<x-ui.flash-toaster validation="first" />'))->toContain('Email is invalid.')->not->toContain('Name is required.');
    expect(Blade::render('<x-ui.flash-toaster validation="summary" />'))->toContain('There are 2 errors in the form.');
});

it('renders an empty toaster when nothing was flashed', function () {
    expect(Blade::render('<x-ui.flash-toaster position="top-center" />'))->toContain('data-slot="toaster"', 'data-position="top-center"');
});

// --- Confirm delete ------------------------------------------------------------------

it('renders confirm-delete as a DELETE form inside an alert dialog', function () {
    $html = Blade::render('<x-ui.confirm-delete action="/posts/1" title="Delete post?" description="Gone for good." confirm-text="Yes, delete" />');

    expect($html)->toContain('method="POST"', 'action="/posts/1"', 'name="_token"', 'name="_method" value="DELETE"', 'role="alertdialog"')
        ->toContain('Delete post?', 'Gone for good.', 'Yes, delete', 'type="submit"')
        ->not->toContain('typed');
});

it('supports other methods and a typed confirmation', function () {
    $html = Blade::render('<x-ui.confirm-delete action="/teams/1" method="patch" confirmation="acme" />');

    expect($html)->toContain('name="_method" value="PATCH"', 'x-model="typed"', 'acme', 'typed !== expected')
        ->and(Blade::render('<x-ui.confirm-delete action="/x" method="post" />'))->not->toContain('name="_method"');
});

it('accepts a custom trigger slot', function () {
    $html = Blade::render('<x-ui.confirm-delete action="/x"><x-slot:trigger><x-ui.alert-dialog-trigger variant="ghost">Remove item</x-ui.alert-dialog-trigger></x-slot:trigger></x-ui.confirm-delete>');

    expect($html)->toContain('Remove item')->and(substr_count($html, 'data-slot="alert-dialog-trigger"'))->toBe(1);
});

// --- Livewire-safe controls -------------------------------------------------------

it('forwards any wire:model modifier to the switch root and ignores it for morphing', function () {
    $html = Blade::render('<x-ui.switch wire:model.live.debounce.250ms="notify" wire:key="n" />');

    expect($html)->toMatch('/data-slot="switch-root"[^>]*x-modelable="checked"\s+wire:model\.live\.debounce\.250ms="notify"/')
        ->toContain('wire:ignore', "\$watch('checked'")
        ->toMatch('/h-\[1\.15rem\] w-8" wire:key="n">/');
});

it('makes combobox, date-picker and slider Livewire-safe only when wire:model is present', function (string $tag, string $property) {
    $plain = Blade::render("<x-ui.{$tag} />");
    $wired = Blade::render("<x-ui.{$tag} wire:model=\"field\" />");

    expect($plain)->not->toContain('wire:ignore')->not->toContain("\$watch('{$property}'")
        ->and($wired)->toContain('wire:model="field"', 'wire:ignore', "\$watch('{$property}'");
})->with([
    ['combobox', 'selected'],
    ['date-picker', 'date'],
    ['slider', 'value'],
]);

it('keeps a user-supplied wire:ignore.self instead of adding wire:ignore', function () {
    $html = Blade::render('<x-ui.combobox wire:model="f" wire:ignore.self />');

    expect($html)->toContain('wire:ignore.self')->not->toMatch('/wire:ignore(?!\.self)/');
});

it('renders input-tags unchanged without wire:model and as an Alpine control with it', function () {
    $plain = Blade::render('<x-ui.input-tags name="kw" :tags="[\'a\']" />');
    expect($plain)->toContain('id="tagsInput-kw"', 'data-tag="a"')->not->toContain('x-modelable');

    $wired = Blade::render('<x-ui.input-tags name="kw" :tags="[\'a\']" wire:model.live="keywords" />');
    expect($wired)->toContain('x-modelable="items"', 'wire:model.live="keywords"', 'wire:ignore', 'name="kw[]"')
        ->not->toContain('id="tagsInput-kw"');
});
