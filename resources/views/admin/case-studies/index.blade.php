@extends('layouts.admin')

@section('title', 'Our Work | Peak Experience')
@section('badge', 'Our Work')

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
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h1 class="page-title">Our Work</h1>
                        <p class="text-muted">Manage the work highlights shown on the Our Work page.</p>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.case-studies.create') }}" class="btn btn-primary font-weight-bold">
                            <i class="fas fa-plus"></i>
                            Add Our Work
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card shadow-lg rounded-lg border-0 mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Our Work Page Intro</h3>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.case-studies.page.update') }}" class="row">
                            @csrf

                            <div class="form-group col-md-6">
                                <label for="our_work_eyebrow">Small Heading</label>
                                <input id="our_work_eyebrow" type="text" name="eyebrow" class="form-control" value="{{ old('eyebrow', $pageContent['eyebrow']) }}" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="our_work_title">Main Heading</label>
                                <input id="our_work_title" type="text" name="title" class="form-control" value="{{ old('title', $pageContent['title']) }}" required>
                            </div>

                            <div class="form-group col-12">
                                <label for="our_work_description">Description</label>
                                <textarea id="our_work_description" name="description" rows="4" class="form-control" required>{{ old('description', $pageContent['description']) }}</textarea>
                                <small class="form-text text-muted">This controls the highlighted intro area at the top of the public Our Work page.</small>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary font-weight-bold">
                                    <i class="fas fa-save"></i>
                                    Save Intro Content
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-lg rounded-lg border-0">
                    <div class="card-header">
                        <h3 class="card-title">Our Work List</h3>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive-md">
                            <table class="table table-hover table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="number-column">No.</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Order</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($caseStudies as $caseStudy)
                                        @php
                                            $imageUrl = \App\Support\HomepageContent::assetUrl((string) ($caseStudy['image'] ?? ''));
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($imageUrl !== '')
                                                    <img src="{{ $imageUrl }}" alt="{{ $caseStudy['image_alt'] !== '' ? $caseStudy['image_alt'] : $caseStudy['title'] }}" class="default-img">
                                                @else
                                                    <div class="default-img default-img-placeholder">No image</div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $caseStudy['title'] }}</strong>
                                                <div class="text-muted small">{{ $caseStudy['slug'] }}</div>
                                            </td>
                                            <td>
                                                <span class="badge {{ $caseStudy['status'] === 'Active' ? 'badge-success' : 'badge-secondary' }}">
                                                    {{ $caseStudy['status'] }}
                                                </span>
                                            </td>
                                            <td>{{ $caseStudy['order'] }}</td>
                                            <td class="text-center">
                                                <div class="pages-actions">
                                                    <a href="{{ route('our-work') }}" target="_blank" rel="noreferrer" class="btn btn-outline-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                        Preview
                                                    </a>
                                                    <a href="{{ route('admin.case-studies.edit', ['caseStudyId' => $caseStudy['id']]) }}" class="btn btn-outline-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                        Edit
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.case-studies.delete', ['caseStudyId' => $caseStudy['id']]) }}">
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
                                            <td colspan="6" class="pages-empty-state">
                                                No work highlights created yet. Use <strong>Add Our Work</strong> to publish the first item on Our Work.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
