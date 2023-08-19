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

            <p class="font-semibold text-2xl py-6">Setting</p>
            <form method="POST" action="{{ route('frontend_v4.users.postSetting', $user->id) }}" enctype="multipart/form-data" class="flex flex-col mb-8 gap-6 border-t border-main-background">
                {{ csrf_field() }}
                <p class="font-semibold text-xl py-6 text-default">Account</p>
                <div class="flex flex-col md:flex-row w-full gap-4 md:gap-12">
                    <div class="flex flex-col w-full">
                        <p class="font-medium text-base text-default mb-2 ml-2">Name</p>
                        <input type="text" name="user_name" value="{{ $user->name }}" class="border border-default-lighter rounded-1.5lg px-4 py-2 hover:border-primary outline-primary">
                    </div>

                    <div class="flex flex-col w-full">
                        <p class="font-medium text-base text-default mb-2 ml-2">Avatar</p>
                        <input type="file" name="user_avatar">
                    </div>
                </div>

                <div class="flex flex-col md:flex-row w-full gap-4 md:gap-12">
                    <div class="flex flex-col w-full md:w-1/2">
                        <p class="font-medium text-base text-default mb-2 ml-2">Language</p>
                        <div class="relative">
                            <select name="language" class="appearance-none w-full border border-default-lighter rounded-1.5lg px-4 py-2 bg-white hover:border-primary outline-primary">
                                <option selected value="en">{{ \App\Libs\CountriesHelper\Languages::getFullName('en') }}</option>
                                @foreach(\App\Libs\CountriesHelper\Languages::getOptions() as $key => $language)
                                    <option value="{{ $key }}">{{ $language }}</option>
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
                                    <option value="{{ $key }}">{{ $country }}</option>
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

                <div class="flex flex-col md:flex-row w-full gap-4 md:gap-12">
                    <div class="flex flex-col w-full md:w-1/2">
                        <p class="font-medium text-base text-default mb-2 ml-2">Email address</p>
                        <input type="email"  name="user_email" disabled value="{{ $user->email }}" class="bg-gray-200 border border-default-lighter rounded-1.5lg px-4 py-2">
                    </div>
                    <div class="flex flex-col w-full md:w-1/2">
                        <p class="font-medium text-base text-default mb-2 ml-2">Phone number</p>
                        <input type="tel" name="user_phone" placeholder="Phone number" value="{{ $user->phone }}" class="border border-default-lighter rounded-1.5lg px-4 py-2 hover:border-primary outline-primary">
                    </div>
                </div>

                <button type="submit" class="bg-primary rounded-4.5xl hover:bg-primary-darker px-4 py-2 text-white w-3/12 md:w-2/12 lg:w-1/12">Save</button>
            </form>

            <form method="POST" action="{{ route('frontend_v4.users.postChangePass', $user->id) }}" class="flex flex-col mb-8 gap-6 border-t border-main-background">
                {{ csrf_field() }}

                <p class="font-semibold text-xl py-6 text-default">Change password</p>
                <div class="flex flex-col">
                    <div class="flex flex-col md:flex-row w-full gap-4 md:gap-12">
                        <div class="flex flex-col w-full md:w-1/2">
                            <p class="font-medium text-base text-default mb-2 ml-2">Current password</p>
                            <input type="password" id="current_password" name="current_password" placeholder="Current password" class="border border-default-lighter rounded-1.5lg px-4 py-2 hover:border-primary outline-primary">
                            @error('current_password')
                            <span class="text-red-500 mt-3 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col w-full md:w-1/2">
                            <p class="font-medium text-base text-default mb-2 ml-2">New password</p>
                            <input type="password" name="new_password" placeholder="New password" class="border border-default-lighter rounded-1.5lg px-4 py-2 hover:border-primary outline-primary">
                            @error('new_password')
                            <span class="text-red-500 mt-3 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col w-full md:w-1/2">
                            <p class="font-medium text-base text-default mb-2 ml-2">Confirm new password</p>
                            <input type="password" name="new_password_confirmation" placeholder="Password confirm" class="border border-default-lighter rounded-1.5lg px-4 py-2 hover:border-primary outline-primary">
                        </div>
                    </div>
                </div>

                <button type="submit" class="bg-primary rounded-4.5xl hover:bg-primary-darker px-4 py-2 text-white w-3/12 md:w-2/12 lg:w-1/12">Save</button>
            </form>

