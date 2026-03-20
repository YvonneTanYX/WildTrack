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

        if (!in_array($role, ['admin','worker','visitor']))
            respond(false, 'Invalid role.');

        $hashed = password_hash($password, PASSWORD_BCRYPT);

        try {
            $pdo  = getDB();
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role, email)
                                   VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $hashed, $role, $email]);
            respond(true, 'Registered successfully.', ['user_id' => $pdo->lastInsertId()]);
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'Duplicate'))
                respond(false, 'Username already taken.');
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
            respond(false, 'Wrong username or password.');
        }

        $_SESSION['user'] = [
            'user_id'  => $user['user_id'],
            'username' => $user['username'],
            'role'     => $user['role'],
            'email'    => $user['email'],
        ];

        respond(true, 'Login successful.', ['user' => $_SESSION['user']]);
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