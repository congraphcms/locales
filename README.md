# congraph/locales

`congraph/locales` manages available locales in a Congraph installation.

## Responsibilities

- locale persistence
- locale CRUD commands and validators
- locale repository bindings

This package is used by `eav` and any localized content workflows.

## Installation

```bash
composer require congraph/locales
```

Laravel package provider:

- [LocalesServiceProvider.php](/Users/nikolap/git/np/congraph/packages/locales/LocalesServiceProvider.php)

## Typical usage

Use the package to:

- create locales like `en_US`, `sr_RS`, or `fr_FR`
- fetch configured locales
- drive localized entity fields in `eav`

In most apps this package is not consumed directly by controllers; instead it is surfaced through `laravel-api`.

## Development

### Install dependencies

```bash
cd /Users/nikolap/git/np/congraph/packages/locales
composer update -W
```

### Run tests

```bash
composer test
composer test:pgsql
```

`composer test` is fast and SQLite-backed by default.  
`composer test:pgsql` uses an ephemeral PostgreSQL container.

## Contribution notes

- preserve locale code normalization behavior
- keep tests env-driven instead of hardcoding a database backend
- prefer PostgreSQL-safe and SQLite-safe queries
