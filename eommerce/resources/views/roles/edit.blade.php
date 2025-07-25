@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card bg-white border-0 shadow-sm mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="mb-0">Edit Role: {{ $role->name }}</h3>
                        <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Roles
                        </a>
                    </div>

                    <form id="roleForm" method="POST" action="{{ route('roles.update', $role) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Role Name</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $role->name) }}" 
                                   required
                                   autocomplete="off">
                            <small id="nameError" class="text-danger d-none"></small>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                             <a href="{{ route('roles.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" id="submitBtn" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Role
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
    const nameInput = document.getElementById('name');
    const nameError = document.getElementById('nameError');
    const submitBtn = document.getElementById('submitBtn');
    const originalName = "{{ $role->name }}";
    let debounceTimer;

    // Real-time validation
    nameInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const roleName = this.value.trim();
        
        if (roleName === originalName) {
            hideError();
            return;
        }
        
        if (roleName.length === 0) {
            showError('Role name cannot be empty');
            return;
        }

        debounceTimer = setTimeout(() => {
            validateRoleName(roleName);
        }, 500);
    });

    function validateRoleName(roleName) {
        fetch('{{ route("roles.check-exists") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                name: roleName,
                exclude_id: "{{ $role->id }}"
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                showError('This role already exists!');
                submitBtn.disabled = true;
            } else {
                hideError();
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Error validating role name');
            submitBtn.disabled = true;
        });
    }

    function showError(message) {
        nameError.textContent = message;
        nameError.classList.remove('d-none');
        nameInput.classList.add('is-invalid');
    }

    function hideError() {
        nameError.classList.add('d-none');
        nameInput.classList.remove('is-invalid');
        submitBtn.disabled = false;
    }
});
</script>
@endsection
@endsection