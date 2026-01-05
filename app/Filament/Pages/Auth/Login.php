<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Http\Responses\Auth\LoginResponse;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    /**
     * (Opsional) Kalau mau pakai Blade kustom:
     * - uncomment baris di bawah
     * - buat file resources/views/auth/custom-login.blade.php
     */
    // protected static string $view = 'auth.custom-login';

    /** Maksimal percobaan login dalam satu jendela waktu */
    protected int $maxAttempts = 3;

    /** Lama blokir (detik) ketika limit tercapai */
    protected int $decaySeconds = 120;

    /**
     * Skema form (email, password, remember)
     */
    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('email')
                ->label('Email address')
                ->placeholder('you@example.com')
                ->email()
                ->required()
                ->autofocus()
                ->validationMessages([
                    'required' => 'Email wajib diisi.',
                    'email'    => 'Format email tidak valid.',
                ]),

            Forms\Components\TextInput::make('password')
                ->label('Password')
                ->password()
                ->revealable()
                ->required()
                ->validationMessages([
                    'required' => 'Kata sandi wajib diisi.',
                ]),

            Forms\Components\Checkbox::make('remember')
                ->label('Ingat saya'),
        ])->columns(1);
    }

    /**
     * Jalankan rate limit sebelum proses login bawaan.
     * Hapus hit kalau sukses login.
     */
    public function authenticate(): ?LoginResponse
    {
        $this->enforceRateLimit();

        $response = parent::authenticate();

        // Berhasil login -> reset hit limiter
        RateLimiter::clear($this->throttleKey());

        return $response;
    }

    /**
     * Rate limit guard (lempar ValidationException dengan pesan kustom).
     */
    protected function enforceRateLimit(): void
    {
        $key = $this->throttleKey();

        if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);

            throw ValidationException::withMessages([
                // tampilkan tepat di bawah field email (state path: data.email)
                'data.email' => $this->rateLimitedMessage($seconds),
            ]);
        }

        // Hit setiap attempt, expired sesuai decaySeconds
        RateLimiter::hit($key, $this->decaySeconds);
    }

    /**
     * Kunci throttle gabungan email (lower) + IP.
     */
    protected function throttleKey(): string
    {
        $email = (string) str($this->form->getState()['email'] ?? 'guest')->lower();
        $ip    = request()->ip();

        return 'filament_login:' . $email . '|' . $ip;
    }

    /**
     * Pesan saat kena limit (Indonesia).
     */
    protected function rateLimitedMessage(int $seconds): string
    {
        return __('Terlalu banyak percobaan login. Coba lagi dalam :seconds detik.', [
            'seconds' => $seconds,
        ]);
    }

    /**
     * Pesan saat kredensial salah (muncul di bawah email).
     */
    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.email' => 'Email atau password salah.',
        ]);
    }
}
