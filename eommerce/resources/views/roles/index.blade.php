@extends('layouts.app')

@section('content')
<style>
    .form-select{
        font-size: 1.3rem !important;
    }
</style>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <!-- Header -->
            <div class="card shadow-sm border-0 mb-4 mt-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="fas fa-user-shield me-2"></i>Roles Management</h2>
                    <a href="{{ route('roles.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create Role
                    </a>
                </div>
            </div>
             @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            <!-- Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                        <form id="searchForm" method="GET" action="{{ route('roles.index') }}" class="row g-3">
                        <div class="col-md-6">
                            <div class="position-relative">
                                <input type="text" id="rolesSearch" name="search" class="form-control pe-5"
                                    value="{{ request('search') }}" placeholder="Search roles...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="status" id="statusFilter" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Roles List -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between">
                    <h5 class="mb-0">Roles List</h5>
                    <small class="text-muted">
                        Showing {{ $roles->firstItem() }} - {{ $roles->lastItem() }} of {{ $roles->total() }} roles
                    </small>
                </div>
                <div class="card-body p-0">
                    @forelse($roles as $role)
                    <div class="p-4 border-bottom role-item">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="d-flex align-items-center">
                                    <h5 class="mb-2">
                                        <i class="fas fa-user-shield me-1"></i>
                                        {{ $role->name }}
                                    </h5>
                                    <span class="badge bg-{{ $role->is_active ? 'success' : 'danger' }} ms-2">
                                        {{ $role->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>

                                @if($role->permissions->count())
                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                        @foreach($role->permissions as $permission)
                                            <span class="badge bg-light border text-dark">
                                                <i class="fas fa-key me-1"></i> {{-- Icon before permission --}}
                                                {{ $permission->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small">No permissions assigned</span>
                                @endif
                            </div>

                            <div class="d-flex align-items-start flex-column">
                                <form method="POST" action="{{ route('roles.toggle-status', $role->id) }}" class="mb-2">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-check form-switch">
                                        
                                        <!-- <input class="form-check-input" type="checkbox" role="switch"
                                            id="statusToggle{{ $role->id }}"
                                            {{ $role->is_active ? 'checked' : '' }}
                                            onchange="this.form.submit()"> -->
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="statusToggle{{ $role->id }}"
                                                {{ $role->is_active ? 'checked' : '' }}
                                                {{ $role->users->count() > 0 ? '' : '' }}
                                                onchange="this.form.submit()">
                                        <label class="form-check-label" for="statusToggle{{ $role->id }}">
                                            {{ $role->is_active ? 'Active' : 'Inactive' }}
                                        </label>
                                    </div>
                                </form>
                                <div class="btn-group">
                                    <a href="{{ route('roles.edit-permissions', $role->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-key me-1"></i>Permissions
                                    </a>
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-muted">
                        No roles found
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            @if($roles->hasPages())
            <div class="card shadow-sm border-0 mt-4">
                <div class="card-body py-2">
                    {{ $roles->appends(request()->query())->links() }}
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const clearBtn = document.getElementById('clearSearchBtn');
    const searchInput = document.getElementById('rolesSearch');
    const statusFilter = document.getElementById('statusFilter');

    if (!clearBtn || !searchInput) {
        alert('Button or input not found!');
        return;
    }

    clearBtn.addEventListener('click', function (e) {
        e.preventDefault(); // 🔒 prevent default button behavior
        console.log('❌ Clear clicked'); // ✅ debug line

        const status = statusFilter?.value || '';

        let baseUrl = "{{ route('roles.index') }}";
        if (status) {
            baseUrl += '?status=' + encodeURIComponent(status);
        }

        // 🔄 Redirect
        window.location.href = baseUrl;
    });
});
</script>
@endsection
