@extends('layouts.admin')

@section('title', ($formMode === 'edit' ? 'Update Page' : 'Add New Page') . ' | Peak Experience')
@section('badge', 'Pages')

@push('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
@endpush

@section('content')
    @php
        $isEditing = $formMode === 'edit';
        $imagePath = old('image_path', (string) ($pageData['image'] ?? ''));
        $imageUrl = \App\Support\HomepageContent::assetUrl($imagePath);
        $descriptionValue = old('description', (string) ($pageData['description'] ?? ''));
    @endphp

    <div class="pages-admin-shell space-y-6">
        @if ($errors->any())
            <div class="admin-alert-error space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section class="pages-form-shell">
            <div class="pages-form-banner">
                <h1>{{ $isEditing ? 'Update Post' : 'Add New Post' }}</h1>
            </div>

            <form
                method="POST"
                action="{{ $isEditing ? route('admin.pages.update', ['pageId' => $pageData['id']]) : route('admin.pages.store') }}"
                enctype="multipart/form-data"
                class="pages-form-body"
                data-page-form
            >
                @csrf
                @if ($isEditing)
                    @method('PUT')
                @endif

                <div class="pages-form-group">
                    <label for="meta_title" class="pages-form-label">Meta Title</label>
                    <input id="meta_title" type="text" name="meta_title" value="{{ old('meta_title', $pageData['meta_title']) }}" placeholder="Enter Meta Title" class="pages-form-input">
                </div>

                <div class="pages-form-group">
                    <label for="meta_description" class="pages-form-label">Meta Description</label>
                    <input id="meta_description" type="text" name="meta_description" value="{{ old('meta_description', $pageData['meta_description']) }}" placeholder="Enter Meta Description" class="pages-form-input">
                </div>

                <div class="pages-form-group">
                    <label for="title" class="pages-form-label">Page Title</label>
                    <input id="title" type="text" name="title" value="{{ old('title', $pageData['title']) }}" placeholder="Enter Keyword Title" class="pages-form-input">
                </div>

                <div class="pages-form-group">
                    <label for="image_file" class="pages-form-label">Featured Image</label>
                    <input type="hidden" name="image_path" value="{{ $imagePath }}">
                    <input id="image_file" type="file" name="image_file" accept="image/*" class="pages-form-file-input">
                    @if ($imageUrl !== '')
                        <div class="pages-image-preview">
                            <img src="{{ $imageUrl }}" alt="{{ old('image_alt', $pageData['image_alt']) !== '' ? old('image_alt', $pageData['image_alt']) : old('title', $pageData['title']) }}">
                            <p>Current image</p>
                        </div>
                    @endif
                    <label class="pages-form-checkbox">
                        <input type="checkbox" name="image_remove" value="1" @checked(old('image_remove'))>
                        <span>Remove current image</span>
                    </label>
                </div>

                <div class="pages-form-group">
                    <label for="image_alt" class="pages-form-label">Image Alt Text</label>
                    <input id="image_alt" type="text" name="image_alt" value="{{ old('image_alt', $pageData['image_alt']) }}" placeholder="Enter Image Alt Text" class="pages-form-input">
                </div>

                <div class="pages-form-group">
                    <label for="heading_two" class="pages-form-label">Heading 2</label>
                    <input id="heading_two" type="text" name="heading_two" value="{{ old('heading_two', $pageData['heading_two']) }}" placeholder="Enter Heading 2" class="pages-form-input">
                </div>

                <div class="pages-form-group">
                    <label for="type" class="pages-form-label">Type</label>
                    <select id="type" name="type" class="pages-form-input pages-form-select">
                        @foreach ($pageTypes as $type)
                            <option value="{{ $type }}" @selected(old('type', $pageData['type']) === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="pages-form-group">
                    <label class="pages-form-label">Page Description:</label>
                    <div class="pages-editor" data-editor-root>
                        <div class="pages-editor-menubar" aria-hidden="true">
                            <span>File</span>
                            <span>Edit</span>
                            <span>View</span>
                            <span>Insert</span>
                            <span>Format</span>
                            <span>Tools</span>
                            <span>Table</span>
                        </div>

                        <div class="pages-editor-toolbar">
                            <button type="button" data-command="undo" aria-label="Undo">&#8630;</button>
                            <button type="button" data-command="redo" aria-label="Redo">&#8631;</button>
                            <button type="button" data-command="bold" aria-label="Bold"><strong>B</strong></button>
                            <button type="button" data-command="italic" aria-label="Italic"><em>I</em></button>
                            <button type="button" data-command="justifyLeft" aria-label="Align left">&#9776;</button>
                            <button type="button" data-command="justifyCenter" aria-label="Align center">&#8801;</button>
                            <button type="button" data-command="insertUnorderedList" aria-label="Bullet list">&#8226; List</button>
                            <button type="button" data-command="insertOrderedList" aria-label="Number list">1. List</button>
                            <button type="button" data-editor-link aria-label="Insert link">&#128279;</button>
                            <button type="button" data-editor-image aria-label="Insert image">&#128247;</button>
                            <button type="button" data-command="removeFormat" aria-label="Clear formatting">&lt;/&gt;</button>
                        </div>

                        <div class="pages-editor-surface" contenteditable="true" data-editor-surface>{!! $descriptionValue !!}</div>
                    </div>

                    <textarea name="description" class="pages-editor-textarea" data-editor-textarea>{{ $descriptionValue }}</textarea>
                </div>

                <div class="pages-form-actions">
                    <button type="submit" class="pages-save-button">{{ $isEditing ? 'Update Post' : 'Save Post' }}</button>
                    <a href="{{ route('admin.section', ['section' => 'pages']) }}" class="pages-cancel-button">Cancel</a>
                </div>
            </form>
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
        })();
    </script>
@endpush
