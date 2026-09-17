<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل دخول المحاضرين</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .login-card { border-radius: 12px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

    <div class="container" style="max-width: 420px;">
        <div class="card login-card p-4 bg-white">
            <div class="text-center mb-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
                <h4 class="fw-bold text-dark">بوابة المحاضرين</h4>
                <p class="text-muted small">قم بإدخال بيانات الاعتماد الخاصة بك للوصول إلى لوحتك</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger py-2 small" role="alert">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('teacher.login.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold text-secondary">البريد الإلكتروني</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control bg-light border-start-0" placeholder="name@example.com" required value="{{ old('email') }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-secondary">رقم الهاتف (كلمة المرور)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-phone text-muted"></i></span>
                        <input type="text" name="phone" class="form-control bg-light border-start-0" placeholder="أدخل رقم هاتفك المسجل" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 8px;">
                    <i class="fa-solid fa-right-to-bracket me-2"></i> تسجيل الدخول
                </button>
            </form>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100 py-2 fw-bold" style="border-radius: 8px;">
                    <i class="fa-solid fa-right-to-bracket me-2"></i> تسجيل الدخول
                </button>
            </form>
        </div>
    </div>

</body>
</html>