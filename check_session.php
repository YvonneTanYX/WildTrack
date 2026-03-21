<?php
/**
 * check_session.php
 * Place in: WildTrack/check_session.php
 *
 * Include at the TOP of every visitor-facing page:
 *   <?php require_once 'check_session.php'; ?>
 *
 * What it does:
 *  - Starts the session
 *  - If not logged in → redirect to login.html
 *  - If logged in but NOT a visitor → redirect to login.html
 *    (admin/worker have their own portals)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user = $_SESSION['user'] ?? null;

if (!$user || $user['role'] !== 'visitor') {
    header('Location: login.html');
    exit;
}
