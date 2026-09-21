<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل بيانات الطالب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .preview-img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body class="p-3 p-md-5">

    <div class="container" style="max-width: 650px;">
        <div class="card card-custom p-4 bg-white">
            <h4 class="fw-bold mb-4 text-dark text-center">
                <i class="fa-solid fa-user-pen text-warning me-2"></i>تعديل بيانات الطالب
            </h4>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">اسم الطالب <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $student->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">البريد الإلكتروني <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $student->email) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">رقم الهاتف</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $student->phone) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">تاريخ الميلاد</label>
                    <input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $student->birth_date) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">الجنس <span class="text-danger">*</span></label>
                    <select name="gender" class="form-select" required>
                        <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>ذكر</option>
                        <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>أنثى</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">تحديث الصورة أو المرفق (اختياري)</label>
                    <input type="file" name="image" class="form-control" accept=".jpeg,.png,.jpg,.pdf">
                    
                    @if($student->image)
                        <div class="mt-2 d-flex align-items-center gap-2">
                            <span class="small text-muted">المرفق الحالي:</span>
                            @php $ext = pathinfo($student->image, PATHINFO_EXTENSION); @endphp
                            @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp']))
                                <img src="{{ asset('storage/' . $student->image) }}" class="preview-img border" alt="المرفق الحالي">
                            @else
                                <a href="{{ asset('storage/' . $student->image) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="fa-solid fa-file me-1"></i> عرض المستند الحالية
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-between gap-2">
                    <a href="{{ route('students.index') }}" class="btn btn-secondary px-4">إلغاء</a>
                    <button type="submit" class="btn btn-warning fw-bold px-4 text-white">
                        <i class="fa-solid fa-rotate me-1"></i> تحديث البيانات
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>