@extends('layouts.app')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card bg-white border-0 shadow-sm mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Permissions
                        </a>
                    </div>

                    <form  id="permissionForm" method="POST" action="{{ route('permissions.store') }}">
                        @csrf
                    
                        
                       <div class="mb-3">
                            <label for="name" class="form-label">Permission Name</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   required
                                   autocomplete="off">
                            <small id="nameError" class="text-danger"></small>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('permissions.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" id="submitBtn" class="btn btn-primary" disabled>
                                Create Permission
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const nameError = document.getElementById('nameError');
    const submitBtn = document.getElementById('submitBtn');
    let timer;

    nameInput.addEventListener('input', function() {
        clearTimeout(timer);
        const roleName = this.value.trim();
        
        nameError.textContent = '';
        submitBtn.disabled = true;
        
        if (roleName.length === 0) return;
        
        timer = setTimeout(() => {
            checkRoleName(roleName);
        }, 500);
    });

    function checkRoleName(roleName) {
        fetch('{{ route("permissions.check-exists") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: roleName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                nameError.textContent = 'This Permission already exists!';
                nameInput.classList.add('is-invalid');
                submitBtn.disabled = true;
            } else {
                nameInput.classList.remove('is-invalid');
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            nameError.textContent = 'Error validating role name';
        });
    }
});
</script>
@endsection