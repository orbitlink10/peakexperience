@extends('layouts.admin')

@section('title', $pageHeading . ' | Peak Experience')
@section('badge', $pageHeading)

@section('content')
    <div class="space-y-6">
        <div class="admin-panel">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-600">Admin Section</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950 sm:text-4xl">{{ $pageHeading }}</h1>
            <p class="mt-3 max-w-3xl text-sm leading-7 text-slate-600 sm:text-base">
                {{ $pageDescription }}
            </p>
        </div>

        <section class="admin-surface px-6 py-8">
            <div class="max-w-3xl space-y-4">
                <p class="text-sm leading-7 text-slate-600 sm:text-base">
                    This page is active in navigation now, so clicks from the sidebar open a real destination instead of staying on the same screen.
                </p>
                <p class="text-sm leading-7 text-slate-600 sm:text-base">
                    The fully built editors currently available are the gallery manager and the homepage editor.
                </p>
                <div class="flex flex-wrap gap-3 pt-2">
                    <a href="{{ route('admin.gallery') }}" class="admin-btn-primary">Open Gallery</a>
                    <a href="{{ route('admin.homepage') }}" class="admin-btn-secondary">Open Homepage</a>
                </div>
            </div>
        </section>
    </div>
@endsection
