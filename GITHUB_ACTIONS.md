
# Build IPA from Ubuntu using GitHub Actions

Bạn có thể phát triển/push code từ Ubuntu. GitHub Actions sẽ chạy job trên máy `macos-14`.

## 1. Upload project

```bash
git init
git add .
git commit -m "Initial MB Demo"
git branch -M main
git remote add origin https://github.com/YOUR_USER/YOUR_REPO.git
git push -u origin main
```

## 2. Build

Vào GitHub:

Actions → Build iOS IPA → Run workflow.

Workflow tạo artifact:

`MB-Demo-unsigned-ipa`

## 3. Quan trọng

IPA unsigned không thể cài bình thường lên iPhone. Để có IPA cài được, cần Apple signing:

- Apple Developer account
- Distribution certificate
- Provisioning profile
- Bundle Identifier/App ID

Sau khi cấu hình secrets/certificate phù hợp, dùng workflow `build-signed-ios.yml.template` làm mẫu để build signed IPA.

Không đặt certificate/private key vào repository.
