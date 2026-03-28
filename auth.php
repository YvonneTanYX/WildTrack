<?php
require_once __DIR__ . '/../config/helpers.php';
session_start();

$action = $_GET['action'] ?? '';

switch ($action) {

    case 'register': {
        $body      = jsonBody();
        $firstName = clean($body['firstName'] ?? '');
        $lastName  = clean($body['lastName']  ?? '');
        $username  = trim($firstName . ' ' . $lastName);
        $password  = $body['password'] ?? '';
        $role      = clean($body['role']      ?? 'visitor');
        $email     = clean($body['email']     ?? '');

        if (!$username || !$password || !$email)
            respond(false, 'Name, email and password are required.');

        // Only visitors can self-register
        // Staff accounts must be created by admin via api/staff.php
        if ($role !== 'visitor')
            respond(false, 'Staff accounts can only be created by an administrator.');

        $hashed = password_hash($password, PASSWORD_BCRYPT);

        try {
            $pdo  = getDB();
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role, email, is_active, must_change_pw)
                                   VALUES (?, ?, 'visitor', ?, 1, 0)");
            $stmt->execute([$username, $hashed, $email]);
            respond(true, 'Registered successfully.', ['user_id' => $pdo->lastInsertId()]);
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'Duplicate'))
                respond(false, 'Email already registered.');
            respond(false, 'Registration failed: ' . $e->getMessage());
        }
        break;
    }

    case 'login': {
        $body     = jsonBody();
        $email    = clean($body['email'] ?? '');
        $password = $body['password'] ?? '';

        if (!$email || !$password)
            respond(false, 'Email and password required.');

        $pdo  = getDB();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            http_response_code(401);
            respond(false, 'Wrong email or password.');
        }

        // Block deactivated accounts
        if (isset($user['is_active']) && !$user['is_active']) {
            http_response_code(403);
            respond(false, 'Your account has been deactivated. Please contact the administrator.');
        }

        $_SESSION['user'] = [
            'user_id'        => $user['user_id'],
            'username'       => $user['username'],
            'role'           => $user['role'],
            'email'          => $user['email'],
            'must_change_pw' => (int)($user['must_change_pw'] ?? 0),
        ];

        respond(true, 'Login successful.', [
            'user'           => $_SESSION['user'],
            'must_change_pw' => (int)($user['must_change_pw'] ?? 0),
        ]);
        break;
    }

    case 'change_password': {
        // Staff must change their temporary password on first login
        $user = $_SESSION['user'] ?? null;
        if (!$user) respond(false, 'Not logged in.');

        $body       = jsonBody();
        $newPw      = $body['new_password'] ?? '';
        $confirmPw  = $body['confirm_password'] ?? '';

        if (strlen($newPw) < 8)
            respond(false, 'Password must be at least 8 characters.');
        if ($newPw !== $confirmPw)
            respond(false, 'Passwords do not match.');

        $hashed = password_hash($newPw, PASSWORD_BCRYPT);

        try {
            $pdo  = getDB();
            $stmt = $pdo->prepare("UPDATE users SET password = ?, must_change_pw = 0 WHERE user_id = ?");
            $stmt->execute([$hashed, $user['user_id']]);

            // Update session so redirect doesn't loop
            $_SESSION['user']['must_change_pw'] = 0;
            respond(true, 'Password changed successfully.');
        } catch (PDOException $e) {
            respond(false, 'Failed to change password: ' . $e->getMessage());
        }
        break;
    }

    case 'logout': {
        session_destroy();
        respond(true, 'Logged out.');
        break;
    }

    case 'me': {
        $user = currentUser();
        if (!$user) respond(false, 'Not logged in.');
        respond(true, 'OK', ['user' => $user]);
        break;
    }

    default:
        respond(false, 'Unknown action.');
}
