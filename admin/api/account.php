<?php
require_once __DIR__ . '/../config/config.php';
$user = user_by_token();
if (!$user) json_response(['ok'=>false,'message'=>'Unauthorized'], 401);

$tx = read_json(TRANSACTIONS_FILE);
$mine = array_values(array_filter($tx, fn($x) => ($x['user_id'] ?? '') === ($user['id'] ?? '')));
unset($user['password_hash']);
json_response(['ok'=>true,'user'=>$user,'transactions'=>$mine]);