{{--            <div class="flex flex-col mb-8 gap-6 border-t border-main-background">--}}
{{--                <p class="font-semibold text-xl py-6 text-default">Email settings</p>--}}
{{--                <div class="flex gap-4">--}}
{{--                    <input type="checkbox" name="" id="email_setting">--}}
{{--                    <label class="text-default font-light text-base" for="email_setting">I'm ok with receiving email about my uploads, recommendations, updates, promotions and more</label>--}}
{{--                </div>--}}

{{--                <button type="submit" class="bg-primary rounded-4.5xl hover:bg-primary-darker px-4 py-2 text-white w-3/12 md:w-2/12 lg:w-1/12">Save</button>--}}
{{--            </div>--}}

{{--            <div class="flex flex-col mb-8 gap-6 border-t border-main-background">--}}
{{--                <p class="font-semibold text-xl py-6 text-default">Social</p>--}}
{{--                <div class="flex gap-4">--}}
{{--                    <input type="checkbox" name="" id="social_setting" checked>--}}
{{--                    <label class="text-default font-light text-base" for="social_setting">Use social profile picture</label>--}}
{{--                </div>--}}

{{--                <button type="submit" class="bg-primary rounded-4.5xl hover:bg-primary-darker px-4 py-2 text-white w-3/12 md:w-2/12 lg:w-1/12">Save</button>--}}
{{--            </div>--}}

{{--            <div x-data="{delete_account:false}">--}}
{{--                <div @click="delete_account=!delete_account" class="flex flex-row py-6 items-center border-t border-main-background">--}}
{{--                    <button type="submit" class="px-4 py-2 text-base font-light border border-default-lighter rounded-4.5xl hover:bg-main-background">--}}
{{--                        <i class="fa-solid fa-trash-can mr-2"></i>--}}
{{--                        Delete account--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                <div--}}
{{--                    x-data="{ modal_delete_account: true }" x-cloak x-show="delete_account"--}}
{{--                    tabindex="-1" aria-hidden="true"--}}
{{--                    class="fixed top-0 left-0 right-0 h-screen bg-gray-400 bg-opacity-50 z-50 p-4 overflow-x-hidden overflow-y-auto md:inset-0"--}}
{{--                    aria-modal="true" role="dialog">--}}
{{--                    <div @click.outside="delete_account=false; modal_delete_account=true"--}}
{{--                         class="relative max-w-2xl max-h-full mx-auto mt-48">--}}
{{--                        <!-- Modal content -->--}}
{{--                        <div--}}
{{--                            class="relative bg-white rounded-lg shadow px-10 py-4">--}}
{{--                            <!-- Modal header -->--}}
{{--                            <div class="flex items-start justify-between py-2 rounded-t">--}}
{{--                                <h3 class="font-semibold text-2xl my-2">--}}
{{--                                    Delete account--}}
{{--                                </h3>--}}
{{--                            </div>--}}
{{--                            <!-- Modal body -->--}}
{{--                            <div class="space-y-6 ">--}}
{{--                                <p class="font-light text-base">Are you absolutely sure you want to delete your account? All information will be lost, there is no way back!</p>--}}
{{--                            </div>--}}
{{--                            <!-- Modal footer -->--}}
{{--                            <div class="flex justify-end items-center p-6 space-x-2">--}}
{{--                                <button @click="delete_account=false" data-modal-hide="modal_report" type="button"--}}
{{--                                        class="text-primary hover:bg-gray-300 font-medium rounded-full text-base px-5 py-2.5 text-center">--}}
{{--                                    Cancel--}}
{{--                                </button>--}}
{{--                                <button @click="delete_account=false" data-modal-hide="modal_report" type="button"--}}
{{--                                        class="w-24 text-white bg-primary hover:bg-primary-darker disabled:opacity-40 hover:bg-opacity-70 rounded-full border border-gray-200 text-base font-medium px-5 py-2.5 focus:z-10">--}}
{{--                                    Delete--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

        </div>
    </div>
@endsection
