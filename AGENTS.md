# AGENTS.md

This file provides guidance to coding agents when working with code in this repository.

## Project

Handbook API is the PHP back end for a scout course handbook (OdyMateriály). Authentication goes through SkautIS. The public API (v1.0) is described in `open-api-spec-v1.0.yaml`. On pushes to `master`, that spec is published as Swagger UI docs to https://scout-handbook.github.io/handbook-api/. `package.json` exists only to pull in `swagger-ui-dist` for those docs.

## Commands

```sh
composer install
composer test                 # deletes tests/db.sqlite*, then runs phpunit (Unit + Feature suites)
vendor/bin/phpunit --filter FieldEndpointTest            # one test class
vendor/bin/phpunit --filter 'FieldEndpointTest::test_add_field'  # one test method
vendor/bin/phpunit --testsuite Unit                      # one suite

composer lint                 # runs pint --test, phpcs, phpmd, phan, phpstan (CI runs this)
vendor/bin/pint               # auto-fix formatting (Laravel preset)
```

CI runs on PHP 8.3.

## Architecture

The code is a thin Laravel 12 shell around an older plain-PHP API. Nearly all logic lives in `legacy/`.

**Request flow:**
1. `routes/api.php` sends every path to `App\Http\Controllers\LegacyController::call`. The API prefix is empty (see `bootstrap/app.php`).
2. `LegacyController` removes an optional `API/` prefix and maps `login`/`logout` to `v1.0/login`/`v1.0/logout`. It splits the path `v1.0/<resource>/<id>/<sub-resource>/<sub-id>` into `$_GET['id']`, `$_GET['sub-resource']` and `$_GET['sub-id']`. It then `require`s `legacy/v1.0/<resource>.php` inside an output buffer and wraps the echoed output and `http_response_code()` in a Laravel `Response`.
3. `legacy/v1.0/<resource>.php` defines `_API_EXEC`, loads `api-config.php` from `$_SERVER['DOCUMENT_ROOT']`, requires `legacy/v1.0/endpoints/<resource>Endpoint.php`, and calls `$xxxEndpoint->handle()`.
4. Each `*Endpoint.php` builds a `Skaut\HandbookAPI\v1_0\Endpoint` and registers closures with a minimum `Role` using `setListMethod`, `setGetMethod`, `setAddMethod`, `setUpdateMethod` and `setDeleteMethod`. HTTP methods map as follows: GET without an id → list, GET with an id → get, POST → add, PUT → update, DELETE → delete. Nested resources such as `lesson/{id}/competence` are separate `Endpoint`s attached with `addSubEndpoint()`. Inside those, the parent id arrives as `$data['parent-id']`.
5. Each closure has the signature `function (Skautis $skautis, array $data[, Endpoint $self]): array` and returns `['status' => ..., 'response' => ...]`. `Endpoint` JSON-encodes that array. To return an error, throw one of the classes in `Skaut\HandbookAPI\v1_0\Exception\*`. Each one has a `handle()` that produces the JSON error body, and `bootstrap/app.php` also renders them.

**Supporting pieces in `legacy/Skaut/HandbookAPI/v1_0/`:**
- `Database` is a wrapper around one shared PDO connection. It reads the DSN from `api-secrets.php` in `$_SERVER['DOCUMENT_ROOT']`. Endpoints write raw SQL in heredocs and use `prepare`/`bindParam`/`execute`/`bindColumn`/`fetch`.
- `Helper::roleTry` and `Helper::skautisTry` handle SkautIS login and role checks. `Helper::parseUuid` validates ids. Ids are stored as binary(16) UUIDs (ramsey/uuid `getBytes()`/`fromBytes()`).
- `Role` defines the role hierarchy: guest < user < editor < administrator < superuser.

`legacy/Skaut/OdyMarkdown` renders lesson Markdown (cebe/markdown) to PDF (mPDF). `legacy/setup/` holds the MySQL schema (`setupQuery.sql`) and install script. `legacy/api-config.php.sample` and `legacy/api-secrets.php.sample` are the templates for the two config files. Those files are plain PHP. The legacy code does not read them through Laravel config or `.env`.

## Testing notes

- `tests/bootstrap.php` sets `$_SERVER['DOCUMENT_ROOT']` to `tests/`. As a result, tests load `tests/api-config.php` (basepath `../legacy`) and `tests/api-secrets.php`, which points at SQLite `tests/db.sqlite`. Always run tests through `composer test`, or delete `tests/db.sqlite*` first, so leftover state doesn't affect the run.
- Feature tests extend `Tests\LegacyEndpointTestCase`. Its `get`/`post`/`put`/`delete` fill in `$_SERVER['REQUEST_METHOD']` and `$_POST` because the legacy code reads superglobals. Pass a role name as the last argument (`overrideRole`) to skip SkautIS: it sets the global `$_TEST_OVERRIDE`, which `Helper` checks.
- Each feature test creates its tables in `setUp()` (`CREATE TABLE IF NOT EXISTS`, SQLite-compatible) and drops them in `tearDownAfterClass()`. Tests in a class depend on data added by earlier methods (for example, `test_list_fields` relies on the row from `test_add_field`), so their order matters.
- Unit tests cover the `Skaut\HandbookAPI\v1_0` model and exception classes directly.

## Lint configuration quirks

- PHPStan runs at level 8 but excludes `legacy/` and `tests/Unit`. It also scans `*.sample` files and uses `bootstrap.php.lint`.
- phpcs (`phpcs.xml`) uses the Slevomat standard plus PHPCompatibility. phpmd uses `phpmd.xml`, and phan uses `.phan/config.php`. Suppressions in the code use `@SuppressWarnings("PHPMD....")`, `@phpcsSuppress` and `// phpcs:ignore`.
