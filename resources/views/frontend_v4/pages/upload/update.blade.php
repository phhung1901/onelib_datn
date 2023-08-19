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

            @include('frontend_v4.alert.alert')

            <p class="font-semibold text-2xl py-6">Upload</p>
            <form method="POST" action="{{ route('frontend_v4.users.update_document', ['id' => $document->id]) }}" enctype="multipart/form-data" class="flex flex-col mb-8 gap-x-6 gap-y-4 border-t border-main-background">
                {{ csrf_field() }}
                <div class="flex flex-col md:flex-row w-full gap-4 md:gap-12">
                    <div class="flex flex-col w-full">
                        <p class="font-medium text-base text-default mb-2 ml-2">Title</p>
                        <input type="text" name="title" value="{{ $document->title }}" class="@error('title')border-red-300 @enderror border border-default-lighter rounded-1.5lg px-4 py-2 hover:border-primary outline-primary">
                        @error('title')
                        <span class="text-red-500 mt-3 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="flex flex-col md:flex-row w-full gap-4 md:gap-12">
                    <div class="flex flex-col w-full md:w-1/2">
                        <p class="font-medium text-base text-default mb-2 ml-2">Category</p>
                        <div class="relative">
                            <select name="category" class="appearance-none w-full border border-default-lighter rounded-1.5lg px-4 py-2 bg-white hover:border-primary outline-primary">
                                @foreach(\App\Models\Category::get() as $category)
                                    <option @if($document->category_id == $category->id) selected @endif value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M10 12l-6-6h12l-6 6z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full md:w-1/2">
                        <p class="font-medium text-base text-default mb-2 ml-2">Price</p>
                        <input type="number" name="price" placeholder="Price" value="{{ $document->price }}" class="border border-default-lighter rounded-1.5lg px-4 py-2 hover:border-primary outline-primary">
                    </div>
                </div>
                <div class="flex flex-col md:flex-row w-full gap-4 md:gap-12">
                    <div class="flex flex-col w-full md:w-1/2">
                        <p class="font-medium text-base text-default mb-2 ml-2">Language</p>
                        <div class="relative">
                            <select name="language" class="appearance-none w-full border border-default-lighter rounded-1.5lg px-4 py-2 bg-white hover:border-primary outline-primary">
                                <option selected value="en">{{ \App\Libs\CountriesHelper\Languages::getFullName('en') }}</option>
                                @foreach(\App\Libs\CountriesHelper\Languages::getOptions() as $key => $language)
                                    <option @if($document->language == $key) selected @endif value="{{ $key }}">{{ $language }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M10 12l-6-6h12l-6 6z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full md:w-1/2">
                        <p class="font-medium text-base text-default mb-2 ml-2">Region</p>
                        <div class="relative">
                            <select name="country" class="appearance-none w-full border border-default-lighter rounded-1.5lg px-4 py-2 bg-white hover:border-primary outline-primary">
                                <option selected value="GB">{{ \App\Libs\CountriesHelper\Countries::getFullName('GB') }}</option>
                                @foreach(\App\Libs\CountriesHelper\Countries::getOptions() as $key => $country)
                                    <option @if($document->country == $key) selected @endif value="{{ $key }}">{{ $country }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M10 12l-6-6h12l-6 6z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center">
                    <button type="submit" class="bg-primary rounded-4.5xl hover:bg-primary-darker px-8 py-4 text-white w-1/2 mx-auto my-4">UPDATE</button>
                </div>
            </form>
            <div x-data="{delete_account:false}">
                <div @click="delete_account=!delete_account"
                     class="flex flex-row py-6 items-center border-t border-main-background">
                    <button type="submit"
                            class="px-4 py-2 text-base font-light border border-default-lighter rounded-4.5xl hover:bg-main-background">
                        <i class="fa-solid fa-trash-can mr-2"></i>
                        Delete document
                    </button>
                </div>
                <div
                    x-cloak x-show="delete_account"
                    tabindex="-1" aria-hidden="true"
                    class="fixed top-0 left-0 right-0 h-screen bg-gray-400 bg-opacity-50 z-50 p-4 overflow-x-hidden overflow-y-auto md:inset-0"
                    aria-modal="true" role="dialog">
                    <div @click.outside="delete_account=false"
                         class="relative max-w-2xl max-h-full mx-auto mt-48">
                        <!-- Modal content -->
                        <div
                            class="relative bg-white rounded-lg shadow px-10 py-4">
                            <!-- Modal header -->
                            <div class="flex items-start justify-between py-2 rounded-t">
                                <h3 class="font-semibold text-2xl my-2">
                                    Delete document
                                </h3>
                            </div>
                            <!-- Modal body -->
                            <div class="space-y-6 ">
                                <p class="text-base font-light">Are you absolutely sure you want to delete your document?
                                    All information will be lost, there is no way back!</p>
                            </div>
                            <!-- Modal footer -->
                            <div class="flex justify-end items-center py-6 space-x-2">
                                <button @click="delete_account=false" data-modal-hide="modal_report" type="button"
                                        class="text-primary hover:bg-gray-300 font-medium rounded-full text-base px-5 py-2.5 text-center">
                                    Cancel
                                </button>
                                <a href="{{ route('frontend_v4.users.delete_document', ['id' => $document->id]) }}" @click="delete_account=false" data-modal-hide="modal_report" type="button"
                                        class="w-24 text-white bg-primary hover:bg-primary-darker disabled:opacity-40 hover:bg-opacity-70 rounded-full border border-gray-200 text-base font-medium px-5 py-2.5 focus:z-10">
                                    Delete
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
