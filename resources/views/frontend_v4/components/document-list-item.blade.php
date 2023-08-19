@php
    $directory = 'assets_v4/images/documents';
    $files = \Illuminate\Support\Facades\File::files($directory);
    $randomFile = $files[array_rand($files)];

@endphp

<div
    class="w-full flex flex-col md:flex-row border border-solid border-slate-300 hover:border-transparent hover:shadow-hover  md:mb-0 gap-2.5 md:gap-4 lg:gap-8 p-2 rounded-2.5xl text-default-lighter">
    <a href="{{ route('document.detail', ['slug' => $document->slug]) }}"
       class="inline-block md:w-3/12 lg:w-2/12 w-full h-[180px] md:h-[150px] rounded-1.5lg self-center overflow-hidden">
        <img class=" w-full object-top  rounded-1.5lg " src="{{ asset($randomFile) }}" alt="">
    </a>
    <div class="gap-4 px-5 md:px-2 md:w-9/12 lg:w-10/12 flex flex-1 rounded-bl rounded-br md:py-2">
        <div class="flex flex-col gap-y-0 md:gap-y-5 lg:justify-between grow ">
            <a href="{{ route('document.detail', ['slug' => $document->slug]) }}"
               class="font-medium md:font-semibold text-primary text-sm lg:text-base line-clamp-2  mb-2 md:mb-0">
                {{ $document->title }} </a>
            <div class="flex flex-col md:flex-row gap-2 md:gap-4 lg:gap-8 text-xs lg:text-base">
                <p class=" order-1 inline-flex gap-1.5 lg:gap-2 items-center min-w-max lg:flex-grow-0 hover:text-primary">
                    <i class="fa-solid fa-user w-4"></i>
                    <span class="">{{ $document->user->name }}</span>
                </p>
                <a href="{{ route('document.category.list', ['slug' => $document->categories->slug]) }}" class="flex gap-1.5 lg:gap-2 items-center order-3 md:order-2 hover:text-primary">
                    <i class="fa-solid fa-book w-4"></i>
                    <span class="line-clamp-1">{{ $document->categories->name }}</span>
                </a>
            </div>

            <div class="flex gap-2 mt-2 md:mt-0 md:gap-16 flex-col md:flex-row text-xs lg:text-base ">
                <div class="flex items-center gap-6">
                    <div class="inline-flex items-start md:items-center gap-1">
                        <svg class="fill-default-lighter w-3 h-4 lg:h-[18px] lg:w-4 -translate-y-0.5"
                             viewBox="0 0 14 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 17.3333C12.442 17.3333 12.866 17.1577 13.1785 16.8452C13.4911 16.5326 13.6667 16.1087 13.6667 15.6667V5.66666L8.66668 0.666656H2.00001C1.55798 0.666656 1.13406 0.842251 0.821499 1.15481C0.508938 1.46737 0.333344 1.8913 0.333344 2.33332V15.6667C0.333344 16.1087 0.508938 16.5326 0.821499 16.8452C1.13406 17.1577 1.55798 17.3333 2.00001 17.3333H12ZM7.83334 2.33332L12 6.49999H7.83334V2.33332ZM2.83334 5.66666H5.33334V7.33332H2.83334V5.66666ZM2.83334 8.99999H11.1667V10.6667H2.83334V8.99999ZM2.83334 12.3333H11.1667V14H2.83334V12.3333Z"
                                fill="" />
                        </svg>
                        <span>{{ $document->page_number }}</span>
                    </div>
                    <div class="inline-flex gap-1 items-center ">
                        <i class="fa-solid fa-cloud-arrow-down"></i>
                        <span>{{ $document->downloaded_count }}</span>
                    </div>
                    <div class="inline-flex gap-1 items-center">
                        <i class="fa-solid fa-eye"></i>
                        <span>{{ $document->viewed_count }}</span>
                    </div>
                </div>
                <div class="flex justify-between">
                    <div class="inline-flex gap-1 items-center">
                        <svg class="fill-default-lighter w-4 h-4 lg:h-5 lg:w-5 md:-translate-y-0.5" viewBox="0 0 20 21"
                             fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M1.66669 7.99996C1.66669 6.7538 1.66669 6.13073 1.93464 5.66663C2.11017 5.36259 2.36265 5.11011 2.66669 4.93457C3.13079 4.66663 3.75387 4.66663 5.00002 4.66663H15C16.2462 4.66663 16.8693 4.66663 17.3334 4.93457C17.6374 5.11011 17.8899 5.36259 18.0654 5.66663C18.3334 6.13073 18.3334 6.7538 18.3334 7.99996C18.3334 8.3115 18.3334 8.46727 18.2664 8.58329C18.2225 8.6593 18.1594 8.72242 18.0834 8.76631C17.9673 8.83329 17.8116 8.83329 17.5 8.83329H2.50002C2.18848 8.83329 2.03271 8.83329 1.91669 8.76631C1.84068 8.72242 1.77756 8.6593 1.73367 8.58329C1.66669 8.46727 1.66669 8.3115 1.66669 7.99996Z"
                                fill="" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M1.66669 14.8333C1.66669 16.719 1.66669 17.6618 2.25247 18.2475C2.83826 18.8333 3.78107 18.8333 5.66669 18.8333H14.3334C16.219 18.8333 17.1618 18.8333 17.7476 18.2475C18.3334 17.6618 18.3334 16.719 18.3334 14.8333V11.5C18.3334 11.0286 18.3334 10.7929 18.1869 10.6464C18.0405 10.5 17.8048 10.5 17.3334 10.5H2.66669C2.19528 10.5 1.95958 10.5 1.81313 10.6464C1.66669 10.7929 1.66669 11.0286 1.66669 11.5V14.8333ZM5.83335 13C5.83335 12.6885 5.83335 12.5327 5.90034 12.4167C5.94423 12.3407 6.00734 12.2775 6.08335 12.2337C6.19938 12.1667 6.35515 12.1667 6.66669 12.1667H8.33335C8.64489 12.1667 8.80066 12.1667 8.91669 12.2337C8.9927 12.2775 9.05582 12.3407 9.0997 12.4167C9.16669 12.5327 9.16669 12.6885 9.16669 13C9.16669 13.3115 9.16669 13.4673 9.0997 13.5833C9.05582 13.6593 8.9927 13.7225 8.91669 13.7663C8.80066 13.8333 8.64489 13.8333 8.33335 13.8333H6.66669C6.35515 13.8333 6.19938 13.8333 6.08335 13.7663C6.00734 13.7225 5.94423 13.6593 5.90034 13.5833C5.83335 13.4673 5.83335 13.3115 5.83335 13ZM5.90034 15.75C5.83335 15.866 5.83335 16.0218 5.83335 16.3333C5.83335 16.6449 5.83335 16.8006 5.90034 16.9167C5.94423 16.9927 6.00734 17.0558 6.08335 17.0997C6.19938 17.1667 6.35515 17.1667 6.66669 17.1667H8.33335C8.64489 17.1667 8.80066 17.1667 8.91669 17.0997C8.9927 17.0558 9.05582 16.9927 9.0997 16.9167C9.16669 16.8006 9.16669 16.6449 9.16669 16.3333C9.16669 16.0218 9.16669 15.866 9.0997 15.75C9.05582 15.674 8.9927 15.6109 8.91669 15.567C8.80066 15.5 8.64489 15.5 8.33335 15.5H6.66669C6.35515 15.5 6.19938 15.5 6.08335 15.567C6.00734 15.6109 5.94423 15.674 5.90034 15.75ZM10.8334 13C10.8334 12.6885 10.8334 12.5327 10.9003 12.4167C10.9442 12.3407 11.0073 12.2775 11.0834 12.2337C11.1994 12.1667 11.3551 12.1667 11.6667 12.1667H13.3334C13.6449 12.1667 13.8007 12.1667 13.9167 12.2337C13.9927 12.2775 14.0558 12.3407 14.0997 12.4167C14.1667 12.5327 14.1667 12.6885 14.1667 13C14.1667 13.3115 14.1667 13.4673 14.0997 13.5833C14.0558 13.6593 13.9927 13.7225 13.9167 13.7663C13.8007 13.8333 13.6449 13.8333 13.3334 13.8333H11.6667C11.3551 13.8333 11.1994 13.8333 11.0834 13.7663C11.0073 13.7225 10.9442 13.6593 10.9003 13.5833C10.8334 13.4673 10.8334 13.3115 10.8334 13ZM10.9003 15.75C10.8334 15.866 10.8334 16.0218 10.8334 16.3333C10.8334 16.6449 10.8334 16.8006 10.9003 16.9167C10.9442 16.9927 11.0073 17.0558 11.0834 17.0997C11.1994 17.1667 11.3551 17.1667 11.6667 17.1667H13.3334C13.6449 17.1667 13.8007 17.1667 13.9167 17.0997C13.9927 17.0558 14.0558 16.9927 14.0997 16.9167C14.1667 16.8006 14.1667 16.6449 14.1667 16.3333C14.1667 16.0218 14.1667 15.866 14.0997 15.75C14.0558 15.674 13.9927 15.6109 13.9167 15.567C13.8007 15.5 13.6449 15.5 13.3334 15.5H11.6667C11.3551 15.5 11.1994 15.5 11.0834 15.567C11.0073 15.6109 10.9442 15.674 10.9003 15.75Z"
                                  fill="" />
                            <path d="M5.83331 3L5.83331 5.5" stroke="#99999A" stroke-width="2" stroke-linecap="round" />
                            <path d="M14.1667 3L14.1667 5.5" stroke="#99999A" stroke-width="2" stroke-linecap="round" />
                        </svg>
                            <span class="inline-block translate-y-0.5 md:-translate-y-[1px] lg:translate-y-0">{{ $document->created_at->year }}</span>
                    </div>
                    <div class=" md:hidden items-baseline gap-1 text-secondary">
                        <i class="fa-solid fa-thumbs-up"></i>
                        <span class="">{{ $document->helpful_count }}</span>
                    </div>
                </div>

            </div>
        </div>
        <div class="hidden md:flex items-baseline gap-2.5 text-secondary">
            <i class="fa-solid fa-thumbs-up"></i>
            <span class="">{{ $document->helpful_count }}</span>
        </div>
    </div>
</div>
