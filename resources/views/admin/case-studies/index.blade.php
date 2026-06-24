@extends('layouts.admin')

@section('title', 'Case Studies | Peak Experience')
@section('badge', 'Case Study')

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
                        <h1 class="page-title">Case Studies</h1>
                        <p class="text-muted">Manage the case studies shown on the Our Work page.</p>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.case-studies.create') }}" class="btn btn-primary font-weight-bold">
                            <i class="fas fa-plus"></i>
                            Add Case Study
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card shadow-lg rounded-lg border-0">
                    <div class="card-header">
                        <h3 class="card-title">Case Study List</h3>
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
                                                No case studies created yet. Use <strong>Add Case Study</strong> to publish the first item on Our Work.
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
