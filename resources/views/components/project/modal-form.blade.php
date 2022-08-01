<!-- This example requires Tailwind CSS v2.0+ -->
<div x-bind="dialog" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">

    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    <form id="modal-form">
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div x-bind="dialog"
                 class="flex items-end items-center justify-center min-h-full p-4 text-center sm:p-0">
                <div
                    class="relative bg-bgMain text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
                    <div class="bg-bgMain px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="hidden sm:inline-block w-full text-center mb-3">New Project</h3>
                        <div class="flex flex-row justify-between">
                            <div class="w-1/2">
                                <label class="block uppercase font-bold text-xs text-gray-700"
                                       for="title">Title</label>
                                <input class="border border-gray-400 p-2 w-full text-sm"
                                       x-model="project.title"
                                       type="text" name="title" id="title"
                                >
                                <p x-bind="titleError" class="text-xs text-red-600"></p>
                                <label class="block uppercase font-bold text-xs text-gray-700 mt-2"
                                       for="description">Description</label>
                                <textarea class="border border-gray-400 p-2 w-full text-sm"
                                          x-model="project.description"
                                          name="description" id="description"
                                          rows="4"
                                ></textarea>
                                <p x-bind="descriptionError" class="text-xs text-red-600"></p>
                            </div>
                            <div class="w-1/2 ml-4">
                                <span class="block uppercase font-bold text-xs">&nbsp;</span>
                                    <template x-for="task in project.tasks">
                                        <input class="w-full p-2 text-sm border border-gray-400 mb-2"
                                               x-model="task.body"
                                               type="text" name="tasks[][body]"
                                               placeholder="A New Task"
                                        >
                                    </template>
                                <button x-bind="addTask" type="button">
                                    <div class="flex flex-row items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                             viewBox="0 0 24 24" stroke="gray" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <p class="text-sm ml-1">Add a New Task</p>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="bg-bgMain px-4 py-3 sm:px-6 flex flex-row-reverse">
                        <x-button x-bind="trigger" type="button"
                                  :button-style="'bg-white border-gray-400 text-gray-700 hover:text-white'"
                        >Cancel
                        </x-button>
                        @csrf
                        <x-button class="mr-2"
                                  x-bind="submit"
                        >Submit
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
