# Task Plan: Implementation of Role-Based Access Control (RBAC)

## Goal
Implement a role-based access control system (RBAC) using Spatie's Laravel Permission package, mapping permissions from the case of use documentation, protecting API endpoints via Policies, and validating with feature tests.

## Current Phase
Phase 2: Seeder & Global Bypass

## Phases

### Phase 1: Package Installation & Configuration
- [x] Run `composer require spatie/laravel-permission` via Sail
- [x] Publish Spatie's migrations and config
- [x] Run migrations inside Sail
- [x] Add `HasRoles` trait to the `User` model
- **Status:** complete

### Phase 2: Seeder & Global Bypass
- [x] Create `RolesAndPermissionsSeeder` mapping permissions from `casouso.md`
- [x] Implement cumulative role assignment in the seeder
- [x] Register Seeder in `DatabaseSeeder`
- [x] Configure `Gate::before` in `AppServiceProvider` for Super-Admin bypass
- **Status:** complete

### Phase 3: Resource Policies
- [x] Create Policy classes for main entities (e.g., `LivestockPolicy`, `MilkingPolicy`, `GrowthPolicy`, `ClinicHistoryPolicy`, `ServicePolicy`)
- [x] Map model abilities (viewAny, view, create, update, delete) to Spatie permissions
- **Status:** complete

### Phase 4: Controller Authorization
- [x] Integrate `Gate::authorize()` checks in resource controllers (e.g. `LivestockController`, `MilkingController`, etc.)
- **Status:** complete

### Phase 5: Testing & Verification
- [x] Create feature tests `tests/Feature/RoleSecurityTest.php` to verify 403 Forbidden and 200 OK responses for different roles
- [x] Run test suite via Sail to ensure zero regressions
- **Status:** complete

### Phase 6: Code Style & Cleanup
- [x] Run Laravel Pint formatting tool to match codebase guidelines
- **Status:** complete

## Key Questions
1. Do we need to run migrations and seeders automatically or can we run them manually? We will run them inside the Sail docker environment.
2. Are there any existing users who need default roles? Yes, we should assign roles to users generated in existing seeders.

## Decisions Made
| Decision | Rationale |
|----------|-----------|
| Spatie Permission + Policies | Industry standard, flexible, keeps routes file clean, supports custom domain authorization logic. |
| Seeder-based Cumulative Permissions | Keeps role-hierarchy clean without requiring custom nested roles packages. |

## Errors Encountered
| Error | Attempt | Resolution |
|-------|---------|------------|
|       | 1       |            |
