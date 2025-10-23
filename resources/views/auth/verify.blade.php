@extends('layouts.app')

@section('title', 'Lyline | Email Verification')

@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #fff3cd 0%, #ffffff 100%);">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <!-- Left Side - Illustration -->
            <div class="col-lg-6 d-none d-lg-block text-center">
                <div class="mb-4">
                    <i class="bi bi-envelope-check text-warning" style="font-size: 6rem;"></i>
                </div>
                <h2 class="text-warning fw-bold mb-3">Almost There!</h2>
                <p class="text-muted fs-5">Just one more step to activate your Lyline account.</p>
                <div class="mt-4">
                    <img src="https://via.placeholder.com/400x300/f39c12/ffffff?text=Check+Your+Email" alt="Email Verification" class="img-fluid rounded shadow" style="max-width: 80%;">
                </div>
            </div>

            <!-- Right Side - Verification Form -->
            <div class="col-lg-5 col-md-8">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body p-5 text-center">
                        <!-- Logo -->
                        <div class="mb-4">
                            <h3 class="text-primary fw-bold">Lyline</h3>
                        </div>

                        <!-- Success Alert -->
                        @if (session('resent'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                                <strong>A fresh verification link has been sent to your email address.</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Main Content -->
                        <div class="mb-4">
                            <i class="bi bi-envelope-paper text-primary" style="font-size: 3rem;"></i>
                        </div>

                        <h4 class="mb-3">Check Your Email</h4>

                        @if (session('resent'))
                            <p class="text-muted mb-4">We have sent a verification link to your email.</p>
                        @else
                            <p class="text-muted mb-4">
                                We have sent a verification link to <strong>{{ auth()->user()->email ?? 'your email' }}</strong>.
                                Please check your inbox and click the link to activate your Lyline account.
                            </p>
                        @endif

                        <!-- Personal Greeting -->
                        @if(auth()->check())
                            <div class="alert alert-info border-0" style="background: rgba(0, 123, 255, 0.1);">
                                <strong>Hey, {{ auth()->user()->name }}!</strong> Just one more step to start your reading journey.
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-grid gap-3 mb-4">
                            <form method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-lg fw-semibold" style="background: linear-gradient(45deg, #ffc107, #fd7e14); border: none;">
                                    <i class="bi bi-arrow-repeat me-2"></i>Resend Verification Email
                                </button>
                            </form>

                            <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg fw-semibold">
                                <i class="bi bi-house-door me-2"></i>Return to Home Page
                            </a>
                        </div>

                        <!-- Help Text -->
                        <div class="text-muted small">
                            <p class="mb-1">Didn't receive the email? Check your spam folder.</p>
                            <p class="mb-0">Still having issues? <a href="mailto:support@lyline.com" class="text-decoration-none">Contact Support</a></p>
                        </div>
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
.btn-warning:hover {
    background: linear-gradient(45deg, #fd7e14, #dc3545) !important;
    transform: translateY(-1px);
}
.btn-outline-primary:hover {
    transform: translateY(-1px);
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
</script>
@endpush
@endsection
