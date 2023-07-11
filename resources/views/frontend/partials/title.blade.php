<div class="mt-9">
    <div class="flex flex-col h-full mx-4 lg:mx-0">
        <div class="basis-2/3 mb-4 flex md:flex-row flex-col">
            <div class="text-2xl font-bold md:basis-2/3 my-auto leading-loose">
                Solutions Fundamentals of Futures and Options Markets 7e by Hull
                Chapter 07
            </div>
            <div class="md:basis-1/3 flex md:justify-end mt-3 md:mt-0">
                <button type="button"
                        class="lg:w-1/4 h-12 bg-opacity-20 text-base text-primary bg-search hover:text-white hover:bg-primary focus:ring-4 focus:outline-none focus:ring-primary font-medium rounded-2lg px-5 py-2.5 text-center flex justify-center items-center mr-2">
                    <i class="fa-solid fa-thumbs-up mr-2"></i>
                    0
                    <p class="lg:block hidden ml-1">Helpful</p>
                </button>
                <button type="button"
                        class="lg:w-1/3 h-12 bg-opacity-20 text-base text-primary bg-search hover:text-white hover:bg-primary focus:ring-4 focus:outline-none focus:ring-primary font-medium rounded-2lg px-5 py-2.5 text-center flex justify-center items-center mr-2">
                    <i class="fa-solid fa-thumbs-down mr-2"></i>
                    0
                    <p class="lg:block hidden ml-1">Unhelpful</p>
                </button>
                <button type="button" id="dropdownShare" data-dropdown-toggle="dropdown"
                        class="h-12 bg-opacity-20 text-base text-text-default bg-search hover:bg-primary hover:text-white focus:ring-4 focus:outline-none focus:ring-primary font-medium rounded-2lg px-5 py-2.5 text-center flex justify-center items-center mr-2">
                    <i class="fa-solid fa-link"></i>
                </button>
                <!-- Dropdown menu -->
                <div id="dropdown"
                     class="z-10 w-96 hidden bg-white divide-y divide-gray-100 mt-4 border-1 border-primary rounded-md">
                    <div class="mx-6 py-3 text-sm text-gray-700 dark:text-gray-200"
                         aria-labelledby="dropdownShare">
                        <p class="text-text-default-darker text-2xl font-medium mb-4">Share this link with a
                            friend: </p>
                        <input type="text" id="document_share"
                               value="https://www.figma.com/file/0ZW2yQ3YX90maBglVO71nL/123dok-web-design?type=design&node-id=651-35328&t=pZZW0IIV6SMVfxQy-0"
                               class="mb-2 border-1 border-search px-4 py-2 w-full font-normal text-xl rounded-2lg">
                        <div id="document_share_message" class="mt-2 text-[#F616B8] font-normal text-lg"></div>
                    </div>
                </div>

                <button type="button" data-modal-target="reportModal" data-modal-toggle="reportModal"
                        onclick="showModalReport('reportModal')"
                        class="transition duration-150 ease-in-out h-12 bg-opacity-20 text-base text-text-default bg-search hover:bg-primary hover:text-white focus:ring-4 focus:outline-none focus:ring-primary font-medium rounded-2lg px-5 py-2.5 text-center flex justify-center items-center">
                    <i class="fa-solid fa-flag"></i>
                </button>


                <!-- Main modal -->
                <div id="reportModal" tabindex="-1" aria-hidden="true"
                     class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full  transform translate-y-full transition-transform duration-500 ease-in-out">
                    <div class="relative w-full max-w-2xl max-h-full">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow">
                            <!-- Modal header -->
                            <div class="flex items-start justify-between p-4 rounded-t">
                                <h3 class="font-bold text-text-default-darker text-xl mx-auto my-2">
                                    Why do you want to report this document?
                                </h3>
                            </div>
                            <!-- Modal body -->
                            <div class="space-y-6 px-6 py-2">
                                <ul class="list-none">
                                    <li class="flex flex-row mb-3">
                                        <input type="radio" name="report_radio" id="report_radio_1"
                                               class="accent-primary w-5 h-5 mt-1">
                                        <label class="ml-2 text-text-default-darker font-light text-base"
                                               for="report_radio_1">This
                                            document contains copyright infringement</label>
                                    </li>
                                    <li class="flex flex-row mb-3">
                                        <input type="radio" name="report_radio" id="report_radio_2"
                                               class="accent-primary w-5 h-5 mt-1">
                                        <label class="ml-2 text-text-default-darker font-light text-base"
                                               for="report_radio_2">The
                                            content is not consistent with the description</label>
                                    </li>
                                    <li class="flex flex-row mb-3">
                                        <input type="radio" name="report_radio" id="report_radio_3"
                                               class="accent-primary w-5 h-5 mt-1">
                                        <label class="ml-2 text-text-default-darker font-light text-base"
                                               for="report_radio_3">This
                                            document has been duplicated</label>
                                    </li>
                                    <li class="flex flex-row mb-3">
                                        <input type="radio" name="report_radio" id="report_radio_4"
                                               class="accent-primary w-5 h-5 mt-1">
                                        <label class="ml-2 text-text-default-darker font-light text-base"
                                               for="report_radio_4">User
                                            has uploaded a document that belongs to me</label>
                                    </li>
                                    <li class="flex flex-row mb-3">
                                        <input type="radio" name="report_radio" id="report_radio_5"
                                               class="accent-primary w-5 h-5 mt-1">
                                        <label class="ml-2 text-text-default-darker font-light text-base"
                                               for="report_radio_5">This
                                            document contains copyright infringement</label>
                                    </li>
                                    <li class="flex flex-row mb-3">
                                        <input type="radio" name="report_radio" id="report_radio_other"
                                               class="accent-primary w-5 h-5 mt-1">
                                        <label class="ml-2 text-text-default-darker font-light text-base"
                                               for="report_radio_other">Other
                                            reason</label>
                                    </li>
                                </ul>
                                <div class="mt-6 hidden" id="report_other_input">
                                    <p class="text-text-default text-base font-medium">Please inform us about
                                        your reason to report
                                        this question:</p>
                                    <textarea rows="4"
                                              class="block mt-3 p-2.5 w-full text-base text-gray-900 rounded-lg border-1 border-text-tag"
                                              placeholder="Write your thoughts here..."></textarea>
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div class="flex justify-end items-center p-6 space-x-2">
                                <button data-modal-hide="reportModal" type="button"
                                        class="colo text-primary hover:border-1 hover:border-red-600 hover:text-red-600 font-medium rounded-4xl text-base px-5 py-2.5 text-center">
                                    Cancel
                                </button>
                                <button data-modal-hide="reportModal" type="button"
                                        class="text-white bg-primary hover:bg-opacity-75 rounded-4xl border border-gray-200 text-base font-medium px-5 py-2.5 focus:z-10">
                                    Send
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="md:basis-1/3 flex justify-start md:flex-row flex-col">
            <div class="font-medium flex flex-row text-text-default mt-3 md:w-1/3">
                <i class="fa-solid fa-book-open mt-1"></i>
                <p class="ml-2">
                    Đại học Công nghiệp Thực phẩm Thành phố Hồ Chí Minh
                </p>
            </div>
            <div class="md:ml-7 font-medium flex flex-row text-text-default mt-3">
                <i class="fa-solid fa-book mt-1"></i>
                <p class="ml-2">Criminal Law</p>
            </div>
            <div class="md:ml-7 font-medium flex flex-row text-text-default mt-3">
                <i class="fa-solid fa-user mt-1"></i>
                <p class="ml-2">AgentDovePerson630</p>
            </div>
            <div class="md:ml-7 font-medium flex flex-row text-text-default mt-3">
                <i class="fa-solid fa-calendar-days mt-1"></i>
                <p class="ml-2">
                    Academic year: <span class="text-[#99999A]">2021</span>
                </p>
            </div>
        </div>
    </div>
</div>
