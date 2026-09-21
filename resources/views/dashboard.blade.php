<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - EduPulse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary-color: #4f46e5;
            --bg-color: #f8fafc;
        }
        body {
            background-color: var(--bg-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        /* Layout Structure */
        .wrapper { display: flex; width: 100%; align-items: stretch; }
        
        /* Sidebar Styling */
        #sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            background: #1e293b;
            color: #fff;
            transition: all 0.3s;
            min-height: 100vh;
            position: fixed;
            z-index: 1000;
        }
        #sidebar .sidebar-header { padding: 20px; background: #0f172a; border-bottom: 1px solid #334155; }
        #sidebar ul.components { padding: 15px 0; }
        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            color: #94a3b8;
            text-decoration: none;
            transition: 0.2s;
            border-right: 4px solid transparent;
        }
        #sidebar ul li a:hover, #sidebar ul li.active > a {
            color: #fff;
            background: #334155;
            border-right-color: var(--primary-color);
        }
        #sidebar ul li a i { width: 30px; font-size: 1.1rem; }

        /* Main Content Area */
        #content {
            width: 100%;
            margin-right: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* Top Navbar */
        .top-navbar {
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            padding: 15px 30px;
        }

        /* Cards Design */
        .card-custom {
            border: none;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        @media (max-width: 991.98px) {
            #sidebar { margin-right: calc(-1 * var(--sidebar-width)); }
            #sidebar.active { margin-right: 0; }
            #content { margin-right: 0; }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- 1. Sidebar القائمة الجانبية -->
    <nav id="sidebar">
        <div class="sidebar-header d-flex align-items-center gap-2">
            <i class="fa-solid fa-graduation-cap text-primary fs-3"></i>
            <h4 class="m-0 fw-bold text-white">EduPulse</h4>
        </div>

        <ul class="list-unstyled components">
            <li class="active">
                <a href="#"><i class="fa-solid fa-chart-pie"></i> لوحة التحكم</a>
            </li>
            <li>
                <a href="{{ route('students.index') }}"><i class="fa-solid fa-user-graduate"></i> إدارة الطلاب</a>
            </li>
            <li>
                <a href="{{ route('courses.index') }}"><i class="fa-solid fa-book-open"></i> الدورات التدريبية</a>
            </li>
            <li>
                <a href="{{ route('teachers.index') }}"><i class="fa-solid fa-chalkboard-user"></i> المحاضرين</a>
            </li>
            <li>
                <a href="{{ route('categories.index') }}"><i class="fa-solid fa-layer-group"></i> التصنيفات</a>
            </li>
            <li>
                <a href="{{ route('class-rooms.index') }}"><i class="fa-solid fa-door-open"></i> القاعات الدراسية</a>
            </li>
            <li>
                <a href="{{ route('course-classes.index') }}"><i class="fa-solid fa-chalkboard"></i> الشُعب الدراسية</a>
            </li>
            <li>
                <a href="{{ route('enrollments.index') }}"><i class="fa-solid fa-user-check"></i> التسجيلات</a>
            </li>
            <li>
                <a href="{{ route('attendances.index') }}"><i class="fa-solid fa-clipboard-user"></i> الحضور والغياب</a>
            </li>
            <li>
                <a href="{{ route('payments.index') }}"><i class="fa-solid fa-file-invoice-dollar"></i> المدفوعات والأقساط</a>
            </li>

            <!-- نموذج تسجيل الخروج القياسي مع حماية CSFR -->
            <li class="nav-item mt-3 px-2">
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="nav-link w-100 d-flex align-items-center border-0 bg-transparent text-danger py-2 px-3 rounded" style="transition: all 0.2s;">
                        <i class="fa-solid fa-right-from-bracket me-2 text-danger" style="width: 24px; text-align: center;"></i>
                        <span class="fw-semibold">تسجيل الخروج</span>
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <!-- 2. Content Area منطقة المحتوى الرئيسي -->
    <div id="content">
        <!-- Top Navbar -->
        <div class="top-navbar d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <button type="button" id="sidebarCollapse" class="btn btn-light d-lg-none">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h5 class="fw-bold m-0 text-dark fs-4">DashBoard</h5>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <!-- تاريخ اليوم -->
                <span class="badge bg-light text-dark border p-2 px-3 fw-semibold">
                    <i class="fa-regular fa-calendar me-1 text-primary"></i> {{ date('Y-m-d') }}
                </span>
            </div>
        </div>

        <!-- Main Body -->
        <div class="container-fluid px-4">
            
            <!-- Cards Grid: تقسيم الكروت -->
            <div class="row g-3 mb-4">
                <!-- الطلاب -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card card-custom p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted d-block small mb-1 fw-bold">إجمالي الطلاب</span>
                                <h3 class="fw-bold m-0 text-dark">{{ $studentsCount ?? (\App\Models\Student::count() ?? 0) }}</h3>
                            </div>
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="fa-solid fa-user-graduate"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الدورات -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card card-custom p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted d-block small mb-1 fw-bold">الدورات التدريبية</span>
                                <h3 class="fw-bold m-0 text-dark">{{ $coursesCount ?? (\App\Models\Course::count() ?? 0) }}</h3>
                            </div>
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- التحصيلات المالية -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card card-custom p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted d-block small mb-1 fw-bold">إجمالي التحصيلات</span>
                                <h3 class="fw-bold m-0 text-success">${{ number_format($totalPayments ?? (\App\Models\Payment::sum('amount') ?? 0), 2) }}</h3>
                            </div>
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- المحاضرين -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card card-custom p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted d-block small mb-1 fw-bold">المحاضرين</span>
                                <h3 class="fw-bold m-0 text-dark">{{ $teachersCount ?? (\App\Models\Teacher::count() ?? 0) }}</h3>
                            </div>
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="fa-solid fa-chalkboard-user"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- التسجيلات -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card card-custom p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted d-block small mb-1 fw-bold">تسجيلات الطلاب</span>
                                <h3 class="fw-bold m-0 text-dark">{{ $enrollmentsCount ?? (\App\Models\Enrollment::count() ?? 0) }}</h3>
                            </div>
                            <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                                <i class="fa-solid fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الشُعب -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card card-custom p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted d-block small mb-1 fw-bold">الشُعب الدراسية</span>
                                <h3 class="fw-bold m-0 text-dark">{{ $courseClassesCount ?? (\App\Models\CourseClass::count() ?? 0) }}</h3>
                            </div>
                            <div class="stat-icon bg-purple bg-opacity-10 text-purple" style="background: rgba(111,66,193,0.1); color: #6f42c1;">
                                <i class="fa-solid fa-chalkboard"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- القاعات -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card card-custom p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted d-block small mb-1 fw-bold">القاعات الدراسية</span>
                                <h3 class="fw-bold m-0 text-dark">{{ $classRoomsCount ?? (\App\Models\ClassRoom::count() ?? 0) }}</h3>
                            </div>
                            <div class="stat-icon bg-indigo bg-opacity-10 text-indigo" style="background: rgba(102,16,242,0.1); color: #6610f2;">
                                <i class="fa-solid fa-door-open"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الحضور -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card card-custom p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted d-block small mb-1 fw-bold">سجلات الحضور</span>
                                <h3 class="fw-bold m-0 text-dark">{{ $attendancesCount ?? (\App\Models\Attendance::count() ?? 0) }}</h3>
                            </div>
                            <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                                <i class="fa-solid fa-clipboard-user"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== القسم الجديد المتفاعل ===== -->
            <div class="row g-4 mb-4">
                
                <!-- 1. أحدث التسجيلات -->
                <div class="col-12 col-lg-8">
                    <div class="card card-custom p-4 h-100 border-0 shadow-sm" style="border-radius: 12px;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="fw-bold text-dark m-0">
                                <i class="fa-solid fa-clock-rotate-left text-primary me-2"></i>أحدث تسجيلات الطلاب
                            </h6>
                            <a href="{{ route('enrollments.index') }}" class="btn btn-sm btn-light border fw-semibold text-muted px-3" style="border-radius: 8px; transition: all 0.2s;">عرض الكل</a>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th class="border-0 py-3 rounded-start">الطالب</th>
                                        <th class="border-0 py-3">الدورة التدريبية</th>
                                        <th class="border-0 py-3">تاريخ التسجيل</th>
                                        <th class="border-0 py-3 rounded-end">الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $recentEnrollmentsList = $recentEnrollments ?? (\App\Models\Enrollment::with(['student', 'courseClass.course'])->latest()->take(5)->get());
                                    @endphp
                                    @forelse($recentEnrollmentsList as $enrollment)
                                        <tr>
                                            <td class="fw-semibold text-dark py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                        <i class="fa-solid fa-user-graduate" style="font-size: 0.85rem;"></i>
                                                    </div>
                                                    <span>{{ $enrollment->student->name ?? 'طالب غير محدد' }}</span>
                                                </div>
                                            </td>
                                            <td class="text-secondary">{{ $enrollment->courseClass->course->title ?? 'دورة غير محددة' }}</td>
                                            <td class="text-muted">{{ $enrollment->created_at ? $enrollment->created_at->format('Y-m-d') : '-' }}</td>
                                            <td>
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-pill">مكتمل</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fa-solid fa-folder-open fa-2x mb-2 text-black-50 d-block"></i>
                                                لا توجد تسجيلات حديثة حتى الآن
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 2. الإجراءات السريعة والمؤشرات -->
                <div class="col-12 col-lg-4">
                    <div class="card card-custom p-4 h-100 d-flex flex-column justify-content-between border-0 shadow-sm" style="border-radius: 12px;">
                        <div>
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="fa-solid fa-bolt text-warning me-2"></i>إجراءات سريعة
                            </h6>
                            <div class="d-flex flex-column gap-2 mb-4">
                                <a href="{{ route('students.create') }}" class="action-btn d-flex align-items-center p-2 rounded text-decoration-none text-dark bg-light bg-opacity-50" style="transition: all 0.2s;">
                                    <div class="stat-icon bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 38px; height: 38px; font-size: 1rem;">
                                        <i class="fa-solid fa-user-plus"></i>
                                    </div>
                                    <span class="fw-bold small">تسجيل طالب جديد</span>
                                </a>

                                <a href="{{ route('enrollments.create') }}" class="action-btn d-flex align-items-center p-2 rounded text-decoration-none text-dark bg-light bg-opacity-50" style="transition: all 0.2s;">
                                    <div class="stat-icon bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 38px; height: 38px; font-size: 1rem;">
                                        <i class="fa-solid fa-file-signature"></i>
                                    </div>
                                    <span class="fw-bold small">إضافة تسجيل لشُعبة</span>
                                </a>

                                <a href="{{ route('payments.create') }}" class="action-btn d-flex align-items-center p-2 rounded text-decoration-none text-dark bg-light bg-opacity-50" style="transition: all 0.2s;">
                                    <div class="stat-icon bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 38px; height: 38px; font-size: 1rem;">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>
                                    <span class="fw-bold small">إصدار سند قبض / دفع</span>
                                </a>
                            </div>
                        </div>

                        <!-- مؤشرات الإشغال الأكاديمي -->
                        @php
                            $cCount = $courseClassesCount ?? (class_exists(\App\Models\CourseClass::class) ? \App\Models\CourseClass::count() : 0);
                            $eCount = $enrollmentsCount ?? (class_exists(\App\Models\Enrollment::class) ? \App\Models\Enrollment::count() : 0);
                            $rCount = $classRoomsCount ?? (class_exists(\App\Models\ClassRoom::class) ? \App\Models\ClassRoom::count() : 0);
                            
                            $classesProgress = $cCount > 0 ? min(round(($eCount / ($cCount * 10)) * 100), 100) : 0;
                            $roomsProgress = $rCount > 0 ? min(round(($cCount / $rCount) * 100), 100) : 0;
                        @endphp

                        <div class="border-top pt-3">
                            <span class="text-muted d-block small mb-2 fw-bold">مؤشر الإشغال الأكاديمي (بيانات حية)</span>
                            
                            <!-- مؤشر الشعب -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>نشاط التسجيل في الشُعب</span>
                                    <span class="fw-bold text-dark">{{ $classesProgress }}%</span>
                                </div>
                                <div class="progress rounded-pill overflow-hidden" style="height: 6px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $classesProgress }}%" aria-valuenow="{{ $classesProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <!-- مؤشر القاعات -->
                            <div>
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span>استغلال القاعات الدراسية</span>
                                    <span class="fw-bold text-dark">{{ $roomsProgress }}%</span>
                                </div>
                                <div class="progress rounded-pill overflow-hidden" style="height: 6px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $roomsProgress }}%" aria-valuenow="{{ $roomsProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sidebarCollapse').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('active');
    });
</script>
</body>
</html>