@extends('layouts.admin')

@section('title', ($formMode === 'edit' ? 'Update ' . $contentLabel : $createButtonLabel) . ' | Peak Experience')
@section('badge', $pageHeading)

@section('content')
    @php
        $isEditing = $formMode === 'edit';
        $isPostContext = ($adminContext ?? 'pages') === 'posts';
        $imagePath = old('image_path', (string) ($pageData['image'] ?? ''));
        $imageUrl = \App\Support\HomepageContent::assetUrl($imagePath);
        $galleryImages = old('gallery_existing', is_array($pageData['gallery_images'] ?? null) ? $pageData['gallery_images'] : []);
        $descriptionValue = old('description', (string) ($pageData['description'] ?? ''));
        $deliveryDescriptionValue = old('delivery_description', (string) ($pageData['delivery_description'] ?? ''));
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
                                    action="{{ $isEditing ? route($updateRouteName, [$routeIdName => $pageData['id']]) : route($storeRouteName) }}"
                                    enctype="multipart/form-data"
                                    data-page-form
                                >
                                    @csrf
                                    @if ($isEditing)
                                        @method('PUT')
                                    @endif

                                    @if ($isPostContext)
                                        <div class="form-group">
                                            <label for="event_date">Event Date</label>
                                            <input id="event_date" type="date" name="event_date" value="{{ old('event_date', $pageData['event_date'] ?? '') }}" class="form-control">
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <label for="meta_title">Meta Title</label>
                                            <input id="meta_title" type="text" name="meta_title" value="{{ old('meta_title', $pageData['meta_title']) }}" placeholder="Enter Meta Title" class="form-control">
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <label for="meta_description">{{ $isPostContext ? 'Summary (Meta Description)' : 'Meta Description' }}</label>
                                        <input id="meta_description" type="text" name="meta_description" value="{{ old('meta_description', $pageData['meta_description']) }}" placeholder="Enter Meta Description" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="title">{{ $isPostContext ? 'Conference / Event Title' : 'Page Title' }}</label>
                                        <input id="title" type="text" name="title" value="{{ old('title', $pageData['title']) }}" placeholder="{{ $isPostContext ? 'Enter Event Title' : 'Enter Keyword Title' }}" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="image_alt">Image Alt Text</label>
                                        <input id="image_alt" type="text" name="image_alt" value="{{ old('image_alt', $pageData['image_alt']) }}" placeholder="Enter Image Alt Text" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label for="heading_two">{{ $isPostContext ? 'Brief Heading' : 'Heading 2' }}</label>
                                        <input id="heading_two" type="text" name="heading_two" value="{{ old('heading_two', $pageData['heading_two']) }}" placeholder="{{ $isPostContext ? 'Brief' : 'Enter Heading 2' }}" class="form-control">
                                    </div>

                                    @if ($isPostContext)
                                        <input type="hidden" name="type" value="Post">
                                    @else
                                        <div class="form-group">
                                            <label for="type">Type</label>
                                            <select id="type" name="type" class="form-control select2">
                                                @foreach ($pageTypes as $type)
                                                    <option value="{{ $type }}" @selected(old('type', $pageData['type']) === $type)>{{ $type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <label>{{ $isPostContext ? 'Brief:' : 'Page Description:' }}</label>
                                        <div class="pages-editor" data-editor-root>
                                            <div class="pages-editor-menubar" aria-label="Editor menu">
                                                <div class="pages-editor-menu" data-editor-menu>
                                                    <button type="button" data-editor-menu-toggle>File</button>
                                                    <div class="pages-editor-menu-panel">
                                                        <button type="button" data-editor-submit>Save page</button>
                                                    </div>
                                                </div>
                                                <div class="pages-editor-menu" data-editor-menu>
                                                    <button type="button" data-editor-menu-toggle>Edit</button>
                                                    <div class="pages-editor-menu-panel">
                                                        <button type="button" data-command="undo">Undo</button>
                                                        <button type="button" data-command="redo">Redo</button>
                                                        <button type="button" data-command="selectAll">Select all</button>
                                                    </div>
                                                </div>
                                                <div class="pages-editor-menu" data-editor-menu>
                                                    <button type="button" data-editor-menu-toggle>View</button>
                                                    <div class="pages-editor-menu-panel">
                                                        <button type="button" data-editor-view-source>HTML source</button>
                                                        <button type="button" data-editor-fullscreen>Fullscreen editor</button>
                                                    </div>
                                                </div>
                                                <div class="pages-editor-menu" data-editor-menu>
                                                    <button type="button" data-editor-menu-toggle>Insert</button>
                                                    <div class="pages-editor-menu-panel">
                                                        <button type="button" data-editor-link>Link</button>
                                                        <button type="button" data-editor-image>Image URL</button>
                                                        <button type="button" data-editor-video>Video embed URL</button>
                                                        <button type="button" data-editor-insert-table>Table</button>
                                                    </div>
                                                </div>
                                                <div class="pages-editor-menu" data-editor-menu>
                                                    <button type="button" data-editor-menu-toggle>Format</button>
                                                    <div class="pages-editor-menu-panel">
                                                        <button type="button" data-command="formatBlock" data-command-value="p">Paragraph</button>
                                                        <button type="button" data-command="formatBlock" data-command-value="h2">Heading 2</button>
                                                        <button type="button" data-command="formatBlock" data-command-value="h3">Heading 3</button>
                                                        <button type="button" data-command="bold">Bold</button>
                                                        <button type="button" data-command="italic">Italic</button>
                                                        <button type="button" data-command="underline">Underline</button>
                                                        <button type="button" data-command="strikeThrough">Strikethrough</button>
                                                    </div>
                                                </div>
                                                <div class="pages-editor-menu" data-editor-menu>
                                                    <button type="button" data-editor-menu-toggle>Tools</button>
                                                    <div class="pages-editor-menu-panel">
                                                        <button type="button" data-command="removeFormat">Clear formatting</button>
                                                    </div>
                                                </div>
                                                <div class="pages-editor-menu" data-editor-menu>
                                                    <button type="button" data-editor-menu-toggle>Table</button>
                                                    <div class="pages-editor-menu-panel">
                                                        <button type="button" data-editor-insert-table>Insert 3 x 3 table</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pages-editor-toolbar">
                                                <button type="button" data-command="undo" aria-label="Undo"><i class="fas fa-undo"></i></button>
                                                <button type="button" data-command="redo" aria-label="Redo"><i class="fas fa-redo"></i></button>
                                                <button type="button" data-command="bold" aria-label="Bold"><strong>B</strong></button>
                                                <button type="button" data-command="italic" aria-label="Italic"><em>I</em></button>
                                                <button type="button" data-command="underline" aria-label="Underline"><u>U</u></button>
                                                <button type="button" data-command="strikeThrough" aria-label="Strikethrough"><s>S</s></button>
                                                <button type="button" data-command="justifyLeft" aria-label="Align left"><i class="fas fa-align-left"></i></button>
                                                <button type="button" data-command="justifyCenter" aria-label="Align center"><i class="fas fa-align-center"></i></button>
                                                <button type="button" data-command="justifyRight" aria-label="Align right"><i class="fas fa-align-right"></i></button>
                                                <button type="button" data-command="justifyFull" aria-label="Justify"><i class="fas fa-align-justify"></i></button>
                                                <button type="button" data-command="insertUnorderedList" aria-label="Bullet list"><i class="fas fa-list-ul"></i></button>
                                                <button type="button" data-command="insertOrderedList" aria-label="Numbered list"><i class="fas fa-list-ol"></i></button>
                                                <button type="button" data-editor-link aria-label="Insert link"><i class="fas fa-link"></i></button>
                                                <button type="button" data-editor-image aria-label="Insert image"><i class="fas fa-image"></i></button>
                                                <button type="button" data-editor-video aria-label="Insert video"><i class="fas fa-square-caret-right"></i></button>
                                                <button type="button" data-editor-view-source aria-label="Edit HTML source"><i class="fas fa-code"></i></button>
                                                <button type="button" data-editor-fullscreen aria-label="Fullscreen editor"><i class="fas fa-expand"></i></button>
                                                <button type="button" data-command="removeFormat" aria-label="Clear formatting"><i class="fas fa-eraser"></i></button>
                                            </div>

                                            <div class="pages-editor-surface" contenteditable="true" role="textbox" aria-multiline="true" spellcheck="true" tabindex="0" data-editor-surface>{!! $descriptionValue !!}</div>
                                        </div>

                                        <textarea id="textarea" name="description" class="pages-editor-textarea" data-editor-textarea>{{ $descriptionValue }}</textarea>
                                    </div>

                                    @if ($isPostContext)
                                        <div class="form-group">
                                            <label for="delivery_heading">Delivery Heading</label>
                                            <input id="delivery_heading" type="text" name="delivery_heading" value="{{ old('delivery_heading', $pageData['delivery_heading'] ?? 'Delivery') }}" placeholder="Delivery" class="form-control">
                                        </div>

                                        <div class="form-group">
                                            <label for="delivery_description">Delivery:</label>
                                            <textarea id="delivery_description" name="delivery_description" rows="8" class="form-control pages-post-textarea" placeholder="Enter delivery details">{{ $deliveryDescriptionValue }}</textarea>
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <label for="image_file">{{ $isPostContext ? 'Hero Image' : 'Upload Image (optional)' }}</label>
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

                                    <div class="form-group">
                                        <label>Page Gallery Images</label>
                                        <p class="admin-help">Upload up to six images for this specific page. These are the images shown in the published page grid.</p>
                                        <div class="pages-gallery-upload-grid">
                                            @for ($galleryIndex = 0; $galleryIndex < 6; $galleryIndex++)
                                                @php
                                                    $galleryPath = (string) ($galleryImages[$galleryIndex] ?? '');
                                                    $galleryUrl = \App\Support\HomepageContent::assetUrl($galleryPath);
                                                @endphp
                                                <div class="pages-gallery-upload">
                                                    <input type="hidden" name="gallery_existing[{{ $galleryIndex }}]" value="{{ $galleryPath }}">
                                                    <label for="gallery_image_{{ $galleryIndex }}">Image {{ $galleryIndex + 1 }}</label>
                                                    @if ($galleryUrl !== '')
                                                        <img src="{{ $galleryUrl }}" alt="Page gallery image {{ $galleryIndex + 1 }}">
                                                        <label class="pages-form-checkbox">
                                                            <input type="checkbox" name="gallery_remove[{{ $galleryIndex }}]" value="1" @checked((bool) old("gallery_remove.$galleryIndex"))>
                                                            <span>Remove this image</span>
                                                        </label>
                                                    @else
                                                        <div class="pages-gallery-placeholder">No image</div>
                                                    @endif
                                                    <input id="gallery_image_{{ $galleryIndex }}" type="file" name="gallery_images[{{ $galleryIndex }}]" accept="image/*" class="pages-form-file-input">
                                                </div>
                                            @endfor
                                        </div>
                                    </div>

                                    <div class="form-group text-right">
                                        <a href="{{ $cancelUrl }}" class="btn btn-danger">Cancel</a>
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
            let savedRange = null;

            if (!root || !surface || !textarea || !form) {
                return;
            }

            const editorContainsSelection = () => {
                const selection = window.getSelection();

                return Boolean(selection && selection.rangeCount > 0 && surface.contains(selection.anchorNode));
            };

            const saveSelection = () => {
                const selection = window.getSelection();

                if (!selection || selection.rangeCount === 0 || !surface.contains(selection.anchorNode)) {
                    return;
                }

                savedRange = selection.getRangeAt(0).cloneRange();
            };

            const restoreSelection = () => {
                surface.focus();

                if (!savedRange) {
                    return;
                }

                const selection = window.getSelection();
                if (!selection) {
                    return;
                }

                selection.removeAllRanges();
                selection.addRange(savedRange);
            };

            const syncEditor = () => {
                textarea.value = surface.innerHTML.trim();
            };

            const runCommand = (command, value = null) => {
                restoreSelection();
                document.execCommand(command, false, value);
                saveSelection();
                syncEditor();
            };

            const insertHtml = (html) => {
                runCommand('insertHTML', html);
            };

            const escapeHtml = (value) => value
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');

            const normalizeUrl = (value) => {
                const url = value.trim();
                if (url === '') {
                    return '';
                }

                if (/^(https?:|mailto:|tel:|\/|#)/i.test(url)) {
                    return url;
                }

                return `https://${url}`;
            };

            const closeMenus = (except = null) => {
                root.querySelectorAll('[data-editor-menu]').forEach((menu) => {
                    if (menu !== except) {
                        menu.classList.remove('is-open');
                    }
                });
            };

            root.querySelectorAll('[data-editor-menu-toggle]').forEach((button) => {
                button.addEventListener('click', () => {
                    const menu = button.closest('[data-editor-menu]');
                    if (!menu) {
                        return;
                    }

                    const isOpen = menu.classList.contains('is-open');
                    closeMenus(menu);
                    menu.classList.toggle('is-open', !isOpen);
                });
            });

            root.querySelectorAll('[data-command]').forEach((button) => {
                button.addEventListener('click', () => {
                    const command = button.getAttribute('data-command');
                    if (!command) {
                        return;
                    }

                    runCommand(command, button.getAttribute('data-command-value'));
                    closeMenus();
                });
            });

            root.querySelectorAll('[data-editor-link]').forEach((linkButton) => {
                linkButton.addEventListener('click', () => {
                    const url = window.prompt('Enter the link URL');
                    const normalizedUrl = normalizeUrl(url || '');
                    if (!normalizedUrl) {
                        return;
                    }

                    runCommand('createLink', normalizedUrl);
                    closeMenus();
                });
            });

            root.querySelectorAll('[data-editor-image]').forEach((imageButton) => {
                imageButton.addEventListener('click', () => {
                    const url = window.prompt('Enter the image URL');
                    const normalizedUrl = normalizeUrl(url || '');
                    if (!normalizedUrl) {
                        return;
                    }

                    const alt = window.prompt('Enter image alt text') || '';
                    insertHtml(`<img src="${escapeHtml(normalizedUrl)}" alt="${escapeHtml(alt)}">`);
                    closeMenus();
                });
            });

            root.querySelectorAll('[data-editor-video]').forEach((videoButton) => {
                videoButton.addEventListener('click', () => {
                    const url = window.prompt('Enter YouTube, Vimeo, or video URL');
                    const normalizedUrl = normalizeUrl(url || '');
                    if (!normalizedUrl) {
                        return;
                    }

                    insertHtml(`<p><a href="${escapeHtml(normalizedUrl)}">${escapeHtml(normalizedUrl)}</a></p>`);
                    closeMenus();
                });
            });

            root.querySelectorAll('[data-editor-insert-table]').forEach((tableButton) => {
                tableButton.addEventListener('click', () => {
                    insertHtml('<table><tbody><tr><td>Cell</td><td>Cell</td><td>Cell</td></tr><tr><td>Cell</td><td>Cell</td><td>Cell</td></tr><tr><td>Cell</td><td>Cell</td><td>Cell</td></tr></tbody></table>');
                    closeMenus();
                });
            });

            root.querySelectorAll('[data-editor-view-source]').forEach((sourceButton) => {
                sourceButton.addEventListener('click', () => {
                    const html = window.prompt('Edit HTML source', surface.innerHTML.trim());
                    if (html === null) {
                        return;
                    }

                    surface.innerHTML = html;
                    syncEditor();
                    closeMenus();
                });
            });

            root.querySelectorAll('[data-editor-fullscreen]').forEach((fullscreenButton) => {
                fullscreenButton.addEventListener('click', () => {
                    root.classList.toggle('is-fullscreen');
                    surface.focus();
                    closeMenus();
                });
            });

            root.querySelectorAll('[data-editor-submit]').forEach((saveButton) => {
                saveButton.addEventListener('click', () => {
                    syncEditor();
                    form.requestSubmit();
                });
            });

            surface.addEventListener('keyup', saveSelection);
            surface.addEventListener('mouseup', saveSelection);
            surface.addEventListener('focus', saveSelection);
            surface.addEventListener('input', syncEditor);
            surface.addEventListener('paste', () => {
                window.setTimeout(syncEditor, 0);
            });
            document.addEventListener('selectionchange', () => {
                if (editorContainsSelection()) {
                    saveSelection();
                }
            });
            document.addEventListener('click', (event) => {
                if (!root.contains(event.target)) {
                    closeMenus();
                }
            });
            form.addEventListener('submit', syncEditor);

            if (fileInput && fileLabel) {
                fileInput.addEventListener('change', () => {
                    fileLabel.textContent = fileInput.files.length > 0 ? fileInput.files[0].name : 'Choose file';
                });
            }
        })();
    </script>
@endpush
