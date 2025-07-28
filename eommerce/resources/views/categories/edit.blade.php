@extends('layouts.app')

@section('content')
<div class="container py-4 ">
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card shadow rounded-3">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Category</h5>
                    <a href="{{ route('categories.index') }}" class="btn btn-sm btn-light">Back</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Category Name -->
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" name="category_name" class="form-control" value="{{ old('category_name', $category->category_name) }}" required>
                        </div>

                        <!-- Category Picture -->
                        <div class="mb-3">
                            <label class="form-label">Category Picture</label><br>
                            @if($category->category_picture)
                                <img src="{{ asset('storage/' . $category->category_picture) }}" class="rounded-circle mb-2" width="100" height="100" alt="Category Image">
                            @endif
                            <input type="file" name="category_picture" class="form-control">
                        </div>

                        <!-- Page Link -->
                        <div class="mb-3">
                            <label class="form-label">Category Page Link</label>
                            <input type="url" name="category_page_link" class="form-control" value="{{ old('category_page_link', $category->category_page_link) }}">
                        </div>

                        <!-- Status Toggle -->
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="statusSwitch" name="status" value="1" {{ $category->status ? 'checked' : '' }}>
                            <label class="form-check-label" for="statusSwitch">Active</label>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn btn-success">Update Category</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
