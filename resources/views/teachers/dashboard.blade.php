<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم المحاضر - EduPulse</title>
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
    <!-- 1. Sidebar القائمة الجانبية الخاصة بالمحاضر -->
    <nav id="sidebar">
        <div class="sidebar-header d-flex align-items-center gap-2">
            <i class="fa-solid fa-chalkboard-user text-primary fs-3"></i>
            <div>
                <h5 class="m-0 fw-bold text-white">بوابة المحاضر</h5>
                <small class="text-muted" style="font-size: 0.75rem;">EduPulse Portal</small>
            </div>
        </div>

        <ul class="list-unstyled components">
            <li class="active">
                <a href="{{ route('teacher.dashboard') }}"><i class="fa-solid fa-chart-pie"></i> لوحة التحكم</a>
            </li>
            
            <!-- زر تسجيل الخروج -->
            <li class="nav-item mt-4 px-2">
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
                <h5 class="fw-bold m-0 text-dark">مرحباً بك، {{ $teacher->name }}</h5>
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
            
            <!-- بطاقات معلومات المحاضر -->
            <div class="row g-3 mb-4">
                <!-- التخصص -->
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card card-custom p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted d-block small mb-1 fw-bold">التخصص الأكاديمي</span>
                                <h5 class="fw-bold m-0 text-dark">{{ $teacher->specialization }}</h5>
                            </div>
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                <i class="fa-solid fa-graduation-cap"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- البريد الإلكتروني -->
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card card-custom p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted d-block small mb-1 fw-bold">البريد الإلكتروني</span>
                                <h6 class="fw-bold m-0 text-dark text-truncate" style="max-width: 200px;">{{ $teacher->email }}</h6>
                            </div>
                            <div class="stat-icon bg-success bg-opacity-10 text-success">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- رقم الهاتف -->
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card card-custom p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted d-block small mb-1 fw-bold">رقم الهاتف</span>
                                <h5 class="fw-bold m-0 text-dark">{{ $teacher->phone }}</h5>
                            </div>
                            <div class="stat-icon bg-info bg-opacity-10 text-info">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- قسم الترحيب والمحتوى -->
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="card card-custom p-5 text-center border-0 shadow-sm" style="border-radius: 12px;">
                        <div class="py-4">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px; font-size: 1.8rem;">
                                <i class="fa-solid fa-chalkboard-user"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">أهلاً بك في لوحة تحكم المحاضرين</h4>
                            <p class="text-muted mx-auto" style="max-width: 500px; font-size: 0.95rem;">
                                تم تخصيص هذه البوابة لعرض بياناتك الشخصية وإدارتها بكل أمان بمعزل عن لوحة تحكم النظام العامة.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sidebarCollapse').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('active');
    });
</script>
</body>
</html>