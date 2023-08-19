@extends('frontend_v4.layouts.master')

@push('before_styles')

@endpush

@push('after_styles')

@endpush

@section('content')

    <h1 class="text-3xl">Buttons</h1>
    <br>
    <a href="#"
       class="bg-primary text-white font-medium rounded-full px-5 py-2 inline-flex items-center justify-center	gap-1">
        <i class="fa fa-cloud-arrow-up"></i>
        Upload
    </a>
    <a href="#" class="shadow-btn font-medium rounded-full px-5 py-2 inline-flex items-center justify-center gap-1">
        <i class="fa fa-globe"></i>
        English (US)
    </a>
    <br><br>
    <h1 class="text-3xl">Typography</h1>
    <br>
    <span class="font-medium text-sm">text-sm</span>
    <p class="text-sm font-medium">
        The quick brown fox jumps over the lazy dog.
    </p>
    <span class="font-medium text-sm">text-base</span>
    <p class="text-base font-medium">
        The quick brown fox jumps over the lazy dog.
    </p>
    <span class="font-medium text-sm">text-lg</span>
    <p class="text-lg font-medium">
        The quick brown fox jumps over the lazy dog.
    </p>
    <span class="font-medium text-sm">text-xl</span>
    <p class="text-xl font-medium">
        The quick brown fox jumps over the lazy dog.
    </p>
    <span class="font-medium text-sm">text-2xl</span>
    <p class="text-2xl font-medium">
        The quick brown fox jumps over the lazy dog.
    </p>
    <br><br>
    <h1 class="text-3xl">Header</h1>
    <br>
    <header id="header" class="w-full px-4 lg:px-0 shadow-3xl fixed top-0 left-0 z-50 right-0 bg-white">
        <div class="container mx-auto flex items-center justify-between md:py-4">
            <div class="py-4 flex gap-4 items-center justify-between md:justify-start">
                <svg @click="open_modal = !open_modal" id="menu_icon" class="w-12 h-12 hover:cursor-pointer"
                     viewBox="0 0 48 48"
                     fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 14H38" stroke="#33363F" stroke-width="2" stroke-linecap="round"/>
                    <path d="M10 24H38" stroke="#33363F" stroke-width="2" stroke-linecap="round"/>
                    <path d="M10 34H38" stroke="#33363F" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <a href="" class="hidden md:block hover:cursor-pointer">
                    <img src="{{ asset('assets_v4/images/libshare-png-2.png') }}" alt=""
                         class="w-[104px] object-cover">
                </a>
            </div>

            <div class="hidden lg:block relative w-1/2">
                <input class="w-full px-5 py-2 rounded-full border border-slate-300 hover:border-primary placeholder:font-medium"
                       name="search" placeholder="Search for documents, universities and other resources" autocomplete="off">
            </div>

            <div class="flex gap-4 items-center">
                <button class="shadow-btn font-medium rounded-full px-5 py-2 inline-flex items-center justify-center gap-1">
                    <i class="fa fa-globe"></i>
                    English (US)
                </button>
                <button class="bg-primary text-white font-medium rounded-full px-5 py-2 inline-flex items-center justify-center	gap-1">
                    <i class="fa fa-cloud-arrow-up"></i>
                    Upload
                </button>
                <button class="text-2xl rounded-full inline-flex items-center justify-center gap-1">
                    <i class="fa-solid fa-bell"></i>
                </button>
                <button class="relative">
                    <img class="w-[40px] rounded-full" src="https://lh3.googleusercontent.com/a/AEdFTp45mrUSLMGlduYjPfK7FMlyXLIvTKw8WS5gri6LyQ=s96-c" alt="Student" loading="lazy" data-initials="GN">
                </button>
            </div>
        </div>
    </header>


@endsection


