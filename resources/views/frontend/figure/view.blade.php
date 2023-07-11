<div class="bg-white mt-4 mx-4 lg:mx-0 rounded-2lg pb-8">
    <p class="font-semibold text-xl md:pl-14 pl-7 pt-10 text-text-default-darker mb-3">Figure</p>
    <div class="md:mx-11 mx-6 py-5 px-7 border border-search rounded-2lg">
            @for($i = 0; $i <= 3 ; $i++)
            <div class="w-full mb-10 flex justify-center flex-col">
                <img class="h-auto max-w-full mx-auto border lazy-load lazy loaded"
                     src="{{ asset('images/document/figure1.webp')}}"
                     alt="image description">
                <figcaption class="mt-3 inline-block italic">
                    <p class="font-bold inline-block">Hình 2.1. </p>
                    Struktur Organisasi
                    <i class="text-primary text-sm inline-block">p.25</i>
                </figcaption>
            </div>
            @endfor
    </div>
</div>
