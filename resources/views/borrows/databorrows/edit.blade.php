@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5>{{ __('Edit Patron') }}</h5>
                        <a href="{{ route('databorrows.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('databorrows.update', $databorrow->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name_borrower" class="form-label">Name Borrower</label>
                            <input type="text" name="name_borrower" id="name_borrower" class="form-control @error('name_borrower') is-invalid @enderror" value="{{ old('name_borrower', $databorrow->name_borrower) }}" required maxlength="60" pattern="^[A-Za-z\s]+$" title="Name must not contain numbers or punctuation." >
                            @error('name_borrower')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                                <option value="">Select Type</option>
                                <option value="User" {{ old('type', $databorrow->type) == 'User' ? 'selected' : '' }}>User</option>
                                <option value="Teacher" {{ old('type', $databorrow->type) == 'Teacher' ? 'selected' : '' }}>Teacher</option>
                                <option value="Staff" {{ old('type', $databorrow->type) == 'Staff' ? 'selected' : '' }}>Staff</option>
                                <option value="Guest" {{ old('type', $databorrow->type) == 'Guest' ? 'selected' : '' }}>Guest</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="class-field" style="{{ $databorrow->type == 'User' ? '' : 'display: none;' }}">
                            <label for="class" class="form-label">Class</label>
                            <select name="class" id="class" class="form-control @error('class') is-invalid @enderror" {{ $databorrow->type == 'User' ? 'required' : '' }}>
                                <option value="">Select Class</option>
                                <option value="X PPLG" {{ old('class', $databorrow->class) == 'X PPLG' ? 'selected' : '' }}>X PPLG</option>
                                <option value="X PMN" {{ old('class', $databorrow->class) == 'X PMN' ? 'selected' : '' }}>X PMN</option>
                                <option value="X HTL" {{ old('class', $databorrow->class) == 'X HTL' ? 'selected' : '' }}>X HTL</option>
                                <option value="XI PPLG" {{ old('class', $databorrow->class) == 'XI PPLG' ? 'selected' : '' }}>XI PPLG</option>
                                <option value="XI PMN" {{ old('class', $databorrow->class) == 'XI PMN' ? 'selected' : '' }}>XI PMN</option>
                                <option value="XI HTL" {{ old('class', $databorrow->class) == 'XI HTL' ? 'selected' : '' }}>XI HTL</option>
                                <option value="XI TJKT" {{ old('class', $databorrow->class) == 'XI TJKT' ? 'selected' : '' }}>XI TJKT</option>
                            </select>
                            @error('class')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="position-field" style="{{ $databorrow->type != 'User' ? '' : 'display: none;' }}">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" name="position" id="position" class="form-control @error('position') is-invalid @enderror" value="{{ old('position', $databorrow->position) }}" maxlength="255" {{ $databorrow->type != 'User' ? 'required' : '' }}>
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="no_hp" class="form-label">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text">+62</span>
                                <input type="text" name="no_hp" id="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', $databorrow->no_hp) }}" required pattern="^[0-9]{10,13}$" title="Phone number must be 10 to 13 digits." maxlength="13" inputmode="numeric" >
                            </div>
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender', $databorrow->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender', $databorrow->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('type').addEventListener('change', function() {
    const type = this.value;
    const classField = document.getElementById('class-field');
    const positionField = document.getElementById('position-field');
    const classSelect = document.getElementById('class');
    const positionInput = document.getElementById('position');

    if (type === 'User') {
        classField.style.display = 'block';
        positionField.style.display = 'none';
        classSelect.required = true;
        positionInput.required = false;
    } else {
        classField.style.display = 'none';
        positionField.style.display = 'block';
        classSelect.required = false;
        positionInput.required = true;
    }
});
</script>
@endsection
