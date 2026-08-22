<?php
require_once __DIR__ . '/../config/config.php';
$user = user_by_token();
if (!$user) json_response(['ok'=>false,'message'=>'Unauthorized'], 401);

$d = input_json();
$to = trim((string)($d['to'] ?? ''));
$amount = (float)($d['amount'] ?? 0);
$note = trim((string)($d['note'] ?? ''));

if ($to === '' || $amount <= 0) json_response(['ok'=>false,'message'=>'Dữ liệu không hợp lệ'], 422);

$users = read_json(USERS_FILE);
$idx = null;
foreach ($users as $i => $u) {
    if (($u['id'] ?? '') === $user['id']) { $idx = $i; break; }
}
if ($idx === null) json_response(['ok'=>false,'message'=>'Không tìm thấy tài khoản'], 404);

if ((float)$users[$idx]['balance'] < $amount) {
    json_response(['ok'=>false,'message'=>'Số dư demo không đủ'], 422);
}

$users[$idx]['balance'] = round((float)$users[$idx]['balance'] - $amount, 2);
write_json(USERS_FILE, $users);

$tx = read_json(TRANSACTIONS_FILE);
$tx[] = [
    'id'=>bin2hex(random_bytes(8)),
    'user_id'=>$user['id'],
    'type'=>'transfer_demo',
    'to'=>$to,
    'amount'=>$amount,
    'note'=>$note,
    'created_at'=>date('c'),
];
write_json(TRANSACTIONS_FILE, $tx);

json_response(['ok'=>true,'message'=>'Giao dịch DEMO thành công']);
