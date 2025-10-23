@extends('layouts.app')

@section('title', 'Lyline | Sign In')

@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #e3f2fd 0%, #ffffff 100%);">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <!-- Left Side -->
            <div class="col-lg-5 col-md-8 mb-5 mb-lg-0 ">
                <h2 class="text-primary fw-bold mb-3">Welcome Back to Lyline</h2>
                <p class="text-muted fs-5 mb-4">Enjoy the convenience of reading and borrowing digital books anytime.</p>              
            </div>

            <!-- Right Side - Login Form -->
            <div class="col-lg-5 col-md-8">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body p-5">
                        <!-- Logo -->
                        <div class="text-center mb-4">
                            <h3 class="text-primary fw-bold">Lyline</h3>
                            <p class="text-muted">Log in to your account</p>
                        </div>

                        <!-- Error Alert -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                                <strong>Please check the form below for errors.</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" novalidate>
                            @csrf

                            <!-- Email Field -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                       placeholder="Enter your email">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password Field -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                                       name="password" required autocomplete="current-password"
                                       placeholder="Enter your password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Remember Me & Forgot Password -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none" href="{{ route('password.request') }}">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                @endif
                            </div>

                            <!-- Login Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg fw-semibold" style="background: linear-gradient(45deg, #007bff, #0056b3); border: none;">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>{{ __('Log in Now') }}
                                </button>
                            </div>

                            <!-- Register Link -->
                            <div class="text-center mt-4">
                                <span class="text-muted">Don't have an account?</span>
                                <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-semibold ms-1">
                                    Register now
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}
.btn-primary:hover {
    background: linear-gradient(45deg, #0056b3, #004085) !important;
    transform: translateY(-1px);
}
.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
.dynamic-text-container {
    background-color: #f0f4f8;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: inset 0 2px 8px rgba(0,0,0,0.06);
}
.dynamic-text-wrapper {
    font-family: 'Courier New', Courier, monospace;
    font-size: 1.75rem;
    font-weight: bold;
}
.cursor {
    display: inline-block;
    background-color: #007bff;
    animation: blink 0.7s infinite;
    width: 4px;
}
@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0; }
}
</style>

@push('scripts')
<script>
    // Auto-hide error alert after 8 seconds
    setTimeout(function() {
        const alert = document.getElementById('error-alert');
        if (alert) {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }
    }, 8000);

    // Typewriter Effect
    document.addEventListener('DOMContentLoaded', function() {
        const textElement = document.getElementById('typewriter-text');
        const words = [
            "New Adventures.",
            "Endless Knowledge.",
            "Favorite Authors.",
            "Digital Books."
        ];
        let wordIndex = 0;
        let charIndex = 0;
        let isDeleting = false;

        function type() {
            const currentWord = words[wordIndex];
            const speed = isDeleting ? 100 : 150;

            if (isDeleting) {
                // Hapus karakter
                textElement.textContent = currentWord.substring(0, charIndex - 1);
                charIndex--;
            } else {
                // Tambah karakter
                textElement.textContent = currentWord.substring(0, charIndex + 1);
                charIndex++;
            }

            if (!isDeleting && charIndex === currentWord.length) {
                // Selesai mengetik, tunggu, lalu mulai hapus
                setTimeout(() => isDeleting = true, 2000);
            } else if (isDeleting && charIndex === 0) {
                // Selesai menghapus, ganti kata
                isDeleting = false;
                wordIndex = (wordIndex + 1) % words.length;
            }

            setTimeout(type, speed);
        }
        type();
    });
</script>
@endpush
@endsection
