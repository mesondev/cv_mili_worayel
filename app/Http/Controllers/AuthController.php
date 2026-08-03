<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function loginForm(): View
    {
        $captchaQuestion = null;
        if (session('login_captcha_required')) {
            $captchaQuestion = session('login_captcha_question');
            if (! $captchaQuestion) {
                $left = random_int(2, 9);
                $right = random_int(1, 9);
                session(['login_captcha_question' => "$left + $right", 'login_captcha_answer' => (string) ($left + $right)]);
                $captchaQuestion = session('login_captcha_question');
            }
        }

        return view('auth.login', compact('captchaQuestion'));
    }

    public function registerForm(): View
    {
        return view('auth.register');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'login' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'max:255'],
            'remember' => ['nullable', 'boolean'],
        ]);
        $login = trim($data['login']);
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $attemptKey = 'dashboard-login:'.sha1(strtolower($login).'|'.$request->ip());

        if (RateLimiter::tooManyAttempts($attemptKey, 5)) {
            return back()->withErrors(['login' => 'Terlalu banyak percobaan login. Silakan tunggu '.ceil(RateLimiter::availableIn($attemptKey) / 60).' menit sebelum mencoba kembali.'])->onlyInput('login');
        }

        if (session('login_captcha_required') && ! hash_equals((string) session('login_captcha_answer'), trim((string) $request->input('captcha_answer')))) {
            return back()->withErrors(['captcha_answer' => 'Verifikasi keamanan belum sesuai.'])->onlyInput('login');
        }

        // Selalu lepaskan sesi sebelumnya agar form Login menjadi autentikasi ulang,
        // bukan jalan pintas ke dashboard memakai sesi lama.
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        $user = User::where($field, $login)->first();
        if (! $user) {
            return $this->failedLogin($request, $attemptKey, 'Email atau password yang Anda masukkan tidak sesuai. Silakan coba kembali.');
        }
        if (! Hash::check($data['password'], $user->password)) {
            return $this->failedLogin($request, $attemptKey, 'Email atau password yang Anda masukkan tidak sesuai. Silakan coba kembali.');
        }
        if ($user->account_status !== 'active') {
            return back()->withErrors(['login' => 'Akun tidak aktif atau sedang dibatasi. Hubungi administrator.'])->onlyInput('login');
        }
        if (! $user->hasVerifiedEmail()) {
            return back()->withErrors(['login' => 'Akun belum aktif. Periksa email Anda untuk melakukan verifikasi.'])->onlyInput('login');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        $request->session()->put('password_changed_at', optional($user->password_changed_at)->timestamp);
        RateLimiter::clear($attemptKey);
        $request->session()->forget(['login_captcha_required', 'login_captcha_question', 'login_captcha_answer']);

        return redirect()->intended(route('dashboard'))->with('status', 'Login berhasil. Selamat datang kembali.');
    }

    private function failedLogin(Request $request, string $attemptKey, string $message): RedirectResponse
    {
        RateLimiter::hit($attemptKey, 600);
        $attempts = RateLimiter::attempts($attemptKey);
        if ($attempts >= 5) {
            $message = 'Terlalu banyak percobaan login. Silakan tunggu 10 menit sebelum mencoba kembali.';
        } elseif ($attempts >= 3) {
            session(['login_captcha_required' => true]);
            $message = 'Terlalu banyak percobaan login yang gagal. Selesaikan verifikasi keamanan sebelum mencoba kembali.';
        } elseif ($attempts === 2) {
            $message = 'Email atau password masih salah. Silakan periksa kembali data login Anda.';
        } else {
            $message = 'Email atau password salah. Percobaan login gagal.';
        }

        return back()->withErrors(['login' => $message])->onlyInput('login');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'], 'username' => ['required', 'alpha_dash', 'min:3', 'max:30', 'unique:users'], 'email' => ['required', 'email', 'unique:users'],
            'phone' => ['required', 'string', 'max:25'], 'address' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'max:2048'], 'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()], 'terms' => ['accepted'],
        ]);
        $member = Role::firstOrCreate(['slug' => 'member'], ['name' => 'Member', 'description' => 'Pengguna terdaftar']);
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }
        unset($data['terms']);
        $user = User::create([...$data, 'account_status' => 'active', 'password' => Hash::make($data['password']), 'password_changed_at' => now(), 'role_id' => $member->id]);
        event(new Registered($user));
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('verification.notice')->with('status', 'Akun berhasil dibuat. Kami mengirim tautan verifikasi ke email Anda.');
    }

    public function forgotPasswordForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);
        $status = PasswordBroker::sendResetLink($request->only('email'));

        return back()->with($status === PasswordBroker::RESET_LINK_SENT ? 'status' : 'error', __($status));
    }

    public function resetPasswordForm(string $token): View
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $data = $request->validate(['token' => ['required'], 'email' => ['required', 'email'], 'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()]]);
        $status = PasswordBroker::reset($data, function (User $user, string $password) {
            $user->forceFill(['password' => Hash::make($password), 'password_changed_at' => now(), 'remember_token' => Str::random(60)])->save();
        });

        return $status === PasswordBroker::PASSWORD_RESET ? redirect()->route('login')->with('status', 'Password berhasil diubah. Silakan masuk.') : back()->withErrors(['email' => __($status)]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
