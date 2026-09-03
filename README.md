# BladeCN

**shadcn for Laravel Blade.** No React. No Livewire.

BladeCN is a starter kit: login, register, password reset, dashboard, and profile, plus a shadcn-style Blade component set on Alpine and Tailwind 4.

Official Laravel kits already ship shadcn with React/Vue/Svelte, or Flux with Livewire. Use BladeCN when you want that look and still want server-rendered Blade.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/bladecn/bladecn.svg?style=flat-square)](https://packagist.org/packages/bladecn/bladecn)
[![Total Downloads](https://img.shields.io/packagist/dt/bladecn/bladecn.svg?style=flat-square)](https://packagist.org/packages/bladecn/bladecn)

## Installation

```bash
composer require bladecn/bladecn
```

```bash
php artisan bladecn:install
```

```bash
npm install tailwindcss @tailwindcss/vite tailwindcss-animate alpinejs @alpinejs/focus
npm run build
```

*Auth and a dashboard in one install.*

![Dashboard Dark](.github/images/dashboard-desktop-dark.png)

![Login](.github/images/login-desktop-light.png)

![Delete Account Dialog](.github/images/dialog-delete-light.png)

## Features

- 🎨 **106 Component Files** - Complete shadcn-style component library across 37 families (Button, Card, Dialog, Dropdown, Table, Sheet, Avatar, and more)
- 🔐 **Authentication System** - Pre-built login, registration, and password reset flows
- 📊 **Dashboard Templates** - Ready-to-use dashboard with stats, tables, and cards
- 👤 **Profile Management** - Complete user profile and account settings pages
- 📱 **Responsive Design** - Mobile-first, fully responsive components
- 🌙 **Dark Mode** - Built-in dark mode support across all components
- ⚡ **Easy Integration** - Simple Blade component syntax
- 🎯 **Type-Safe** - PHP component classes with comprehensive type hints

## Quick Start

### 1. Run the Installer

```bash
php artisan bladecn:install
```

This command publishes all necessary files to your application:

- Views (authentication, components, layouts)
- Component classes
- Authentication controllers and routes
- CSS and JavaScript assets
- Helper functions

### 2. Install Frontend Dependencies

```bash
npm install tailwindcss @tailwindcss/vite tailwindcss-animate alpinejs @alpinejs/focus
npm run build
```

### 3. Update Autoloader

```bash
composer dump-autoload
```

### 4. Start Building

Your application now includes:

- **Authentication Pages** - `/login`, `/register`, `/forgot-password`
- **Dashboard** - `/dashboard` with pre-built components
- **Profile** - `/profile` for account management

## Usage

### Authentication Layout

```blade
<x-layout.auth title="Welcome back" description="Sign in to your account">
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <x-ui.label for="email">Email</x-ui.label>
        <x-ui.input id="email" type="email" name="email" required />
        
        <x-ui.label for="password">Password</x-ui.label>
        <x-ui.input id="password" type="password" name="password" required />
        
        <x-ui.button type="submit" class="w-full">Sign in</x-ui.button>
    </form>
</x-layout.auth>
```

### Application Layout

```blade
<x-layout.app title="Dashboard">
    <div class="space-y-6">
        <h1 class="text-2xl font-semibold">Dashboard</h1>
        
        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Welcome</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content>
                Your content here
            </x-ui.card-content>
        </x-ui.card>
    </div>
</x-layout.app>
```

### Components

All UI components are namespaced under `x-ui`:

```blade
<!-- Buttons -->
<x-ui.button>Default</x-ui.button>
<x-ui.button variant="outline">Outline</x-ui.button>
<x-ui.button variant="destructive">Delete</x-ui.button>

<!-- Forms -->
<x-ui.input type="text" name="name" />
<x-ui.textarea name="description" />
<x-ui.checkbox label="Remember me" />
<x-ui.select :options="$options" />

<!-- Cards -->
<x-ui.card>
    <x-ui.card-header>
        <x-ui.card-title>Title</x-ui.card-title>
    </x-ui.card-header>
    <x-ui.card-content>Content</x-ui.card-content>
</x-ui.card>

<!-- Tables -->
<x-ui.table>
    <x-ui.table-header>
        <x-ui.table-row>
            <x-ui.table-head>Name</x-ui.table-head>
        </x-ui.table-row>
    </x-ui.table-header>
    <x-ui.table-body>
        <x-ui.table-row>
            <x-ui.table-cell>John Doe</x-ui.table-cell>
        </x-ui.table-row>
    </x-ui.table-body>
</x-ui.table>

<!-- Dialogs -->
<x-ui.dialog>
    <x-ui.dialog-trigger>
        <x-ui.button>Open</x-ui.button>
    </x-ui.dialog-trigger>
    <x-ui.dialog-content>
        <x-ui.dialog-header>
            <x-ui.dialog-title>Confirm Action</x-ui.dialog-title>
        </x-ui.dialog-header>
        <p>Are you sure?</p>
    </x-ui.dialog-content>
</x-ui.dialog>
```

## Component Families

- **Input** - Input, Textarea, Checkbox, Radio, Select, Label, OTP, Phone
- **Card** - Card, Header, Content, Footer, Title, Description, Action
- **Dialog** - Dialog, Trigger, Content, Header, Footer, Title, Close, Overlay
- **Dropdown** - Dropdown, Trigger, Content, Item, Label, Separator, Sub-menus
- **Table** - Table, Header, Body, Row, Cell, Head
- **Sheet** - Sheet, Trigger, Content, Header, Footer, Title, Close
- **Avatar** - Avatar, Image, Fallback, Upload
- **Empty State** - Empty state, Title, Description, Media, Header, Content
- **Item** - Item, Header, Title, Description, Media, Actions, Separator, Group
- **Navigation** - Breadcrumbs, Text links
- **Feedback** - Tooltips, Spinners, Progress bars, Badges
- **Layout** - Separators, Headings

## Customization

### Theming

BladeCN uses CSS variables for theming. Customize colors in your `resources/css/app.css`:

```css
:root {
    --background: 0 0% 100%;
    --foreground: 222.2 84% 4.9%;
    --primary: 222.2 47.4% 11.2%;
    /* Customize other variables */
}
```

### Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag="bladecn-config"
```

## Requirements

- PHP 8.3 or higher
- Laravel 10.x, 11.x, or 12.x
- Tailwind CSS 4.x

## Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

## License

BladeCN is open-sourced software licensed under the [MIT license](LICENSE.md).

## Credits

BladeCN is inspired by [shadcn/ui](https://ui.shadcn.com) and built for the Laravel ecosystem.
