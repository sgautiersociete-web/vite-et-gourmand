<?php
// app/helpers/functions.php

function e(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

function view(string $template, array $data = []): void
{
    extract($data);
    require APP_PATH . '/views/' . $template . '.php';
}

function isPost(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function post(string $key, string $default = ''): string
{
    return trim((string)($_POST[$key] ?? $default));
}

function get(string $key, string $default = ''): string
{
    return trim((string)($_GET[$key] ?? $default));
}

/**
 * Calcul distance Haversine (km) entre deux points GPS
 */
function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
{
    $R = 6371; // Rayon Terre en km
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) * sin($dLat/2)
       + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
       * sin($dLon/2) * sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    return round($R * $c, 2);
}

/**
 * Calcul prix livraison
 * 0€ si Bordeaux, sinon 5€ + 0.59€/km
 */
function calculPrixLivraison(string $ville, float $distance = 0.0): float
{
    if (strtolower(trim($ville)) === 'bordeaux') {
        return 0.0;
    }
    return round(5.0 + (0.59 * $distance), 2);
}

/**
 * Calcul réduction : 10% si nb_personnes >= (min + 5)
 */
function calculReduction(float $prixBase, int $nbPersonnes, int $nbMin): float
{
    if ($nbPersonnes >= ($nbMin + 5)) {
        return round($prixBase * 0.10, 2);
    }
    return 0.0;
}

/**
 * Génère un numéro de commande unique
 */
function genNumeroCommande(): string
{
    return 'VG-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
}

/**
 * Validation mot de passe fort
 */
function isPasswordStrong(string $password): bool
{
    return strlen($password) >= 10
        && preg_match('/[A-Z]/', $password)
        && preg_match('/[a-z]/', $password)
        && preg_match('/[0-9]/', $password)
        && preg_match('/[\W_]/', $password);
}

/**
 * Envoi email (PHPMailer ou mail() natif selon config)
 */
function sendMail(string $to, string $subject, string $htmlBody): bool
{
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Vite & Gourmand <noreply@viteetgourmand.fr>\r\n";
    return mail($to, $subject, $htmlBody, $headers);
}

/**
 * Chargement des variables d'environnement depuis .env
 */
function loadEnv(string $path): void
{
    if (!file_exists($path)) return;
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        [$key, $val] = array_map('trim', explode('=', $line, 2));
        $_ENV[$key] = $val;
    }
}

loadEnv(ROOT_PATH . '/.env');
