@extends('layouts.app')

@section('content')
<div class="container py-5 ">
    <div class="row justify-content-center mt-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-tags me-2"></i>Create Category</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Category Name</label>
                            <input type="text" name="category_name" class="form-control" placeholder="Enter category name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Category Picture</label>
                            <input type="file" name="category_picture" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Category Page Link</label>
                            <input type="url" name="category_page_link" class="form-control" placeholder="https://example.com/category">
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Create Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
