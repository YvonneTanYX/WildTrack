<?php
// api/staff.php
// Admin-only endpoint for managing staff accounts
// All actions require an active admin session.

require_once __DIR__ . '/../config/helpers.php';
session_start();

// ── Auth guard ────────────────────────────────────────────────
$currentUser = $_SESSION['user'] ?? null;
if (!$currentUser || $currentUser['role'] !== 'admin') {
    http_response_code(403);
    respond(false, 'Administrators only.');
}

$action = $_GET['action'] ?? '';
$pdo    = getDB();

switch ($action) {

    // ── List all staff (workers + admins, not visitors) ──────
    case 'get_staff': {
        $stmt = $pdo->query("
            SELECT
                u.user_id,
                u.username,
                u.email,
                u.role,
                u.is_active,
                u.must_change_pw,
                u.created_at,
                w.worker_id,
                w.full_name,
                w.position,
                w.phone,
                w.hire_date
            FROM users u
            LEFT JOIN workers w ON w.user_id = u.user_id
            WHERE u.role IN ('admin', 'worker')
            ORDER BY u.created_at DESC
        ");
        $staff = $stmt->fetchAll();
        respond(true, 'OK', ['staff' => $staff]);
        break;
    }

    // ── Create a new staff account ───────────────────────────
    case 'create_staff': {
        $body     = jsonBody();
        $name     = clean($body['name']     ?? '');
        $email    = clean($body['email']    ?? '');
        $role     = clean($body['role']     ?? 'worker');
        $position = clean($body['position'] ?? '');
        $phone    = clean($body['phone']    ?? '');
        $tempPw   = $body['temp_password']  ?? '';

        if (!$name || !$email || !$tempPw)
            respond(false, 'Name, email and temporary password are required.');
        if (!in_array($role, ['admin', 'worker']))
            respond(false, 'Invalid role. Must be admin or worker.');
        if (strlen($tempPw) < 6)
            respond(false, 'Temporary password must be at least 6 characters.');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
            respond(false, 'Invalid email address.');

        // Check email not already taken
        $chk = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $chk->execute([$email]);
        if ($chk->fetch())
            respond(false, 'An account with this email already exists.');

        $hashed = password_hash($tempPw, PASSWORD_BCRYPT);

        try {
            $pdo->beginTransaction();

            // 1. Insert into users — must_change_pw = 1 forces staff to set own password
            $stmt = $pdo->prepare("
                INSERT INTO users (username, password, role, email, is_active, must_change_pw)
                VALUES (?, ?, ?, ?, 1, 1)
            ");
            $stmt->execute([$name, $hashed, $role, $email]);
            $userId = $pdo->lastInsertId();

            // 2. Insert into workers table (position & phone details)
            if ($role === 'worker') {
                $stmt2 = $pdo->prepare("
                    INSERT INTO workers (user_id, full_name, position, phone, hire_date)
                    VALUES (?, ?, ?, ?, CURDATE())
                ");
                $stmt2->execute([$userId, $name, $position, $phone]);
            }

            $pdo->commit();
            $adminList = $pdo->query("SELECT user_id FROM users WHERE role = 'admin'")->fetchAll();
                foreach ($adminList as $adminUser) {
                    $pdo->prepare(
                        "INSERT INTO notifications (user_id, type, title, body, created_at)
                        VALUES (?, 'new_staff', 'New Staff Account Created', ?, NOW())"
                    )->execute([
                        $adminUser['user_id'],
                        "Staff account for {$name} has been created. Role: {$role}."
                    ]);
                }
            respond(true, 'Staff account created successfully.', ['user_id' => $userId]);
        } catch (PDOException $e) {
            $pdo->rollBack();
            respond(false, 'Failed to create staff: ' . $e->getMessage());
        }
        break;
    }

    // ── Update staff name, role, position, phone ─────────────
    case 'update_staff': {
        $body     = jsonBody();
        $userId   = (int)($body['user_id']  ?? 0);
        $name     = clean($body['name']     ?? '');
        $role     = clean($body['role']     ?? '');
        $position = clean($body['position'] ?? '');
        $phone    = clean($body['phone']    ?? '');

        if (!$userId || !$name || !$role)
            respond(false, 'User ID, name and role are required.');
        if (!in_array($role, ['admin', 'worker']))
            respond(false, 'Invalid role.');

        try {
            $pdo->beginTransaction();

            $pdo->prepare("UPDATE users SET username = ?, role = ? WHERE user_id = ?")
                ->execute([$name, $role, $userId]);

            // Update or insert workers record
            $chk = $pdo->prepare("SELECT worker_id FROM workers WHERE user_id = ?");
            $chk->execute([$userId]);
            $workerRow = $chk->fetch();

            if ($workerRow) {
                $pdo->prepare("UPDATE workers SET full_name = ?, position = ?, phone = ? WHERE user_id = ?")
                    ->execute([$name, $position, $phone, $userId]);
            } else {
                $pdo->prepare("INSERT INTO workers (user_id, full_name, position, phone, hire_date) VALUES (?, ?, ?, ?, CURDATE())")
                    ->execute([$userId, $name, $position, $phone]);
            }

            $pdo->commit();
            respond(true, 'Staff updated successfully.');
        } catch (PDOException $e) {
            $pdo->rollBack();
            respond(false, 'Update failed: ' . $e->getMessage());
        }
        break;
    }

    // ── Toggle active/inactive ────────────────────────────────
    case 'toggle_status': {
        $body   = jsonBody();
        $userId = (int)($body['user_id'] ?? 0);
        if (!$userId) respond(false, 'User ID required.');

        // Prevent admin from deactivating their own account
        if ($userId === (int)$currentUser['user_id'])
            respond(false, 'You cannot deactivate your own account.');

        $pdo->prepare("UPDATE users SET is_active = NOT is_active WHERE user_id = ?")
            ->execute([$userId]);
        respond(true, 'Status updated.');
        break;
    }

    // ── Reset password (admin sets new temp password) ─────────
    case 'reset_password': {
        $body   = jsonBody();
        $userId = (int)($body['user_id']      ?? 0);
        $tempPw = $body['temp_password'] ?? '';

        if (!$userId || strlen($tempPw) < 6)
            respond(false, 'User ID and a temp password (min 6 chars) are required.');

        $hashed = password_hash($tempPw, PASSWORD_BCRYPT);
        $pdo->prepare("UPDATE users SET password = ?, must_change_pw = 1 WHERE user_id = ?")
            ->execute([$hashed, $userId]);
        respond(true, 'Password reset. Staff will be prompted to change it on next login.');
        break;
    }

    // ── Delete staff account ──────────────────────────────────
    case 'delete_staff': {
        $body   = jsonBody();
        $userId = (int)($body['user_id'] ?? 0);
        if (!$userId) respond(false, 'User ID required.');

        if ($userId === (int)$currentUser['user_id'])
            respond(false, 'You cannot delete your own account.');

        try {
            $pdo->prepare("DELETE FROM users WHERE user_id = ? AND role IN ('admin','worker')")
                ->execute([$userId]);
            respond(true, 'Staff account deleted.');
        } catch (PDOException $e) {
            respond(false, 'Delete failed: ' . $e->getMessage());
        }
        break;
    }

    // ── Get all zoo_settings key/value pairs ─────────────────
    case 'get_settings': {
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM zoo_settings");
        $rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);  // ['key' => 'value', ...]
        respond(true, 'OK', ['settings' => $rows]);
        break;
    }

    // ── Save a single zoo_settings entry ──────────────────────
    case 'save_setting': {
        $body  = jsonBody();
        $key   = clean($body['key']   ?? '');
        $value = $body['value'] ?? '';

        if (!$key) respond(false, 'Setting key is required.');

        // Whitelist allowed keys so admins can't write arbitrary rows
        $allowed = [
            'notif_pref_tickets', 'notif_pref_reviews',
            'notif_pref_events',  'notif_pref_stars',
            'open_time', 'close_time', 'last_entry_mins', 'last_online_purchase_mins',
        ];
        if (!in_array($key, $allowed)) respond(false, 'Unknown setting key.');

        $pdo->prepare(
            "INSERT INTO zoo_settings (setting_key, setting_value)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
        )->execute([$key, $value]);

        respond(true, 'Setting saved.');
        break;
    }

    default:
        respond(false, 'Unknown action.');
}
