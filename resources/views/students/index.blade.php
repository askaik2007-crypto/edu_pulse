<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الطلاب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .table th { background-color: #1e293b; color: white; text-align: center; }
        .table td { vertical-align: middle; text-align: center; }
        .avatar-img { width: 45px; height: 45px; object-fit: cover; border-radius: 50%; border: 2px solid #e2e8f0; }
    </style>
</head>
<body class="p-3 p-md-5">

    <div class="container-fluid">
        <!-- Header -->
        <div class="card card-custom p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                
                <div>
                    <h2 class="fw-bold m-0 text-dark">
                        إدارة الطلاب <i class="fa-solid fa-user-graduate text-success ms-1"></i>
                    </h2>
                    <small class="text-muted">عرض وتعديل بيانات الطلاب المسجلين والمرفقات</small>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <a href="{{ route('students.create') }}" class="btn btn-success fw-bold">
                        <i class="fa-solid fa-user-plus me-1"></i> إضافة طالب جديد
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary fw-bold">
                        <i class="fa-solid fa-gauge me-1"></i> لوحة التحكم
                    </a>
                    <a href="{{ route('students.export', request()->query()) }}" class="btn btn-outline-success fw-bold">
                        <i class="fa-solid fa-file-excel me-1"></i> تصدير إلى Excel
                    </a>
                </div>

            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center justify-content-between" role="alert">
                <div>
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center justify-content-between" role="alert">
                <div>
                    <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Table -->
        <div class="card card-custom p-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المرفق / الصورة</th>
                            <th>اسم الطالب</th>
                            <th>البريد الإلكتروني</th>
                            <th>رقم الهاتف</th>
                            <th>الجنس</th>
                            <th>الحالة</th>
                            <th>التحكم والعمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td class="fw-bold">{{ $loop->iteration }}</td>
                                
                                <td>
                                    @if($student->image)
                                        @php
                                            $extension = pathinfo($student->image, PATHINFO_EXTENSION);
                                        @endphp

                                        @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']))
                                            <a href="{{ asset('storage/' . $student->image) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $student->image) }}" alt="صورة الطالب" class="avatar-img">
                                            </a>
                                        @elseif(strtolower($extension) === 'pdf')
                                            <a href="{{ asset('storage/' . $student->image) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                <i class="fa-solid fa-file-pdf me-1"></i> PDF
                                            </a>
                                        @else
                                            <a href="{{ asset('storage/' . $student->image) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                <i class="fa-solid fa-paperclip me-1"></i> عرض
                                            </a>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-muted border">لا يوجد</span>
                                    @endif
                                </td>

                                <td class="fw-bold text-dark">{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->phone ?? 'غير محدد' }}</td>
                                <td>
                                    @if(in_array(strtolower($student->gender ?? ''), ['female', 'f', 'أنثى']))
                                        <span class="badge bg-danger p-2"><i class="fa-solid fa-venus me-1"></i> أنثى</span>
                                    @else
                                        <span class="badge bg-primary p-2"><i class="fa-solid fa-mars me-1"></i> ذكر</span>
                                    @endif
                                </td>
                                <td>
                                    @if(($student->status ?? 'active') == 'active')
                                        <span class="badge bg-success p-2">نشط</span>
                                    @else
                                        <span class="badge bg-secondary p-2">غير نشط</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-outline-info" title="عرض">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-outline-warning" title="تعديل">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطالب؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">لا يوجد طلاب مسجلين حالياً.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- الترقيم الصفحي Pagination -->
            @if(method_exists($students, 'hasPages') && $students->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>