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

            <p class="font-semibold text-2xl py-6">Payment</p>
            <form method="POST" action="#" enctype="multipart/form-data" class="flex flex-col mb-8 gap-6 border-t border-main-background">
                {{ csrf_field() }}
                <p class="font-semibold text-xl py-6 text-default">Account</p>
                <div class="flex flex-col md:flex-row w-full gap-4 md:gap-12">
                    <div class="flex flex-col w-full">
                        <p class="font-medium text-base text-default mb-2 ml-2">Name</p>
                        <input type="text" name="user_name" value="Minh" class="border border-default-lighter rounded-1.5lg px-4 py-2 hover:border-primary outline-primary">
                    </div>

                    <div class="flex flex-col w-full">
                        <p class="font-medium text-base text-default mb-2 ml-2">Avatar</p>
                        <input type="file" name="user_avatar">
                    </div>
                </div>
                <button type="submit" class="bg-primary rounded-4.5xl hover:bg-primary-darker px-4 py-2 text-white w-3/12 md:w-2/12 lg:w-1/12">Save</button>
            </form>

        </div>
    </div>
@endsection
