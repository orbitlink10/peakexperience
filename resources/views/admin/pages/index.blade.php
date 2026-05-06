@extends('layouts.admin')

@section('title', 'Pages | Peak Experience')
@section('badge', 'Pages')

@push('head')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
@endpush

@section('content')
    <div class="pages-admin-shell space-y-6">
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

        <section class="pages-admin-hero">
            <h1>Pages</h1>
            <p>Manage site pages and published content.</p>
        </section>

        <section class="pages-admin-card">
            <div class="pages-admin-card-header">
                <h2>Post List</h2>
                <a href="{{ route('admin.pages.create') }}" class="pages-add-button">+ Add Page</a>
            </div>

            <form method="POST" action="{{ route('admin.pages.bulk-delete') }}" class="pages-bulk-form">
                @csrf

                <div class="pages-bulk-toolbar">
                    <select name="bulk_action" class="pages-bulk-select" aria-label="Bulk action">
                        <option value="">Bulk actions</option>
                        <option value="delete">Delete</option>
                    </select>
                    <button type="submit" class="pages-bulk-apply">Apply</button>
                </div>

                <div class="overflow-x-auto">
                    <table class="pages-table">
                        <thead>
                            <tr>
                                <th class="pages-table-checkbox">
                                    <input type="checkbox" data-select-all aria-label="Select all pages">
                                </th>
                                <th>No.</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Alt Text</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pages as $page)
                                @php
                                    $imageUrl = \App\Support\HomepageContent::assetUrl((string) ($page['image'] ?? ''));
                                @endphp
                                <tr>
                                    <td class="pages-table-checkbox">
                                        <input type="checkbox" name="selected[]" value="{{ $page['id'] }}" aria-label="Select {{ $page['title'] }}">
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($imageUrl !== '')
                                            <img src="{{ $imageUrl }}" alt="{{ $page['image_alt'] !== '' ? $page['image_alt'] : $page['title'] }}" class="pages-table-image">
                                        @else
                                            <div class="pages-table-image pages-table-image-placeholder">No image</div>
                                        @endif
                                    </td>
                                    <td class="pages-table-title">{{ $page['title'] }}</td>
                                    <td>{{ $page['image_alt'] !== '' ? $page['image_alt'] : 'No alt text' }}</td>
                                    <td>{{ $page['type'] }}</td>
                                    <td>
                                        <div class="pages-actions">
                                            <a href="{{ route('pages.show', ['page' => $page['slug']]) }}" target="_blank" rel="noreferrer" class="pages-action pages-action-preview">Preview</a>
                                            <a href="{{ route('admin.pages.edit', ['pageId' => $page['id']]) }}" class="pages-action pages-action-update">Update</a>
                                            <form method="POST" action="{{ route('admin.pages.delete', ['pageId' => $page['id']]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="pages-action pages-action-delete">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="pages-empty-state">
                                        No pages created yet. Use <strong>Add Page</strong> to create the first one with the new template.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        (() => {
            const selectAll = document.querySelector('[data-select-all]');
            if (!selectAll) {
                return;
            }

            const rowChecks = Array.from(document.querySelectorAll('input[name="selected[]"]'));

            selectAll.addEventListener('change', () => {
                rowChecks.forEach((checkbox) => {
                    checkbox.checked = selectAll.checked;
                });
            });
        })();
    </script>
@endpush
