<?php
ob_start();
/**
 * HydroFlow Configuration
 * Centralized settings for the project including dynamic BASE_URL resolution.
 */

if (!defined('BASE_URL')) {
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    
    // Physical path of the project root (where index.php resides)
    // __DIR__ is .../config. Project root is one level up.
    $project_dir = str_replace('\\', '/', realpath(__DIR__ . '/..'));
    
    // Web path relative to document root
    $doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    
    // Calculate the base path (e.g., /waterS)
    if (!empty($doc_root) && strpos($project_dir, $doc_root) === 0) {
        $base_path = str_replace($doc_root, '', $project_dir);
    } else {
        // Fallback for cases where DOCUMENT_ROOT doesn't match project_dir (e.g. symlinks or CLI)
        // Usually, in XAMPP, this is /waterS if the project is in htdocs
        $base_path = '/' . basename($project_dir);
    }
    
    // Ensure base path doesn't have a trailing slash for consistency
    $base_path = rtrim($base_path, '/');
    
    define('BASE_URL', $protocol . "://" . $host . $base_path . '/');
}

// Session security & preferences
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Multi-language Support
if (isset($_GET['lang'])) {
    $requested_lang = $_GET['lang'];
    if (in_array($requested_lang, ['en', 'hi'])) {
        $_SESSION['lang'] = $requested_lang;
    }
}

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en'; // Default language
}

$lang = $_SESSION['lang'];
$lang_file = __DIR__ . "/../lang/{$lang}.php";
$translations = file_exists($lang_file) ? include $lang_file : include __DIR__ . '/../lang/en.php';

// Global translation helper function
function __($key, $default = null) {
    global $translations;
    return $translations[$key] ?? ($default ?? $key);
}
?>
