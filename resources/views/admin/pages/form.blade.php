@extends('layouts.admin')

@section('title', ($formMode === 'edit' ? 'Update Page' : 'Add New Page') . ' | Peak Experience')
@section('badge', 'Pages')

@section('content')
    @php
        $isEditing = $formMode === 'edit';
        $imagePath = old('image_path', (string) ($pageData['image'] ?? ''));
        $imageUrl = \App\Support\HomepageContent::assetUrl($imagePath);
        $descriptionValue = old('description', (string) ($pageData['description'] ?? ''));
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
                        <h1 class="font-weight-bold">Manage Pages</h1>
                    </div>
                    <div class="col-sm-6 text-right"></div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-10">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title font-weight-bold">{{ $isEditing ? 'Update Post' : 'Add New Post' }}</h3>
                            </div>

                            <div class="card-body">
                                <form
                                    method="POST"
                                    action="{{ $isEditing ? route('admin.pages.update', ['pageId' => $pageData['id']]) : route('admin.pages.store') }}"
                                    enctype="multipart/form-data"
                                    data-page-form
                                >
                                    @csrf
                                    @if ($isEditing)
                                        @method('PUT')
                                    @endif

                                    <div class="form-group">
                                        <label for="meta_title">Meta Title</label>
                                        <input id="meta_title" type="text" name="meta_title" value="{{ old('meta_title', $pageData['meta_title']) }}" placeholder="Enter Meta Title" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="meta_description">Meta Description</label>
                                        <input id="meta_description" type="text" name="meta_description" value="{{ old('meta_description', $pageData['meta_description']) }}" placeholder="Enter Meta Description" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="title">Page Title</label>
                                        <input id="title" type="text" name="title" value="{{ old('title', $pageData['title']) }}" placeholder="Enter Keyword Title" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="image_alt">Image Alt Text</label>
                                        <input id="image_alt" type="text" name="image_alt" value="{{ old('image_alt', $pageData['image_alt']) }}" placeholder="Enter Image Alt Text" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="heading_two">Heading 2</label>
                                        <input id="heading_two" type="text" name="heading_two" value="{{ old('heading_two', $pageData['heading_two']) }}" placeholder="Enter Heading 2" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="type">Type</label>
                                        <select id="type" name="type" class="form-control select2">
                                            @foreach ($pageTypes as $type)
                                                <option value="{{ $type }}" @selected(old('type', $pageData['type']) === $type)>{{ $type }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Page Description:</label>
                                        <div class="pages-editor" data-editor-root>
                                            <div class="pages-editor-menubar" aria-hidden="true">
                                                <span>File</span>
                                                <span>Edit</span>
                                                <span>View</span>
                                                <span>Insert</span>
                                                <span>Format</span>
                                                <span>Tools</span>
                                                <span>Table</span>
                                                <span>Help</span>
                                            </div>

                                            <div class="pages-editor-toolbar">
                                                <button type="button" data-command="undo" aria-label="Undo"><i class="fas fa-undo"></i></button>
                                                <button type="button" data-command="redo" aria-label="Redo"><i class="fas fa-redo"></i></button>
                                                <button type="button" data-command="bold" aria-label="Bold"><strong>B</strong></button>
                                                <button type="button" data-command="italic" aria-label="Italic"><em>I</em></button>
                                                <button type="button" data-command="justifyLeft" aria-label="Align left"><i class="fas fa-align-left"></i></button>
                                                <button type="button" data-command="justifyCenter" aria-label="Align center"><i class="fas fa-align-center"></i></button>
                                                <button type="button" data-command="justifyRight" aria-label="Align right"><i class="fas fa-align-right"></i></button>
                                                <button type="button" data-command="insertUnorderedList" aria-label="Bullet list"><i class="fas fa-list-ul"></i></button>
                                                <button type="button" data-command="insertOrderedList" aria-label="Numbered list"><i class="fas fa-list-ol"></i></button>
                                                <button type="button" data-editor-link aria-label="Insert link"><i class="fas fa-link"></i></button>
                                                <button type="button" data-editor-image aria-label="Insert image"><i class="fas fa-image"></i></button>
                                                <button type="button" data-command="removeFormat" aria-label="Clear formatting"><i class="fas fa-eraser"></i></button>
                                            </div>

                                            <div class="pages-editor-surface" contenteditable="true" data-editor-surface>{!! $descriptionValue !!}</div>
                                        </div>

                                        <textarea id="textarea" name="description" class="pages-editor-textarea" data-editor-textarea>{{ $descriptionValue }}</textarea>
                                    </div>

                                    <div class="form-group">
                                        <label for="image_file">Upload Image (optional, only for Posts)</label>
                                        <input type="hidden" name="image_path" value="{{ $imagePath }}">
                                        <div class="custom-file">
                                            <input id="image_file" type="file" name="image_file" accept="image/*" class="custom-file-input" data-file-input>
                                            <label class="custom-file-label" for="image_file" data-file-label>Choose file</label>
                                        </div>
                                        @if ($imageUrl !== '')
                                            <div class="pages-image-preview">
                                                <img src="{{ $imageUrl }}" alt="{{ old('image_alt', $pageData['image_alt']) !== '' ? old('image_alt', $pageData['image_alt']) : old('title', $pageData['title']) }}">
                                                <p>Current image</p>
                                            </div>
                                            <label class="pages-form-checkbox">
                                                <input type="checkbox" name="image_remove" value="1" @checked(old('image_remove'))>
                                                <span>Remove current image</span>
                                            </label>
                                        @endif
                                    </div>

                                    <div class="form-group text-right">
                                        <a href="{{ route('admin.section', ['section' => 'pages']) }}" class="btn btn-danger">Cancel</a>
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
            const root = document.querySelector('[data-editor-root]');
            const surface = document.querySelector('[data-editor-surface]');
            const textarea = document.querySelector('[data-editor-textarea]');
            const form = document.querySelector('[data-page-form]');
            const fileInput = document.querySelector('[data-file-input]');
            const fileLabel = document.querySelector('[data-file-label]');

            if (!root || !surface || !textarea || !form) {
                return;
            }

            const syncEditor = () => {
                textarea.value = surface.innerHTML.trim();
            };

            root.querySelectorAll('[data-command]').forEach((button) => {
                button.addEventListener('click', () => {
                    const command = button.getAttribute('data-command');
                    if (!command) {
                        return;
                    }

                    surface.focus();
                    document.execCommand(command, false);
                    syncEditor();
                });
            });

            const linkButton = root.querySelector('[data-editor-link]');
            if (linkButton) {
                linkButton.addEventListener('click', () => {
                    const url = window.prompt('Enter the link URL');
                    if (!url) {
                        return;
                    }

                    surface.focus();
                    document.execCommand('createLink', false, url);
                    syncEditor();
                });
            }

            const imageButton = root.querySelector('[data-editor-image]');
            if (imageButton) {
                imageButton.addEventListener('click', () => {
                    const url = window.prompt('Enter the image URL');
                    if (!url) {
                        return;
                    }

                    surface.focus();
                    document.execCommand('insertImage', false, url);
                    syncEditor();
                });
            }

            surface.addEventListener('input', syncEditor);
            form.addEventListener('submit', syncEditor);

            if (fileInput && fileLabel) {
                fileInput.addEventListener('change', () => {
                    fileLabel.textContent = fileInput.files.length > 0 ? fileInput.files[0].name : 'Choose file';
                });
            }
        })();
    </script>
@endpush
