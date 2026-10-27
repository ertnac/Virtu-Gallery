<?php
// Vercel Serverless Entry Point & Front Controller for VirtuGallery

// Extract requested path from REQUEST_URI
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$parsedPath = parse_url($requestUri, PHP_URL_PATH);
$path = urldecode($parsedPath ?: '/');

// Base project directory (parent of api/)
$baseDir = dirname(__DIR__);

// Default root request to index.php
if ($path === '/' || $path === '') {
    $path = '/index.php';
}

// Remove leading slash
$cleanPath = ltrim($path, '/\\');

// Check if targeting a directory (like /admin or /admin/)
$fullPath = $baseDir . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $cleanPath);
if (is_dir($fullPath)) {
    if (is_file($fullPath . DIRECTORY_SEPARATOR . 'index.php')) {
        $fullPath = $fullPath . DIRECTORY_SEPARATOR . 'index.php';
    } elseif (is_file($fullPath . DIRECTORY_SEPARATOR . 'index.html')) {
        $fullPath = $fullPath . DIRECTORY_SEPARATOR . 'index.html';
    }
}

// Fallback: check if file exists in Pages/ if not found at root (e.g. /cart.html -> Pages/cart.html)
if (!file_exists($fullPath) && file_exists($baseDir . DIRECTORY_SEPARATOR . 'Pages' . DIRECTORY_SEPARATOR . $cleanPath)) {
    $fullPath = $baseDir . DIRECTORY_SEPARATOR . 'Pages' . DIRECTORY_SEPARATOR . $cleanPath;
}

// Resolve real canonical path
$realPath = realpath($fullPath);
$realBase = realpath($baseDir);

// Security: Prevent directory traversal outside the project directory
if ($realPath && $realBase && strpos($realPath, $realBase) === 0 && is_file($realPath)) {
    $extension = strtolower(pathinfo($realPath, PATHINFO_EXTENSION));

    if ($extension === 'php') {
        // Change working directory to the target file's directory so relative includes work seamlessly
        chdir(dirname($realPath));
        require $realPath;
        exit;
    }

    // Static assets fallback
    $mimeTypes = [
        'html'  => 'text/html; charset=UTF-8',
        'htm'   => 'text/html; charset=UTF-8',
        'css'   => 'text/css; charset=UTF-8',
        'js'    => 'application/javascript; charset=UTF-8',
        'json'  => 'application/json; charset=UTF-8',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'gif'   => 'image/gif',
        'svg'   => 'image/svg+xml',
        'webp'  => 'image/webp',
        'ico'   => 'image/x-icon',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
    ];

    if (isset($mimeTypes[$extension])) {
        header('Content-Type: ' . $mimeTypes[$extension]);
    }
    header('Content-Length: ' . filesize($realPath));
    readfile($realPath);
    exit;
}

// If file not found, return 404
http_response_code(404);
header('Content-Type: text/html; charset=UTF-8');
echo '<!DOCTYPE html><html><head><title>404 Not Found</title></head><body style="font-family:sans-serif;text-align:center;padding:50px;">';
echo '<h1>404 - Page Not Found</h1>';
echo '<p>The requested path <code>' . htmlspecialchars($path) . '</code> was not found on VirtuGallery.</p>';
echo '<a href="/">Return to VirtuGallery Home</a>';
echo '</body></html>';
exit;
