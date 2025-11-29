# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a CodeIgniter 4-based Human Resources Management (HRM) system with multiple specialized modules for appraisals, recruitment, statistics, and attendance management. The application follows a modular architecture with both legacy and modern components.

## Essential Commands

### Development Server
```bash
php spark serve --port 8080
```

### Testing
```bash
composer test                    # Run all tests
phpunit                         # Direct PHPUnit execution
phpunit --filter ModuleNameTest  # Run specific test module
```

### Database Management
```bash
php spark migrate               # Apply database migrations
php spark migrate:refresh --seed # Refresh migrations with seed data
```

### Dependency Management
```bash
composer install               # Install dependencies
composer dump-autoload        # Regenerate autoloader after namespace changes
```

### Code Quality
```bash
php -l path/to/file           # Lint PHP file
```

### Build Management
```bash
php builds release            # Switch to stable CodeIgniter release
php builds development        # Switch to development CodeIgniter version
```

## Architecture Overview

### Core Structure
- **app/**: Main application code following CodeIgniter 4 MVC pattern
  - **Controllers/**: HTTP request handlers, organized by module (Appraisals, Auth, Dashboard, etc.)
  - **Models/**: Data access layer with Eloquent-style models
  - **Views/**: Template files organized by module in subdirectories
  - **Config/**: Application configuration including routes, database, and custom settings
  - **Commands/**: CLI commands for automated tasks
  - **Database/**: Migrations and database schema definitions

### Module Organization
- **appraisal/**, **recruitment/**, **stats/**, **demo1/**: Legacy front-end modules with dedicated UI bundles
- **public/**: Web-accessible assets and entry point (index.php)
- **writable/**: Runtime files (logs, cache, uploads) - never expose via web server
- **tests/**: Organized test suites (unit/, database/, session/, _support/)

### Key Configuration Files
- **app/Config/Routes.php**: URL routing definitions
- **app/Config/Database.php**: Database connection settings
- **composer.json**: PHP dependencies and autoloading configuration
- **phpunit.xml.dist**: Test configuration with coverage reporting
- **.env**: Environment-specific configuration (copy from `env` template)

## Development Guidelines

### Naming Conventions
- Controllers: `SomethingController` (PascalCase)
- Models: Follow CodeIgniter 4 model conventions
- Views: `snake_case.php` reflecting route structure
- Methods: `camelCase`
- Database columns: `snake_case`

### Code Organization
- Place new PHP controllers in `app/Controllers/`
- Group related controllers in subdirectories by module
- Keep services under `App\Services` namespace
- Store shared assets in `public/` mirroring module names
- Configuration changes go in `app/Config/`

### Security Best Practices
- Copy `env` to `.env` and configure for your environment
- Never commit secrets or credentials to version control
- Validate and sanitize all file uploads before storage
- Use environment variables for sensitive configuration
- Restrict write permissions on `writable/` directory

### Testing Strategy
- Unit tests in `tests/unit/` mirroring application namespaces
- Database tests in `tests/database/` with dedicated seeds
- Use descriptive test method names: `test_handles_empty_schedule()`
- Test both success and failure scenarios
- Run `php spark migrate:refresh --seed` before integration tests

## Important Notes

- **Entry Point**: Application entry is `public/index.php`, not project root
- **PHP Requirements**: Minimum PHP 8.1 with intl and mbstring extensions
- **Legacy Modules**: Existing front-end modules (appraisal/, recruitment/, etc.) contain legacy UI - integrate through `app/Controllers/`
- **File Uploads**: Handle through dedicated controllers, store in `writable/uploads/`
- **CLI Tools**: Use `php spark` for CodeIgniter CLI commands
- **Autoloading**: Follows PSR-4 with `App\` namespace mapping to `app/` directory