<div
    class="border border-slate-300 rounded-1.5lg px-6 py-4 lg:py-6 flex flex-col justify-start items-start hover:bg-yellow-300 hover:bg-opacity-10">
    <a href="{{ route('document.category.list', ['slug' =>$category->slug]) }}" class="flex mb-2 md:mb-5">
        <i class="fa-solid fa-graduation-cap mt-1 text-secondary mr-2"></i>
        <p class="font-medium text-base text-default line-clamp-2">{{ $category->name }}</p>
    </a>
    <p class="text-base text-default ml-6">{{ rand(1,500) }} documents</p>
</div>
