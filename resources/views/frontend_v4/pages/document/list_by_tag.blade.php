@extends('frontend_v4.layouts.master')

@push('title')
    <title>Libshare - {{ $tag->name }}</title>
    <meta name="description" content="{{ $tag->name }}">
@endpush
@push('before_styles')
@endpush

@push('after_styles')
@endpush

@push('before_scripts')
@endpush

@section('content')
    <div class="bg-white px-4 ">
        <div class="container border-b border-gray-300 mx-auto py-2 md:py-8">
            <h1 class="mx-auto text-sm sm:text-2xl md:text-3xl font-medium mb-4 md:mb-6">{{ $tag->name }}</h1>
            <div class="flex gap-4 font-light text-sm sm:text-lg">
                <a href="{{ route('document.home.index') }}" class="text-primary">Home</a>
                <span class="text-default-lighter">
                    <i class="fa-solid fa-angle-right text-sm"></i>
                </span>
                <p class="text-default-lighter">{{ $tag->name }}</p>
            </div>
        </div>
    </div>
    <div class="bg-white px-4">
        <div class="container mx-auto pt-2 md:pt-5 pb-5 md:pb-11">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2 sm:mb-6"
                 x-data="filterData">
                <div class=" p-2 font-medium text-xs sm:text-base">Showing {{ count($documents) }} documents</div>
            </div>
            <div class="w-full flex md:flex-col flex-wrap gap-4 justify-around mb-11">
                @foreach($documents as $document)
                    @include('frontend_v4.components.document-list-item')
                @endforeach
            </div>
            {{-- Pagination--}}
            <div
                class="max-4xl xl:max-w-5xl mx-auto paginator my-1 md:my-4 lg:mt-8 flex md:gap-4 items-center justify-center">
                @if ($documents->onFirstPage())
                    <span class="inline-flex items-center gap-3 p-2 text-slate-300 text-sm md:text-base cursor-pointer">
            <i class="fa-solid fa-chevron-left"></i>
            <span class="leading-8">Previous</span>
        </span>
                @else
                    <a href="{{ $documents->previousPageUrl() }}"
                       class="inline-flex items-center gap-3 p-2 text-primary text-sm md:text-base hover:bg-[#E6F7F4]">
                        <i class="fa-solid fa-chevron-left"></i>
                        <span class="leading-8">Previous</span>
                    </a>
                @endif

                <div class="hidden md:flex gap-2">
                    @foreach ($documents->getUrlRange(1, $documents->lastPage()) as $page => $url)
                        @if ($documents->currentPage() == $page)
                            <a href="{{ $url }}"
                               class="paginator_item-2 inline-flex text-white bg-primary items-center rounded-full text-base w-8 h-8">
                                <span class="grow text-center">{{ $page }}</span>
                            </a>
                        @else
                            <a href="{{ $url }}"
                               class="paginator_item-2 inline-flex text-primary hover:bg-[#E6F7F4] items-center rounded-full text-base w-8 h-8">
                                <span class="grow text-center">{{ $page }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>

                @if ($documents->hasMorePages())
                    <a href="{{ $documents->nextPageUrl() }}"
                       class="inline-flex items-center gap-3 hover:bg-[#E6F7F4] rounded-4xl px-4 py-1 text-primary text-sm md:text-base">
                        <span class="leading-8">Next</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                @else
                    <span
                        class="inline-flex items-center gap-3 rounded-4xl px-4 py-1 text-slate-300 text-sm md:text-base cursor-pointer">
            <span class="leading-8">Next</span>
            <i class="fa-solid fa-chevron-right"></i>
        </span>
                @endif
            </div>

        </div>
    </div>
@endsection
