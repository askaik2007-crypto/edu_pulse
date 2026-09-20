<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthController extends Controller
{
    // 1. تسجيل حساب جديد (Register)
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            // تسجيل السجل بالعربي عند النجاح (INFO)
            Log::info('تم تسجيل حساب جديد بنجاح', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 201);

        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            // تسجيل السجل بالعربي عند الخطأ (ERROR)
            Log::error('فشل عملية تسجيل حساب جديد', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);

            return response()->json(['message' => 'Server error'], 500);
        }
    }

    // 2. تسجيل الدخول (Login)
    public function login(Request $request)
    {
        try {
            // 1. التحقق من مدخلات النموذج
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // 2. البحث عن المستخدم في قاعدة البيانات
            $user = User::where('email', $request->email)->first();

            // 3. التحقق من وجود الحساب وصحة كلمة السر
            if (! $user || ! Hash::check($request->password, $user->password)) {
                // تسجيل تنبيه أمني محلي وعلى Render عند خطأ الدخول (WARNING)
                Log::warning('محاولة تسجيل دخول فاشلة', [
                    'email' => $request->email,
                    'ip' => $request->ip()
                ]);

                throw ValidationException::withMessages([
                    'email' => ['بيانات الدخول غير صحيحة.'],
                ]);
            }

            // 4. تسجيل الدخول وإنشاء الجلسة
            Auth::login($user);
            $request->session()->regenerate();

            // 5. تسجيل اللوج بالعربي عند النجاح (INFO)
            Log::info('تم تسجيل دخول المستخدم بنجاح', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role ?? 'N/A',
                'ip' => $request->ip()
            ]);

            // 6. التوجيه بناءً على رتبة المستخدم (Role)
            $userRole = strtolower(trim($user->role ?? ''));

            if ($userRole === 'admin') {
                return redirect()->to('/admin/dashboard');
            }

            if ($userRole === 'teacher') {
                return redirect()->to('/teacher/dashboard');
            }

            if ($userRole === 'student') {
                return redirect()->to('/student/dashboard');
            }

            return redirect()->to('/dashboard');

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            // تسجيل خطأ النظام أثناء الدخول (ERROR)
            Log::error('حدث خطأ غير متوقع في نظام تسجيل الدخول', [
                'error' => $e->getMessage(),
                'ip' => $request->ip()
            ]);

            return back()->withErrors(['email' => 'حدث خطأ في الخادم، يرجى المحاولة لاحقاً.']);
        }
    }

    // 3. تسجيل الخروج (Logout)
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user && method_exists($user, 'currentAccessToken') && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // تسجيل سجل الخروج بالعربي (INFO)
        Log::info('تم تسجيل خروج المستخدم بنجاح', [
            'user_id' => $user->id ?? null,
            'ip' => $request->ip()
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Logged out successfully'], 200);
        }

        return redirect('/login');
    }

    // 4. جلب بيانات المستخدم الحالي (Profile)
    public function me(Request $request)
    {
        return response()->json($request->user(), 200);
    }
}