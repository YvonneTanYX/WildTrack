<?php
require_once __DIR__ . '/db.php';

function respond(bool $success, string $message, array $data = []): void {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *'); // allows your HTML to call PHP
    echo json_encode(array_merge(
        ['success' => $success, 'message' => $message],
        $data
    ));
    exit;
}

function currentUser(): ?array {
    if (session_status() === PHP_SESSION_NONE) session_start();
    return $_SESSION['user'] ?? null;
}

function requireLogin(): array {
    $user = currentUser();
    if (!$user) {
        http_response_code(401);
        respond(false, 'Not logged in. Please login first.');
    }
    return $user;
}

function requireRole(string ...$roles): array {
    $user = requireLogin();
    if (!in_array($user['role'], $roles)) {
        http_response_code(403);
        respond(false, 'Access denied.');
    }
    return $user;
}

function clean(?string $value): string {
    return htmlspecialchars(trim($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function jsonBody(): array {
    $raw = file_get_contents('php://input');
    return json_decode($raw, true) ?? [];
}