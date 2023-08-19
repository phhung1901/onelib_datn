@php
    $directory = 'assets_v4/images/documents';
    $files = \Illuminate\Support\Facades\File::files($directory);
    $randomFile = $files[array_rand($files)];

@endphp
<a href="{{ route('document.detail', ['slug' => $document->slug]) }}" class="group">
    <div
        class="border border-slate-300 border-solid rounded-1.5lg px-1 lg:px-2 pt-1 pb-2 mb-1 md:mb-2.5 group-hover:shadow-card ">
        <div class="aspect-[5/4] md:max-h-[150px] overflow-hidden w-full">
            <img src="{{ $randomFile }}" alt="" class="w-full max-w-full object-top">
        </div>

        <div class=" mt-2 md:mt-0 px-2 lg:px-3.5 lg:pt-5">
            <div class="mb-1 md:mb-5">
                <h2 class="text-primary font-semibold text-sm md:text-base line-clamp-2 min-h-[40px] md:min-h-[48px]">
                    {{ $document->title }}
                </h2>
                <p class="text-default-lighter font-thin text-sm py-2 line-clamp-1">{{ $document->categories->name }}</p>
            </div>
            <div class="flex justify-between text-default-lighter font-light text-xs lg:text-base ">
                <div class="inline-flex items-start md:items-center gap-1">
                    <i class="fa-solid fa-file"></i>
                    <span>{{ $document->page_number }}</span>
                </div>
                <div class="inline-flex items-center gap-1">
                    <i class="fa-solid fa-cloud-arrow-down"></i>
                    <span>{{ $document->downloaded_count }}</span>
                </div>
                <div class="inline-flex items-center gap-1">
                    <i class="fa-solid fa-eye"></i>
                    <span>{{ $document->viewed_count }}</span>
                </div>
            </div>
        </div>
    </div>
{{--    @if ($showNumberLike)--}}
{{--        <div--}}
{{--            class="text-default-lighter flex justify-center gap-1 items-center border border-solid border-slate-300 w-full rounded-4xl lg:py-2 text-xs lg:text-base group-hover:shadow-card">--}}
{{--            <i class="fa-solid fa-thumbs-up text-secondary"></i>--}}
{{--            <span class="text-default-lighter font-light lg:font-medium leading-6">100%</span>--}}
{{--        </div>--}}
{{--    @endif--}}
</a>
