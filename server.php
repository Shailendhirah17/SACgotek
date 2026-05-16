<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// ─── SPECIAL ROUTE: /api.php/* → public/api.php ───
// Handle /api.php and /api.php/anything paths
if (preg_match('#^/api\.php(/.*)?$#', $uri)) {
    $apiFile = __DIR__.'/public/api.php';
    if (file_exists($apiFile)) {
        require $apiFile;
        return;
    }
}

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.

// ─── Handle PHP files with PATH_INFO (e.g. /some/file.php/extra/path) ───
if (preg_match('#^(.+\.php)(/.*)?$#', $uri, $matches)) {
    $phpFile = __DIR__ . $matches[1];
    if (file_exists($phpFile) && !is_dir($phpFile)) {
        if (isset($matches[2])) {
            $_SERVER['PATH_INFO'] = $matches[2];
        }
        require $phpFile;
        return;
    }
}

// ─── Serve static files (follows symlinks via PHP's file_exists) ───
if ($uri != '/' && file_exists(__DIR__.$uri) && !is_dir(__DIR__.$uri)) {

    $mimeTypes = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'json' => 'application/json',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'svg'  => 'image/svg+xml',
        'ico'  => 'image/x-icon',
        'webp' => 'image/webp',
        'woff' => 'font/woff',
        'woff2'=> 'font/woff2',
        'ttf'  => 'font/ttf',
        'eot'  => 'application/vnd.ms-fontobject',
        'otf'  => 'font/otf',
        'map'  => 'application/json',
        'mp4'  => 'video/mp4',
        'pdf'  => 'application/pdf',
        'xml'  => 'application/xml',
        'txt'  => 'text/plain',
        'html' => 'text/html',
        'htm'  => 'text/html',
    ];

    $ext = strtolower(pathinfo($uri, PATHINFO_EXTENSION));

    // For PHP files, execute them
    if ($ext === 'php') {
        require __DIR__.$uri;
        return;
    }

    $mime = $mimeTypes[$ext] ?? 'application/octet-stream';

    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize(__DIR__.$uri));
    header('Cache-Control: public, max-age=3600');
    readfile(__DIR__.$uri);
    return;
}

// ─── Fall through to Laravel ───
require_once __DIR__.'/index.php';
