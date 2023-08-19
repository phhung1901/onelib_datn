@php
    $directory = 'assets_v4/images/documents';
    $files = \Illuminate\Support\Facades\File::files($directory);
    $randomFile = $files[array_rand($files)];
@endphp

<a href="{{ route('document.detail', ['slug' => $document->slug]) }}"
   class="w-full h-96">
    <div
        class="flex flex-col gap-4 border border-slate-300 border-solid rounded-lg px-8 py-5 hover:shadow-card h-full hover:shadow-xl">
        <div class="aspect-[3/4] bg-slate-100 h-1/2 lg:h-1/3 xl:h-1/2">
            <img src="{{ $randomFile }}" alt=""
                 class="w-full h-full object-cover">
        </div>
        <div class="grow flex flex-col justify-around   ">
            <h2 class="mr-34 h-12 text-primary font-semibold line-clamp-1 md:line-clamp-2 lg:line-clamp-3">{{ $document->title }}</h2>
            <p class="mr-3 h-20 text-default font-thin text-sm my-2 line-clamp-2 md:line-clamp-3 lg:line-clamp-4">{{ $document->description }}</p>

            <div class="flex gap-10 md:gap-6 lg:gap-10 font-light text-default-lighter">
                <div class="inline-flex items-center gap-2">
                    <i class="fa-solid fa-file"></i>
                    <span>{{ $document->page_number }}</span>

                </div>
                <div class="inline-flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-down"></i>
                    <span>{{ $document->downloaded_count }}</span>

                </div>
                <div class="inline-flex items-center gap-2">
                    <i class="fa-solid fa-eye"></i>
                    <span>{{ $document->viewed_count }}</span>

                </div>
            </div>

        </div>

    </div>
</a>
