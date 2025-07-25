@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-header bg-white border-0 pt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            <i class="fas fa-user-shield me-2"></i>Assign Permissions: {{ $role->name }}
                        </h3>
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Roles
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('roles.update-permissions', $role->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <h5 class="mb-3"><i class="fas fa-list-check me-2"></i>Available Permissions</h5>
                            
                            @if($activePermissions->isEmpty())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                No active permissions available
                            </div>
                            @else
                            <div class="row">
                                @foreach($activePermissions->chunk(ceil($activePermissions->count()/2)) as $chunk)
                                <div class="col-md-6">
                                    @foreach($chunk as $permission)
                                    <div class="permission-card mb-3 p-3 border rounded">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $permission->name }}</h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-shield-alt me-1"></i>
                                                    {{ $permission->guard_name }}
                                                </small>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input permission-toggle" 
                                                       type="checkbox" 
                                                       name="permissions[]"
                                                       value="{{ $permission->id }}"
                                                       id="perm_{{ $permission->id }}"
                                                       {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="perm_{{ $permission->id }}"></label>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add visual feedback when toggling
    const toggles = document.querySelectorAll('.permission-toggle');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const card = this.closest('.permission-card');
            if (this.checked) {
                card.classList.add('bg-light');
                card.classList.add('border-primary');
            } else {
                card.classList.remove('bg-light');
                card.classList.remove('border-primary');
            }
        });
        
        // Initialize state
        if (toggle.checked) {
            toggle.closest('.permission-card').classList.add('bg-light', 'border-primary');
        }
    });
});
</script>
<style>
.permission-card {
    transition: all 0.3s ease;
}
.permission-card:hover {
    background-color: rgba(0, 0, 0, 0.02) !important;
}
.permission-card.bg-light {
    background-color: rgba(13, 110, 253, 0.05) !important;
}
.form-switch .form-check-input {
    width: 2.75em;
    height: 1.5em;
    cursor: pointer;
}
.border-primary {
    border-color: rgba(13, 110, 253, 0.3) !important;
}

</style>
@endsection
@endsection