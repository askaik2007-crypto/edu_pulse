<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // 1. استدعاء الـ Log
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthController extends Controller
{
    // 1. تسجيل حساب جديد (Register)
    public function register(Request $request)
    {
        // المرحلة الأولى: استخدام try ... catch لمعالجة الأخطاء والتسجيل المباشر
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

            // المرحلة الأولى: عند النجاح سجل INFO
            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return response()->json([
                'message' => 'User registered successfully',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 201);

        } catch (ValidationException $e) {
            throw $e; // نترك خطأ التحقق للارفيل كالعادة
        } catch (Exception $e) {
            // المرحلة الأولى: عند الفشل سجل ERROR
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'user_id' => null
            ]);

            return response()->json(['message' => 'Server error'], 500);
        }
    }

    public function login(Request $request)
    {
    try {
        // 1. التحقق من المدخلات
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. تعيين وتعرف المتغير $user أولاً من قاعدة البيانات
        $user = User::where('email', $request->email)->first();

        // 3. التحقق من وجود الحساب وصحة كلمة السر
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['بيانات الدخول غير صحيحة.'],
            ]);
        return redirect()->route('dashboard');
        // 1. التحقق من مدخلات النموذج
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. البحث عن المستخدم في قاعدة البيانات
        $user = User::where('email', $request->email)->first();

        // 3. التحقق من صحة كلمة السر
        if (! $user || ! Hash::check($request->password, $user->password)) {
            Log::warning('Failed login attempt', [
                'email' => $request->email,
                'ip' => $request->ip()
            ]);

            throw ValidationException::withMessages([
                'email' => ['بيانات الدخول غير صحيحة.'],
            ]);
        }

        // 4. تسجيل الدخول وإنشاء الجلسة للمتصفح
        Auth::login($user);
        $request->session()->regenerate();

        // 5. تسجيل اللوج
        Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        // 6. التوجيه بناءً على رتبة المستخدم (بعد التأكد من وجود $user)
        if ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        if ($user->role === 'student') {
            return redirect()->route('student.dashboard');
        }

        return redirect()->route('dashboard');
    }
    // 4. تسجيل الدخول وإنشاء الجلسة
        Auth::login($user);
        $request->session()->regenerate();

        // 5. التوجيه بناءً على الرتبة (الآن $user معرف وجاهز)
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        if ($user->role === 'student') {
            return redirect()->route('student.dashboard');
        }

        return redirect()->route('dashboard');

    } catch (ValidationException $e) {
        throw $e;
    } catch (\Exception $e) {
        Log::error('Login process encountered an error', [
            'error' => $e->getMessage(),
            'user_id' => null
        ]);

        return back()->withErrors(['email' => 'حدث خطأ في الخادم، يرجى المحاولة لاحقاً.']);
    }
}

    // 3. تسجيل الخروج (Logout)
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        Log::info('User logged out', ['user_id' => $user->id]);

        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }

    // 4. جلب بيانات المستخدم الحالي (Profile)
    public function me(Request $request)
    {
        return response()->json($request->user(), 200);
    }
}