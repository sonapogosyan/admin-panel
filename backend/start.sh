#!/bin/bash
set -e

# Wait for MySQL
echo "🟢 Waiting for MySQL..."
php -r '
$connected = false;
while (!$connected) {
    try {
        new PDO("mysql:host=" . getenv("DB_HOST") . ";port=" . getenv("DB_PORT") . ";dbname=" . getenv("DB_DATABASE"), getenv("DB_USERNAME"), getenv("DB_PASSWORD"));
        $connected = true;
    } catch (Exception $e) {
        sleep(2);
    }
}
'
echo "✅ MySQL is ready!"

# Composer install
if [ ! -d "vendor" ]; then
    echo "📦 Installing Composer dependencies..."
    composer install --no-interaction --optimize-autoloader
fi

# ENV setup
if [ ! -f ".env" ]; then
    echo "⚙️ Creating .env..."
    cp .env.example .env
fi

# Ensure key is generated
if ! grep -q "APP_KEY=base64" .env; then
    php artisan key:generate
fi

# Fix permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Run migrations
echo "🗄 Running migrations..."
php artisan migrate --force

# Seed database (including admin)
echo "🌱 Seeding database..."
php artisan db:seed --force

echo "🚀 Starting Laravel..."
php artisan serve --host=0.0.0.0 --port=8000
