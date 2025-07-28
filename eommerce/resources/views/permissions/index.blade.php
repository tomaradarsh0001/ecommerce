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
                        <h2 class="mb-0 text-dark">
                            <i class="fas fa-shield-alt me-2"></i>Permissions Management
                        </h2>
                        <a href="{{ route('permissions.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create Permission
                        </a>
                    </div>
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


            <!-- Permissions List Card -->
            <div class="card bg-white border-0 shadow-sm">
                <div class="card-body p-0">
                    @forelse($permissions as $permission)
                    <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                       <div>
                        <h5 class="mb-1 text-dark d-flex align-items-center">
                            <i class="fas fa-key me-2"></i> {{-- Icon before permission name --}}
                            {{ $permission->name }}
                            <span class="badge ms-2 bg-{{ $permission->is_active ? 'success' : 'danger' }}">
                                <i class="fas fa-check-circle me-1" style="display: {{ $permission->is_active ? 'inline' : 'none' }}"></i>
                                <i class="fas fa-times-circle me-1" style="display: {{ $permission->is_active ? 'none' : 'inline' }}"></i>
                                {{ $permission->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </h5>
                        <small class="text-muted">
                            <i class="far fa-calendar-alt me-1"></i> {{-- Icon before created date --}}
                            Created: {{ $permission->created_at->format('M d, Y') }}
                        </small>
                    </div>

                        
                        <div class="d-flex align-items-center">
                            <!-- Status Toggle Switch -->
                            <form method="POST" action="{{ route('permissions.toggle-status', $permission->id) }}" class="me-3">
                                @csrf
                                @method('PATCH')
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="statusToggle{{ $permission->id }}" 
                                           {{ $permission->is_active ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <label class="form-check-label" for="statusToggle{{ $permission->id }}">
                                        {{ $permission->is_active ? 'Active' : 'Inactive' }}
                                    </label>
                                </div>
                            </form>

                            <!-- Edit Button -->
                            <a href="{{ route('permissions.edit', $permission->id) }}" class="btn btn-sm btn-outline-primary me-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <h5>No permissions found</h5>
                        <a href="{{ route('permissions.create') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-plus me-1"></i> Create First Permission
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination Card -->
            @if($permissions instanceof \Illuminate\Pagination\AbstractPaginator && $permissions->hasPages())
            <div class="card bg-white border-0 shadow-sm mt-4">
                <div class="card-body py-2">
                    {{ $permissions->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection