@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>User Details
                        <a href="{{ route('users.index') }}" class="btn btn-danger float-end">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Name:</strong> {{ $user->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> {{ $user->email }}
                    </div>
                    <div class="mb-3">
                        <strong>Role:</strong> {{ $user->role->value }}
                    </div>
                    @if(in_array($user->role->value, ['staff', 'teacher']))
                    <div class="mb-3">
                        <strong>{{ $user->role->value === 'staff' ? 'Position' : 'Subject' }}:</strong> {{ $user->position ?: 'Not specified' }}
                    </div>
                    @endif
                    <div class="mb-3">
                        <strong>Email Verified At:</strong> {{ $user->email_verified_at ? $user->email_verified_at->format('Y-m-d H:i:s') : 'Not Verified' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
