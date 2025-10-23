@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5>{{ __('Patron Details') }}</h5>
                        <a href="{{ route('databorrows.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Name Borrower</th>
                            <td>{{ $databorrow->name_borrower }}</td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td>{{ $databorrow->type }}</td>
                        </tr>
                        @if($databorrow->type === 'User')
                        <tr>
                            <th>Class</th>
                            <td>{{ $databorrow->class }}</td>
                        </tr>
                        @else
                        <tr>
                            <th>Position</th>
                            <td>{{ $databorrow->position }}</td>
                        </tr>
                        @endif
                        <tr>
                            <th>Phone Number</th>
                            <td>{{ $databorrow->formatted_phone_number }}</td>
                        </tr>
                        <tr>
                            <th>Gender</th>
                            <td>{{ $databorrow->gender }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
