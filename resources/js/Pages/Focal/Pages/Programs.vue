<script setup>
import { Head } from '@inertiajs/vue3';
import Focalpage from '../FocalLayout/Focalpage.vue';
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

defineProps({
    role: String,
    programs: Array,
});

const searchInput = ref('');

function gotoProgramRoute(id) {
    router.get(route('focal.program_info', id));
}

function formattedDate(dateCreated) {
    const date = new Date(dateCreated);
    const stringifyDate = date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    return stringifyDate;
}

function filtersearchInput(){
    router.get(route('focal.programs'),
        { searchInput: searchInput.value },
        {
            preserveState: true,
            replace: true
        })
}

watch(searchInput, (value) => {
    if (value == ""){
        router.get(route('focal.programs'),
        { searchInput: value },
        {
            preserveState: true,
            replace: true
        })
    }
});

</script>

<template>

    <Head title="Programs" />
    <Focalpage>
        <div class="bg-gray-50 p-4 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="text-2xl p-2 mb-2">Programs</div>
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="relative w-full md:w-2/4">
                                    <button type="button" class="absolute inset-y-0 right-0 flex items-center btn bg-blue-800 rounded-none h-full rounded-r" @click="filtersearchInput()">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-100 dark:text-gray-400"
                                            fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <input type="text" id="searchInput" v-model="searchInput"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="Search">
                        </div>

                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Title</th>
                                    <th scope="col" class="px-4 py-3">Start</th>
                                    <th scope="col" class="px-4 py-3">End</th>
                                    <th scope="col" class="px-4 py-3">Venue</th>
                                    <th scope="col" class="px-4 py-3">Date Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-200 cursor-pointer" v-for="program in programs" :key="program.id" @click="gotoProgramRoute(program.id)">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ program.title }}
                                    </th>
                                    <td class="px-4 py-3">{{ program.start_date }}</td>
                                    <td class="px-4 py-3">{{ program.end_date }}</td>
                                    <td class="px-4 py-3">{{ program.venue }}</td>
                                    <td class="px-4 py-3">{{ formattedDate(program.created_at) }}</td>
                                    
                                </tr>
                                <tr v-if="programs.length <= 0">
                                    <td colspan="5" class="px-4 py-3 text-center">No data found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </Focalpage>
</template>
