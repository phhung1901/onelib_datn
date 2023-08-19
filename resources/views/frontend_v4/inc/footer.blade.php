<footer x-cloak x-show="!open_comment_responsive" class="pt-10 pb-4 px-4 bg-white border-t border-[#cbcccc]">
    <div class="container mx-auto flex flex-wrap gap-6 justify-between">
        <button class="block md:hidden shadow-btn font-medium rounded-full px-5 py-2 inline-flex items-center justify-center gap-1">
            <i class="fa fa-globe"></i>
            English (US)
        </button>
        <div class="md:block hidden basis-full md:basis-2/12">
            <img class="mx-auto lg:mx-0 w-[104px] object-cover"
                src="{{ asset('assets_v4/images/libshare-png-2.png') }}" alt="">
        </div>
        <div class="basis-full md:basis-9/12 flex flex-col md:flex-row flex-wrap justify-between gap-3">
            <div class=" md:basis-1/5 mb-4">
                <h2 class="font-medium text-lg mb-2">Company</h2>
                <div class="flex flex-col gap-1">
                    <a href="#" class="hover:underline">About</a>
                    <a href="#" class="hover:underline">Scholarships</a>
                    <a href="#" class="hover:underline">Blog</a>
                    <a href="#" class="hover:underline">Sitemap</a>
                </div>
            </div>
            <div class=" md:basis-1/5 mb-4">
                <h2 class="font-medium text-lg mb-2">Contact &amp; Help</h2>
                <div class="flex flex-col gap-1">
                    <a href="" class="hover:underline">Contact us</a>
                    <a href="" class="hover:underline">F.A.Q</a>
                    <a href="" class="hover:underline">Feedback</a>
                </div>
            </div>

            <div class=" md:basis-1/5 sm:mb-4">
                <h2 class="font-medium text-lg mb-2">Legal</h2>
                <div class="flex flex-col gap-1">
                    <a href="" class="hover:underline">Terms</a>
                    <a href="" class="hover:underline">Privacy Policy</a>
                    <a href="" class="hover:underline">Cookie Statement</a>
                </div>
            </div>
            <div class="md:basis-1/5 flex md:flex-col gap-2 md:items-end justify-center md:justify-start mb-3">
                <a href="#" class="inline-block w-[104px] h-10 bg-black rounded">
                    <img class="max-w-full object-cover" src="{{ asset('assets_v4/images/appstore.png') }}"
                        alt="">
                </a>
                <a href="#" class="inline-block w-[104px] h-10 bg-black rounded">
                    <img class="max-w-full object-cover" src="{{ asset('assets_v4/images/google-play-badge.png') }}"
                        alt="">
                </a>
            </div>
            <div class="block md:hidden  md:basis-1/5  sm:flex-col gap-2 justify-center sm:items-end">
                <img class="mx-auto lg:mx-0 w-[104px] object-cover"
                    src="{{ asset('assets_v4/images/libshare-png-2.png') }}" alt="">
            </div>
        </div>
        <div class="basis-full md:basis-2/5 flex gap-6 justify-between md:justify-start">
            <a href="#">
                <i class="fa-brands fa-facebook"></i>
            </a>
            <a href="#">
                <i class="fa-brands fa-instagram"></i>
            </a>
            <a href="#">
                <i class="fa-brands fa-linkedin"></i>
            </a>
            <a href="#">
                <i class="fa-brands fa-tiktok"></i>
            </a>
            <a href="#">
                <i class="fa-brands fa-youtube"></i>
            </a>
            <a href="#">
                <i class="fa-brands fa-twitter"></i>
            </a>
        </div>
        <div class="basis-full md:basis-2/5 lg:text-right text-center">
            <p class="text-sm">
                Copyright libshare.xyz © 2023
            </p>
        </div>
    </div>
</footer>
