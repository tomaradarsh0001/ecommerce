@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center m-5">
        <div class="col-lg-12">
            <!-- Header Card with improved layout -->
            <div class="card mb-4 mt-4 border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div class="mb-3 mb-md-0">
                            <h2 class="mb-1 text-dark fw-bold">Product Categories</h2>
                            <p class="text-muted mb-0">Manage your product categories and their visibility</p>
                        </div>
                        <a href="{{ route('categories.create') }}" class="btn btn-primary shadow-sm" 
                           style="background-color: #1976d2; border: none; border-radius: 8px; padding: 10px 20px;">
                            <i class="fas fa-plus me-2"></i> Add New Category
                        </a>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" 
                     style="border-left: 4px solid #2e7d32; border-radius: 4px;">
                    <i class="fas fa-check-circle me-2" style="font-size: 1.5rem;"></i>
                    <div>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert"
                     style="border-left: 4px solid #d32f2f; border-radius: 4px;">
                    <i class="fas fa-exclamation-circle me-2" style="font-size: 1.5rem;"></i>
                    <div>
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($categories->count() > 0)
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                    @foreach($categories as $category)
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm hover-effect">
                                <div class="card-img-top p-4 text-center gradient-bg">
                                    @if($category->category_picture)
                                        <img src="{{ asset('storage/' . $category->category_picture) }}" 
                                             class="img-fluid rounded-circle shadow category-image"
                                             alt="{{ $category->category_name }}">
                                    @else
                                        <div class="rounded-circle bg-light mx-auto d-flex align-items-center justify-content-center shadow default-image">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="card-body pt-3 pb-4 px-4">
                                    <h5 class="card-title text-center mb-3 fw-bold text-dark">
                                        {{ $category->category_name }}
                                    </h5>
                                    
                                    @if($category->category_page_link)
                                        <div class="d-grid mb-3">
                                            <a href="{{ $category->category_page_link }}" target="_blank" 
                                               class="btn btn-sm text-white shadow-none page-link-btn">
                                                <i class="fas fa-external-link-alt me-1"></i> Visit Page
                                            </a>
                                        </div>
                                    @endif

                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <a href="{{ route('categories.edit', $category->id) }}" 
                                           class="btn btn-sm shadow-none edit-btn">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        
                                        <form method="POST" action="{{ route('categories.toggle-status', $category->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input status-toggle" type="checkbox" role="switch" 
                                                       id="status-{{ $category->id }}"
                                                       {{ $category->status ? 'checked' : '' }}
                                                       onChange="this.form.submit()">
                                                <label class="form-check-label small ms-2 status-label" for="status-{{ $category->id }}">
                                                    {{ $category->status ? 'Active' : 'Inactive' }}
                                                </label>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if($categories->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    <nav aria-label="Page navigation">
                        <ul class="pagination shadow-sm">
                            <li class="page-item {{ $categories->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $categories->previousPageUrl() }}">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                            @foreach(range(1, $categories->lastPage()) as $i)
                                <li class="page-item {{ $categories->currentPage() == $i ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $categories->url($i) }}">
                                        {{ $i }}
                                    </a>
                                </li>
                            @endforeach
                            <li class="page-item {{ !$categories->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $categories->nextPageUrl() }}">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                @endif
            @else
                <div class="card border-0 shadow-sm empty-state-card">
                    <div class="card-body text-center py-5">
                        <div class="mb-4 empty-state-icon">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <h4 class="mb-3 empty-state-title">No categories found</h4>
                        <p class="text-muted mb-4">Start by adding your first product category</p>
                        <a href="{{ route('categories.create') }}" class="btn btn-primary shadow-sm add-category-btn">
                            <i class="fas fa-plus me-2"></i> Add Category
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Card hover effect */
    .hover-effect {
        transition: all 0.3s ease; 
        border-radius: 12px; 
        overflow: hidden;
    }
    
    .hover-effect:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1) !important;
    }
    
    /* Gradient background */
    .gradient-bg {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
    }
    
    /* Category image styling */
    .category-image {
        width: 120px; 
        height: 120px; 
        object-fit: cover; 
        border: 3px solid white;
    }
    
    .default-image {
        width: 120px; 
        height: 120px; 
        background-color: #e3f2fd;
    }
    
    .default-image i {
        color: #1976d2; 
        font-size: 2.5rem;
    }
    
    /* Button styles */
    .page-link-btn {
        background-color: #2196f3; 
        border-radius: 8px; 
        padding: 8px; 
        text-transform: none;
    }
    
    .edit-btn {
        background-color: #e3f2fd; 
        color: #1976d2; 
        border-radius: 8px; 
        padding: 8px 12px;
    }
    
    .add-category-btn {
        background-color: #1976d2; 
        border: none; 
        border-radius: 8px; 
        padding: 10px 24px;
    }
    
  
    .status-label {
        color: #d32f2f;
    }
    
    .status-toggle:checked ~ .status-label {
        color: #2e7d32;
    }
    
    /* Pagination */
    .page-item.active .page-link {
        background-color: #1976d2;
        color: white;
        border: none;
    }
    
    .page-link {
        border-radius: 8px; 
        border: none; 
        padding: 8px 16px;
        margin: 0 2px;
    }
    
    /* Empty state */
    .empty-state-card {
        border-radius: 12px;
        background-color: #f5f7fa;
    }
    
    .empty-state-icon {
        color: #90a4ae;
        font-size: 4rem;
    }
    
    .empty-state-title {
        color: #546e7a;
    }
    
    /* Button hover effects */
    .btn-primary:hover {
        background-color: #1565c0 !important;
    }
    
    .edit-btn:hover {
        background-color: #bbdefb !important;
    }
    
    .page-link-btn:hover {
        background-color: #0d8aee !important;
    }
</style>
@endsection