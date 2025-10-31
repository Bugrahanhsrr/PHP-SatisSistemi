<?php
/**
 * Configuration File
 * Site-wide settings and constants
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Site configuration
define('SITE_NAME', 'Product Selling System');
define('BASE_URL', 'http://localhost');

// Timezone
date_default_timezone_set('Europe/Istanbul');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

