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

# تهيئة الـ Cache وقاعدة البيانات
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. تشغيل المايجريشن تلقائياً وبشكل إجباري على السيرفر
php artisan migrate --force

# تشغيل خادم لارافيل
php artisan serve --host=0.0.0.0 --port=10000

# تنفيذ الجداول مع إدخال البيانات التجريبية
php artisan migrate:fresh --seed --force

# 5. إنشاء رابط التخزين المباشر للملفات المرفوعة
php artisan storage:link || true