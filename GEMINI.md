<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.5
- laravel/framework (LARAVEL) - v13
- laravel/prompts (PROMPTS) - v0
- laravel/sanctum (SANCTUM) - v4
- laravel/boost (BOOST) - v2
- laravel/breeze (BREEZE) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- phpunit/phpunit (PHPUNIT) - v12

## Domain Glossary (Ubiquitous Language)

This application is a Farm Management System (FMS) for a livestock ranch ("finca"). You must understand and use the following domain terms correctly:
- **Livestock**: Represents an individual animal (cow, bull, calf).
- **Milking**: A daily record of milk production (measured in liters/kilograms).
- **Batch**: A group of animals clustered together for a specific purpose or physical location on the farm.
- **Service**: A reproductive event (natural mating or artificial insemination).
- **Pedigree**: The genealogical family tree of the animal. Animals are self-referencing via `father_id` and `mother_id`.

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.
- Be redundant at variables declaration, although a var was only use one time, is better define it before its using. For example, `$name = request->name(); parse($name);` not `parse(request->name())`

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `vendor/bin/sail npm run build`, `vendor/bin/sail npm run dev`, or `vendor/bin/sail composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== project architecture (ddd lite) ===

# Project Architecture & DDD

This project follows a "DDD Lite" approach to centralize business logic, keep models clean, and ensure data integrity.

## Domain Business Rules (Immutable)

When writing logic, validators, or tests, you MUST strictly enforce these biological and operational rules:

### Reproductive & Genealogical Rules
- **Parental Gender Restriction**: The `father_id` (Sire) must strictly belong to an animal with `male` category, and `mother_id` (Dam) to a `female` animal.
- **Birth Temporal Logic**: An animal's `birth_date` must be strictly greater than its parents' birth dates by at least the sexual maturity + gestation period (~15-18 months).
- **Gestation & Calving Intervals**: A female cannot have two `Birth` or `Abort` events in a period shorter than the biological cycle (e.g., minimum 9 months for bovines).
- **Minimum Breeding Age**: An animal cannot have `Service` records (natural or AI) if its age is below the puberty threshold (e.g., 12 months), unless overridden by specific settings.

### Sanitary & Clinical Rules
- **Withdrawal Period**: If an animal receives a `Treatment` with a medicine having a defined withdrawal period, the system must flag/block the sale of the animal or the commercial collection of its milk during that period.
- **Quarantine**: New entries or animals diagnosed with contagious diseases must be set to `quarantine` state. While in this state, they cannot be assigned to batches shared with the general herd.
- **Mortality Records**: If an animal's state is updated to `dead`, a detailed incidence or medical record specifying the cause and date of death is strictly mandatory.

### Traceability & IoT Rules
- **Identifier Uniqueness**: Physical (Ear tags) and Electronic (RFID, IoT collars) IDs must be unique among `alive` animals. An ID can only be reused if the previous carrier is marked as `dead` or `sold`.
- **Spatial Uniqueness**: An animal can only be associated with one active `Herd` or `Batch` at a time. New movements must automatically close the previous location record.

### Metrics & Prediction Rules
- **Biological Weight Limits**: `Growths` (weights) must pass threshold validation. Newborns cannot be 300kg; adults cannot be 2,000kg. Outliers require manual "override" approval.
- **Weight Sequences**: While weight is generally progressive, minor weight loss is allowed (stress, disease). However, drastic drops (e.g., >40% in 2 days) must trigger a validation exception or API alert.

### Transactional & Administrative Rules
- **Sale Restrictions**: An animal cannot be marked as `sold` if its current state is `missing` or `dead`.
- **Historical Immutability**: Financial and medical application records, once closed/reconciled, are immutable. Corrections must be made via reversal transactions, never by editing original rows.

## UX Incongruity & Field Operations

The system is designed for high-stakes, fast-paced rural environments where "perfect data" is often impossible during initial capture.

- **"Lazy" Data Entry**: Allow `null` for fields like `father_id`, `mother_id`, and even `birth_date` during initial registration to avoid blocking field technicians.
- **Flexible Constraints**: While business rules are strict, the system should allow "warnings" instead of "hard blocks" during field data capture when possible, postponing strict validation until a "review" phase.
- **Dynamic Thresholds**: Time periods and biological thresholds (e.g., gestation length, maturity age) are not hardcoded but defined in the `settings` system, allowing for farm-specific adjustments.
- **Incongruity Management**: The system accepts that data might be incomplete or slightly inconsistent in the short term (Incongruencia por UX) to prioritize operational speed, as long as it doesn't violate core database integrity.

## Core Architectural Layers

1.  **Models (`app/Models`)**: Pure Eloquent models.
    -   Use `#[Fillable]` and `#[Includable]` attributes.
    -   Use `#[ObservedBy]` to link the model to its Observer.
    -   Avoid putting complex business logic or validation here.
