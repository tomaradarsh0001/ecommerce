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
            <!-- Header Card -->
            <div class="card bg-white border-0 shadow-sm mb-4 mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0 text-dark"><i class="fas fa-users me-2"></i>Users Management</h2>
                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create User
                        </a>
                    </div>
                </div>
            </div>

            <!-- Search Card -->
            <div class="card bg-white border-0 shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('users.index') }}" class="row g-3" id="searchForm">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-transparent"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="Search users..." 
                                       value="{{ request('search') }}" id="usersSearch">
                                @if(request('search'))
                                <button type="button" class="btn btn-outline-secondary" id="clearSearchBtn">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
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

            <!-- Users List Card -->
            <div class="card bg-white border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Users List</h5>
                        <span class="text-muted small">
                            Showing {{ $users->firstItem() }} - {{ $users->lastItem() }} of {{ $users->total() }} users
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @forelse($users as $user)
                    <div class="p-4 border-bottom user-item" 
                         data-user-name="{{ strtolower($user->name) }}" 
                         data-user-email="{{ strtolower($user->email) }}"
                         data-user-status="{{ $user->is_active ? 'active' : 'inactive' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="me-3">
                            <div class="d-flex align-items-center mb-1">
                                <h5 class="mb-0 text-dark">
                                    <i class="fas fa-user me-2 text-secondary"></i> {{-- User icon --}}
                                    {{ $user->name }}
                                </h5>
                                <span class="badge ms-2 bg-{{ strtolower($user->status) === 'active' ? 'success' : 'danger' }}">
                                    <i class="fas {{ strtolower($user->status) === 'active' ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                    {{ ucfirst($user->status) }}
                                </span>
                            </div>

                            <div class="text-muted small mb-2">
                                <i class="fas fa-envelope me-1"></i> {{-- Email icon --}}
                                {{ $user->email }}
                            </div>

                            @if($user->roles->count())
                                <div class="flex-wrap gap-2 mt-2">
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-primary text-white">
                                            <i class="fas fa-user-shield me-1"></i> {{-- Role icon --}}
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted small">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{-- Warning icon --}}
                                    No roles assigned
                                </span>
                            @endif
                        </div>

                            
                            <div class="d-flex align-items-center">
                                <!-- Status Toggle Switch -->
                           <form method="POST" action="{{ route('users.toggle-status', $user->id) }}" class="me-2">
                            @csrf
                            @method('PATCH')

                            <div class="form-check form-switch">
                                <!-- Hidden field ensures 'inactive' is sent if unchecked -->
                                <input type="hidden" name="status" value="inactive">

                                <input class="form-check-input" type="checkbox" role="switch"
                                    id="statusToggle{{ $user->id }}"
                                    name="status" value="active"
                                    {{ $user->status === 'active' ? 'checked' : '' }}
                                    onchange="this.form.submit()">

                                <label class="form-check-label" for="statusToggle{{ $user->id }}">
                                    {{ ucfirst($user->status) }}
                                </label>
                            </div>
                        </form>


                                <div class="btn-group" role="group">
                                    <a href="{{ route('users.edit-roles', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-user-shield me-1"></i>Roles
                                    </a>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-muted">
                        No users found
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination Card -->
            @if($users instanceof \Illuminate\Pagination\AbstractPaginator && $users->hasPages())
            <div class="card bg-white border-0 shadow-sm mt-4">
                <div class="card-body py-2">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth toggle animation
        document.querySelectorAll('.form-check-input').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                this.parentElement.classList.add('processing');
                this.disabled = true;
            });
        });

        // Get elements
        const searchForm = document.getElementById('searchForm');
        const usersSearch = document.getElementById('usersSearch');
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        const statusFilter = document.querySelector('select[name="status"]');

        // Clear search functionality
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                usersSearch.value = '';
                searchForm.submit();
            });
        }

        // Client-side filtering (optional)
        if (usersSearch) {
            usersSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const userItems = document.querySelectorAll('.user-item');
                
                userItems.forEach(item => {
                    const userName = item.getAttribute('data-user-name');
                    const userEmail = item.getAttribute('data-user-email');
                    const matchesSearch = userName.includes(searchTerm) || userEmail.includes(searchTerm);
                    
                    item.style.display = (searchTerm.length > 0 && !matchesSearch) ? 'none' : '';
                });
            });
        }
    });
</script>
<style>
    .user-item {
        transition: all 0.3s ease;
    }
    .user-item:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    .form-switch .form-check-input {
        cursor: pointer;
    }
    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }
    .badge.bg-primary {
        transition: all 0.2s ease;
    }
    .badge.bg-primary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }
</style>
@endsection
@endsection