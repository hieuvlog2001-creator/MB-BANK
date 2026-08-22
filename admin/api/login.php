<?php
require_once __DIR__ . '/../config/config.php';
ensure_data();
$d = input_json();
$username = trim((string)($d['username'] ?? ''));
$password = (string)($d['password'] ?? '');

$users = read_json(USERS_FILE);
foreach ($users as $u) {
    if (($u['username'] ?? '') === $username && password_verify($password, $u['password_hash'] ?? '')) {
        $token = bin2hex(random_bytes(32));
        $sessions = read_json(SESSIONS_FILE);
        $sessions[] = ['token'=>$token,'user_id'=>$u['id'],'expires'=>time()+86400];
        write_json(SESSIONS_FILE, $sessions);
        unset($u['password_hash']);
        json_response(['ok'=>true,'token'=>$token,'user'=>$u]);
    }
}
json_response(['ok'=>false,'message'=>'Sai tài khoản hoặc mật khẩu'], 401);
