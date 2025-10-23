@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Edit User
                        <a href="{{ route('users.index') }}" class="btn btn-danger float-end">Back</a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name">Name</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password">Password (leave blank to keep current)</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="role">Role</label>
                            <select name="role" id="roleSelect" class="form-control" required>
                                @foreach($allowedRoles as $role)
                                <option value="{{ $role }}" {{ $user->role->value == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3" id="positionField">
                            <label for="position" id="positionLabel">{{ $user->role->value === 'staff' ? 'Position' : ($user->role->value === 'teacher' ? 'Subject' : ($user->role->value === 'student' ? 'Class' : 'Position')) }}</label>
                            <input type="text" name="position" value="{{ $user->position }}" class="form-control" placeholder="Enter {{ $user->role->value === 'staff' ? 'position' : ($user->role->value === 'teacher' ? 'subject' : ($user->role->value === 'student' ? 'class' : 'position')) }}">
                        </div>
                        <div class="mb-3">
                            <label for="active">Active Status</label>
                            <select name="active" class="form-control">
                                <option value="1" {{ $user->active ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$user->active ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('roleSelect').addEventListener('change', function() {
        const selectedRole = this.value;
        const positionField = document.getElementById('positionField');
        const positionLabel = document.getElementById('positionLabel');
        const positionInput = document.querySelector('input[name="position"]');

        if (selectedRole === 'staff' || selectedRole === 'teacher' || selectedRole === 'student') {
            positionField.style.display = 'block';
            if (selectedRole === 'staff') {
                positionLabel.textContent = 'Position';
                positionInput.placeholder = 'Enter position';
            } else if (selectedRole === 'teacher') {
                positionLabel.textContent = 'Subject';
                positionInput.placeholder = 'Enter subject';
            } else if (selectedRole === 'student') {
                positionLabel.textContent = 'Class';
                positionInput.placeholder = 'Enter class';
            }
        } else {
            positionField.style.display = 'none';
            positionInput.value = ''; // Clear the field if hidden
        }
    });
</script>
@endsection
