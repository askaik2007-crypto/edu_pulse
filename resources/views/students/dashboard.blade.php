<x-app-layout>
    <div class="container-fluid py-4 px-4 bg-light" dir="rtl" style="min-height: 90vh;">
        
        <!-- 1. الهيدر والترحيب (تدرج أزرق داكن) -->
        <div class="card border-0 rounded-4 shadow-sm mb-4 text-white p-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1">مرحباً بك، {{ $student->name }} 👋</h2>
                    <p class="mb-0 text-white-50 fs-6">مرحباً بك في لوحتك الأكاديمية الخاصة بمركز EduPulse</p>
                </div>
                <div>
                    <span class="badge bg-primary text-white fs-6 px-3 py-2 rounded-pill shadow-sm">
                        <i class="fa-solid fa-user-graduate me-1"></i> حساب طالب
                    </span>
                </div>
            </div>
        </div>

        <!-- 2. كروت الإحصائيات (أقسام منفصلة بألوان مميزة) -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 border-start border-4 border-primary shadow-sm rounded-3 p-3 bg-white h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small fw-bold d-block mb-1">الدورات المسجل بها</span>
                            <h3 class="fw-bold mb-0 text-primary">{{ isset($student->registrations) ? $student->registrations->count() : 0 }}</h3>
                        </div>
                        <div class="rounded-3 bg-primary bg-opacity-10 p-3 text-primary">
                            <i class="fa-solid fa-graduation-cap fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 border-start border-4 border-success shadow-sm rounded-3 p-3 bg-white h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small fw-bold d-block mb-1">نسبة الحضور</span>
                            <h3 class="fw-bold mb-0 text-success">100%</h3>
                        </div>
                        <div class="rounded-3 bg-success bg-opacity-10 p-3 text-success">
                            <i class="fa-solid fa-user-check fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 border-start border-4 border-warning shadow-sm rounded-3 p-3 bg-white h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-secondary small fw-bold d-block mb-1">حالة الحساب</span>
                            <h3 class="fw-bold mb-0 text-warning fs-5">نشط أكاديمياً</h3>
                        </div>
                        <div class="rounded-3 bg-warning bg-opacity-10 p-3 text-warning">
                            <i class="fa-solid fa-circle-check fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. المحتوى الرئيسي (قسم جدول الدورات وقسم البروفايل) -->
        <div class="row g-4">
            
            <!-- قسم جدول الدورات -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden h-100">
                    <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center border-0">
                        <h5 class="fw-bold mb-0">
                            <i class="fa-solid fa-book-open me-2"></i>الدورات والمسارات الدراسية
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">اسم الدورة</th>
                                        <th>تاريخ الانضمام</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($student->registrations) && $student->registrations->count() > 0)
                                        @foreach($student->registrations as $registration)
                                            <tr>
                                                <td class="ps-4 fw-bold text-dark">
                                                    <div class="d-flex align-items-center">
                                                        <span class="p-2 me-2 rounded bg-primary bg-opacity-10 text-primary">
                                                            <i class="fa-solid fa-laptop-code"></i>
                                                        </span>
                                                        {{ $registration->courseClass->course->name ?? 'دورة تدريبية' }}
                                                    </div>
                                                </td>
                                                <td class="text-muted">{{ $registration->created_at ? $registration->created_at->format('Y-m-d') : '-' }}</td>
                                                <td>
                                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill border border-success">
                                                        <i class="fa-solid fa-check me-1"></i> مسجل
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="text-center py-5 text-muted">
                                                <div class="mb-2">
                                                    <i class="fa-solid fa-folder-open text-secondary fs-1"></i>
                                                </div>
                                                <span class="fw-semibold">لا توجد دورات مسجلة باسمك حالياً.</span>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- قسم البروفايل الشخصي -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden h-100">
                    <div class="card-header bg-dark text-white py-3 border-0">
                        <h5 class="fw-bold mb-0">
                            <i class="fa-solid fa-id-card text-warning me-2"></i>البروفايل الشخصي
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-user-graduate fs-1"></i>
                            </div>
                            <h4 class="fw-bold mb-1 text-dark">{{ $student->name }}</h4>
                            <span class="badge bg-info text-dark px-3 py-1 rounded-pill">طالب في المركز</span>
                        </div>

                        <!-- المربعات الجانبية المنفصلة -->
                        <div class="bg-light p-3 rounded-3 mb-3 border">
                            <div class="d-flex align-items-center mb-1">
                                <i class="fa-solid fa-envelope text-primary me-2"></i>
                                <span class="text-muted small">البريد الإلكتروني:</span>
                            </div>
                            <div class="fw-bold text-dark text-break me-4">{{ $student->email }}</div>
                        </div>

                        <div class="bg-light p-3 rounded-3 border">
                            <div class="d-flex align-items-center mb-1">
                                <i class="fa-solid fa-user-shield text-success me-2"></i>
                                <span class="text-muted small">نوع الحساب:</span>
                            </div>
                            <div class="fw-bold text-dark me-4">Student Account</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>