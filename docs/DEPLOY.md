# Deploy không MySQL / không SQLite

## 1. VPS

Yêu cầu PHP 8.1+ và Apache/Nginx.

Copy thư mục `admin` lên VPS, ví dụ:

`/var/www/mb-demo/admin`

Trỏ document root vào:

`/var/www/mb-demo/admin/public`

API nằm tại:

`/api/login.php`
`/api/account.php`
`/api/transfer.php`

## 2. Bảo vệ data

Thư mục `admin/data` chứa JSON và phải bị chặn truy cập trực tiếp. Apache có `.htaccess`; với Nginx cần thêm location deny.

## 3. Tài khoản quản trị demo

Username: `admin`
Password: `hieudn95`

Đổi ngay trong `config/config.php` trước khi đưa lên Internet.

## 4. Flutter

Trong `mobile/lib/services/api_service.dart`, thay:

`https://YOUR-DOMAIN.example/api`

bằng API domain của bạn.

Sau đó trên macOS:

```bash
cd mobile
flutter create .
flutter pub get
flutter build ipa --release
```

File IPA được Flutter tạo trong:

`build/ios/ipa/*.ipa`

Lưu ý: việc ký IPA bằng chứng chỉ Apple cần macOS + Xcode + Apple Developer/Signing phù hợp.