2.  **Observers (`app/Observers`)**: The orchestration glue.
    -   Intercept model events (primarily `saving`).
    -   Inject and delegate to **Sanitizers** and **Validators**.
3.  **Sanitizers (`app/Sanitizers`)**: Data formatting and cleanup.
    -   Run BEFORE validation.
    -   Standardize data (e.g., capitalization, defaulting values based on other attributes).
4.  **Validators (`app/Validators`)**: **Domain/Business Validation**.
    -   Enforce complex business rules that depend on model state (e.g., "a male cannot have tits").
    -   Throw `ValidationException` on failure.
    -   This is separate from **FormRequests** which handle HTTP-level validation.
5.  **Services (`app/Services`)**: Action Orchestrators.
    -   Used for operations involving multiple models or complex multi-step processes.
6. **DTO's**: We don't use this in the project.

## Shared Traits & Specialized Logic

### `HasComment` Trait
For models requiring comments, use this trait to handle polymorphic relationships:
-   **Relationships**: Provides a `morphOne` relation to `Comment`.
-   **Static `register()`**: Use this method for atomic creation of the model and its comment within a transaction.
-   **Method `amend()`**: Use this for atomic updates of the model and its comment.
-   **Syncing**: `syncComment(?string $text)` manages creation, update, or deletion of the associated comment.

### `HasInclude` Trait
Used in conjunction with the `#[Includable]` attribute for dynamic relationship loading via the `?include=` query parameter.

## Authorization & Roles (Actors)

The system is used by different roles with strict access levels. When generating Policies, FormRequests, or Tests, consider these actors:
- **Admin / Owner**: Full system access, including financial metrics and system settings.
- **Veterinarian**: Write access for clinical histories, reproductive `Services`, and `Births`.
- **Technician**: Write access restricted to daily field operations only (`Milkings`, `Growths`, and `BatchMovements`).

## Testing Conventions

-   **Feature Tests**: Required for every API resource.
-   **Authentication**: Use `$this->actingAs($this->user)` (defined in `TestCase`).
-   **Assertions**:
    -   `assertJsonStructure` to verify the API contract.
    -   `assertJsonFragment` for specific data.
    -   `assertDatabaseHas`/`assertSoftDeleted` for persistence verification.
-   **Coverage**: Test happy paths, validation failures (422), and unauthorized access (401).

## Factories

-   **Domain States**: Use factory states for specific business roles (e.g., `asBull()`, `asCow()`, `dead()`).
-   **Pedigree**: Use `withPedigree(int $level)` for recursive generation of parent animals.
-   **Data Consistency**: Ensure factories respect business rules (e.g., gender-appropriate names and tit counts).
-   **Agricultural Realism**: Factories MUST generate realistic agricultural data. Use realistic breed names, plausible weight ranges (e.g., a newborn calf is ~30kg, an adult bull is ~800kg), and logical date sequences (e.g., a birth date cannot be in the future, and an animal cannot give birth before being 2 years old).

## API Resources

-   **Transformations**: Use `JsonResource` to mask DB structure and format dates (`Y-m-d`).
-   **Conditional Loading**: Always use `$this->whenLoaded('relation')` to avoid N+1 issues and respect the `Includable` system.
-   **Naming**: Follow the `ModelResource` convention.
-   **The Rural Context**: The API is consumed by a Next.js SPA operating in a rural agricultural environment with low, highly unstable bandwidth.
-   **Strict Payloads**: API responses must be strictly paginated. Never return deep nested relationships unless explicitly requested via the `?include=` parameter.
-   **Nested Resources**: For transactional records (e.g., `milkings`, `growths` belonging to a specific animal), use Nested Resource Controllers (`LivestockMilkingController`) instead of bloating the main `LivestockController`.

