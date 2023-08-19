@extends('frontend_v4.layouts.master')
@push('before_styles')
@endpush

@push('after_styles')
@endpush

@push('before_scripts')
@endpush

@section('content')
<div class="bg-white px-4 md:px-6 lg:px-10 mb-4">
    <div class="container mx-auto">
        <div class="font-light text-sm flex gap-4 items-center mb-3 mt-3">
            <a href="{{ route('document.home.index') }}"
               class="text-primary text-sm md:text-base lg:text-lg font-normal">Home</a>
            <i class="fa-solid fa-chevron-right"></i>
            <span class="text-sm md:text-base lg:text-lg font-light">Document uploaded</span>
        </div>

        <div class="text-base md:text-lg lg:text-2xl font-semibold text-black mb-3">
            My document
        </div>

        <div class="flex flex-col md:flex-row gap-6 md:gap-2 lg:gap-6">
            <div x-data="{submitted_by_me:true,my_answers:false}"
                 class="w-full border-y md:border border-gray-300 md:rounded-md">
                <div class="flex flex-row">
                    <div @click="submitted_by_me=true;my_answers=false"
                         class="py-3 text-sm md:text-base lg:text-lg text-center w-1/2 cursor-pointer"
                         :class="submitted_by_me ? 'border-b-2 border-primary font-semibold' : 'border-b border-gray-300 font-normal'">
                        My document
                    </div>
                    <div @click="submitted_by_me=false;my_answers=true"
                         class="py-3 font-normal text-sm md:text-base lg:text-lg text-center w-1/2 cursor-pointer"
                         :class="my_answers ? 'border-b-2 border-primary font-semibold' : 'border-b border-gray-300 font-normal'"
                    >My answers
                    </div>
                </div>
                <div x-show="submitted_by_me">
                    @foreach($documents as $document)
                        <div class="px-4 md:px-8 py-4 m-4 border border-gray-300 rounded-4.5xl flex flex-row gap-4">
                            <div class="w-10/12 flex flex-col gap-3">
                                <div class="flex flex-row gap-4 justify-between">
                                    <a href="{{ route('document.detail', ['slug' => $document->slug]) }}" class="w-8/12 lg:w-10/12 font-normal text-sm md:text-base text-primary-darker">{{ $document->title }}</a>
                                    <p class="w-4/12 lg:w-2/12 text-end text-xs md:text-base font-light text-default-lighter mr-5">
                                        {{ $document->created_at->toDateString() }}</p>
                                </div>
                                <div class="text-xs md:text-base font-light line-clamp-4 md:line-clamp-3">
                                    {{ $document->description }}
                                </div>
                                <div
                                    class="flex items-center gap-4 text-sm md:text-base font-medium text-default-lighter">
                                    <div class="flex items-center gap-2">
                                        <i class="fa fa-cloud-arrow-down"></i>
                                        {{ $document->downloaded_count }}
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fa fa-thumbs-up"></i>
                                        {{ $document->helpful_count }}
                                    </div>
                                </div>
                            </div>
                            <div class="w-2/12 flex items-center">
                                <a href="{{ route('frontend_v4.users.edit_document', ['id' => $document->id]) }}" class="w-full bg-primary text-white px-4 py-2 text-center rounded-4.5xl cursor-pointer hover:bg-primary-darker">
                                    Update
                                </a>
                            </div>
                        </div>

                    @endforeach
                </div>
                <div x-cloak x-show="my_answers">
                    @foreach($comments as $comment)
                    <div class="py-4 md:m-4 flex flex-col gap-2 border-t border-gray-300">
                        <div class="flex flex-row gap-4 justify-between">
                            <a href="{{ route('document.detail', ['slug' => $comment->documents->slug]) }}" class="w-8/12 lg:w-10/12 font-normal text-sm md:text-base text-primary-darker">{{ $comment->documents->title }}</a>
                            <p class="w-4/12 lg:w-2/12 text-end text-xs md:text-base font-light text-default-lighter mr-5">
                                {{ $comment->created_at->toDateString() }}</p>
                        </div>
                        <div class="flex flex-row gap-2">
                            <img class="mt-3 mr-2 h-12 w-12 rounded-full"
                                 src="https://lh3.googleusercontent.com/a/AEdFTp45mrUSLMGlduYjPfK7FMlyXLIvTKw8WS5gri6LyQ=s96-c"
                                 alt="Student" loading="lazy" data-initials="GN">
                            <div class="relative flex items-center">
                                <div class="border border-gray-300 rounded-md px-4 py-2">
                                    <p class="font-normal text-sm md:text-base lg:text-lg">{{ $comment->content }}</p>
                                </div>
                                <div class="absolute left-0 w-4 h-4 transform -translate-x-2  rotate-45 bg-white border-l border-b border-gray-300"></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
