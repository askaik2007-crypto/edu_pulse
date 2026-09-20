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
    public function store(LoginRequest $request): RedirectResponse
    {
            try {
            // 1. محاولة تسجيل الدخول
            $request->authenticate();

            $request->session()->regenerate();

            // المرحلة الأولى: تسجيل نجاح العملية (INFO)
            Log::info('User logged in successfully', [
                'user_id' => auth()->id(),
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);

            return redirect()->intended(RouteServiceProvider::HOME);

            } catch (Throwable $e) {
                // المرحلة الثانية: تسجيل محاولة دخول خاطئة أو أمنية (WARNING)
                Log::warning('User failed login attempt', [
                    'email' => $request->email,
                    'ip' => $request->ip(),
                    'error_message' => $e->getMessage(),
            ]);

            // إرجاع الخطأ للمستخدم كالمعتاد
            throw $e;
        }

        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // التوجيه حسب الـ role
        if ($user->role === 'teacher') {
            return redirect()->intended(route('teacher.dashboard'));
        }

        if ($user->role === 'student') {
            return redirect()->intended(route('student.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
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
