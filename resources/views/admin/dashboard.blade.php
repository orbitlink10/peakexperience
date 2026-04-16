@extends('layouts.admin')

@section('title', 'Gallery Dashboard | Peak Experience')
@section('badge', 'Gallery')

@section('content')
    <div class="space-y-6">
        @if (session('status'))
            <div class="admin-alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="admin-alert-error space-y-1">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="admin-panel flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-600">Media Library</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl">Gallery</h1>
                <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                    Manage the images displayed on the gallery page. Upload new work, review the current set, and remove images in bulk.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.gallery.upload') }}" enctype="multipart/form-data" id="gallery-upload-form" class="shrink-0">
                @csrf
                <input type="file" name="image_file" accept="image/*" id="gallery-image-file" class="sr-only">
                <button type="button" class="admin-btn-primary w-full sm:w-auto" id="gallery-add-btn">Add Image</button>
            </form>
        </div>

        <section class="admin-surface overflow-hidden">
            <div class="border-b border-slate-200 px-6 py-4 text-sm leading-6 text-slate-600">
                Use Add Image to upload a file. Select existing items and delete them in one action.
            </div>

            <form method="POST" action="{{ route('admin.gallery.delete') }}" id="gallery-delete-form">
                @csrf

                <div class="flex flex-col gap-4 border-b border-slate-200 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700">
                        <input type="checkbox" name="select-all" id="gallery-select-all" class="admin-checkbox">
                        <span>Select all</span>
                    </label>

                    <button class="admin-btn-danger w-full sm:w-auto" type="submit">Delete selected</button>
                </div>

                @if (count($galleryItems) > 0)
                    <div class="grid gap-4 p-4 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                        @foreach ($galleryItems as $index => $item)
                            <article class="group relative overflow-hidden rounded-2xl ring-1 ring-slate-200">
                                <input
                                    type="checkbox"
                                    class="card-check admin-checkbox absolute left-3 top-3 z-10"
                                    name="selected[]"
                                    value="{{ $index }}"
                                >

                                <img
                                    src="{{ \App\Support\HomepageContent::assetUrl($item['image']) }}"
                                    alt="{{ $item['title'] }}"
                                    class="aspect-[4/3] w-full object-cover transition duration-300 group-hover:scale-105"
                                >

                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-slate-950/95 via-slate-950/55 to-transparent p-4 text-white">
                                    <div class="flex items-end justify-between gap-3">
                                        <span class="text-sm font-semibold leading-6">{{ $item['title'] }}</span>
                                        <span class="shrink-0 rounded-full bg-white/10 px-2 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-white/80">
                                            #{{ $index + 1 }}
                                        </span>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-12 text-center text-sm leading-7 text-slate-500">
                        No gallery images uploaded yet.
                    </div>
                @endif
            </form>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const uploadButton = document.getElementById('gallery-add-btn');
            const uploadInput = document.getElementById('gallery-image-file');
            const uploadForm = document.getElementById('gallery-upload-form');
            const selectAll = document.getElementById('gallery-select-all');
            const deleteForm = document.getElementById('gallery-delete-form');

            if (uploadButton && uploadInput) {
                uploadButton.addEventListener('click', () => {
                    uploadInput.click();
                });
            }

            if (uploadInput && uploadForm) {
                uploadInput.addEventListener('change', () => {
                    if (!uploadInput.files || uploadInput.files.length === 0) {
                        return;
                    }

                    uploadForm.requestSubmit();
                });
            }

            if (selectAll && deleteForm) {
                const cardChecks = Array.from(deleteForm.querySelectorAll('.card-check'));

                selectAll.addEventListener('change', () => {
                    cardChecks.forEach((checkbox) => {
                        checkbox.checked = selectAll.checked;
                    });
                });

                cardChecks.forEach((checkbox) => {
                    checkbox.addEventListener('change', () => {
                        selectAll.checked = cardChecks.length > 0 && cardChecks.every((item) => item.checked);
                    });
                });

                deleteForm.addEventListener('submit', (event) => {
                    const hasSelection = cardChecks.some((checkbox) => checkbox.checked);

                    if (!hasSelection) {
                        event.preventDefault();
                        return;
                    }

                    if (!window.confirm('Delete the selected gallery images?')) {
                        event.preventDefault();
                    }
                });
            }
        })();
    </script>
@endpush
