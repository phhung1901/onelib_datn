<header id="header" class="w-full px-4 bg-white border-b border-gray-200">
    <div class="container mx-auto flex items-center justify-between">
        <div class="py-4 flex flex-1 gap-4 items-center justify-start md:justify-start">
            <i class="fa-solid fa-xl fa-bars cursor-pointer" @click="open_sidebar = true"></i>
            <a href="{{ route('document.home.index') }}" class=" hover:cursor-pointer">
                <img src="{{ asset('assets_v4/images/libshare-png-2.png') }}" alt=""
                     class="max-h-6 w-12 object-cover">
            </a>
            <form id="search_form" method="GET" action="{{ route('frontend_v4.document.search') }}"
                  class="hidden h-10 md:flex items-center container_search mx-4 lg:max-w-3xl lg:ml-10 relative border border-slate-300 rounded-4xl hover:border-primary grow group">
                <div class="grow flex items-center justify-between md:mr-3">
                    <input id="search_global" name="search"
                           class="search_global search rounded-4xl md:pl-6 w-full px-4 outline-none placeholder:text-base placeholder:font-thin placeholder:text-search peer"
                           type="text" placeholder="Search for documents, categories and other resources">
                </div>
                <button id="search_button" class="mr-4 text-gray-500 group-hover:text-primary" type="submit">
                    <i class=" fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        </div>


        <div class="flex gap-4 items-center">
            <button class="block md:hidden text-gray-500 hover:text-primary "
                    @click="open_search_responsive= !open_search_responsive ">
                <i class="text-base md:text-2xl fa-solid fa-magnifying-glass "></i>
            </button>
            <div x-data="{open_payment_content:false}">
                @if(Auth::check())
                    <button @click="open_payment_content=!open_payment_content"
                            class="shadow-btn font-medium rounded-full px-5 py-2 hidden md:inline-flex items-center justify-center gap-2 hover:bg-primary hover:text-white">
                        <i class="fa fa-globe"></i>
                        Payment
                    </button>
                    <div x-data="{open_payment_body:true,open_payment_vnpay:false,open_payment_paypal:false}" x-cloak
                         x-show="open_payment_content" tabindex="-1" aria-hidden="true"
                         class="fixed top-0 left-0 right-0 h-screen bg-gray-400 bg-opacity-50  z-50 p-4 overflow-x-hidden overflow-y-auto md:inset-0"
                         aria-modal="true" role="dialog">
                        <div @click.outside="open_payment_content = false;"
                             class="relative max-w-2xl max-h-full mx-auto mt-48">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow">
                                <!-- Modal header -->
                                <div class="flex items-start justify-between p-4 rounded-t">
                                    <h3 class="font-bold text-default text-xl mx-auto my-2">
                                        Please choose payment method
                                    </h3>
                                </div>
                                <!-- Modal body -->
                                <div x-show="open_payment_body" class="space-y-6 px-6 py-2">
                                    <div
                                        @click="open_payment_vnpay=true;open_payment_body=false;"
                                        class="flex flex-row gap-2 justify-center items-center rounded-4.5xl border border-[#AFAFAF] px-4 py-4 hover:bg-main-background cursor-pointer">
                                        <img src="{{ asset('assets_v4/images/logo/vnpay-logo-inkythuatso-01.png') }}"
                                             class="h-8 object-fill">
                                        <p class="text-default font-normal text-lg">Continue with VNPay</p>
                                    </div>

                                    <div
                                        @click="open_payment_paypal=true;open_payment_body=false;"
                                        class="flex flex-row gap-2 justify-center items-center rounded-4.5xl border border-[#AFAFAF] px-4 py-4 hover:bg-main-background cursor-pointer">
                                        <img src="{{ asset('assets_v4/images/logo/paypal_icon.png') }}"
                                             class="h-8 object-fill">
                                        <p class="text-default font-normal text-lg">Continue with Paypal</p>
                                    </div>

                                </div>
                                <div x-cloak x-show="open_payment_vnpay" class="space-y-6 px-6 py-2">
                                    <form method="post" action="{{ route('frontend_v4.postVNPay') }}"
                                          class="space-y-6">
                                        {{ csrf_field()}}
                                        <div @click="open_payment_vnpay=false;open_payment_body=true"
                                             class="flex flex-row items-center text-primary gap-3 cursor-pointer">
                                            <i class="fa-solid fa-arrow-left"></i>
                                            <p class="text-lg font-normal">Other payment option</p>
                                        </div>
                                        <div class="w-full">
                                            <p class="text-default text-base font-medium mb-3">Select the amount you
                                                want to deposit (VND)</p>
                                            <input type="number" name="price" id="price"
                                                   placeholder="Select the amount you want to deposit"
                                                   class="text-base font-medium rounded-1.5lg px-3 py-4 border border-main-background w-full hover:border-primary outline-primary">
                                        </div>

                                        <button type="submit" id="sign_in"
                                                class="w-full text-white font-normal text-lg mb-6 flex flex-row gap-2 justify-center items-center rounded-4.5xl px-4 py-2 bg-primary hover:bg-primary-darker">
                                            Continue
                                        </button>
                                    </form>

                                </div>
                                <div x-cloak x-show="open_payment_paypal" class="space-y-6 px-6 py-2">
                                    <form method="post" action="{{ route('frontend_v4.redirectPaypal') }}"
                                          class="space-y-6">
                                        {{ csrf_field()}}
                                        <div @click="open_payment_paypal=false;open_payment_body=true"
                                             class="flex flex-row items-center text-primary gap-3 cursor-pointer">
                                            <i class="fa-solid fa-arrow-left"></i>
                                            <p class="text-lg font-normal">Other payment option</p>
                                        </div>
                                        <div class="w-full">
                                            <p class="text-default text-base font-medium mb-3">Select the amount you
                                                want to deposit ($)</p>
                                            <input type="number" name="price_paypal" id="price_paypal"
                                                   placeholder="Select the amount you want to deposit"
                                                   class="text-base font-medium rounded-1.5lg px-3 py-4 border border-main-background w-full hover:border-primary outline-primary">
                                        </div>

                                        <button type="submit" id="sign_in"
                                                class="w-full text-white font-normal text-lg mb-6 flex flex-row gap-2 justify-center items-center rounded-4.5xl px-4 py-2 bg-primary hover:bg-primary-darker">
                                            Continue
                                        </button>
                                    </form>

                                </div>
                                <!-- Modal footer -->
                                <div class="flex justify-end items-center p-6 space-x-2">
                                    <button @click="open_payment_content=false" data-modal-hide="modal_content"
                                            type="button"
                                            class="text-primary bg-gray-100 hover:bg-gray-300 font-medium rounded-full text-base px-5 py-2.5 text-center">
                                        Cancel
                                    </button>
{{--                                    <button @click="open_report_content=false" data-modal-hide="modal_report"--}}
{{--                                            type="button"--}}
{{--                                            class="w-24 text-white bg-primary hover:bg-primary-darker disabled:opacity-40 hover:bg-opacity-70 rounded-full border border-gray-200 text-base font-medium px-5 py-2.5 focus:z-10">--}}
{{--                                        Send--}}
{{--                                    </button>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>


            @if(Auth::check() === true)
                <a href="{{ route('frontend_v4.users.getUpload') }}"
                   class="w-32 bg-primary text-white font-medium rounded-full px-5 py-2 hidden lg:inline-flex items-center justify-center gap-2 hover:bg-primary-darker">
                    <i class="fa fa-cloud-arrow-up"></i>
                    Upload
                </a>
            @else
                <a href="{{ route('frontend.auth.getLogin') }}"
                   class="w-32 bg-primary text-white font-medium rounded-full px-5 py-2 hidden lg:inline-flex items-center justify-center gap-2 hover:bg-primary-darker">
                    Login
                </a>
            @endif
            <div x-data="{ open: false }" class="relative">
                <div @click="open = !open " id="profile_icon" class="flex items-center hover:cursor-pointer">
                    <img class="w-10 h-10 rounded-full"
                         src="https://lh3.googleusercontent.com/a/AEdFTp45mrUSLMGlduYjPfK7FMlyXLIvTKw8WS5gri6LyQ=s96-c"
                         alt="Student" loading="lazy" data-initials="GN">
                    <i class="text-sm md:text-base fa-solid fa-chevron-down ml-2"></i>
                </div>
                <div x-cloak x-show="open" @click.outside="open = false" id="profiles"
                     class=" absolute top-[calc(100%+10px)] md:top-[calc(100%+16px)] right-0 w-80 border border-solid border-slate-300 rounded-1.5lg text-text-default  bg-white z-50">
                    <span
                        class=" -translate-x-[200%] md:block absolute w-4 h-4  rotate-45 right-0 z-10 bg-white border-t border-l border-solid border-slate-300 -translate-y-[calc(100%-7px)] )"></span>
                    <div class="  flex flex-col  rounded select-none">
                        @if(Auth::check())
                            <a href="{{ route('document.home.index') }}"
                               class="flex justify-between items-center rounded-tr-[10px] rounded-tl-[10px] hover:cursor-pointer group px-6 py-4 hover:bg-green-100 relative z-50">
                                <span class="font-thin  group-hover:text-primary ">Home</span>
                                <i class="fa-solid fa-house text-primary"></i>
                            </a>
                            <a href="{{ route('frontend_v4.users.profile', Auth::id()) }}"
                               class="flex justify-between items-center hover:cursor-pointer group px-6 py-4 hover:bg-green-100 hover:text-primary ">
                                <span class="font-thin ">Profile</span>
                                <i class="fa-solid fa-user text-primary"></i>
                            </a>
                            <a href="{{ route('frontend_v4.users.setting', Auth::id()) }}"
                               class="flex justify-between items-center hover:cursor-pointer group px-6 py-4 hover:bg-green-100 hover:text-primary">
                                <span class="font-thin ">Settings</span>
                                <i class="fa-solid fa-gear text-primary"></i>
                            </a>
                            <a href="{{ route('frontend_v4.users.document_upload', Auth::id()) }}"
                               class="flex justify-between items-center hover:cursor-pointer group px-6 py-4 hover:bg-green-100 hover:text-primary">
                                <span class="font-thin ">Uploads</span>
                                <i class="fa-solid fa-cloud-arrow-up text-primary"></i>
                            </a>
                            <a href="{{ route('frontend.auth.logout') }}"
                               class="flex justify-between items-center rounded-br-[10px] rounded-bl-[10px] border-t border-solid border-slate-300 hover:cursor-pointer group px-6 py-4 hover:bg-green-100 hover:text-primary">
                                <span class="font-thin">Sign out</span>
                                <i class="fa-solid fa-right-from-bracket text-primary"></i>
                            </a>
                        @else
                            <a href="{{ route('document.home.index') }}"
                               class="flex justify-between items-center rounded-tr-[10px] rounded-tl-[10px] hover:cursor-pointer group px-6 py-4 hover:bg-green-100 relative z-50">
                                <span class="font-thin  group-hover:text-primary ">Home</span>
                                <i class="fa-solid fa-house text-primary"></i>
                            </a>
                            <a href="{{ route('frontend.auth.getLogin') }}"
                               class="flex justify-between items-center rounded-br-[10px] rounded-bl-[10px] border-t border-solid border-slate-300 hover:cursor-pointer group px-6 py-4 hover:bg-green-100 hover:text-primary">
                                <span class="font-thin">Login</span>
                                <i class="fa-solid fa-right-to-bracket text-primary"></i>
                            </a>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- search bar responsive --}}
<div x-cloak x-show="open_search_responsive" class="p-4 md:hidden bg-white md:px-10">
    <form @click.outside="open_search_responsive = false" id="search_form_mobile" method="GET" action="{{ route('frontend_v4.document.search') }}"
         class="container mx-auto h-10  flex items-center container_search lg:ml-10 relative border border-slate-300 rounded-4xl hover:border-primary grow group">
        <div class="grow flex items-center justify-between md:mr-3">
            <input id="search_global_mobile" name="search"
                   class="search_global rounded-4xl md:pl-6 w-full px-4 outline-none placeholder:text-base md:placeholder:text-lg placeholder:font-thin placeholder:text-search peer"
                   type="text" placeholder="Search for documents, categories and other resources">
        </div>
        <button id="search_button_mobile" class="mr-4 group-hover:text-primary" type="submit">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </form>
</div>
