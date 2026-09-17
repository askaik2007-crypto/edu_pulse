#!/usr/bin/env bash
# إيقاف التنفيذ في حال حدوث أي خطأ
set -o errexit

# 1. تثبيت حزم PHP المخصصة للإنتاج
composer install --no-dev --optimize-autoloader

# 2. تثبيت وتجميع ملفات Frontend (Vite/NPM)
npm install
npm run build

# 3. مسح التخزين المؤقت القديم وإعادة بنائه
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize

# 4. تشغيل المايجريشن تلقائياً وبشكل إجباري على السيرفر
php artisan migrate --force

# 5. إنشاء رابط التخزين المباشر للملفات المرفوعة
php artisan storage:link || true