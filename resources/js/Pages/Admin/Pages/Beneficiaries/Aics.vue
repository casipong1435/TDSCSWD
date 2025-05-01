<script setup>
import { Head, router } from '@inertiajs/vue3';
import Adminpage from '../../AdminLayout/Adminpage.vue';
import { ref, watch, reactive } from 'vue';
import axios from 'axios';

const searchInput = ref('');

defineProps({
    beneficiaries: Array
});

function gotoAicsEditForm(id) {
    router.get(route('admin.aics_edit_form', id));
}

function filtersearchInput() {
    router.get(route('admin.beneficiaries.aics'),
        {
            searchInput: searchInput.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true
        })
}

watch(searchInput, (value) => {
    if (value == "") {
        router.get(route('admin.beneficiaries.aics'),
            { searchInput: value },
            {
                preserveState: true,
                replace: true
            })
    }
});



const foundData = ref(0);
const result = ref(false);
const canFind = ref(false);
const exportSelected = ref(0);
const processing = ref(false);

const assistance_types = ref([
    {
        id: 1,
        assistance_type: 'Financial Assistance'
    },
    {
        id: 2,
        assistance_type: 'Material Assistance'
    },
    {
        id: 3,
        assistance_type: 'Medial Assistance'
    },
    {
        id: 4,
        assistance_type: 'Educational Assistance'
    },
    {
        id: 5,
        assistance_type: 'Psychosocial Assistance'
    },

]);

const requestData = reactive({
    date_from: null, // Replace with the actual value or ref
    date_to: null,     // Replace with the actual value or ref
    assistance_ids: [], // Replace with the array of assistance IDs
});


function cancelReport() {
    requestData.assistance_ids = [];
    requestData.date_from = null;
    requestData.date_to = null;
    result.value = false;
}

const dropdownModalAssistanceOpen = ref(false);

const toggleModalAssistanceDropdown = () => {
    dropdownModalAssistanceOpen.value = !dropdownModalAssistanceOpen.value;
};

const isAllModalAssistanceSelected = ref(false);

const handleModalSelectAllAssistance = () => {
    if (isAllModalAssistanceSelected.value) {
        requestData.assistance_ids = [];
    } else {
        requestData.assistance_ids = assistance_types.value.map((assistance_type) => assistance_type.id);
        console.log(requestData.assistance_ids);
    }
    isAllModalAssistanceSelected.value = !isAllModalAssistanceSelected.value;

};

watch(() => [requestData.assistance_ids, requestData.date_from, requestData.date_to], ([value1, value2, value3]) => {
    canFind.value = value1.length > 0 && value2 && value3;
});

const findBeneficiaries = async () => {
    processing.value = true;
    try {
        const response = await axios.get(route('admin.findAicsBeneficiaries'), {
            params: {
                assistance_ids: JSON.stringify(requestData.assistance_ids),
                date_from: requestData.date_from,
                date_to: requestData.date_to,
            },
        });

        if (response.data > 0) {
            result.value = true;
            foundData.value = response.data;
            processing.value = false;
      
        } else {
            processing.value = false;
            alert("No Beneficiaries found in the selected parameters.");
        }
    } catch (error) {
        processing.value = false;
        console.error("Error fetching programs:", error);
        alert("An error occurred while fetching programs.");
    }
};
</script>

