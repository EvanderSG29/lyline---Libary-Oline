@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5 my-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Welcome to Lyline</h1>
                <p class="lead mb-4">A digital library that opens a window to your knowledge anytime, anywhere.</p>
                <div class="d-flex gap-3">
                    
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Login now
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <i class="bi bi-book-half display-1 text-white-50"></i>
            </div>
        </div>
    </div>
</section>

{{-- <!-- About Lyline Section -->
<section class="py-5 my-5 bg-light">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold text-primary mb-3">About Lyline</h2>
                <p class="lead text-muted">Lyline is dedicated to improving digital literacy among Islamic boarding school students through accessible digital resources and modern library management.</p>
            </div>
        </div>
        
    </div>
</section>

<!-- Features Section -->
<section class="py-5 my-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="display-5 fw-bold text-primary mb-3">Lyline Features</h2>
                <p class="lead text-muted">Discover the modern features that make learning accessible and enjoyable.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-search text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Easy Book Search</h5>
                        <p class="card-text text-muted">Find books instantly with our advanced search and filtering system.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-bookshelf text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Complete Collection</h5>
                        <p class="card-text text-muted">Access thousands of digital books across various categories and subjects.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-calendar-check text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Quick Booking</h5>
                        <p class="card-text text-muted">Reserve books instantly and manage your borrowing schedule effortlessly.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div class="feature-icon mb-3">
                            <i class="bi bi-bar-chart-line text-primary" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="card-title">Smart Dashboard</h5>
                        <p class="card-text text-muted">Track your reading progress and library statistics with detailed analytics.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quote Section -->
<section class="py-5 my-5 bg-primary text-white">
    <div class="container text-center">
        <blockquote class="blockquote">
            <p class="display-6 mb-3">"Reading is a bridge to a world without limits."</p>
            <footer class="blockquote-footer text-white-50">Lyline Digital Library</footer>
        </blockquote>
    </div>
</section> --}}
@endsection
