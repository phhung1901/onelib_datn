<!DOCTYPE html>
<html>
<head>
    @include('frontend.partials.head')
</head>
<body>

@include('frontend.partials.header')

<!-- Title -->
<div class="bg-white mt-32">
    <div class="container mx-auto">
        <!-- Breadcrumbs -->
        @include('frontend.partials.breadcrumbs')

        <!-- Title -->
        @include('frontend.partials.title')
    </div>
</div>

<!-- View document -->
<div class="bg-search bg-opacity-20 mt-8 py-4">
    <div class="container mx-auto">
        <div class="flex md:flex-row flex-col mx-4 lg:mx-0">
            <!-- Column left -->
            @include('frontend.document.view')

            <!-- Column right -->
            <div class="md:ml-4 mt-8 md:mt-0 md:basis-3/12 flex flex-col">
                <!-- Comment -->
                @include('frontend.comment.create')

                <!-- Upload -->
                @include('frontend.document.upload')

                <!-- Related documents -->
                @include('frontend.document.related')
            </div>
        </div>

        <!-- Preview text -->
        @include('frontend.document.preview_text')

        <!-- Figure -->
        @include('frontend.figure.view')

        <!-- Reference -->
        @include('frontend.document.reference')

        <!-- Related Keywords -->
        @include('frontend.keyword.related_keyword')

        <!-- Students also viewed -->
        @include('frontend.top_view.top_view')

    </div>
</div>

@include('frontend.partials.footer')

<script src="{{ asset('assets/plugins/pdfjs/build/pdf.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>

{{-- Alpine js--}}
<script src="{{ asset('assets/alpine.js') }}"></script>

{{-- PDF JS --}}
@include('frontend.partials.scripts')

{{-- Style --}}
<script src="{{ asset('assets/main.js') }}"></script>
</body>
</html>
