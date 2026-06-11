<?php
/**
 * UrbanSync Configuration File
 * Store all application settings and constants
 */

// Application Settings
define('APP_NAME', 'UrbanSync');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost:8000');
define('APP_ENV', 'development'); // 'development' or 'production'

// Timezone
date_default_timezone_set('UTC');

// Session Settings
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds
define('SESSION_NAME', 'urbansync_session');

// Security
define('HASH_ALGORITHM', PASSWORD_BCRYPT);
define('HASH_COST', 12);

// Pagination
define('ITEMS_PER_PAGE', 12);
define('MESSAGES_PER_PAGE', 20);

// File Upload Settings
define('MAX_UPLOAD_SIZE', 5242880); // 5MB in bytes
define('UPLOAD_DIR', __DIR__ . '/../../public_html/uploads/');
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// Payment Settings (Stripe/PayPal placeholders)
define('PAYMENT_GATEWAY', 'stripe'); // or 'paypal'
define('STRIPE_API_KEY', getenv('STRIPE_API_KEY') ?: 'sk_test_placeholder');
define('PAYPAL_CLIENT_ID', getenv('PAYPAL_CLIENT_ID') ?: 'sb_placeholder');

// Email Settings
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');
define('SMTP_PORT', getenv('SMTP_PORT') ?: 587);
define('SMTP_USER', getenv('SMTP_USER') ?: 'your-email@gmail.com');
define('SMTP_PASS', getenv('SMTP_PASS') ?: 'your-password');
define('FROM_EMAIL', 'noreply@urbansync.local');
define('FROM_NAME', 'UrbanSync');

// Map Settings (OpenStreetMap)
define('MAP_CENTER_LAT', -3.34);
define('MAP_CENTER_LNG', 37.34);
define('MAP_DEFAULT_ZOOM', 13);
define('MAP_TILE_URL', 'https://tile.openstreetmap.org/{z}/{x}/{y}.png');
define('MAP_ATTRIBUTION', '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>');

// Currency
define('CURRENCY_CODE', 'USD');
define('CURRENCY_SYMBOL', '$');

// API Response
define('API_VERSION', 'v1');

// Admin Settings
define('ADMIN_EMAIL', 'admin@urbansync.local');
define('ADMIN_PHONE', '+1234567890');

// Error Handling
error_reporting(APP_ENV === 'production' ? 0 : E_ALL);
ini_set('display_errors', APP_ENV === 'production' ? '0' : '1');

// Load environment variables from .env file if exists
if (file_exists(__DIR__ . '/../../.env')) {
    $env_vars = parse_ini_file(__DIR__ . '/../../.env');
    foreach ($env_vars as $key => $value) {
        putenv($key . '=' . $value);
    }
}
