@extends('frontend_v4.layouts.master')

@push('before_styles')
@endpush
@push('after_styles')
@endpush

@push('before_scripts')
@endpush

@push('after_scripts')
@endpush
@section('content')
    <div class="bg-white py-8">
        <div
            class="container mx-auto mb-10 px-2 md:px-0 flex justify-between md:gap-6 flex-row md:flex-col flex-wrap text-default-lighter">
            <p class="text-lg md:text-xl lg:text-3xl text-black">Result</p>
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
        <div class=" py-8 md:py-16 bg-[#FFEABF]"
             style="background-image: url('{{ asset('assets_v4/images/thumnail-bg-text.png') }}')">
            <h2 class="text-base md:text-3.25xl font-medium w-3/4 mx-auto text-center">
                Upload more documents and download any material studies right away!
            </h2>
        </div>
    </div>
@endsection
