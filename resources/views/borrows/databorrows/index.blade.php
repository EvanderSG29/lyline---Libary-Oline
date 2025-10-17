@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('Book borrowers') }}
                     <a href="{{ route('home') }}" class="btn btn-secondary">Back</a>
                </div>

                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-end mb-3 gap-2">
                        <a href="{{ route('databorrows.create') }}" class="btn btn-success">+ Add User</a>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @foreach($databorrows as $databorrow)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $databorrow->name_borrower }}</td>
                                <td>{{ $databorrow->class }}</td>
                                <td>{{ $databorrow->formatted_phone_number }}</td>
                                <td>{{ $databorrow->gender }}</td>
                                <td>
                                    <a href="{{ route('databorrows.show', $databorrow->id) }}" class="btn btn-info btn-sm">Show</a>
                                    <a href="{{ route('databorrows.edit', $databorrow->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('databorrows.destroy', $databorrow->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to delete?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $databorrows->links() }}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection