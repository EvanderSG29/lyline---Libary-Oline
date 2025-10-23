@extends('layouts.app')

@section('title', 'Lyline | Sign Up')

@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #e3f2fd 0%, #ffffff 100%);">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <!-- Left Side - Illustration -->
            <div class="col-lg-6 d-none d-lg-block text-center">
                <div class="mb-4">
                    <i class="bi bi-book-open text-success" style="font-size: 6rem;"></i>
                </div>
                <h2 class="text-success fw-bold mb-3">Join the Lyline Community</h2>
                <p class="text-muted fs-5">Start your reading journey with our digital library collection.</p>
                <div class="mt-4">
                    <img src="https://via.placeholder.com/400x300/28a745/ffffff?text=New+Reader" alt="New Reader Journey" class="img-fluid rounded shadow" style="max-width: 80%;">
                </div>
            </div>

            <!-- Right Side - Register Form -->
            <div class="col-lg-5 col-md-8">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body p-5">
                        <!-- Logo -->
                        <div class="text-center mb-4">
                            <h3 class="text-primary fw-bold">Lyline</h3>
                            <p class="text-muted">Create your account</p>
                        </div>

                        <!-- Success Alert -->
                        @if (session('resent'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                                <strong>A fresh verification link has been sent to your email address.</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Error Alert -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="error-alert">
                                <strong>Please check the form below for errors.</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" novalidate>
                            @csrf

                            <!-- Name Field -->
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold">{{ __('Full Name') }}</label>
                                <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                       name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                                       placeholder="Enter your full name">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" required autocomplete="email"
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
                                       name="password" required autocomplete="new-password"
                                       placeholder="Create a password (min. 8 characters)">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="mb-4">
                                <label for="password-confirm" class="form-label fw-semibold">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                                       name="password_confirmation" required autocomplete="new-password"
                                       placeholder="Confirm your password">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Register Button -->
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-success btn-lg fw-semibold" style="background: linear-gradient(45deg, #28a745, #20c997); border: none;">
                                    <i class="bi bi-person-plus me-2"></i>{{ __('Register Now') }}
                                </button>
                            </div>

                            <!-- Login Link -->
                            <div class="text-center">
                                <span class="text-muted">Already have an account?</span>
                                <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold ms-1">
                                    Log in here
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
.btn-success:hover {
    background: linear-gradient(45deg, #20c997, #17a2b8) !important;
    transform: translateY(-1px);
}
.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}
</style>

@push('scripts')
<script>
    // Auto-hide success alert after 8 seconds
    setTimeout(function() {
        const alert = document.getElementById('success-alert');
        if (alert) {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }
    }, 8000);

    // Auto-hide error alert after 8 seconds
    setTimeout(function() {
        const alert = document.getElementById('error-alert');
        if (alert) {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 150);
        }
    }, 8000);

document.addEventListener('DOMContentLoaded', function() {
    // Real-time password validation
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password-confirm');

    function validatePasswords() {
        if (password.value && confirmPassword.value) {
            if (password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity("Passwords don't match");
            } else {
                confirmPassword.setCustomValidity('');
            }
        }
    }

    password.addEventListener('input', validatePasswords);
    confirmPassword.addEventListener('input', validatePasswords);
});
</script>
@endpush
@endsection
