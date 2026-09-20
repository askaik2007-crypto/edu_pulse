<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    /**
 * Handle an incoming authentication request.
 */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            // 1. التوثيق وتوليد الجلسة
            $request->authenticate();
            $request->session()->regenerate();

            $user = $request->user();

            // 2. كتابة الـ Log مباشرة عند نجاح العملية بالعربي
            Log::info('تم تسجيل دخول المستخدم بنجاح', [
                'user_id' => $user->id,
                'email'   => $user->email,
                'role'    => $user->role ?? 'N/A',
                'ip'      => $request->ip(),
            ]);

            // 3. التنظيف والتوجيه المباشر والدقيق حسب الـ role (مطابق لأسماء الـ Routes في web.php)
            $role = strtolower(trim($user->role ?? ''));

            if ($role === 'teacher') {
                return redirect()->route('teacher.dashboard');
            }

            if ($role === 'student') {
                return redirect()->route('student.dashboard');
            }

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('dashboard');

        } catch (Throwable $e) {
            // تسجيل التنبيه عند فشل الدخول
            Log::warning('محاولة تسجيل دخول فاشلة', [
                'email'         => $request->email,
                'ip'            => $request->ip(),
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
    protected function authenticated(Request $request, $user)
    {
        // توجيه الأدمن
        if (in_array($user->role, ['admin', 'super_admin'])) {
            return redirect()->route('dashboard');
        }

        // توجيه المحاضر
        if ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        // توجيه الطالب
        if ($user->role === 'student') {
            return redirect()->route('student.dashboard');
        }

        return redirect('/login');
    }
}
