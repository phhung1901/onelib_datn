@extends('frontend_v4.layouts.master')
@push('before_styles')
@endpush

@push('after_styles')
@endpush

@push('before_scripts')
@endpush

@section('content')
    <div class="bg-white px-4 md:px-8 lg:px-10">
        <div class="container mx-auto">
            <div class="flex flex-row justify-between">
                <div class="flex flex-row items-center gap-4">
                    <img class="w-16 h-16 rounded-full"
                         src="https://lh3.googleusercontent.com/a/AEdFTp45mrUSLMGlduYjPfK7FMlyXLIvTKw8WS5gri6LyQ=s96-c"
                         alt="Student" loading="lazy" data-initials="GN">
                    <div>
                        <p class="font-medium text-3xl">{{ Auth::user()->name }}</p>
                        <div class="flex flex-row items-center gap-1">
                            <p class="font-normal text-base text-default-lighter">Total document downloaded: </p>
                            <p class="font-semibold text-base text-primary">{{ Auth::user()->total_downloaded }}</p>
                        </div>
                    </div>
                </div>
                <div class="border border-main-default">
                    <div class="flex flex-row gap-2 mx-4 my-2 items-center">
                        <i class="fa-solid fa-heart text-red-500"></i>
                        <p class="font-normal text-base text-default-lighter">Money</p>
                    </div>
                    <div
                        class="border-t border-main-default flex flex-row gap-1 justify-center items-center py-6 px-10">
                        <p class="font-bold text-base text-default-lighter">{{ Auth::user()->money }}</p>
                        <i class="fa-solid fa-dollar-sign text-primary"></i>
                    </div>
                </div>
            </div>

            <div class="border-t border-primary-100 py-4">
                <div x-data="{numberShow:10}" class="mb-4 lg:mb-8">
                    <div
                        class="mb-4 lg:mb-8 flex justify-between gap-5 md:gap-4 lg:gap-6 flex-col text-default-lighter ">
                        <p class="text-lg font-bold mb-4">Your documents</p>
                        <div>
                            <div
                                class=" border-[#CBE5E0] hover:bg-primary-100 flex items-center text-sm lg:text-base py-2 md:py-4 px-1 md:px-4 lg:px-6 lg:py-6 border-b border-l border-r border-solid  hover:cursor-pointer first-of-type:border-t ">
                                <span class="font-light text-base hidden md:block w-9/12">
                                    NAME
                                </span>
                                <span class="font-light text-base hidden md:block w-1/12">
                                    VIEWS
                                </span>

                                <span class="font-light text-base hidden md:block w-1/12">
                                    DOWNLOAD
                                </span>
                                <span class="font-light text-base hidden md:block w-1/12">
                                    PRICE
                                </span>

                            </div>
                            @foreach($downloads as $download)
                                <div
                                    class=" border-[#CBE5E0] hover:bg-primary-100 flex items-center text-sm lg:text-base py-2 md:py-4 px-1 md:px-4 lg:px-6 lg:py-6 border-b border-l border-r border-solid  hover:cursor-pointer first-of-type:border-t ">
                                    <a href="{{ route('document.detail', ['slug' => $download->document->slug]) }}" class="text-primary flex flex-row gap-2 items-center w-9/12">
                                        <i class="fa-solid fa-file-lines"></i>
                                        <p class="font-medium text-base">{{ $download->document->title }}</p>
                                    </a>
                                    <div class="w-1/12">
                                   <span class="text-base font-light hidden md:block">
                                       {{ $download->document->viewed_count }}
                                    </span>
                                    </div>
                                    <div class="w-1/12">
                                   <span class="text-base font-light hidden md:block">
                                       {{ $download->document->downloaded_count }}
                                    </span>
                                    </div>
                                    <div class="w-1/12 flex flex-row gap-1 items-center text-default-lighter">
                                        <p>{{ $download->payload['price']}}</p>
                                        <i class="fa-solid fa-dollar-sign text-primary"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
{{--                    <button @click="numberShow+=5"--}}
{{--                            x-cloak x-show="numberShow<20"--}}
{{--                            class="hidden md:block py-3 w-full mt-4 text-base font-medium text-primary rounded-full border-2 border-primary hover:bg-primary hover:text-white">--}}
{{--                        <span>Show more</span>--}}
{{--                        <i class="fa-solid fa-angle-down ml-3"></i>--}}
{{--                    </button>--}}
                    <div class="flex justify-center md:hidden ">
                        <button
                            class="text-sm md:text-base inline-flex items-center  gap-2.5 p-2 text-slate-300 ">
                            <i class="fa-solid fa-chevron-left"></i>
                            <span class="font-medium ">Previous</span>
                        </button>
                        <button
                            class="text-sm md:text-base inline-flex items-center gap-2.5 p-2 text-primary ">
                            <span class="font-medium">Next</span>
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
