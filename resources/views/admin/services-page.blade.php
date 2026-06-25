@extends('layouts.admin')

@section('title', 'Our Services | Peak Experience')
@section('badge', 'Our Services')

@section('content')
    @php
        $pageValues = [
            'eyebrow' => old('eyebrow', $pageContent['eyebrow']),
            'title' => old('title', $pageContent['title']),
            'description' => old('description', $pageContent['description']),
        ];
        $cards = old('cards', $pageContent['cards']);
    @endphp

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
                    <div class="col-sm-8">
                        <h1 class="page-title">Our Services</h1>
                        <p class="text-muted">Edit the public Our Services page intro and service cards.</p>
                    </div>
                    <div class="col-sm-4 text-right">
                        <a href="{{ route('our-services') }}" target="_blank" rel="noreferrer" class="btn btn-outline-info">
                            <i class="fas fa-eye"></i>
                            Preview
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <form method="POST" action="{{ route('admin.services-page.update') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="card shadow-lg rounded-lg border-0 mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Page Intro</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="services_eyebrow">Small Heading</label>
                                    <input id="services_eyebrow" type="text" name="eyebrow" class="form-control" value="{{ $pageValues['eyebrow'] }}" required>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="services_title">Main Heading</label>
                                    <input id="services_title" type="text" name="title" class="form-control" value="{{ $pageValues['title'] }}" required>
                                </div>

                                <div class="form-group col-12">
                                    <label for="services_description">Intro Description</label>
                                    <textarea id="services_description" name="description" rows="5" class="form-control" required>{{ $pageValues['description'] }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-lg rounded-lg border-0">
                        <div class="card-header">
                            <h3 class="card-title">Service Cards</h3>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                @foreach ($cards as $index => $card)
                                    @php
                                        $imagePath = (string) ($card['image'] ?? '');
                                        $imageUrl = \App\Support\HomepageContent::assetUrl($imagePath);
                                    @endphp
                                    <div class="col-xl-6 mb-4">
                                        <div class="border rounded-lg p-4 h-100">
                                            <h4 class="font-weight-bold mb-3">Service {{ $loop->iteration }}</h4>

                                            <input type="hidden" name="cards[{{ $index }}][image]" value="{{ $imagePath }}">

                                            <div class="form-group">
                                                <label for="card_title_{{ $index }}">Title</label>
                                                <input id="card_title_{{ $index }}" type="text" name="cards[{{ $index }}][title]" class="form-control" value="{{ $card['title'] ?? '' }}" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="card_description_{{ $index }}">Description</label>
                                                <textarea id="card_description_{{ $index }}" name="cards[{{ $index }}][description]" rows="4" class="form-control" required>{{ $card['description'] ?? '' }}</textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="card_image_alt_{{ $index }}">Image Alt Text</label>
                                                <input id="card_image_alt_{{ $index }}" type="text" name="cards[{{ $index }}][image_alt]" class="form-control" value="{{ $card['image_alt'] ?? '' }}">
                                            </div>

                                            <div class="form-group">
                                                <label for="card_image_{{ $index }}">Upload Image</label>
                                                <input id="card_image_{{ $index }}" type="file" name="cards[{{ $index }}][image_file]" accept="image/*" class="form-control-file">
                                            </div>

                                            @if ($imageUrl !== '')
                                                <div class="mb-3">
                                                    <img src="{{ $imageUrl }}" alt="{{ $card['image_alt'] ?? $card['title'] }}" class="default-img">
                                                </div>

                                                <label class="d-inline-flex align-items-center gap-2 text-sm">
                                                    <input type="checkbox" name="cards[{{ $index }}][remove_image]" value="1">
                                                    <span>Remove current image</span>
                                                </label>
                                            @else
                                                <p class="text-muted small mb-0">No image uploaded. The public page will use a fallback image until you add one.</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary font-weight-bold">
                                    <i class="fas fa-save"></i>
                                    Save Our Services Page
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
