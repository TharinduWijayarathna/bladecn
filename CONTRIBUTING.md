# Contributing

Issues and pull requests are welcome.

## Local setup

```bash
composer install
npm install
npm run docs:build
vendor/bin/testbench serve   # http://127.0.0.1:8000/docs
```

## Before opening a pull request

```bash
vendor/bin/pest
vendor/bin/phpstan analyse
vendor/bin/pint
```

Every component needs a docs page. See [`workbench/README.md`](workbench/README.md) for how the docs site is built.
