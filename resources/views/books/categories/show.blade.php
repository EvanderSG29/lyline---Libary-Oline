@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5>{{ __('Category Details') }}</h5>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Category</th>
                            <td>{{ $category->category }}</td>
                        </tr>
                        <tr>
                            <th>Created By</th>
                            <td>{{ $category->creator ? $category->creator->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Updated By</th>
                            <td>{{ $category->updater ? $category->updater->name : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $category->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $category->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>

                    <!-- Log Changes Section -->
                    <div class="mt-4">
                        <h5>Log Changes</h5>
                        <div class="mb-3">
                            <label for="logFilter" class="form-label">Filter Logs:</label>
                            <select id="logFilter" class="form-select">
                                <option value="all">All Changes</option>
                                <option value="updates">Update History</option>
                            </select>
                        </div>
                        <div id="logContent">
                            <!-- Update Logs -->
                            <div class="log-section" data-type="updates">
                                <h6>Update History</h6>
                                @if($category->audits->count() > 0)
                                    <ul class="list-group">
                                        @foreach($category->audits->sortByDesc('created_at') as $audit)
                                            <li class="list-group-item">
                                                <strong>{{ $audit->user ? $audit->user->name : 'System' }}</strong> updated the category
                                                <small class="text-muted">{{ $audit->created_at->diffForHumans() }}</small>
                                                @if($audit->old_values || $audit->new_values)
                                                    <br><em>Changes: {{ json_encode(array_diff_assoc($audit->new_values, $audit->old_values)) }}</em>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted">No update history recorded.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logFilter = document.getElementById('logFilter');
        const logSections = document.querySelectorAll('.log-section');

        logFilter.addEventListener('change', function() {
            const selectedValue = this.value;

            logSections.forEach(section => {
                if (selectedValue === 'all' || section.getAttribute('data-type') === selectedValue) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
