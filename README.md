# Legacy Blog Project

This project has been modernised with Composer, Docker and GitHub Actions.

## Requirements
- PHP 8.3+
- Composer
- Docker (optional)

## Setup
1. Copy `config/config.example.php` to `config/config.php` and update database credentials.
2. Run `composer install` to install dependencies.
3. Launch development server with `php -S localhost:8000` or use Docker:
   ```bash
   docker-compose up --build
   ```

## Running Tests
```bash
composer test
```
