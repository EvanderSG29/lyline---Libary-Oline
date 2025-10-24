@extends('layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ __('Settings') }}</h4>
                    <div>
                        <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                            <i class="bi bi-arrow-left"></i> {{ __('Back') }}
                        </button>
                        <button type="submit" form="settingsForm" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> {{ __('Save') }}
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form id="settingsForm" method="POST" action="{{ route('settings.update') }}">
                        @csrf

                        <h5 class="card-title">{{ __('General Settings') }}</h5>

                        <div class="mb-3">
                            <label for="app_name" class="form-label">{{ __('Application Name') }}</label>
                            <input type="text" class="form-control" id="app_name" name="app_name" value="{{ $settings['app_name'] }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="default_language" class="form-label">{{ __('Default Language') }}</label>
                            <select name="default_language" id="default_language" class="form-select" required>
                                <option value="en" {{ $settings['default_language'] == 'en' ? 'selected' : '' }}>English</option>
                                <option value="id" {{ $settings['default_language'] == 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="timezone" class="form-label">{{ __('Timezone') }}</label>
                            <select name="timezone" id="timezone" class="form-select" required>
                                <option value="Asia/Jakarta" {{ $settings['timezone'] == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                                <option value="Asia/Makassar" {{ $settings['timezone'] == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                                <option value="Asia/Jayapura" {{ $settings['timezone'] == 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                                <option value="UTC" {{ $settings['timezone'] == 'UTC' ? 'selected' : '' }}>UTC</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="date_format" class="form-label">{{ __('Date Format') }}</label>
                            <select name="date_format" id="date_format" class="form-select" required>
                                <option value="d/m/Y" {{ $settings['date_format'] == 'd/m/Y' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                <option value="m/d/Y" {{ $settings['date_format'] == 'm/d/Y' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                <option value="Y-m-d" {{ $settings['date_format'] == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="currency" class="form-label">{{ __('Currency') }}</label>
                            <select name="currency" id="currency" class="form-select" required>
                                <option value="IDR" {{ $settings['currency'] == 'IDR' ? 'selected' : '' }}>IDR (Rupiah)</option>
                                <option value="USD" {{ $settings['currency'] == 'USD' ? 'selected' : '' }}>USD (US Dollar)</option>
                                <option value="EUR" {{ $settings['currency'] == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('settingsForm');
    const inputs = form.querySelectorAll('input, select');
    let hasChanges = false;

    inputs.forEach(input => {
        const originalValue = input.value;
        input.addEventListener('change', function() {
            hasChanges = true;
        });
    });

    window.addEventListener('beforeunload', function(e) {
        if (hasChanges) {
            e.preventDefault();
            e.returnValue = '{{ __("unsaved_changes_warning") }}';
            return e.returnValue;
        }
    });

    form.addEventListener('submit', function() {
        hasChanges = false;
    });
});
</script>
@endsection
