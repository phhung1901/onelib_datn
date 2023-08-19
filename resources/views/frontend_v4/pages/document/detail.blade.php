@extends('frontend_v4.layouts.master')


@push('title')
    <title>Libshare - {{ $document->title }}</title>
    <meta name="description" content="{{ $document->title }}">
@endpush

@push('before_styles')
@endpush

@push('after_styles')
@endpush

@push('before_scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
            integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        function addDocumentToLocalStorage(document) {
            if (localStorage.getItem('v4_viewed_documents')) {
                let viewed_document = JSON.parse(localStorage.getItem('v4_viewed_documents'));
                viewed_document.unshift(document);
                viewed_document = [...new Map(viewed_document.map(item => [item['id'], item])).values()];
                viewed_document = viewed_document.slice(0,5);
                localStorage.setItem('v4_viewed_documents', JSON.stringify(viewed_document));
            } else {
                localStorage.setItem('v4_viewed_documents', JSON.stringify([document]));
            }
        }

        setTimeout(function () {
            addDocumentToLocalStorage({id: '{{$document->id}}', title: '{{$document->title}}', url: "{{route('document.detail', ['slug' => $document->slug]) }}"});
        }, 5000);
    </script>
@endpush

@push('after_scripts')
    <script src="{{ asset('assets_v4/packages/pdfjs/build/pdf.js') }}"></script>
    <script src="{{ asset('assets_v4/packages/pdfjs/web/viewer.js') }}"></script>
    <script>
        const container = document.getElementById('pdfContainer');
        {{--const pdfUrl = "{{ url('assets_v4/packages/pdfjs/web/compressed.tracemonkey-pldi-09.pdf') }}";--}}
        const pdfUrl = "{{ url('storage/'.$document->path) }}";
        let currentPage = 1;
        let totalPageCount = 0;
        const document_controller = document.getElementById('document_controller');
        const totalPage = document.getElementById('total_page');
        const currentPageElement = document.getElementById('current_page');

        const content = document.getElementById('content');
        const windowHeight = window.innerHeight;
        let document_controller_position_status = 'absolute';

        const toPreviousPage = document.getElementById('toPreviousPage');
        const toNextPage = document.getElementById('toNextPage');

        let pageArray = [];
        let currentPageInView = 1;

        function checkIfPageInView() {
            pageArray.forEach(pageNumber => {
                const element = document.getElementById("page-" + pageNumber);
                const rect = element.getBoundingClientRect();
                if (windowHeight - rect.top >= (windowHeight / 4)) {
                    current_page.value = pageNumber;
                    currentPageInView = pageNumber;
                    if (currentPageInView === 1) {
                        toPreviousPage.disabled = true;
                    } else {
                        toPreviousPage.disabled = false;
                    }
                    if (currentPageInView === totalPageCount || currentPageInView >= 20) {
                        toNextPage.disabled = true;
                    } else {
                        toNextPage.disabled = false;
                    }
                }
            });
        }

        function modifyDocumentController() {
            let container_position = container.getBoundingClientRect();
            let distance_container_top_window = windowHeight - container_position.top;
            if (distance_container_top_window >= 40 && document_controller_position_status === 'absolute') {
                document_controller.classList.remove('absolute');
                document_controller.classList.add('fixed');
                document_controller_position_status = 'fixed';
            }
            let distance_container_bottom_window = windowHeight - container_position.bottom;
            if (distance_container_bottom_window >= 0 && document_controller_position_status === 'fixed') {
                document_controller.classList.remove('fixed');
                document_controller.classList.add('absolute');
                document_controller_position_status = 'absolute';
            }
        }


        content.addEventListener('scroll', () => {
            checkIfPageInView();
            modifyDocumentController();
        });


        // View pdf
        function renderPage(page, canvas) {
            const viewport = page.getViewport({
                scale: 3
            });
            const canvasContext = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;
            page.render({
                canvasContext,
                viewport
            });
        }

        // Get pdf from prdUrl
        pdfjsLib.getDocument(pdfUrl).promise.then(function (pdf) {
            totalPage.innerText = pdf.numPages;
            totalPageCount = pdf.numPages;
            currentPageElement.addEventListener('keyup', (e) => {
                if (e.keyCode !== 13) return;
                let page = parseInt(currentPageElement.value);
                if (page >= 20) {
                    page = 20;
                    alert("You need download to view all document")
                }
                if (page > totalPageCount || page <= 0) return;
                if (page <= pageArray[pageArray.length - 1]) {
                    document.getElementById('page-' + page).scrollIntoView({
                        behavior: 'smooth'
                    });
                } else {
                    for (let index = parseInt(pageArray[pageArray.length - 1]) + 1; index <=
                    page; index++) {
                        if (index === page)
                            loadPage(index, () => {
                                document.getElementById('page-' + index)
                                    .scrollIntoView({
                                        behavior: 'smooth'
                                    });
                            });
                        else
                            loadPage(index);
                    }
                }

            })

            function loadPage(pageNumber, callback = null) {
                {{--                @if($document->price > 0)--}}
                {{--                    const page_preview = pageNumber/pdf.numPages;--}}
                {{--                    if (page_preview > 0.3){--}}
                {{--                        return--}}
                {{--                    }--}}
                {{--                @endif--}}
                // New div
                const newDiv = document.createElement('div');
                newDiv.classList.add('bg-white', 'rounded-1.5lg', 'mb-4');
                newDiv.id = 'page-' + pageNumber;
                pageArray.push(pageNumber);

                pdf.getPage(pageNumber).then(function (page) {

                    // New canvas
                    const newCanvas = document.createElement('canvas');
                    newCanvas.classList.add('w-full', 'rounded-1.5lg');

                    // Add div to pdfContainer
                    newDiv.appendChild(newCanvas);
                    container.appendChild(newDiv);

                    // Render pdf in new page
                    renderPage(page, newCanvas);

                    if (callback !== null) {
                        callback()
                    }

                    if (currentPage < pdf.numPages) {
                        currentPage++;
                    }
                    document_controller.style.display = 'flex';
                });
            }


            toNextPage.addEventListener('click', function () {
                if ((currentPageInView + 1) < currentPage) {
                    currentPageInView++;
                    document.getElementById('page-' + currentPageInView).scrollIntoView({
                        behavior: 'smooth'
                    });
                    return;
                }
                if (currentPage <= pdf.numPages) {
                    loadPage(currentPage, () => {
                        document.getElementById('page-' + currentPage).scrollIntoView({
                            behavior: 'smooth'
                        });
                    });
                }
                if (parseInt(currentPage) >= 20) {
                    alert("You need download to view all document")
                }
            });

            toPreviousPage.addEventListener('click', function () {
                if (currentPageInView > 1) {
                    currentPageInView--;
                    document.getElementById('page-' + currentPageInView).scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });

            loadPage(currentPage);

            if (totalPageCount > 1) {
                loadPage(currentPage + 1);
            }

            if (pdf.numPages <= 2) {
                document.getElementById('toNextPage').disabled = true;
            }
        });


        // const documentFullScreenButton = document.getElementById("document_full_screen");
        const pdfContainer = document.getElementById("pdfContainer");
    </script>
    <script>
        let isFullScreen = false;
        const document_full_screen = document.getElementById('document_full_screen');
        const element = document.getElementById("pdf_wrapper");
        document_full_screen.onclick = function () {
            const svgElement = document_full_screen.querySelector("svg");
            if (isFullScreen) {
                element.classList.remove('bg-gray-100');
                if (svgElement) {
                    const svgString = `   <svg height="28px" width="28px" version="1.1" id="Layer_1"
                                 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                 viewBox="0 0 473.931 473.931" xml:space="preserve" fill="#000000">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linoneecap="rounoned" stroke-linoneejoinone="rounoned">
                                </g>
                                <g id="SVGRepo_icononeCarrier">
                                    <circle style="fill:none;" cx="236.966" cy="236.966" r="236.966"></circle>
                                    <g>
                                        <path style="fill:#cbcccc;"
                                              d="M249.459,217.718c18.118,18.118,38.652-11.831,49.994-23.173 c10.859-10.859,21.721-21.725,32.58-32.583c1.01,11.352,2.099,22.709,3.772,33.949c2.653,17.867,24.782,24.542,32.946,8.801 c0.842-1.119,1.381-2.522,1.594-4.157c0.374-1.523,0.382-2.922,0.079-4.176c-0.292-14.724-0.584-29.448-0.876-44.175 c-0.284-14.353,1.643-29.953-3.611-43.431c-0.206-0.76-0.468-1.534-0.861-2.316c-1.059-2.122-4.539-8.976-0.109-0.116 c-1.265-2.522-3.588-3.974-6.148-4.513c-12.408-4.707-26.17-3.147-39.457-3.412c-16.501-0.329-33.002-0.655-49.503-0.992 c-19.068-0.382-19.745,24.123-5.818,32.28c6.956,4.079,18.14,3.611,25.863,4.404c4.969,0.513,9.938,0.977,14.915,1.463 c-10.795,10.787-21.582,21.579-32.366,32.362C261.126,179.253,231.394,199.653,249.459,217.718z">
                                        </path>
                                        <path style="fill:#cbcccc;"
                                              d="M96.736,198.014c-0.382,19.068,24.116,19.745,32.273,5.818c4.079-6.956,3.611-18.133,4.4-25.863 c0.513-4.965,0.98-9.938,1.463-14.907c10.787,10.787,21.575,21.582,32.366,32.366c11.319,11.326,31.726,41.055,49.799,22.986 c18.11-18.11-11.839-38.645-23.18-49.99c-10.859-10.859-21.721-21.721-32.587-32.579c11.356-1.014,22.712-2.103,33.953-3.772 c17.867-2.657,24.527-24.774,8.808-32.95c-1.119-0.834-2.522-1.373-4.157-1.594c-1.527-0.374-2.93-0.382-4.195-0.079 c-14.72,0.292-29.436,0.584-44.164,0.876c-14.309,0.284-29.867-1.631-43.322,3.57c-0.621,0.157-1.227,0.397-1.826,0.7 c-0.198,0.082-0.4,0.131-0.591,0.225c0.03,0.015,0.067,0.026,0.101,0.041c-0.109,0.06-0.213,0.105-0.314,0.168 c0.666-0.906,0.52-0.995-1.908,1.433c-1.557,1.557-2.376,3.296-2.69,5.059c-4.494,12.284-2.975,25.878-3.237,39 C97.394,165.015,97.065,181.513,96.736,198.014z">
                                        </path>
                                        <path style="fill:#cbcccc;"
                                              d="M103.781,364.238c0,0,0-0.007-0.007-0.007C105.263,365.712,106.651,367.108,103.781,364.238z">
                                        </path>
                                        <path style="fill:#cbcccc;"
                                              d="M103.767,364.223c0.007,0,0.007,0.004,0.007,0.004C102.217,362.666,100.579,361.035,103.767,364.223 z">
                                        </path>
                                        <path style="fill:#cbcccc;"
                                              d="M217.729,250.844c-18.118-18.118-38.652,11.831-49.994,23.173 c-10.859,10.862-21.721,21.728-32.579,32.587c-1.01-11.356-2.099-22.712-3.772-33.953c-2.653-17.863-24.774-24.527-32.946-8.808 c-0.834,1.123-1.381,2.529-1.594,4.165c-0.382,1.527-0.382,2.93-0.079,4.187c0.292,14.72,0.584,29.44,0.876,44.164 c0.277,14.077-1.572,29.347,3.311,42.641c0.307,1.774,1.126,3.525,2.698,5.096c0.052,0.056,0.079,0.082,0.131,0.138 c1.557,1.542,3.289,2.365,5.036,2.675c12.292,4.505,25.893,2.982,39.012,3.244c16.501,0.329,33.002,0.659,49.503,0.988 c19.068,0.378,19.745-24.119,5.818-32.28c-6.956-4.079-18.14-3.607-25.863-4.4c-4.969-0.513-9.938-0.973-14.915-1.463 c10.795-10.788,21.582-21.575,32.366-32.362C206.074,289.309,235.795,268.909,217.729,250.844z">
                                        </path>
                                        <path style="fill:#cbcccc;"
                                              d="M370.46,270.54c0.382-19.068-24.116-19.749-32.28-5.818c-4.071,6.956-3.603,18.133-4.4,25.859 c-0.513,4.969-0.973,9.942-1.463,14.911c-10.788-10.791-21.575-21.582-32.359-32.366c-11.326-11.326-31.73-41.055-49.799-22.986 c-18.118,18.11,11.831,38.649,23.173,49.986c10.866,10.862,21.728,21.725,32.587,32.583c-11.356,1.014-22.712,2.103-33.953,3.772 c-17.867,2.657-24.527,24.774-8.801,32.95c1.119,0.834,2.522,1.373,4.15,1.594c1.534,0.374,2.937,0.382,4.195,0.079 c14.72-0.292,29.444-0.584,44.164-0.876c14.077-0.281,29.35,1.564,42.641-3.311c1.777-0.307,3.528-1.126,5.1-2.698 c2.425-2.432,2.339-2.574,1.426-1.901c0.06-0.105,0.108-0.217,0.168-0.322c0.015,0.037,0.03,0.071,0.045,0.101 c0.086-0.202,0.146-0.408,0.236-0.61c0.284-0.565,0.513-1.152,0.674-1.747c4.831-12.471,3.244-26.327,3.51-39.704 C369.802,303.539,370.131,287.042,370.46,270.54z">
                                        </path>
                                    </g>
                                </g>
                            </svg>`
                    const newSvgElement = createNewSvgElement(svgString); // Thẻ SVG mới đã có sẵn
                    const svgElement = document_full_screen.querySelector("svg");
                    if (svgElement) {
                        document_full_screen.replaceChild(newSvgElement, svgElement);
                    }
                }
                return exitFullScreen()
            }

            if (svgElement) {
                const svgString = `<svg fill="#000000" width="26px" height="26px" viewBox="0 0 24 24" id="minimize-size" data-name="Flat Line" xmlns="http://www.w3.org/2000/svg" class="icon flat-line"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path id="primary" d="M8.71,15.29,3,21M8.71,8.71,3,3M21,21l-5.71-5.71M21,3,15.29,8.71" style="fill: none; stroke: #cbcccc; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path><path id="primary-2" data-name="primary" d="M4,15H8a1,1,0,0,1,1,1v4" style="fill: none; stroke: #cbcccc; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path><path id="primary-3" data-name="primary" d="M9,4V8A1,1,0,0,1,8,9H4" style="fill: none; stroke: #cbcccc; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path><path id="primary-4" data-name="primary" d="M15,20V16a1,1,0,0,1,1-1h4" style="fill: none; stroke: #cbcccc; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path><path id="primary-5" data-name="primary" d="M20,9H16a1,1,0,0,1-1-1V4" style="fill: none; stroke: #cbcccc; stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path></g></svg>`;
                const newSvgElement = createNewSvgElement(svgString); // Thẻ SVG mới đã có sẵn
                const svgElement = document_full_screen.querySelector("svg");
                if (svgElement) {
                    document_full_screen.replaceChild(newSvgElement, svgElement);
                }
            }
            element.classList.add('bg-gray-100');
            return requestFullScreen()

        };

        function createNewSvgElement(svgString) {
            const parser = new DOMParser();
            const newSvgDocument = parser.parseFromString(svgString, "image/svg+xml");
            return newSvgDocument.documentElement;
        }

        function requestFullScreen() {
            if (isFullScreen) return;

            element.addEventListener('scroll', () => {
                checkIfPageInView();
            });

            document_controller.classList.remove('absolute');
            document_controller.classList.add('fixed');
            document_controller_position_status = 'fixed';

            const docElmWithBrowsersFullScreenFunctions = element;

            if (docElmWithBrowsersFullScreenFunctions.requestFullscreen) {
                docElmWithBrowsersFullScreenFunctions.requestFullscreen();
            } else if (docElmWithBrowsersFullScreenFunctions.mozRequestFullScreen) {
                // Firefox
                docElmWithBrowsersFullScreenFunctions.mozRequestFullScreen();
            } else if (docElmWithBrowsersFullScreenFunctions.webkitRequestFullscreen) {
                // Chrome, Safari and Opera
                docElmWithBrowsersFullScreenFunctions.webkitRequestFullscreen();
            } else if (docElmWithBrowsersFullScreenFunctions.msRequestFullscreen) {
                // IE/Edge
                docElmWithBrowsersFullScreenFunctions.msRequestFullscreen();
            }

            isFullScreen = true;
        }

        function exitFullScreen() {
            if (!isFullScreen) return;
            element.removeEventListener('scroll', function () {
            })
            const docWithBrowsersExitFunctions = document;

            if (docWithBrowsersExitFunctions.exitFullscreen) {
                docWithBrowsersExitFunctions.exitFullscreen();
            } else if (docWithBrowsersExitFunctions.mozCancelFullScreen) {
                // Firefox
                docWithBrowsersExitFunctions.mozCancelFullScreen();
            } else if (docWithBrowsersExitFunctions.webkitExitFullscreen) {
                // Chrome, Safari and Opera
                docWithBrowsersExitFunctions.webkitExitFullscreen();
            } else if (docWithBrowsersExitFunctions.msExitFullscreen) {
                // IE/Edge
                docWithBrowsersExitFunctions.msExitFullscreen();
            }

            isFullScreen = false;
        }

    </script>

    {{-- Report --}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $('#submit_report').on('click', function () {
                let report_radio = $('.report_radio:checked').val();
                let message_other = "";
                if (report_radio === 'other') {
                    message_other = $('.message_other').val()
                }
                $.ajax({
                    url: "{{ route('frontend_v4.document.report', ['slug' => $document->slug]) }}",
                    method: "POST",
                    data: {
                        report_radio: report_radio,
                        message_other: message_other
                    },
                    success: function (dt) {
                        console.log(dt)
                    },
                    error: function (dt) {
                        console.log('error')
                    }
                });
            })
        });
    </script>
@endpush

@section('content')
    <div class="bg-white py-4 px-4" :class="open_comment_responsive && 'h-full'">
        <div class="container mx-auto flex flex-col">
            <div class="font-thin flex gap-4 items-center mb-3">
                <a href="{{ route('document.home.index') }}" class="text-primary">Home</a>
                <i class="fa-solid fa-chevron-right"></i>
                <span>Document</span>
            </div>
            <div class="lg:basis-2/3 mb-4 flex lg:flex-row flex-col">
                <h1 class="md:text-lg lg:text-2xl break-words font-bold md:basis-2/3 my-auto leading-loose md:mb-5">
                    {{ $document->title }}
                </h1>
                <div class="hidden md:flex lg:basis-1/3 md:justify-between  lg:justify-end gap-2 mt-3 md:mt-0">
                    <div class="flex gap-2">
                        <a
                            href="{{ Auth::check() ? route('frontend_v4.document.like', ['slug' => $document->slug]) : '#' }}"
                            class="bg-primary-100 text-primary font-medium rounded-1.5lg px-5 py-1 h-12 inline-flex items-center justify-center gap-2">
                            <i class="fa fa-thumbs-up"></i>
                            <p>{{ $document->helpful_count }}</p>
                            <p class="md:block hidden">Helpful</p>
                        </a>
                        <a
                            href="{{ Auth::check() ? route('frontend_v4.document.dislike', ['slug' => $document->slug]) : '#' }}"
                            class="bg-primary-100 text-primary font-medium rounded-1.5lg px-5 py-1 h-12 inline-flex items-center justify-center gap-2">
                            <i class="fa fa-thumbs-down"></i>
                            <p class="md:block hidden">Unhelpful</p>
                        </a>
                    </div>
                    <div class="flex gap-2">
                        <div x-data="{ open: false, hasCopy:false }" class="relative">
                            <button @click="open=!open" id="shareLinkDropdownButton" type="button"
                                    data-dropdown-toggle="shareLinkDropdown"
                                    :class="open && 'text-primary bg-green-100'"
                                    class="w-16 bg-opacity-20 bg-gray-300 text-default-lighter font-medium rounded-1.5lg px-5 py-2 h-12 inline-flex items-center justify-center gap-1 hover:bg-green-100 hover:text-primary focus:outline-none ">
                                <i class="fa fa-link"></i>
                            </button>
                            <!-- Dropdown menu -->
                            <div x-cloak @click.outside="open=false; hasCopy=false" x-show="open" id="shareLinkDropdown"
                                 class="z-10 w-96 bg-white mt-4 divide-y divide-gray-100 border-2 border-primary rounded-md absolute  right-[10%]"
                                 data-popper-placement="bottom">
                                <div class="mx-6 py-3 text-sm text-gray-700" aria-labelledby="shareLinkDropdownButton">
                                    <p class="text-2xl font-medium mb-4">Share this link with a friend: </p>
                                    <input type="text" :disabled="hasCopy" id="document_share"
                                           @click="navigator.clipboard.writeText($el.value);hasCopy=true"
                                           value="{{ url()->current() }}"
                                           class="outline-none border-2 border-gray-100 px-4 py-2 w-full font-normal text-xl rounded-1.5lg peer mb-2"
                                           :class="hasCopy && 'text-white bg-blue-400'">
                                    <div x-cloak x-show="hasCopy" class="text-[#F616B8] text-lg"> Copied!</div>


                                </div>
                            </div>
                        </div>
                        <div>
                            <button @click="open_report=!open_report" type="button"
                                    :class="open_report && 'text-primary bg-green-100'"
                                    class="w-16 bg-opacity-20 bg-gray-300 text-default-lighter font-medium rounded-1.5lg px-5 py-2 h-12 inline-flex items-center justify-center gap-1 hover:bg-green-100 hover:text-primary">
                                <i class="fa fa-flag"></i>
                            </button>


                        </div>


                    </div>
                </div>
            </div>
            <div x-data="{ open: true }" class="lg:basis-1/3 flex flex-col md:flex-row justify-start">
                <div class="md:basis-2/3 text-sm md:text-base">
                    <div class="flex lg:flex-row flex-col gap-3">
                        <div class="w-full md:mt-0 lg:w-fit font-medium flex flex-row text-default-lighter">
                            <i class="fa-solid fa-language mt-1"></i>
                            <p class=" ml-1 block">
                                {{ \App\Libs\CountriesHelper\Languages::getFullName($document->language) }}
                            </p>
                        </div>
                        <div class="lg:ml-16 md:mt-0 w-full  lg:w-fit font-medium flex flex-row text-default-lighter">
                            <i class="fa-solid fa-book mt-1"></i>
                            <a href="{{ route('document.category.list', ['slug' => $document->categories->slug]) }}" class="hover:underline ml-2">{{ $document->categories->name }}</a>
                        </div>
                        <div
                            class="lg:ml-16 md:mt-0 w-full lg:w-fit font-medium flex flex-row items-center text-default-lighter">
                            <i class="fa-brands fa-cc-visa"></i>
                            <a href="#" class="hover:underline ml-2">{{ $document->price }}</a>
                        </div>
                    </div>
                    <div class="hidden md:flex lg:flex-row flex-col mt-3 lg:mt-5 gap-3">
                        <div class=" font-medium flex flex-row text-default-lighter">
                            <i class="fa-solid fa-user mt-1"></i>
                            <p class="ml-2">{{ $document->user->name }}</p>
                        </div>
                        <div class="lg:ml-16 md:mt-0 font-medium flex flex-row text-default-lighter">
                            <i class="fa-solid fa-calendar-days mt-1"></i>
                            <p class="ml-2">
                                Uploaded date: <span
                                                  class="text-gray-400">{{ $document->created_at->toDateString() }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex md:hidden justify-between mt-4">
                        <div @click="open_comment_responsive=!open_comment_responsive">
                            <span
                                class="inline-flex gap-4 items-center border border-solid border-slate-300 px-4 py-2 rounded-full hover:border-primary group">
                                <svg class="fill-default-lighter group-hover:fill-primary" width="14" height="14"
                                     viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                          d="M6 12C2.68629 12 0 9.31371 0 6C0 2.68629 2.68629 0 6 0C9.31371 0 12 2.68629 12 6C12 9.31371 9.31371 12 6 12ZM6.00001 2.33333C5.44772 2.33333 5.00001 2.78105 5.00001 3.33333C5.00001 3.88562 5.44772 4.33333 6.00001 4.33333H6.00667C6.55896 4.33333 7.00667 3.88562 7.00667 3.33333C7.00667 2.78105 6.55896 2.33333 6.00667 2.33333H6.00001ZM5 5C4.44772 5 4 5.44772 4 6C4 6.55228 4.44772 7 5 7V8.66667C5 9.21895 5.44772 9.66667 6 9.66667H7.33333C7.88562 9.66667 8.33333 9.21895 8.33333 8.66667C8.33333 8.11438 7.88562 7.66667 7.33333 7.66667H7V6C7 5.44772 6.55228 5 6 5H5Z"
                                          fill=""/>
                                </svg>
                                <span class="group-hover:text-primary">infor</span>
                                <i x-cloak x-show="open_comment_responsive"
                                   class="fa-solid fa-chevron-up group-hover:text-primary"></i>
                                <i x-cloak x-show="!open_comment_responsive"
                                   class="fa-solid fa-chevron-down group-hover:text-primary"></i>
                            </span>
                        </div>
    {{--                        <div>--}}
    {{--                            <a href="{{ route('frontend_v4.document.download', ['id' => $document->id,'slug' => $document->slug]) }}"--}}
    {{--                               class="inline-flex h-10 w-10 rounded-full bg-primary justify-center items-center mr-2 hover:bg-primary-darker">--}}
    {{--                                <i class="fa-solid fa-cloud-arrow-down text-white"></i>--}}
    {{--                            </a>--}}
    {{--                        </div>--}}
                    </div>
                    <div x-cloak x-show="open_comment_responsive" class="md:hidden mt-4 md:mt-0 w-full items-end">
                        <div class="flex lg:flex-row flex-col mt-5 gap-3">
                            <div class=" font-medium flex flex-row text-default-lighter">
                                <i class="fa-solid fa-user mt-1"></i>
                                <p class="hover:underline ml-2">{{ $document->user->name }}</p>
                            </div>
                            <div class="lg:ml-16 md:mt-0 font-medium flex flex-row text-default-lighter">
                                <i class="fa-solid fa-calendar-days mt-1"></i>
                                <p class="ml-2">
                                    Uploaded date: <span
                                                      class="text-gray-400 hover:underline">{{ $document->created_at->toDateString() }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex lg:basis-1/3 gap-2 lg:justify-end mt-3 md:mt-0 mb-4">
                            <a href="{{ Auth::check() ? route('frontend_v4.document.like', ['slug' => $document->slug]) : '#' }}"
                               class="bg-green-100 text-primary font-medium rounded-1.5lg px-5 py-1 h-12 inline-flex items-center justify-center gap-2">
                                <i class="fa fa-thumbs-up"></i>
                                <p>{{ $document->helpful_count }}</p>
                                <p class="md:block hidden">Helpful</p>
                            </a>
                            <a href="{{ Auth::check() ? route('frontend_v4.document.dislike', ['slug' => $document->slug]) : '#' }}"
                               class="bg-green-100 text-primary font-medium rounded-1.5lg px-5 py-1 h-12 inline-flex items-center justify-center gap-2">
                                <i class="fa fa-thumbs-down"></i>
                                <p class="md:block hidden">Unhelpful</p>
                            </a>
                            <div x-data="{ open: false, hasCopy:false }" class="relative">
                                <button @click="open=!open" id="shareLinkDropdownButton" type="button"
                                        data-dropdown-toggle="shareLinkDropdown"
                                        :class="open && 'text-primary bg-green-100'"
                                        class="w-16 bg-opacity-20 bg-gray-300 text-default-lighter font-medium rounded-1.5lg px-5 py-2 h-12 inline-flex items-center justify-center gap-1 hover:bg-green-100 hover:text-primary focus:outline-none ">
                                    <i class="fa fa-link"></i>
                                </button>
                                <!-- Dropdown menu -->
                                <div x-cloak @click.outside="open=false;hasCopy=false" x-show="open"
                                     id="shareLinkDropdown"
                                     class="z-10 w-96 bg-white mt-4 divide-y divide-gray-100 border-2 border-primary rounded-md absolute -translate-x-[40%] md:translate-x-0 md:right-[10%]"
                                     data-popper-placement="bottom">
                                    <div class="mx-2 md:mx-6 py-3 text-sm text-gray-700"
                                         aria-labelledby="shareLinkDropdownButton">
                                        <p class="text-lg md:text-xl lg:text-2xl font-medium mb-4">Share this link with
                                            a
                                            friend: </p>
                                        <input type="text" :disabled="hasCopy" id="document_share"
                                               @click="navigator.clipboard.writeText($el.value);hasCopy=true"
                                               value="{{ url()->current() }}"
                                               class="outline-none border-2 border-gray-100 px-4 py-2 w-full font-normal text-xl rounded-1.5lg peer mb-2"
                                               :class="hasCopy && 'text-white bg-blue-400'">
                                        <div x-cloak x-show="hasCopy" class="text-[#F616B8] text-lg"> Copied!</div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <button @click="open_report=!open_report" type="button"
                                        :class="open_report && 'text-primary bg-green-100'"
                                        class="bg-opacity-20 bg-gray-300 text-default-lighter font-medium rounded-1.5lg px-5 py-2 h-12 inline-flex items-center justify-center gap-1 hover:bg-green-100 hover:text-primary">
                                    <i class="fa fa-flag"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex md:justify-end items-end h-full lg:items-start gap-2 mb-4">
                            <div
                                class="px-4 h-12 text-default-lighter rounded-1.5lg bg-gray-100 flex items-center justify-center ">
                                <i class="fa-solid fa-eye mr-2"></i> {{ $document->viewed_count }}
                            </div>
                            <div
                                class="px-4 h-12 text-default-lighter rounded-1.5lg bg-gray-100 flex items-center justify-center ">
                                <i class="fa-solid fa-file mr-2"></i> {{ $document->page_number }}
                            </div>
                            <div
                                class="px-4 h-12 text-default-lighter rounded-1.5lg bg-gray-100 flex items-center justify-center ">
                                <i class="fa-solid fa-cloud-arrow-down mr-2"></i> {{ $document->downloaded_count }}
                            </div>
                        </div>
                        <div class="bg-white rounded-1.5lg p-5 border border-slate-300">
                            <p class="font-medium text-center">Comments</p>
                            @if(Auth::check())
                                <form method="POST"
                                      action="{{ route('frontend_v4.document.comment', ['slug' => $document->slug]) }}">
                                    {{ csrf_field() }}
                                    <div x-data="{disableSubmit: true, message_comment: null}">
                                        <input type="text" x-model='message_comment' name="message_comment"
                                               x-on:input="[(message_comment.length != 0) ? disableSubmit = false : disableSubmit = true]"
                                               class="block rounded-1.5lg border border-slate-300 placeholder:text-gray-400 placeholder:font-light outline-none w-full px-3 py-4 mt-4 hover:border-primary"
                                               placeholder="Comments or ask a question">
                                        <div class="flex justify-end">
                                            <button x-bind:disabled="disableSubmit"
                                                    class="disabled:opacity-50 w-fit bg-primary text-white font-medium rounded-full mt-3 px-5 py-2 inline-flex items-center justify-center gap-2 hover:bg-primary-darker">
                                                <i class="fa-solid fa-paper-plane"></i>
                                                Post
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @else
                                {{ csrf_field() }}
                                <input type="text"
                                       class="block rounded-1.5lg border border-slate-300 placeholder:text-gray-400 placeholder:font-light outline-none w-full px-3 py-4 mt-4 hover:border-primary"
                                       placeholder="Comments or ask a question">
                                <div class="flex justify-end">
                                    <a href="{{ route('frontend.auth.getLogin') }}"
                                       class="disabled:opacity-50 w-fit bg-primary text-white font-medium rounded-full mt-3 px-5 py-2 inline-flex items-center justify-center gap-2 hover:bg-primary-darker">
                                        <i class="fa-solid fa-paper-plane"></i>
                                        Login to comment
                                    </a>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="hidden md:block md:basis-1/3 mt-4 md:mt-0 w-full items-end">
                    <div class="flex md:justify-end items-end h-full lg:items-start gap-2">
                        <div
                            class="px-4 md:h-12 text-default-lighter rounded-1.5lg bg-gray-100 h-10 flex items-center justify-center ">
                            <i class="fa-solid fa-eye mr-2"></i> {{ $document->viewed_count }}
                        </div>
                        <div
                            class="px-4 md:h-12 text-default-lighter rounded-1.5lg bg-gray-100 h-10 flex items-center justify-center ">
                            <i class="fa-solid fa-file mr-2"></i>{{ $document->page_number }}
                        </div>
                        <div
                            class="px-4 md:h-12 text-default-lighter rounded-1.5lg bg-gray-100 h-10 flex items-center justify-center ">
                            <i class="fa-solid fa-cloud-arrow-down mr-2"></i> {{ $document->downloaded_count }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div x-cloak x-show="!open_comment_responsive" class="w-full lg:px-4">
        <div class="container mx-auto mt-4 flex flex-col lg:flex-row lg:gap-4">
            <div class="md:basis-9/12 relative flex items-center flex-col h-full">
                <div id="pdf_wrapper" class="md:basis-9/12 relative flex items-center flex-col h-max overflow-y-auto">
                    <div class="w-full rounded-1.5lg relative" id="pdfContainer">
                    </div>
                    <div id="document_controller" style="display: none;"
                         class="px-5 py-2 rounded-4xl bg-default absolute bottom-10 z-40 gap-4 items-center justify-center text-white">
                        <div id="document_full_screen" class="hidden md:block">
                            <svg height="28px" width="28px" version="1.1" id="Layer_1"
                                 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                 viewBox="0 0 473.931 473.931" xml:space="preserve" fill="#000000">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linoneecap="rounoned"
                                   stroke-linoneejoinone="rounoned">
                                </g>
                                <g id="SVGRepo_icononeCarrier">
                                    <circle style="fill:none;" cx="236.966" cy="236.966" r="236.966"></circle>
                                    <g>
                                        <path style="fill:#cbcccc;"
                                              d="M249.459,217.718c18.118,18.118,38.652-11.831,49.994-23.173 c10.859-10.859,21.721-21.725,32.58-32.583c1.01,11.352,2.099,22.709,3.772,33.949c2.653,17.867,24.782,24.542,32.946,8.801 c0.842-1.119,1.381-2.522,1.594-4.157c0.374-1.523,0.382-2.922,0.079-4.176c-0.292-14.724-0.584-29.448-0.876-44.175 c-0.284-14.353,1.643-29.953-3.611-43.431c-0.206-0.76-0.468-1.534-0.861-2.316c-1.059-2.122-4.539-8.976-0.109-0.116 c-1.265-2.522-3.588-3.974-6.148-4.513c-12.408-4.707-26.17-3.147-39.457-3.412c-16.501-0.329-33.002-0.655-49.503-0.992 c-19.068-0.382-19.745,24.123-5.818,32.28c6.956,4.079,18.14,3.611,25.863,4.404c4.969,0.513,9.938,0.977,14.915,1.463 c-10.795,10.787-21.582,21.579-32.366,32.362C261.126,179.253,231.394,199.653,249.459,217.718z">
                                        </path>
                                        <path style="fill:#cbcccc;"
                                              d="M96.736,198.014c-0.382,19.068,24.116,19.745,32.273,5.818c4.079-6.956,3.611-18.133,4.4-25.863 c0.513-4.965,0.98-9.938,1.463-14.907c10.787,10.787,21.575,21.582,32.366,32.366c11.319,11.326,31.726,41.055,49.799,22.986 c18.11-18.11-11.839-38.645-23.18-49.99c-10.859-10.859-21.721-21.721-32.587-32.579c11.356-1.014,22.712-2.103,33.953-3.772 c17.867-2.657,24.527-24.774,8.808-32.95c-1.119-0.834-2.522-1.373-4.157-1.594c-1.527-0.374-2.93-0.382-4.195-0.079 c-14.72,0.292-29.436,0.584-44.164,0.876c-14.309,0.284-29.867-1.631-43.322,3.57c-0.621,0.157-1.227,0.397-1.826,0.7 c-0.198,0.082-0.4,0.131-0.591,0.225c0.03,0.015,0.067,0.026,0.101,0.041c-0.109,0.06-0.213,0.105-0.314,0.168 c0.666-0.906,0.52-0.995-1.908,1.433c-1.557,1.557-2.376,3.296-2.69,5.059c-4.494,12.284-2.975,25.878-3.237,39 C97.394,165.015,97.065,181.513,96.736,198.014z">
                                        </path>
                                        <path style="fill:#cbcccc;"
                                              d="M103.781,364.238c0,0,0-0.007-0.007-0.007C105.263,365.712,106.651,367.108,103.781,364.238z">
                                        </path>
                                        <path style="fill:#cbcccc;"
                                              d="M103.767,364.223c0.007,0,0.007,0.004,0.007,0.004C102.217,362.666,100.579,361.035,103.767,364.223 z">
                                        </path>
                                        <path style="fill:#cbcccc;"
                                              d="M217.729,250.844c-18.118-18.118-38.652,11.831-49.994,23.173 c-10.859,10.862-21.721,21.728-32.579,32.587c-1.01-11.356-2.099-22.712-3.772-33.953c-2.653-17.863-24.774-24.527-32.946-8.808 c-0.834,1.123-1.381,2.529-1.594,4.165c-0.382,1.527-0.382,2.93-0.079,4.187c0.292,14.72,0.584,29.44,0.876,44.164 c0.277,14.077-1.572,29.347,3.311,42.641c0.307,1.774,1.126,3.525,2.698,5.096c0.052,0.056,0.079,0.082,0.131,0.138 c1.557,1.542,3.289,2.365,5.036,2.675c12.292,4.505,25.893,2.982,39.012,3.244c16.501,0.329,33.002,0.659,49.503,0.988 c19.068,0.378,19.745-24.119,5.818-32.28c-6.956-4.079-18.14-3.607-25.863-4.4c-4.969-0.513-9.938-0.973-14.915-1.463 c10.795-10.788,21.582-21.575,32.366-32.362C206.074,289.309,235.795,268.909,217.729,250.844z">
                                        </path>
                                        <path style="fill:#cbcccc;"
                                              d="M370.46,270.54c0.382-19.068-24.116-19.749-32.28-5.818c-4.071,6.956-3.603,18.133-4.4,25.859 c-0.513,4.969-0.973,9.942-1.463,14.911c-10.788-10.791-21.575-21.582-32.359-32.366c-11.326-11.326-31.73-41.055-49.799-22.986 c-18.118,18.11,11.831,38.649,23.173,49.986c10.866,10.862,21.728,21.725,32.587,32.583c-11.356,1.014-22.712,2.103-33.953,3.772 c-17.867,2.657-24.527,24.774-8.801,32.95c1.119,0.834,2.522,1.373,4.15,1.594c1.534,0.374,2.937,0.382,4.195,0.079 c14.72-0.292,29.444-0.584,44.164-0.876c14.077-0.281,29.35,1.564,42.641-3.311c1.777-0.307,3.528-1.126,5.1-2.698 c2.425-2.432,2.339-2.574,1.426-1.901c0.06-0.105,0.108-0.217,0.168-0.322c0.015,0.037,0.03,0.071,0.045,0.101 c0.086-0.202,0.146-0.408,0.236-0.61c0.284-0.565,0.513-1.152,0.674-1.747c4.831-12.471,3.244-26.327,3.51-39.704 C369.802,303.539,370.131,287.042,370.46,270.54z">
                                        </path>
                                    </g>
                                </g>
                        </svg>
                        </div>
                        <div class="h-10 hidden md:block w-0.5 bg-white"></div>
                        <button class="mr-3 disabled:text-gray-500" id="toPreviousPage" disabled>
                            <i class="fa-solid fa-arrow-up"></i>
                        </button>
                        <button class="disabled:text-gray-500" id="toNextPage">
                            <i class="fa-solid fa-arrow-down"></i>
                        </button>
                        <div class="h-10 block md:hidden w-0.5 bg-white"></div>
                        <div class=" px-2 py-1 border border-slate-200 rounded-1.5lg flex flex-row">
                            <input id="current_page" class="mr-1 text-black w-8 text-center rounded-1.5lg outline-none"
                                   value="1"/>
                            /
                            <div id="total_page" class="ml-1"></div>
                        </div>
                        <div class="justify-center">
                            @if($document->price > 0)
                                @if(Auth::check())
                                    @if(Auth::id() == $document->user_id)
                                        <a href="{{ route('frontend_v4.document.download', ['id' => $document->id,'slug' => $document->slug]) }}"
                                           type="button"
                                           class="w-full bg-transparent sm:bg-primary text-white font-medium rounded-full sm:px-5 py-2 inline-flex items-center justify-center gap-2 sm:hover:bg-primary-darker">
                                            <i class="fa-solid fa-cloud-arrow-down"></i>
                                            <span class="sm:block hidden ml-2">Download</span>
                                        </a>
                                    @else
                                        <div x-data="{open_buy_document: false}">
                                            <button @click="open_buy_document = !open_buy_document"
                                                    type="button"
                                                    class="w-full bg-transparent sm:bg-primary text-white font-medium rounded-full sm:px-5 py-2 inline-flex items-center justify-center gap-2 sm:hover:bg-primary-darker">
                                                <i class="fa-solid fa-cloud-arrow-down"></i>
                                                <span class="sm:block hidden ml-2">Download</span>
                                            </button>
                                            @if(Auth::user()->money >= $document->price)
                                                <div
                                                    x-cloak
                                                    x-show="open_buy_document" tabindex="-1" aria-hidden="true"
                                                    class="fixed top-0 left-0 right-0 h-screen bg-gray-400 bg-opacity-50  z-50 p-4 overflow-x-hidden overflow-y-auto md:inset-0"
                                                    aria-modal="true" role="dialog">
                                                    <div @click.outside="open_buy_document = false;"
                                                         class="relative max-w-2xl max-h-full mx-auto mt-48">
                                                        <!-- Modal content -->
                                                        <form method="GET" action="{{ route('frontend_v4.document.download', ['id' => $document->id, 'slug' => $document->slug]) }}" class="relative bg-white rounded-lg shadow">
                                                            <!-- Modal header -->
                                                            <div class="flex items-start justify-between p-4 rounded-t">
                                                                <h3 class="font-bold text-default text-xl mx-auto my-2">
                                                                    Buy document
                                                                </h3>
                                                            </div>
                                                            <!-- Modal body -->
                                                            <div
                                                                class="space-y-6 px-6 py-2">
                                                                <div
                                                                    @click=""
                                                                    class="flex flex-row gap-2 justify-center items-center rounded-4.5xl border border-[#AFAFAF] px-4 py-4 hover:bg-main-background cursor-pointer">
                                                                    <div class="text-default font-normal text-lg flex flex-row">
                                                                        Are you sure you want to buy this brochure for <p class="text-primary font-bold mx-1">${{ $document->price }}</p>?
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Modal footer -->
                                                            <div class="flex justify-end items-center p-6 space-x-2">
                                                                <button @click="open_buy_document=false"
                                                                        data-modal-hide="modal_content"
                                                                        type="button"
                                                                        class="text-primary bg-gray-100 hover:bg-gray-300 font-medium rounded-full text-base px-5 py-2.5 text-center">
                                                                    Cancel
                                                                </button>
                                                                <button
                                                                    @click="open_buy_document=false"
                                                                    data-modal-hide="modal_report"
                                                                    type="submit"
                                                                    class="w-24 text-white bg-primary hover:bg-primary-darker disabled:opacity-40 hover:bg-opacity-70 rounded-full border border-gray-200 text-base font-medium px-5 py-2.5 focus:z-10">
                                                                    Buy
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            @else
                                                <div
                                                    x-cloak
                                                    x-show="open_buy_document" tabindex="-1" aria-hidden="true"
                                                    class="fixed top-0 left-0 right-0 h-screen bg-gray-400 bg-opacity-50  z-50 p-4 overflow-x-hidden overflow-y-auto md:inset-0"
                                                    aria-modal="true" role="dialog">
                                                    <div @click.outside="open_buy_document = false;"
                                                         class="relative max-w-2xl max-h-full mx-auto mt-48">
                                                        <!-- Modal content -->
                                                        <div class="relative bg-white rounded-lg shadow">
                                                            <!-- Modal header -->
                                                            <div class="flex items-start justify-between p-4 rounded-t">
                                                                <h3 class="font-bold text-default text-xl mx-auto my-2">
                                                                    Buy document
                                                                </h3>
                                                            </div>
                                                            <!-- Modal body -->
                                                            <div
                                                                class="space-y-6 px-6 py-2">
                                                                <div
                                                                    @click=""
                                                                    class="flex flex-row gap-2 justify-center items-center rounded-4.5xl border border-[#AFAFAF] px-4 py-4 hover:bg-main-background cursor-pointer">
                                                                    <div class="text-default font-normal text-lg flex flex-row">
                                                                        Your account balance is not enough, please recharge and try again
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Modal footer -->
                                                            <div class="flex justify-end items-center p-6 space-x-2">
                                                                <button @click="open_buy_document=false"
                                                                        data-modal-hide="modal_content"
                                                                        type="button"
                                                                        class="text-primary bg-gray-100 hover:bg-gray-300 font-medium rounded-full text-base px-5 py-2.5 text-center">
                                                                    Cancel
                                                                </button>
                                                                <button
                                                                    @click="open_buy_document=false"
                                                                    data-modal-hide="modal_report"
                                                                    type="button"
                                                                    class="w-24 text-white bg-primary hover:bg-primary-darker disabled:opacity-40 hover:bg-opacity-70 rounded-full border border-gray-200 text-base font-medium px-5 py-2.5 focus:z-10">
                                                                    OK
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    @endif
                                @else
                                    <div x-data="{open_payment_document: false}">
                                        <button @click="open_payment_document=!open_payment_document"
                                                type="button"
                                                class="w-full bg-transparent sm:bg-primary text-white font-medium rounded-full sm:px-5 py-2 inline-flex items-center justify-center gap-2 sm:hover:bg-primary-darker">
                                            <i class="fa-solid fa-cloud-arrow-down"></i>
                                            <span class="sm:block hidden ml-2">Download</span>
                                        </button>
                                        <div
                                            x-data="{open_payment_document_body:true,open_payment_document_vnpay:false,open_payment_document_paypal:false}"
                                            x-cloak
                                            x-show="open_payment_document" tabindex="-1" aria-hidden="true"
                                            class="fixed top-0 left-0 right-0 h-screen bg-gray-400 bg-opacity-50  z-50 p-4 overflow-x-hidden overflow-y-auto md:inset-0"
                                            aria-modal="true" role="dialog">
                                            <div @click.outside="open_payment_document = false;"
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
                                                    <div x-show="open_payment_document_body"
                                                         class="space-y-6 px-6 py-2">
                                                        <div
                                                            @click="open_payment_document_vnpay=true;open_payment_document_body=false;"
                                                            class="flex flex-row gap-2 justify-center items-center rounded-4.5xl border border-[#AFAFAF] px-4 py-4 hover:bg-main-background cursor-pointer">
                                                            <img
                                                                src="{{ asset('assets_v4/images/logo/vnpay-logo-inkythuatso-01.png') }}"
                                                                class="h-8 object-fill">
                                                            <p class="text-default font-normal text-lg">Continue with
                                                                VNPay</p>
                                                        </div>

                                                        <div
                                                            @click="open_payment_document_paypal=true;open_payment_document_body=false;"
                                                            class="flex flex-row gap-2 justify-center items-center rounded-4.5xl border border-[#AFAFAF] px-4 py-4 hover:bg-main-background cursor-pointer">
                                                            <img
                                                                src="{{ asset('assets_v4/images/logo/paypal_icon.png') }}"
                                                                class="h-8 object-fill">
                                                            <p class="text-default font-normal text-lg">Continue with
                                                                Paypal</p>
                                                        </div>

                                                    </div>
                                                    <div x-cloak x-show="open_payment_document_vnpay"
                                                         class="space-y-6 px-6 py-2">
                                                        <form method="post" target="_blank"
                                                              action="{{ route('frontend_v4.document.postVNPay', ['id' => $document->id,'slug' => $document->slug]) }}"
                                                              class="space-y-6">
                                                            {{ csrf_field()}}
                                                            <div
                                                                @click="open_payment_document_vnpay=false;open_payment_document_body=true"
                                                                class="flex flex-row items-center text-primary gap-3 cursor-pointer">
                                                                <i class="fa-solid fa-arrow-left"></i>
                                                                <p class="text-lg font-normal">Other payment option</p>
                                                            </div>
                                                            <div class="w-full">
                                                                <p class="text-default text-base font-medium mb-3">This
                                                                    is the amount you need to pay to download this
                                                                    document (VND)</p>
                                                                <input type="text" id="price"
                                                                       placeholder="Select the amount you want to deposit"
                                                                       disabled
                                                                       value="{{ $document->price * 23000 }}"
                                                                       class="text-default text-base font-medium rounded-1.5lg px-3 py-4 border border-main-background w-full hover:border-primary outline-primary">
                                                                <input type="hidden" name="price"
                                                                       value="{{ $document->price * 23000 }}">
                                                            </div>

                                                            <button type="submit" id="sign_in"
                                                                    class="w-full text-white font-normal text-lg mb-6 flex flex-row gap-2 justify-center items-center rounded-4.5xl px-4 py-2 bg-primary hover:bg-primary-darker">
                                                                Continue
                                                            </button>
                                                        </form>

                                                    </div>
                                                    <div x-cloak x-show="open_payment_document_paypal"
                                                         class="space-y-6 px-6 py-2">
                                                        <form method="post" target="_blank"
                                                              action="{{ route('frontend_v4.document.redirectPaypal', ['id' => $document->id,'slug' => $document->slug]) }}"
                                                              class="space-y-6">
                                                            {{ csrf_field()}}
                                                            <div
                                                                @click="open_payment_document_paypal=false;open_payment_document_body=true"
                                                                class="flex flex-row items-center text-primary gap-3 cursor-pointer">
                                                                <i class="fa-solid fa-arrow-left"></i>
                                                                <p class="text-lg font-normal">Other payment option</p>
                                                            </div>
                                                            <div class="w-full">
                                                                <p class="text-default text-base font-medium mb-3">This
                                                                    is the amount you need to pay to download this
                                                                    document ($)</p>
                                                                <input type="text" id="price_paypal"
                                                                       placeholder="Select the amount you want to deposit"
                                                                       disabled
                                                                       value="{{ $document->price }}"
                                                                       class="text-base text-default font-medium rounded-1.5lg px-3 py-4 border border-main-background w-full hover:border-primary outline-primary">
                                                                <input type="hidden" name="price_paypal"
                                                                       value="{{ $document->price }}">
                                                            </div>

                                                            <button type="submit" id="sign_in"
                                                                    class="w-full text-white font-normal text-lg mb-6 flex flex-row gap-2 justify-center items-center rounded-4.5xl px-4 py-2 bg-primary hover:bg-primary-darker">
                                                                Continue
                                                            </button>
                                                        </form>

                                                    </div>
                                                    <!-- Modal footer -->
                                                    <div class="flex justify-end items-center p-6 space-x-2">
                                                        {{--                                                    <button @click="open_payment_document=false"--}}
                                                        {{--                                                            data-modal-hide="modal_content"--}}
                                                        {{--                                                            type="button"--}}
                                                        {{--                                                            class="text-primary bg-gray-100 hover:bg-gray-300 font-medium rounded-full text-base px-5 py-2.5 text-center">--}}
                                                        {{--                                                        Cancel--}}
                                                        {{--                                                    </button>--}}
                                                        {{--                                                    <button--}}
                                                        {{--                                                            data-modal-hide="modal_report"--}}
                                                        {{--                                                            type="button"--}}
                                                        {{--                                                            class="w-24 text-white bg-primary hover:bg-primary-darker disabled:opacity-40 hover:bg-opacity-70 rounded-full border border-gray-200 text-base font-medium px-5 py-2.5 focus:z-10">--}}
                                                        {{--                                                        Send--}}
                                                        {{--                                                    </button>--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                @endif
                            @else
                                <a href="{{ route('frontend_v4.document.download', ['id' => $document->id,'slug' => $document->slug]) }}"
                                   type="button"
                                   class="w-full bg-transparent sm:bg-primary text-white font-medium rounded-full sm:px-5 py-2 inline-flex items-center justify-center gap-2 sm:hover:bg-primary-darker">
                                    <i class="fa-solid fa-cloud-arrow-down"></i>
                                    <span class="sm:block hidden ml-2">Download</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full md:basis-3/12 flex flex-col gap-4 mx-0">
                <!-- Comment -->
                <div class="hidden lg:block bg-white rounded-1.5lg p-5">
                    <p class="font-medium text-center">Comments</p>
                    <form method="POST"
                          action="{{ route('frontend_v4.document.comment', ['slug' => $document->slug]) }}">
                        {{ csrf_field() }}
                        <div x-data="{disableSubmit: true, message_comment: null}">
                            <input type="text" x-model='message_comment' name="message_comment"
                                   x-on:input="[(message_comment.length != 0) ? disableSubmit = false : disableSubmit = true]"
                                   class="block rounded-1.5lg border border-slate-300 placeholder:text-gray-400 placeholder:font-light outline-none w-full px-3 py-4 mt-4 hover:border-primary"
                                   placeholder="Comments or ask a question">
                            <div class="flex justify-end">
                                <button x-bind:disabled="disableSubmit"
                                        class="disabled:opacity-50 w-fit bg-primary text-white font-medium rounded-full mt-3 px-5 py-2 inline-flex items-center justify-center gap-2 hover:bg-primary-darker">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    Post
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Upload -->
                <div class="hidden lg:block bg-white rounded-1.5lg p-5">
                    <p class="font-medium text-center	">Upload your study materials to download all documents.</p>
                    <div class="flex justify-center mt-4 w-full">
                        <a href="{{ route('frontend_v4.users.getUpload') }}" type="button"
                           class="w-full bg-primary text-white font-medium rounded-full mt-3 px-5 py-2 inline-flex items-center justify-center gap-2 hover:bg-primary-darker">
                            <i class="fa fa-cloud-arrow-up"></i>
                            <span>Choose a doc</span>
                        </a>
                    </div>
                    <p class="font-light text-sm text-default-lighter mt-3 text-center">Your document will be enriched,
                        shared
                        on libshare to
                        assist in studying</p>
                </div>

                <!-- Related documents -->
                <div class="hidden lg:block bg-white rounded-1.5lg p-5 pb-0 shadow-around lg:shadow-none mb-3">
                    <h2 class="lg:font-medium lg:text-center font-semibold text-xl">Other related documents</h2>
                    <div class="flex flex-wrap mt-4 xl:px-6">
                        @foreach($top_documents as $top_document)
                            @php
                                $directory = 'assets_v4/images/documents';
                                $files = \Illuminate\Support\Facades\File::files($directory);
                                $randomFile = $files[array_rand($files)];
                            @endphp
                            <div class="w-full md:w-1/2 lg:w-full flex flex-col px-1 pb-3">
                                <div class="flex lg:block p-1 border border-gray-200 rounded-1.5lg hover:shadow-hover">
                                    <a href="{{ route('document.detail', ['slug' => $top_document->slug]) }}"
                                       class="block rounded h-28 lg:h-32 xl:h-40 w-2/5 lg:w-full py-3 lg:py-0 overflow-hidden">
                                        <img class=""
                                             src="{{ asset($randomFile) }}"
                                             alt="">
                                    </a>
                                    <div class="p-3 text-default-lighter">
                                        <a href="{{ route('document.detail', ['slug' => $top_document->slug]) }}" class="text-primary font-medium line-clamp-2">
                                            <h3>{{ $top_document->title }}</h3>
                                        </a>
                                        <div class="flex mt-2 justify-between">
                                            <div class=" text-sm font-light">
                                                <i class="fa-solid fa-book lg:hidden"></i>
                                                <span>{{ $top_document->categories->name }}</span>
                                            </div>
                                            <div class="flex lg:hidden items-baseline gap-2.5">
                                                <i class="fa-solid fa-thumbs-up text-secondary"></i>
                                                <span class="text-secondary">{{ $top_document->helpful_count }}</span>
                                            </div>
                                        </div>
                                        <div class="mt-5 hidden lg:block">
                                            <div class="flex justify-around">
                                                <div class="inline-flex items-center justify-center gap-2">
                                                    <i class="fa-solid fa-file"></i>
                                                    <span>{{ $top_document->page_number }}</span>
                                                </div>
                                                <div class="inline-flex items-center justify-center gap-2">
                                                    <i class="fa-solid fa-cloud-arrow-down"></i>
                                                    <span>{{ $top_document->downloaded_count }}</span>
                                                </div>
                                                <div class="inline-flex items-center justify-center gap-2">
                                                    <i class="fa-solid fa-eye"></i>
                                                    <span>{{ $top_document->viewed_count }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


    </div>
    <div x-cloak x-show="!open_comment_responsive" class="w-full bg-white py-6 px-4">
        <div
            class="container mx-auto mb-4 block lg:hidden bg-white rounded-1.5lg p-5 pb-0 shadow-around lg:shadow-none">
            <h2 class="lg:font-medium lg:text-center font-semibold text-xl">Other related documents</h2>
            <div class="flex flex-wrap mt-4 xl:px-6">
                @foreach($top_documents as $top_document)
                    <div class="w-full md:w-1/2 lg:w-full flex flex-col px-1 pb-3">
                        <div class="flex lg:block p-1 border border-gray-200 rounded-1.5lg hover:shadow-hover">
                            <a href="{{ route('document.detail', ['slug' => $top_document->slug]) }}"
                               class="block rounded h-28 lg:h-32 xl:h-40 w-2/5 lg:w-full py-3 lg:py-0 overflow-hidden w-3/12">
                                <img class=""
                                     src="https://data03.123doks.com/thumbv2/123dok/000/068/68235/cover.webp"
                                     alt="">
                            </a>
                            <div class="p-3 text-default-lighter w-9/12">
                                <a href="{{ route('document.detail', ['slug' => $top_document->slug]) }}" class="text-primary font-medium line-clamp-2">
                                    <h3>{{ $top_document->title }}</h3>
                                </a>
                                <div class="flex mt-2 justify-between">
                                    <div class=" text-sm font-light">
                                        <i class="fa-solid fa-book lg:hidden"></i>
                                        <span>{{ $top_document->categories->name }}</span>
                                    </div>
                                    <div class="flex lg:hidden items-baseline gap-2.5">
                                        <i class="fa-solid fa-thumbs-up text-secondary"></i>
                                        <span class="text-secondary">{{ $top_document->helpful_count }}</span>
                                    </div>
                                </div>
                                <div class="mt-5 block">
                                    <div class="flex justify-start gap-6">
                                        <div class="inline-flex items-center justify-center gap-2">
                                            <i class="fa-solid fa-file"></i>
                                            <span>{{ $top_document->page_number }}</span>
                                        </div>
                                        <div class="inline-flex items-center justify-center gap-2">
                                            <i class="fa-solid fa-cloud-arrow-down"></i>
                                            <span>{{ $top_document->downloaded_count }}</span>
                                        </div>
                                        <div class="inline-flex items-center justify-center gap-2">
                                            <i class="fa-solid fa-eye"></i>
                                            <span>{{ $top_document->viewed_count }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="container mx-auto shadow-around mb-4 rounded-1.5lg">
            <div class="bg-white rounded-1.5lg p-5 mx-4 md:mx-0">
                <p class="font-semibold text-xl">Preview text</p>
                <div
                    class="mt-4 px-3 border rounded-1.5lg prose break-words max-h-[50vh] overflow-y-auto scrollbar-thin scrollbar-thumb-rounded-2xl scrollbar-thumb-gray-500 scrollbar-track-gray-300">
                    <div class="page_container">
                        {!! $document->full_text !!}
                    </div>
                </div>
            </div>
        </div>

{{--        // Figure--}}
{{--        <div class="container mx-auto shadow-around mb-4 rounded-1.5lg">--}}
{{--            <div class="bg-white rounded-1.5lg p-5 mx-4 md:mx-0">--}}
{{--                <p class="font-semibold text-xl">Figure</p>--}}
{{--                <div class="mt-4 p-3 border rounded-1.5lg">--}}
{{--                    <div class="flex max-w-full overflow-auto h-52 gap-4">--}}
{{--                        <a href="#" target="_blank"--}}
{{--                           class="relative h-full grow bg-gray-400 cursor-pointer border border-gray-500 border-solid"--}}
{{--                           style="aspect-ratio: 1">--}}
{{--                            <img--}}
{{--                                src="https://data03.123doks.com/thumbv2/123dok/000/413/413960/70.595.115.514.205.448/gambar-perbabandingan-pretest-posttest-kelompok-kontrolrol.webp"--}}
{{--                                class="w-full h-full object-cover">--}}
{{--                        </a>--}}
{{--                        <a href="#" target="_blank"--}}
{{--                           class="relative h-full grow bg-gray-400 cursor-pointer border border-gray-500 border-solid"--}}
{{--                           style="aspect-ratio: 1">--}}
{{--                            <img--}}
{{--                                src="https://data03.123doks.com/thumbv2/123dok/000/413/413960/72.595.113.514.237.351/grafik-perbandingan-gambar-kemampuan-berpikir-kreatif-berdasarkan-kategorisasi.webp"--}}
{{--                                class="w-full h-full object-cover">--}}
{{--                        </a>--}}
{{--                        <a href="#" target="_blank"--}}
{{--                           class="relative h-full grow bg-gray-400 cursor-pointer border border-gray-500 border-solid"--}}
{{--                           style="aspect-ratio: 1">--}}
{{--                            <img--}}
{{--                                src="https://data03.123doks.com/thumbv2/123dok/000/413/413960/78.595.131.494.277.693/tabel-data-hasil-angket.webp"--}}
{{--                                class="w-full h-full object-cover">--}}
{{--                        </a>--}}
{{--                        <a href="#" target="_blank"--}}
{{--                           class="relative h-full grow bg-gray-400 cursor-pointer border border-gray-500 border-solid"--}}
{{--                           style="aspect-ratio: 1">--}}
{{--                            <img--}}
{{--                                src="https://data03.123doks.com/thumbv2/123dok/000/413/413960/94.595.144.479.109.753/tabel-data-hasil-observasi-pertemuan-iii.webp"--}}
{{--                                class="w-full h-full object-cover">--}}
{{--                            <div--}}
{{--                                class="absolute top-0 left-0 w-full h-full bg-black bg-opacity-30 flex items-center justify-center">--}}
{{--                                <span class="text-3.25xl text-white font-medium">+18</span>--}}
{{--                            </div>--}}
{{--                        </a>--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        // References--}}
{{--        <div class="container mx-auto shadow-around mb-4 rounded-1.5lg">--}}
{{--            <div class="bg-white rounded-1.5lg p-5 mx-4 md:mx-0">--}}
{{--                <p class="font-semibold text-xl">Reference</p>--}}
{{--                <div class="mt-4 p-3 border rounded-1.5lg prose break-all">--}}
{{--                    <p>--}}
{{--                        <b>[1]</b> M. Dahl, “A (Fake) Terms-of-Service Contract Asked People to Give Up Their--}}
{{--                        Firstborn,”--}}
{{--                        The Cut, Aug. 23, 2016.--}}
{{--                        <a--}}
{{--                            href="https://www.thecut.com/2016/08/terms-of-service-asked-people-to-give-up-their-firstborn.html">https://www.thecut.com/2016/08/terms-of-service-asked-people-to-give-up-their-firstborn.html</a>--}}
{{--                        (accessed May 11, 2023).--}}
{{--                    </p>--}}
{{--                    <p>--}}
{{--                        <b>[2]</b> S. Ashraf, “What Is Forensic Linguistics? (with pictures),” Language Humanities, Apr.--}}
{{--                        15,--}}
{{--                        2023.--}}
{{--                        <a--}}
{{--                            href="https://www.languagehumanities.org/what-is-forensic-linguistics.htm">https://www.languagehumanities.org/what-is-forensic-linguistics.htm</a>--}}
{{--                    </p>--}}
{{--                    <p>--}}
{{--                        <b>[3]</b> M. Komnenic , “Legalese: Definition & Meaning,” Termly, Apr. 22, 2022. <a--}}
{{--                            href="https://termly.io/resources/articles/legalese/">https://termly.io/resources/articles/legalese/</a>--}}
{{--                        (accessed May 11, 2023)--}}
{{--                    </p>--}}
{{--                    <p>--}}
{{--                        <b>[4]</b> R. Sosas, “The Language of Agreement: A Content Analysis of Employment Contracts,”--}}
{{--                        Journal Of Humanities And Social Science, vol. 22, no. 12, pp. 58–86, Dec. 2017, doi:--}}
{{--                        <a href="https://doi.org/10.9790/0837-2212025886">https://doi.org/10.9790/0837-2212025886</a>.--}}
{{--                    </p>--}}
{{--                    <p>--}}
{{--                        <b>[4]</b> C. Melore, “Here’s why legal documents are so hard to read -- and how to easily fix--}}
{{--                        it,”--}}
{{--                        Study Finds, Mar. 08, 2022.--}}
{{--                        <a--}}
{{--                            href="https://studyfinds.org/legal-documents-so-hard-to-read/">https://studyfinds.org/legal-documents-so-hard-to-read/</a>--}}
{{--                    </p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        {{-- Related Keywords--}}
        <div class="container mx-auto shadow-around rounded-1.5lg">
            <div class="bg-white rounded-1.5lg p-5 mx-4 md:mx-0">
                <p class="font-semibold text-xl">Hashtag</p>
                <div class="mt-4">
                    @foreach($tags as $tag)
                        <a href="{{ route('document.tag.list', ['slug' => $tag->slug]) }}"
                           class="font-light break-all hover:text-primary border border-gray-400 hover:border-primary inline-block rounded-1.5lg my-1 mx-1 px-2 py-1">{{ $tag->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="container mx-auto shadow-around mt-4 rounded-1.5lg pb-4">
            <div class="bg-white rounded-1.5lg p-5 mx-4 md:mx-0">
                <p class="font-semibold text-xl">Comments</p>
                @foreach($comments as $comment)
                    <div class="px-4 md:px-8 py-4 m-4 border border-gray-300 rounded-4.5xl flex flex-col gap-3">
                        <div class="flex flex-row gap-4 justify-between">
                            <p class="w-8/12 lg:w-10/12 font-normal text-sm md:text-base text-primary">{{ $comment->users->name }}</p>
                            <p class="w-4/12 lg:w-2/12 text-end text-xs md:text-base font-light text-default-lighter mr-5">
                                {{ $comment->created_at->toDateString() }}</p>
                        </div>
                        <div class="text-xs md:text-base font-light line-clamp-4 md:line-clamp-3">
                            {{ $comment->content }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div x-data="{open_report_content:true}" x-cloak x-show="open_report" tabindex="-1" aria-hidden="true"
         class="fixed top-0 left-0 right-0 h-screen bg-gray-400 bg-opacity-50  z-50 p-4 overflow-x-hidden overflow-y-auto md:inset-0"
         aria-modal="true" role="dialog">
        <div @click.outside="open_report=false; open_report_content=true"
             x-data="{ message: '', report: ''}" class="relative max-w-2xl max-h-full mx-auto mt-48">
            <!-- Modal content -->
            @if(Auth::check())
                <div x-cloak x-show='open_report_content' class="relative bg-white rounded-lg shadow">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 rounded-t">
                        <h3 class="font-bold text-text-default-darker text-xl mx-auto my-2">
                            Why do you want to report this document?
                        </h3>
                    </div>
                    <!-- Modal body -->
                    <div class="space-y-6 px-6 py-2">
                        <ul class="list-none">
                            <li class="flex flex-row mb-3 items-center">
                                <input type="radio" name="report_radio" id="report_radio_1"
                                       value="infringement" x-model='report'
                                       class="report_radio accent-primary min-w-[20px] w-5 h-5">
                                <label class="ml-2 text-text-default-darker font-light text-base "
                                       for="report_radio_1">This
                                    document contains copyright infringement</label>
                            </li>
                            <li class="flex flex-row mb-3 items-center">
                                <input type="radio" name="report_radio" id="report_radio_2"
                                       value="noconsistent" x-model="report"
                                       class="report_radio accent-primary min-w-[20px] w-5 h-5">
                                <label class="ml-2 text-text-default-darker font-light text-base"
                                       for="report_radio_2">The
                                    content is not consistent with the description</label>
                            </li>
                            <li class="flex flex-row mb-3 items-center">
                                <input type="radio" name="report_radio" id="report_radio_3"
                                       value="duplicated" x-model="report"
                                       class="report_radio accent-primary min-w-5 w-5 h-5">
                                <label class="ml-2 text-text-default-darker font-light text-base"
                                       for="report_radio_3">This
                                    document has been duplicated</label>
                            </li>
                            <li class="flex flex-row mb-3 items-center">
                                <input type="radio" name="report_radio" id="report_radio_4"
                                       value="belongme" x-model="report"
                                       class="report_radio accent-primary min-w-[20px] w-5 h-5">
                                <label class="ml-2 text-text-default-darker font-light text-base"
                                       for="report_radio_4">User
                                    has uploaded a document that belongs to me</label>
                            </li>

                            <li class="flex flex-row mb-3" @click="open_textarea=true">
                                <input type="radio" name="report_radio" id="report_radio_other"
                                       value="other" x-model="report"
                                       class="report_radio accent-primary min-w-[20px] w-5 h-5">
                                <label class="ml-2 text-text-default-darker font-light text-base"
                                       for="report_radio_other">Other
                                    reason</label>
                            </li>
                        </ul>
                        <div x-cloak x-show="report==='other'" class="mt-6 "
                             id="report_other_input">
                            <p class="text-text-default text-base font-medium">Please inform us
                                about your
                                reason to report
                                this question:</p>
                            <textarea rows="4" x-model="message" name="message_other"
                                      class="message_other block mt-3 p-2.5 w-full text-base text-gray-900 rounded-lg border-2 border-text-tag"
                                      placeholder="Write your thoughts here..."></textarea>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex justify-end items-center p-6 space-x-2">
                        <button @click="open_report=false" data-modal-hide="modal_report" type="button"
                                class="text-primary bg-gray-100 font-medium rounded-full text-base px-5 py-2.5 text-center">
                            Cancel
                        </button>
                        <button
                            :disabled="(!message.length && report === 'other') || !report.length"
                            @click="open_report_content=false"
                            data-modal-hide="modal_report" type="submit"
                            id="submit_report"
                            class="text-white bg-primary disabled:opacity-40 hover:bg-opacity-70 rounded-full border border-gray-200 text-sm font-medium px-5 py-2.5 focus:z-10">
                            Send
                        </button>
                    </div>
                </div>
            @else
                <div x-cloak x-show='open_report_content' class="relative bg-white rounded-lg shadow">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 rounded-t">
                        <h3 class="font-bold text-text-default-darker text-xl mx-auto my-2">
                            You need to be logged in to perform this function
                        </h3>
                    </div>
                    <!-- Modal body -->
                    <div class="space-y-6 px-6 py-2">

                    </div>
                    <!-- Modal footer -->
                    <div class="flex justify-end items-center p-6 space-x-2">
                        <button @click="open_report=false" data-modal-hide="modal_report" type="button"
                                class="text-primary bg-gray-100 font-medium rounded-full text-base px-5 py-2.5 text-center">
                            Cancel
                        </button>
                        <a href="{{ route('frontend.auth.getLogin') }}"
                           data-modal-hide="modal_report" type="submit"
                           class="text-white bg-primary disabled:opacity-40 hover:bg-opacity-70 rounded-full border border-gray-200 text-sm font-medium px-5 py-2.5 focus:z-10">
                            Login now
                        </a>
                    </div>
                </div>

            @endif
            <div x-cloak x-show="!open_report_content"
                 class="relative bg-white rounded-lg shadow p-5 max-w-lg">
                <div class="text-right mb-2">
                    <i @click="open_report=false; open_report_content=true"
                       class="fa-solid fa-xmark text-lg cursor-pointer"></i>
                </div>
                <div class=" text-center mb-6 flex items-center justify-center">
                    <svg width="34" height="34" viewBox="0 0 34 34" fill="none"
                         xmlns="http://www.w3.org/2000/svg"
                         xmlns:xlink="http://www.w3.org/1999/xlink">
                        <rect width="34" height="34" fill="url(#pattern0)"/>
                        <defs>
                            <pattern id="pattern0" patternContentUnits="objectBoundingBox"
                                     width="1" height="1">
                                <use xlink:href="#image0_1040_266"
                                     transform="scale(0.0078125)"/>
                            </pattern>
                            <image id="image0_1040_266" width="128" height="128"
                                   xlink:href="data:image/png;base64,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"/>
                        </defs>
                    </svg>

                    <span class="font-medium">Thanks for reporting</span>
                </div>
                <p class="text-center">We will try to solve this as quickly as possible.</p>
            </div>
        </div>
    </div>

@endsection
