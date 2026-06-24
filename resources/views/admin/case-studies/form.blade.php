@extends('layouts.admin')

@section('title', ($formMode === 'edit' ? 'Edit Case Study' : 'Add Case Study') . ' | Peak Experience')
@section('badge', 'Case Study')

@section('content')
    @php
        $isEditing = $formMode === 'edit';
        $imagePath = old('image_path', (string) ($caseStudy['image'] ?? ''));
        $imageUrl = \App\Support\HomepageContent::assetUrl($imagePath);
    @endphp

    <div class="pages-admin-shell">
        @if ($errors->any())
            <div class="admin-alert-error space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <h1 class="font-weight-bold">{{ $isEditing ? 'Edit Case Study' : 'Add Case Study' }}</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.case-studies.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left"></i>
                            Back to Case Studies
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-10">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title font-weight-bold">{{ $isEditing ? 'Update Case Study' : 'Add New Case Study' }}</h3>
                            </div>

                            <div class="card-body">
                                <form
                                    method="POST"
                                    action="{{ $isEditing ? route('admin.case-studies.update', ['caseStudyId' => $caseStudy['id']]) : route('admin.case-studies.store') }}"
                                    enctype="multipart/form-data"
                                >
                                    @csrf
                                    @if ($isEditing)
                                        @method('PUT')
                                    @endif

                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input id="title" type="text" name="title" value="{{ old('title', $caseStudy['title']) }}" placeholder="Enter case study title" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="image_alt">Image Alt Text</label>
                                        <input id="image_alt" type="text" name="image_alt" value="{{ old('image_alt', $caseStudy['image_alt']) }}" placeholder="Describe the image" class="form-control">
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="status">Status</label>
                                            <select id="status" name="status" class="form-control">
                                                @foreach (['Active', 'Draft'] as $status)
                                                    <option value="{{ $status }}" @selected(old('status', $caseStudy['status']) === $status)>{{ $status }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="order">Order</label>
                                            <input id="order" type="number" min="0" max="9999" name="order" value="{{ old('order', $caseStudy['order']) }}" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">Content</label>
                                        <textarea id="description" name="description" rows="8" class="form-control" placeholder="Write the short case study summary shown on Our Work.">{{ old('description', $caseStudy['description']) }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="image_file">Upload Image</label>
                                        <input type="hidden" name="image_path" value="{{ $imagePath }}">
                                        <div class="custom-file">
                                            <input id="image_file" type="file" name="image_file" accept="image/*" class="custom-file-input" data-file-input>
                                            <label class="custom-file-label" for="image_file" data-file-label>Choose file</label>
                                        </div>
                                        @if ($imageUrl !== '')
                                            <div class="pages-image-preview">
                                                <img src="{{ $imageUrl }}" alt="{{ old('image_alt', $caseStudy['image_alt']) !== '' ? old('image_alt', $caseStudy['image_alt']) : old('title', $caseStudy['title']) }}">
                                                <p>Current image</p>
                                            </div>
                                            <label class="pages-form-checkbox">
                                                <input type="checkbox" name="image_remove" value="1" @checked(old('image_remove'))>
                                                <span>Remove current image</span>
                                            </label>
                                        @endif
                                    </div>

                                    <div class="form-group text-right">
                                        <a href="{{ route('admin.case-studies.index') }}" class="btn btn-danger">Cancel</a>
                                        <button type="submit" class="btn btn-primary">{{ $isEditing ? 'Update' : 'Submit' }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const fileInput = document.querySelector('[data-file-input]');
            const fileLabel = document.querySelector('[data-file-label]');

            if (fileInput && fileLabel) {
                fileInput.addEventListener('change', () => {
                    fileLabel.textContent = fileInput.files.length > 0 ? fileInput.files[0].name : 'Choose file';
                });
            }
        })();
    </script>
@endpush
