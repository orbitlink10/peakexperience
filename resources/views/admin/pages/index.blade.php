@extends('layouts.admin')

@section('title', $pageHeading . ' | Peak Experience')
@section('badge', $pageHeading)

@section('content')
    <div class="pages-admin-shell">
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

        <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="page-title">{{ $pageHeading }}</h1>
                        <p class="text-muted">{{ $pageDescription }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card shadow-lg rounded-lg border-0">
                    <div class="card-header">
                        <h3 class="card-title">Post List</h3>
                        <a href="{{ route($createRouteName) }}" class="btn btn-light btn-sm text-primary font-weight-bold">
                            <i class="fas fa-plus"></i>
                            {{ $createButtonLabel }}
                        </a>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route($bulkDeleteRouteName) }}" id="bulk-action-form">
                            @csrf

                            <div class="bulk-action-row">
                                <select name="bulk_action" class="custom-select custom-select-sm w-auto" aria-label="Bulk action">
                                    <option value="">Bulk actions</option>
                                    <option value="delete">Delete</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                            </div>

                            <div class="table-responsive-md">
                                <table class="table table-hover table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th class="select-column">
                                                <input type="checkbox" data-select-all aria-label="Select all pages">
                                            </th>
                                            <th class="number-column">No.</th>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Alt Text</th>
                                            <th>Type</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($pages as $page)
                                            @php
                                                $imageUrl = \App\Support\HomepageContent::assetUrl((string) ($page['image'] ?? ''));
                                            @endphp
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="selected[]" value="{{ $page['id'] }}" class="select-item" aria-label="Select {{ $page['title'] }}">
                                                </td>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if ($imageUrl !== '')
                                                        <img src="{{ $imageUrl }}" alt="{{ $page['image_alt'] !== '' ? $page['image_alt'] : $page['title'] }}" class="default-img">
                                                    @else
                                                        <div class="default-img default-img-placeholder">No image</div>
                                                    @endif
                                                </td>
                                                <td>{{ $page['title'] }}</td>
                                                <td>{{ $page['image_alt'] !== '' ? $page['image_alt'] : 'No alt text' }}</td>
                                                <td>{{ $page['type'] }}</td>
                                                <td class="text-center">
                                                    <div class="pages-actions">
                                                        <a href="{{ route('pages.show', ['page' => $page['slug']]) }}" target="_blank" rel="noreferrer" class="btn btn-outline-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                            Preview
                                                        </a>
                                                        <a href="{{ route($editRouteName, [$routeIdName => $page['id']]) }}" class="btn btn-outline-warning btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                            Update
                                                        </a>
                                                        <form method="POST" action="{{ route($deleteRouteName, [$routeIdName => $page['id']]) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                                <i class="fas fa-trash-alt"></i>
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="pages-empty-state">
                                                    {{ $emptyMessage }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
