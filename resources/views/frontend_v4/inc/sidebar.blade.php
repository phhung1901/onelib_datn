<div x-cloak x-show="open_sidebar"
     class="modal fixed z-50 top-0 h-screen w-full bg-gray-400 bg-opacity-50  text-gray-500 font-light">
    <nav id="modal_sidebar_content" x-show="open_sidebar" @click.outside="open_sidebar = false"
         x-transition:enter="-translate-x-full ease-out duration-100"
         x-transition:leave="-translate-x-full ease-in duration-100"
         class="flex flex-col bg-white h-full py-4 pl-4 max-w-xs transition-all ease-in-out ">
        <div class="flex gap-4 items-center justify-between px-3">
            <div class="flex gap-4 items-center">
                <i class="fa-solid fa-xl fa-bars cursor-pointer" @click="open_sidebar = false"></i>
                <img src="{{ asset('assets_v4/images/libshare-png-2.png') }}" alt=""
                     class="max-h-12">
            </div>
        </div>
        <div id="sidebar-content"
             class="grow pr-4 overflow-y-scroll scrollbar mt-8">
            @if(Auth::check() === true)
                <div class="p-3 flex items-center ">
                    <img class="w-[40px] rounded-full"
                         src="https://lh3.googleusercontent.com/a/AEdFTp45mrUSLMGlduYjPfK7FMlyXLIvTKw8WS5gri6LyQ=s96-c"
                         alt="Student" loading="lazy" data-initials="GN">
                    <a href="#" class="text-base text-text-default ml-2">{{ Auth::user()->name}}</a>
                </div>
            @endif

            <a href="{{ route('document.home.index') }}"
               class="flex items-center gap-4 rounded-xl p-3 mt-1 hover:text-primary hover:bg-green-100">
                <i class="fa-solid fa-house text-primary"></i>
                <span class="text-base">Home</span>
            </a>
            {{--            <a href="#" class="flex items-center gap-4 rounded-xl p-3 mt-1 hover:text-primary hover:bg-green-100">--}}
            {{--                <i class="fa-solid fa-circle-question text-primary"></i>--}}
            {{--                <span class="text-base">Q.A</span>--}}
            {{--            </a>--}}
            <p class="rounded-xl p-3 mt-1 border-2 border-white font-semibold">
                My Library
            </p>
            {{--            <div x-data="{ open_dropdown: false }">--}}
            {{--                <div @click="open_dropdown= !open_dropdown" :class="open_dropdown && 'text-primary bg-green-100'"--}}
            {{--                     class=" flex justify-between items-center rounded-xl p-3 mt-1 hover:text-primary hover:bg-green-100 cursor-default select-none">--}}
            {{--                    <div class="flex items-center gap-4">--}}
            {{--                        <i class="fa-solid fa-file-arrow-down text-primary"></i>--}}
            {{--                        <span class="text-base">Downloaded documents</span>--}}
            {{--                    </div>--}}

            {{--                    <i x-show="!open_dropdown" class="fa-solid fa-chevron-down"></i>--}}
            {{--                    <i x-show="open_dropdown" class="fa-solid fa-chevron-up"></i>--}}
            {{--                </div>--}}
            {{--                <ul x-cloak x-show="open_dropdown" @click.outside="open_dropdown=false"--}}
            {{--                    class=" flex flex-col gap-4 px-4 max-w-full py-2  h-max">--}}
            {{--                    <li>--}}
            {{--                        <a href="#"--}}
            {{--                           class="font-thin text-text-default hover:text-primary hover:underline hover:decoration-1 decoration-primary">You--}}
            {{--                            don't have any document download yet</a>--}}
            {{--                    </li>--}}
            {{--                </ul>--}}
            {{--            </div>--}}
            <div x-data="{recentlyDocuments: [], open_dropdown: false }"
                 x-init="recentlyDocuments = JSON.parse(localStorage.getItem('v4_viewed_documents'))"
            >
                <div @click="open_dropdown= !open_dropdown" :class="open_dropdown && 'text-primary bg-green-100'"
                     class=" flex items-center justify-between rounded-xl p-3 mt-1 hover:text-primary hover:bg-green-100 cursor-default select-none">
                    <div class="flex items-center gap-4">
                        <i class="fa-solid fa-clock text-primary"></i>
                        <span class="text-base">Recent documents</span>
                    </div>

                    <i x-show="!open_dropdown" class="fa-solid fa-chevron-down"></i>
                    <i x-show="open_dropdown" class="fa-solid fa-chevron-up"></i>
                </div>
                <ul x-cloak x-show="open_dropdown" @click.outside="open_dropdown=false"
                    class="px-4 max-w-full py-2 h-max">
                    <template x-for="document in recentlyDocuments">
                        <a :href="document.url"
                           class="mb-4 last-of-type:mb-0 font-light  hover:text-primary hover:underline hover:decoration-1 decoration-primary line-clamp-2"
                           x-text="document.title"></a>
                    </template>
                    <p x-cloak x-show="!recentlyDocuments" class="font-light">
                        You don’t have any documents yet
                    </p>
                </ul>
            </div>
            @if(Auth::check())
                <a href="{{ route('frontend_v4.users.document_upload', ['id' => Auth::id()]) }}" class="flex items-center gap-4 rounded-xl p-3 mt-1 hover:text-primary hover:bg-green-100">
                    <i class="fa-solid fa-cloud-arrow-up text-primary"></i>
                    <span class="text-base">Uploads</span>
                </a>
            @endif
        </div>
    </nav>
</div>
