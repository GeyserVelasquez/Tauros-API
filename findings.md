# Findings: RBAC Implementation

## Discovery & Context
- **Roles in casouso.md:** Technician, Veterinarian, Manager, Admin.
- **Linear Hierarchy:** Vet inherits Tech; Manager inherits Vet; Admin inherits Manager.
- **Routes:** Protected under `auth:sanctum` group.
- **User Model:** `app/Models/User.php` does not have any role implementation.
- **Database:** Standard SQLite or MySQL database running in Sail. We can check the active connection.

## Package Info
- **Package:** `spatie/laravel-permission`
- **Installation Command:** `sail composer require spatie/laravel-permission`
- **Publish Config & Migrations:**
  - `sail artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`
- **Migration Command:** `sail artisan migrate`
