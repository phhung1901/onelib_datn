<!-- Main modal -->
<div id="reportModal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full transform translate-y-full transition-transform duration-500 ease-in-out justify-center items-center">
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
                        <input type="radio" name="report_radio" id="report_radio_1" class="accent-primary w-5 h-5 mt-1">
                        <label class="ml-2 text-text-default-darker font-light text-base" for="report_radio_1">This
                            document contains copyright infringement</label>
                    </li>
                    <li class="flex flex-row mb-3">
                        <input type="radio" name="report_radio" id="report_radio_2" class="accent-primary w-5 h-5 mt-1">
                        <label class="ml-2 text-text-default-darker font-light text-base" for="report_radio_2">The
                            content is not consistent with the description</label>
                    </li>
                    <li class="flex flex-row mb-3">
                        <input type="radio" name="report_radio" id="report_radio_3" class="accent-primary w-5 h-5 mt-1">
                        <label class="ml-2 text-text-default-darker font-light text-base" for="report_radio_3">This
                            document has been duplicated</label>
                    </li>
                    <li class="flex flex-row mb-3">
                        <input type="radio" name="report_radio" id="report_radio_4" class="accent-primary w-5 h-5 mt-1">
                        <label class="ml-2 text-text-default-darker font-light text-base" for="report_radio_4">User
                            has uploaded a document that belongs to me</label>
                    </li>
                    <li class="flex flex-row mb-3">
                        <input type="radio" name="report_radio" id="report_radio_5" class="accent-primary w-5 h-5 mt-1">
                        <label class="ml-2 text-text-default-darker font-light text-base" for="report_radio_5">This
                            document contains copyright infringement</label>
                    </li>
                    <li class="flex flex-row mb-3">
                        <input type="radio" name="report_radio" id="report_radio_other" class="accent-primary w-5 h-5 mt-1">
                        <label class="ml-2 text-text-default-darker font-light text-base" for="report_radio_other">Other
                            reason</label>
                    </li>
                </ul>
                <div class="mt-6 hidden" id="report_other_input">
                    <p class="text-text-default text-base font-medium">Please inform us about your reason to report this question:</p>
                    <textarea rows="4" class="block mt-3 p-2.5 w-full text-base text-gray-900 rounded-lg border-1 border-text-tag" placeholder="Write your thoughts here..."></textarea>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="flex justify-end items-center p-6 space-x-2">
                <button data-modal-hide="reportModal" type="button" class="text-primary hover:border-1 hover:border-red-600 hover:text-red-600 font-medium rounded-4xl text-base px-5 py-2.5 text-center">Cancel</button>
                <button data-modal-hide="reportModal" type="button" class="text-white bg-primary hover:bg-opacity-75 rounded-4xl border border-gray-200 text-sm font-medium px-5 py-2.5 focus:z-10">Decline</button>
            </div>
        </div>
    </div>
</div>