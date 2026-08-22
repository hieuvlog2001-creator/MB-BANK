<?php
session_start();
require_once __DIR__ . '/../config/config.php';
ensure_data();

if (isset($_POST['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['admin'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $u = $_POST['username'] ?? '';
        $p = $_POST['password'] ?? '';
        if ($u === ADMIN_USERNAME && $p === ADMIN_PASSWORD) {
            $_SESSION['admin'] = true;
            header('Location: index.php');
            exit;
        }
        $error = 'Sai tài khoản quản trị.';
    }
?>
<!doctype html>
<html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>MB Demo Admin</title>
<style>
body{font-family:Arial;background:#f3f5f9;margin:0;display:grid;place-items:center;height:100vh}
.card{background:#fff;padding:32px;border-radius:22px;width:min(380px,90%);box-shadow:0 15px 50px #0001}
h1{color:#1428a0}.input{width:100%;box-sizing:border-box;padding:14px;margin:7px 0;border:1px solid #ddd;border-radius:12px}
button{width:100%;padding:14px;background:#1428a0;color:#fff;border:0;border-radius:12px;font-weight:bold}
.err{color:#d33}
</style></head><body><form class="card" method="post">
<h1>MB Demo Admin</h1><p>Quản lý dữ liệu JSON, không MySQL/SQLite.</p>
<?php if(isset($error)) echo '<p class="err">'.htmlspecialchars($error).'</p>'; ?>
<input class="input" name="username" placeholder="Tài khoản" required>
<input class="input" name="password" type="password" placeholder="Mật khẩu" required>
<button>Đăng nhập</button>
</form></body></html>
<?php exit; }

$users = read_json(USERS_FILE);
$tx = read_json(TRANSACTIONS_FILE);
?>
<!doctype html>
<html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>MB Demo Admin</title>
<style>
body{font-family:Arial;background:#f5f6f8;margin:0;color:#17181c}.top{background:#1428a0;color:#fff;padding:18px 24px;display:flex;justify-content:space-between}
main{padding:24px;max-width:1200px;margin:auto}.grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
.card{background:#fff;padding:20px;border-radius:18px;box-shadow:0 5px 20px #00000008}.num{font-size:28px;font-weight:800}
table{width:100%;border-collapse:collapse;background:#fff;border-radius:18px;overflow:hidden}th,td{text-align:left;padding:13px;border-bottom:1px solid #eee}
button{background:#fff;border:1px solid #ddd;border-radius:10px;padding:8px 12px}.logout{color:#1428a0}
@media(max-width:800px){.grid{grid-template-columns:1fr}main{padding:14px}}
</style></head>
<body><div class="top"><b>MB Demo Admin</b><form method="post"><button name="logout">Đăng xuất</button></form></div>
<main>
<div class="grid">
<div class="card"><div>Người dùng</div><div class="num"><?=count($users)?></div></div>
<div class="card"><div>Giao dịch</div><div class="num"><?=count($tx)?></div></div>
<div class="card"><div>Tổng số dư demo</div><div class="num"><?=number_format(array_sum(array_map(fn($u)=>(float)($u['balance']??0),$users)),0,',','.')?> ₫</div></div>
</div>
<h2>Tài khoản</h2>
<table><tr><th>ID</th><th>Username</th><th>Họ tên</th><th>Số dư</th><th>Trạng thái</th></tr>
<?php foreach($users as $u): ?>
<tr><td><?=htmlspecialchars($u['id']??'')?></td><td><?=htmlspecialchars($u['username']??'')?></td><td><?=htmlspecialchars($u['name']??'')?></td><td><?=number_format((float)($u['balance']??0),0,',','.')?> ₫</td><td><?=htmlspecialchars($u['status']??'active')?></td></tr>
<?php endforeach; ?></table>
<h2>Giao dịch gần đây</h2>
<table><tr><th>Thời gian</th><th>User</th><th>Loại</th><th>Số tiền</th><th>Đến</th></tr>
<?php foreach(array_slice(array_reverse($tx),0,50) as $t): ?>
<tr><td><?=htmlspecialchars($t['created_at']??'')?></td><td><?=htmlspecialchars($t['user_id']??'')?></td><td><?=htmlspecialchars($t['type']??'')?></td><td><?=number_format((float)($t['amount']??0),0,',','.')?> ₫</td><td><?=htmlspecialchars($t['to']??'')?></td></tr>
<?php endforeach; ?></table>
</main></body></html>
