@extends('admin.layouts.app')

@section('title', 'Edit Post')

@section('content')
<div class="container mt-4">
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h1 class="display-5 fw-bold">Edit Post</h1>
            <p class="text-muted lead">Make changes to your post content and settings</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Posts
            </a>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3 mb-5">
        <div class="card-body p-4">
            <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    <div class="col-lg-8">
                        <!-- Content editing section -->
                        <div class="card border-0 bg-light rounded-3 p-4 mb-4">
                            <h4 class="mb-3 border-bottom pb-2"><i class="fas fa-edit me-2 text-primary"></i>Content</h4>
                            
                            <div class="mb-4">
                                <label for="title" class="form-label fw-bold">Post Title</label>
                                <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                    id="title" name="title" value="{{ old('title', $post->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="slug" class="form-label fw-bold">URL Slug</label>
                                <div class="input-group">
                                    <span class="input-group-text text-muted">/posts/</span>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                        id="slug" name="slug" value="{{ old('slug', $post->slug) }}">
                                </div>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Leave empty to auto-generate from title</div>
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label fw-bold">Post Content</label>
                                <textarea class="form-control editor @error('content') is-invalid @enderror" 
                                    id="content" name="content" rows="15">{{ old('content', $post->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                            
                            @if($post->status != 'approved')
                            <a href="{{ route('admin.posts.approve', $post) }}" 
                               onclick="event.preventDefault(); document.getElementById('approve-form').submit();"
                               class="btn btn-success btn-lg px-4">
                                <i class="fas fa-check me-2"></i> Approve Post
                            </a>
                            <form id="approve-form" action="{{ route('admin.posts.approve', $post) }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <!-- Post Settings Card -->
                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-header bg-primary text-white py-3">
                                <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Post Settings</h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-4">
                                    <label for="category_id" class="form-label fw-bold">Category</label>
                                    <select class="form-select form-select-lg @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="tags" class="form-label fw-bold">Tags</label>
                                    <select class="form-select tags-select @error('tags') is-invalid @enderror" 
                                        id="tags" name="tags[]" multiple>
                                        @foreach($tags as $tag)
                                            <option value="{{ $tag->id }}" 
                                                {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $tag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tags')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Select multiple tags by holding Ctrl/Cmd while clicking</div>
                                </div>

                                <div class="mb-4">
                                    <label for="status" class="form-label fw-bold">Publication Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                        <option value="pending" {{ old('status', $post->status) == 'pending' ? 'selected' : '' }}>
                                            Pending Review
                                        </option>
                                        <option value="approved" {{ old('status', $post->status) == 'approved' ? 'selected' : '' }}>
                                            Published
                                        </option>
                                        <option value="rejected" {{ old('status', $post->status) == 'rejected' ? 'selected' : '' }}>
                                            Rejected
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-2">
                                        <div class="d-flex align-items-center mt-3 p-2 bg-light rounded">
                                            <div class="me-3 text-center">
                                                <i class="fas fa-info-circle text-primary fs-3"></i>
                                            </div>
                                            <div>
                                                <span class="fw-bold d-block">Publishing Info</span>
                                                <span class="small text-muted">Setting to "Published" makes the post visible to readers</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-check form-switch mb-4">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                        value="1" {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="is_featured">
                                        Feature on Homepage
                                    </label>
                                    <div class="form-text">Highlight this post in the featured section</div>
                                </div>
                            </div>
                        </div>

                        <!-- Featured Image Card -->
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-header bg-primary text-white py-3">
                                <h5 class="mb-0"><i class="fas fa-image me-2"></i>Featured Image</h5>
                            </div>
                            <div class="card-body p-4">
                                @if($post->featured_image)
                                    <div class="mb-4 featured-image-preview rounded-3 overflow-hidden">
                                        <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                            alt="Current featured image" class="img-fluid w-100" style="height: 200px; object-fit: cover;">
                                    </div>
                                @endif
                                
                                <div class="mb-3">
                                    <label for="featured_image" class="form-label fw-bold">Upload New Image</label>
                                    <input type="file" class="form-control @error('featured_image') is-invalid @enderror" 
                                        id="featured_image" name="featured_image" accept="image/*">
                                    @error('featured_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="bg-light p-3 rounded-3 small">
                                    <div class="fw-bold mb-2">Image Guidelines:</div>
                                    <ul class="mb-0 ps-3">
                                        <li>Use high-quality, relevant images</li>
                                        <li>Recommended size: 1200×630 pixels</li>
                                        <li>Maximum file size: 2MB</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .card {
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #9fa6b2;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
    .select2-container .select2-selection--multiple {
        min-height: 38px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Initialize Select2 for tags
    $(document).ready(function() {
        $('.tags-select').select2({
            placeholder: 'Select tags',
            allowClear: true,
            tags: true,
            tokenSeparators: [',', ' ']
        });
    });

    // Initialize rich text editor
    ClassicEditor
        .create(document.querySelector('#content'), {
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', '|', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo']
        })
        .catch(error => {
            console.error(error);
        });
        
    // Slug generation
    document.getElementById('title').addEventListener('blur', function() {
        const slugInput = document.getElementById('slug');
        if (slugInput.value === '') {
            const title = this.value;
            const slug = title.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            slugInput.value = slug;
        }
    });
</script>
@endpush