## Business Logic Flow (Saving a Model)

1.  **FormRequest**: Validates basic input (required, types, existence).
2.  **Observer (saving)**:
    -   `Sanitizer->sanitize($model)`: Standardizes the data.
    -   `Validator->validate($model)`: Runs domain-specific integrity checks.
3.  **Persistence**: The model is saved to the database only if all previous steps succeed.

## API & Dynamic Relationships

We use a custom system to load relationships dynamically via the API:
-   **Includable Attribute**: Define allowed relationships in the model: `#[Includable(['breed', 'state'])]`.
-   **HasInclude Trait**: Provides `scopeWithIncludes($query, $includes)` and `loadIncludes($includes)`.
-   **Usage**: Clients request relationships via the `include` query parameter: `/api/livestock?include=breed,state`.

## Enums & Type Safety

-   Always use **Enums (`app/Enums`)** for categories, states, and types (e.g., `AnimalCategory`).
-   Cast attributes to Enums in the model's `casts()` method.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Artisan

- Run Artisan commands directly via the command line (e.g., `vendor/bin/sail artisan route:list`). Use `vendor/bin/sail artisan list` to discover available commands and `vendor/bin/sail artisan [command] --help` to check parameters.
- Inspect routes with `vendor/bin/sail artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `vendor/bin/sail artisan config:show app.name`, `vendor/bin/sail artisan config:show database.default`. Or read config files directly from the `config/` directory.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `vendor/bin/sail artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `vendor/bin/sail artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Follow existing application Enum naming conventions.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== sail rules ===

# Laravel Sail

- This project runs inside Laravel Sail's Docker containers. You MUST execute all commands through Sail.
- Start services using `vendor/bin/sail up -d` and stop them with `vendor/bin/sail stop`.
- Open the application in the browser by running `vendor/bin/sail open`.
- Always prefix PHP, Artisan, Composer, and Node commands with `vendor/bin/sail`. Examples:
    - Run Artisan Commands: `vendor/bin/sail artisan migrate`
    - Install Composer packages: `vendor/bin/sail composer install`
    - Execute Node commands: `vendor/bin/sail npm run dev`
    - Execute PHP scripts: `vendor/bin/sail php [script]`
- View all available Sail commands by running `vendor/bin/sail` without arguments.
- The `vendor/bin/sail` commands must be shorted to `sail`.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `vendor/bin/sail artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `vendor/bin/sail artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `vendor/bin/sail artisan list` and check their parameters with `vendor/bin/sail artisan [command] --help`.
- If you're creating a generic PHP class, use `vendor/bin/sail artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `vendor/bin/sail artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `vendor/bin/sail artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `vendor/bin/sail npm run build` or ask the user to run `vendor/bin/sail npm run dev` or `vendor/bin/sail composer run dev`.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/sail bin pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/sail bin pint --test --format agent`, simply run `vendor/bin/sail bin pint --format agent` to fix any formatting issues.

=== phpunit/core rules ===

# PHPUnit

- This application uses PHPUnit for testing. All tests must be written as PHPUnit classes. Use `vendor/bin/sail artisan make:test --phpunit {name}` to create a new test.
- If you see a test using "Pest", convert it to PHPUnit.
- Every time a test has been updated, run that singular test.
- When the tests relating to your feature are passing, ask the user if they would like to also run the entire test suite to make sure everything is still passing.
- Tests should cover all happy paths, failure paths, and edge cases.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files; these are core to the application.

## Running Tests

- Run the minimal number of tests, using an appropriate filter, before finalizing.
- To run all tests: `vendor/bin/sail artisan test --compact`.
- To run all tests in a file: `vendor/bin/sail artisan test --compact tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `vendor/bin/sail artisan test --compact --filter=testName` (recommended after making a change to a related file).

</laravel-boost-guidelines>
