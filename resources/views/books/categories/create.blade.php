
@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create') }}</div>

                <div class="card-body">
                 <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('categories.index') }}"
                     class="btn btn-primary btn-sm">Back</a>
                 </div>

                 <form action="{{ route('categories.store') }}" method="POST">
                 @csrf

                 <div class="mb-3">
                    <label for="inputCategory" class="form-label">Category</label>
                    <input type="text" name="category"
                           class="form-control @error('category') is-invalid @enderror"
                           id="inputCategory" placeholder="Category Name">

                    @error('category')
                    <div class="form-text text-danger">{{ $message }}</div>
                    @enderror
                 </div>

                 <button type="submit" class="btn btn-success">Submit</button>

                 </form>
                </div>
                  
            </div>
        </div>
    </div>
</div>
@endsection