<template>

    <Head title="AICS" />
    <Adminpage>
        <section class="bg-gray-50 dark:bg-gray-900 p-4 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">

                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="flex md:justify-between md:items-center p-3 flex-col lg:flex-row gap-5">
                        <div class="text-2xl">AICS Beneficiaries</div>
                        <label for="isReportModalOpen"
                            class="py-2.5 px-3 me-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 w-full md:w-36 flex justify-center items-center cursor-pointer">
                            <span>
                                Export
                            </span>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                            </span>

                        </label>
                    </div>
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="w-full md:w-1/2">
                            <div class="flex items-center">
                                <label for="simple-search" class="sr-only">Search</label>
                                <div class="relative w-full">
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
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3">GIS No.</th>
                                    <th scope="col" class="px-4 py-3">Name</th>
                                    <th scope="col" class="px-4 py-3">Age</th>
                                    <th scope="col" class="px-4 py-3">Sex</th>
                                    <th scope="col" class="px-4 py-3">Birthdate</th>
                                    <th scope="col" class="px-4 py-3">Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="benefeciary in beneficiaries" :key="benefeciary.id"
                                    class="border-b dark:border-gray-700">
                                    <td class="px-4 py-3">{{ benefeciary.id }}</td>
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ benefeciary.name }}
                                    </th>
                                    <td class="px-4 py-3">{{ benefeciary.age }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.sex }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.birthdate }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.address }}</td>

                                    <td class="px-4 py-3 gap-2 flex flex-row">
                                        <button type="btn" class="text-blue-700"
                                            @click="gotoAicsEditForm(benefeciary.id)"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>

                                        </button>

                                    </td>

                                </tr>
                                <tr v-if="beneficiaries.length <= 0">
                                    <td colspan="7" class="px-4 py-3 text-center">
                                        No Data Found!
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </section>

        
        <input class="modal-state" id="isReportModalOpen" type="checkbox" />
        <div class="modal" style="z-index: 9999">
            <label class="modal-overlay"></label>
            <div class="modal-content flex flex-col gap-5">
                <label for="isReportModalOpen" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</label>
                <h2 class="text-xl">Beneficiary Report</h2>
                <div class="flex flex-col">
                    <label for="date_from">Date From</label>
                    <input type="date" v-model="requestData.date_from" class="input min-w-full input-ghost-primary">
                </div>
                <div class="flex flex-col">
                    <label for="date_to">Date To</label>
                    <input type="date" v-model="requestData.date_to" class="input min-w-full input-ghost-primary">
                </div>
                <div>
                    <label for="barangay_ids">Select Barangay</label>
                    <div class="relative w-full md:max-w-sm col-span-2 md:col-span-1">
                        <!-- Trigger Button -->
                        <button @click="toggleModalAssistanceDropdown"
                            class="w-full px-4 py-2 text-left border border-gray-300 rounded-md shadow-sm bg-white focus:outline-none">
                            <span class="flex items-center justify-center text-sm">
                                <span>Select Assistance Type </span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </span>
                        </button>

                        <!-- Dropdown -->
                        <div v-if="dropdownModalAssistanceOpen"
                            class="absolute z-10 w-full mt-2 bg-white border border-gray-300 rounded-md shadow-lg">
                            <div class="p-2 gap-y-2 max-h-40 overflow-y-auto">
                                <label class="flex items-center space-x-2 cursor-pointer" for="all">
                                    <input type="checkbox" id="all" :checked="isAllModalAssistanceSelected"
                                        @change="handleModalSelectAllAssistance()"
                                        class="text-blue-600 rounded focus:ring-2 focus:ring-blue-500" />
                                    <span class="text-gray-700">All</span>
                                </label>
                                <label v-for="assistance_type in assistance_types" :key="assistance_type.id"
                                    class="flex items-center space-x-2 cursor-pointer" :for="assistance_type.id">
                                    <input type="checkbox" :id="assistance_type.id" :value="assistance_type.id"
                                        v-model="requestData.assistance_ids"
                                        class="text-blue-600 rounded focus:ring-2 focus:ring-blue-500" />
                                    <span class="text-gray-700">{{ assistance_type.assistance_type }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="result" class="flex justify-between gap-5 justify-center items-center">
                    <div class="inline">
                        <span class="me-2 font-bold">Found:</span>
                        <span>{{ foundData }} Beneficiaries</span>
                    </div>
                    <div class="flex flex-row justify-center items-center">
                        <select v-model="exportSelected">
                            <option selected value="0">Export to Excel</option>
                            <option value="1">Export to PDF</option>
                        </select>
                        <a type="button" target="_blank" :href="exportSelected == 1 ? route('admin.exportToPdfAics', { data: JSON.stringify(requestData)}) : route('admin.exportToExcelAics', { data: JSON.stringify(requestData)})" :class="{'disabled opcacity-80 pointer-events-none' : foundData <= 0, 'text-red-700 hover:text-red-900' : exportSelected == 1, 'text-green-700 hover:text-green-900' : exportSelected == 0}"><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        </a>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button @click="findBeneficiaries" class="btn btn-error btn-block"
                        :class="{ 'disabled opacity-80 pointer-events-none': !canFind || processing }">Find</button>
                    <label for="isReportModalOpen" class="btn btn-block" @click="cancelReport()">Cancel</label>
                </div>
            </div>
        </div>

    </Adminpage>
</template>
