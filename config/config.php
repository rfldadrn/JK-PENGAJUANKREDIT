<?php

// Application Configuration
define('APP_NAME', 'Sistem Pengajuan Kredit BRI');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost/PengajuanKredit/public/');
define('BASE_PATH', __DIR__ . '/../');

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Session Configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds (adjust as needed)

// Upload Configuration
define('UPLOAD_PATH', BASE_PATH . 'public/assets/uploads/');
define('MAX_FILE_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_EXTENSIONS', ['pdf', 'jpg', 'jpeg', 'png']);

// Pagination
define('PER_PAGE', 10);

// Development Mode
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
