@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card bg-white border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-user-shield me-2"></i>Edit Roles for: {{ $user->name }}
                        </h4>
                        <!-- <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span> -->
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.update-roles', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <h5 class="mb-3">Available Roles</h5>
                            
                            @if($roles->isEmpty())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                No active roles available
                            </div>
                            @else
                            <div class="row">
                                @foreach($roles->chunk(ceil($roles->count()/2)) as $chunk)
                                <div class="col-md-6">
                                    @foreach($chunk as $role)
                                    <div class="role-card mb-3 p-3 border rounded">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $role->name }}</h6>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input role-toggle" 
                                                       type="checkbox" 
                                                       name="roles[]"
                                                       value="{{ $role->id }}"
                                                       id="role_{{ $role->id }}"
                                                       {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="role_{{ $role->id }}"></label>
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
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-4">
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
    const toggles = document.querySelectorAll('.role-toggle');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const card = this.closest('.role-card');
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
            toggle.closest('.role-card').classList.add('bg-light', 'border-primary');
        }
    });
});
</script>
<style>
.role-card {
    transition: all 0.3s ease;
}
.role-card:hover {
    background-color: rgba(0, 0, 0, 0.02) !important;
}
.role-card.bg-light {
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