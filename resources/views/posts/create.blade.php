@extends('layouts.app')
@section('title', 'Create Post')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/easymde@2.20.0/dist/easymde.min.css" rel="stylesheet">
<style>
<style>
    .post-creation-container {
        background: rgba(30, 30, 30, 0.85);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid #333333;
    }

    .editor-toolbar button {
        color: #e0e0e0 !important;
    }

    .editor-toolbar.fullscreen {
        background-color: #1e1e1e;
    }

    .CodeMirror {
        border-radius: 0.25rem;
        border: 1px solid #333333;
        background-color: #121212;
        color: #e0e0e0;
    }

    .markdown-body {
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #333333;
        background-color: #1e1e1e;
        color: #e0e0e0;
        margin-top: 15px;
    }

    .preview-toggle {
        cursor: pointer;
        font-size: 0.9rem;
        color: #a0a0a0;
    }

    .image-upload-container {
        border: 2px dashed #333333;
        border-radius: 5px;
        padding: 20px;
        text-align: center;
        margin-bottom: 15px;
        transition: all 0.3s;
        background-color: #121212;
        color: #e0e0e0;
    }

    .image-upload-container:hover {
        border-color: #a0a0a0;
    }

    .hidden {
        display: none;
    }

    .image-preview-container {
        margin-top: 15px;
    }

    .image-preview {
        max-width: 100%;
        max-height: 300px;
        border-radius: 5px;
    }

    .form-label {
        font-weight: 500;
        color: #e0e0e0;
    }

    .post-header {
        border-bottom: 1px solid #333333;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .page-background {
        background: linear-gradient(135deg, #121212 0%, #1e1e1e 100%);
        min-height: 100vh;
        padding: 40px 0;
        color: #e0e0e0;
    }

    .btn-primary {
        background-color: #2563eb;
        border-color: #2563eb;
        color: #ecf0f1;
    }

    .btn-primary:hover {
        background-color: #1e4ed8;
        border-color: #1e3abf;
    }
</style>
@endsection

@section('content')
<div class="page-background">
    <div class="container">
        <div class="row">
            <div class="content-inner">
                <div class="post-creation-container">
                    <div class="post-header">
                        <h1 class="h2 mb-1">Create New Post</h1>
                        <p class="text">Share your thoughts with the world</p>
                    </div>
                    
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="post-form">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" 
                                id="title" name="title" value="{{ old('title') }}" required placeholder="Add a catchy title">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Featured Image</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="image-upload-container" id="image-upload-area">
                                        <i class="fa fa-cloud-upload fa-2x mb-2"></i>
                                        <h5>Drop image here or click to upload</h5>
                                        <p class="small mb-0">Supports JPG, PNG, GIF up to 5MB</p>
                                        <input type="file" id="featured-image-input" name="featured_image" 
                                            class="hidden @error('featured_image') is-invalid @enderror" 
                                            accept="image/*">
                                        @error('featured_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="image-preview-container text-center" id="image-preview-container">
                                        @if(old('featured_image_url'))
                                            <img src="{{ old('featured_image_url') }}" alt="Preview" class="image-preview" id="image-preview">
                                        @else
                                            <div class="text">Image preview will appear here</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-0 mt-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="image_source" id="image-upload" value="upload" checked>
                                    <label class="form-check-label" for="image-upload">Upload Image</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="image_source" id="image-url" value="url">
                                    <label class="form-check-label" for="image-url">Use URL</label>
                                </div>
                            </div>
                            
                            <div id="image-url-container" class="mt-3 hidden">
                                <input type="url" class="form-control @error('featured_image_url') is-invalid @enderror" 
                                    id="featured_image_url" name="featured_image_url" value="{{ old('featured_image_url') }}" 
                                    placeholder="https://example.com/image.jpg">
                                @error('featured_image_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="content" class="form-label mb-0">Content</label>
                                <span class="preview-toggle" id="preview-toggle">
                                    <i class="fa fa-eye"></i> Toggle Preview
                                </span>
                            </div>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                id="content" name="content" rows="12">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fa fa-markdown"></i> Markdown supported. You can use 
                                <strong>bold</strong>, <em>italic</em>, headings, links, images, and more.
                            </div>
                            
                            <div class="markdown-body hidden mt-3" id="markdown-preview"></div>
                        </div>
                        
                        <div class="d-flex justify-content-between pt-3">
                            <a href="{{ route('posts.my-posts') }}" class="btn btn-light px-4">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fa fa-paper-plane me-1"></i> Create Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/easymde@2.20.0/dist/easymde.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/marked@15.0.11/lib/marked.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize EasyMDE for markdown editing
        const easyMDE = new EasyMDE({
            element: document.getElementById('content'),
            spellChecker: false,
            autosave: {
                enabled: true,
                uniqueId: 'post-content-autosave',
                delay: 1000,
            },
            toolbar: [
                'bold', 'italic', 'heading', '|', 
                'quote', 'unordered-list', 'ordered-list', '|',
                'link', 'image', 'table', '|',
                'preview', 'side-by-side', 'fullscreen', '|',
                'guide'
            ],
            status: ['autosave', 'lines', 'words', 'cursor'],
            uploadImage: true,
            imageMaxSize: 5242880, // 5MB
            placeholder: 'Write your post content here using markdown...'
        });
        
        // Toggle preview
        const previewToggle = document.getElementById('preview-toggle');
        const markdownPreview = document.getElementById('markdown-preview');
        
        previewToggle.addEventListener('click', function() {
            const content = easyMDE.value();
            markdownPreview.innerHTML = marked.parse(content);
            markdownPreview.classList.toggle('hidden');
        });
        
        // Image upload handling
        const imageUploadArea = document.getElementById('image-upload-area');
        const imageInput = document.getElementById('featured-image-input');
        const imagePreview = document.getElementById('image-preview-container');
        
        imageUploadArea.addEventListener('click', function() {
            imageInput.click();
        });
        
        imageUploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            imageUploadArea.style.borderColor = '#4e73df';
        });
        
        imageUploadArea.addEventListener('dragleave', function() {
            imageUploadArea.style.borderColor = '#dee2e6';
        });
        
        imageUploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            imageUploadArea.style.borderColor = '#dee2e6';
            
            if (e.dataTransfer.files.length) {
                imageInput.files = e.dataTransfer.files;
                handleImagePreview(e.dataTransfer.files[0]);
            }
        });
        
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                handleImagePreview(this.files[0]);
            }
        });
        
        function handleImagePreview(file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="image-preview" id="image-preview">`;
            };
            
            reader.readAsDataURL(file);
        }
        
        // Toggle between image upload and URL
        const uploadRadio = document.getElementById('image-upload');
        const urlRadio = document.getElementById('image-url');
        const uploadContainer = document.getElementById('image-upload-area');
        const urlContainer = document.getElementById('image-url-container');
        const imageUrlInput = document.getElementById('featured_image_url');
        
        uploadRadio.addEventListener('change', function() {
            if (this.checked) {
                uploadContainer.classList.remove('hidden');
                urlContainer.classList.add('hidden');
            }
        });
        
        urlRadio.addEventListener('change', function() {
            if (this.checked) {
                uploadContainer.classList.add('hidden');
                urlContainer.classList.remove('hidden');
            }
        });
        
        imageUrlInput.addEventListener('input', function() {
            const url = this.value;
            if (url) {
                imagePreview.innerHTML = `<img src="${url}" alt="Preview" class="image-preview" id="image-preview" onerror="this.src='https://via.placeholder.com/400x300?text=Invalid+Image+URL'">`;
            }
        });
    });
</script>
@endsection