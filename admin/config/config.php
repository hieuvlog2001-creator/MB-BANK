<?php
declare(strict_types=1);

const DATA_DIR = __DIR__ . '/../data';
const USERS_FILE = DATA_DIR . '/users.json';
const TRANSACTIONS_FILE = DATA_DIR . '/transactions.json';
const SESSIONS_FILE = DATA_DIR . '/sessions.json';

const ADMIN_USERNAME = 'admin';
const ADMIN_PASSWORD = 'hieudn95';

function ensure_data(): void {
    if (!is_dir(DATA_DIR)) mkdir(DATA_DIR, 0755, true);
    foreach ([USERS_FILE, TRANSACTIONS_FILE, SESSIONS_FILE] as $file) {
        if (!file_exists($file)) file_put_contents($file, "[]", LOCK_EX);
    }
}

function read_json(string $file): array {
    ensure_data();
    $raw = @file_get_contents($file);
    $data = json_decode($raw ?: '[]', true);
    return is_array($data) ? $data : [];
}

function write_json(string $file, array $data): void {
    ensure_data();
    $tmp = $file . '.tmp';
    file_put_contents($tmp, json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE), LOCK_EX);
    rename($tmp, $file);
}

function input_json(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: '{}', true);
    return is_array($data) ? $data : [];
}

function bearer_token(): ?string {
    $h = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (preg_match('/Bearer\s+(.+)/i', $h, $m)) return trim($m[1]);
    return null;
}

function user_by_token(): ?array {
    $token = bearer_token();
    if (!$token) return null;
    $sessions = read_json(SESSIONS_FILE);
    foreach ($sessions as $s) {
        if (($s['token'] ?? '') === $token && ($s['expires'] ?? 0) > time()) {
            $users = read_json(USERS_FILE);
            foreach ($users as $u) {
                if (($u['id'] ?? '') === ($s['user_id'] ?? '')) return $u;
            }
        }
    }
    return null;
}

function json_response(array $data, int $status = 200): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
