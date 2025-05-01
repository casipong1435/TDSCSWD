<script setup>
import { Head } from '@inertiajs/vue3';
import Adminpage from '../../AdminLayout/Adminpage.vue';
import ProcessingButton from '@/Components/ProcessingButton.vue';
import { ref, watch, reactive } from 'vue';
import { useForm, router, Link } from '@inertiajs/vue3';
import axios from 'axios';

const searchInput = ref('');
const searchInputRequest = ref('');
const inList = ref(true);
const isModalOpen = ref(false);
const isConfirmModalOpen = ref(false);
const processing = ref(false);
const isRespondRequestOpen = ref(false);
const error_msg = ref(null);

defineProps({
    errors: Object,
    program_requests: Array,
    approved_programs: Array
});

const programID = ref(null);

const openInfoModal = (program) => {
    isModalOpen.value = true;
    programID.value = program.id;
    getProgramData(program);
};



const resetFields = () => {
    form.title = null;
    form.purpose = null;
    form.beneficiaries = null;
    form.start_date = null;
    form.end_date = null;
    form.venue = null;
};

const cancelEdit = () => {
    isModalOpen.value = false;
    resetFields();
};

const form = useForm({
    title: null,
    purpose: null,
    beneficiaries: null,
    start_date: null,
    end_date: null,
    venue: null,
});

const respondProgramRequest = () => {
    router.put(route('admin.respondProgramRequest'), program_info, {
        onSuccess: (page) => {
            if(page.props.flash.success){
                resetFields(),
                isRespondRequestOpen.value = false,
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmation: false,
                    title: page.props.flash.success,
                })
            }else{
                error_msg.value = page.props.flash.error
            }
        },
    })
};

function getProgramData(program) {
    form.title = program.title;
    form.purpose = program.purpose;
    form.beneficiaries = program.beneficiaries;
    form.start_date = program.start_date;
    form.end_date = program.end_date;
    form.venue = program.venue;
}

function getStatusName(status) {
    switch (status) {
        case 0:
            return "Pending";
        case 1:
            return "In Beneficiary Selection";
        case 2:
            return "Pending Approval";
        case 3:
            return "Approved";
        case 4:
            return "Rejected";
    }
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

function gotoProgramRoute(id) {
    router.get(route('admin.program_info', id));
}

function filtersearchInput() {
    router.get(route('admin.request.programs'),
        { searchInput: searchInput.value },
        {
            preserveState: true,
            replace: true
        })
}

watch(searchInput, (value) => {
    if (value == "") {
        router.get(route('admin.request.programs'),
            { searchInput: value },
            {
                preserveState: true,
                replace: true
            })
    }
});

function filtersearchInputRequest() {
    router.get(route('admin.request.programs'),
        { searchInputRequest: searchInputRequest.value },
        {
            preserveState: true,
            replace: true
        })
}

watch(searchInputRequest, (value) => {
    if (value == "") {
        router.get(route('admin.request.programs'),
            { searchInputRequest: value },
            {
                preserveState: true,
                replace: true
            })
    }
});


const date_from = ref(null);
const date_to = ref(null);
const foundData = ref([]);
const result = ref(false);
const canFind = ref(false);
const exportSelected = ref(0);


watch([date_from, date_to], ([value1, value2]) => {
    canFind.value = value1 && value2;
});


const findProgram = async () => {
    processing.value = true;
    try {
        const response = await axios.get(route('admin.findPrograms'), {
            params: {
                date_from: date_from.value,
                date_to: date_to.value,
            },
        });

        if (response.data.length > 0) {
            result.value = true;
            foundData.value = response.data;
            console.log(response.data);
            processing.value = false;
        } else {
            processing.value = false;
            alert("No programs found in the selected date range.");
        }
    } catch (error) {
        processing.value = false;
        console.error("Error fetching programs:", error);
        alert("An error occurred while fetching programs.");
    }
};


function cancelReport() {
    date_from.value = null;
    date_to.value = null;
    result.value = false;
}

const program_info = reactive({
    id: null,
    status: null,
    reason: null,
});

function openResponseModal(id, status){
    isRespondRequestOpen.value = true;
    program_info.id = id;
    program_info.status = status;
}

function closeResponseModal(){
    isRespondRequestOpen.value = false;
    program_info.id = null;
    program_info.status = null;
}

</script>

<template>

    <Head title="Programs" />
    <Adminpage>
        <section class="bg-gray-50 dark:bg-gray-900 p-4 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="tabs mb-2">
                    <div class="tab tab-bordered px-6" :class="{ 'tab-active': inList }" @click="inList = true">
                        Requests
                    </div>
                    <div class="tab tab-bordered px-6" :class="{ 'tab-active': !inList }" @click="inList = false">
                        Approved Programs
                    </div>
                </div>
                <div v-if="inList" class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="text-2xl p-2 mb-2">
                        Program Request
                    </div>
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="w-full md:w-1/2">
                            <div class="flex items-center">
                                <label for="simple-search" class="sr-only">Search</label>
                                <div class="relative w-full">
                                    <button type="button"
                                        class="absolute inset-y-0 right-0 flex items-center btn bg-blue-800 rounded-none h-full rounded-r"
                                        @click="filtersearchInput()">
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
                    <div class="breadcrumbs text-sm">
                        <ul>
                            <li>
                                <a>Requests</a>
                            </li>
                            <li>
                                <a></a>
                            </li>
                        </ul>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Title</th>
                                    <th scope="col" class="px-4 py-3">Purpose</th>
                                    <th scope="col" class="px-4 py-3">Start</th>
                                    <th scope="col" class="px-4 py-3">End</th>
                                    <th scope="col" class="px-4 py-3">Venue</th>
                                    <th scope="col" class="px-4 py-3">Status</th>
                                    <th scope="col" class="px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b dark:border-gray-700" v-for="program in program_requests"
                                    :key="program.id">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ program.title }}
                                    </th>
                                    <td class="px-4 py-3">{{ program.purpose }}</td>
                                    <td class="px-4 py-3">{{ program.start_date }}</td>
                                    <td class="px-4 py-3">{{ program.end_date }}</td>
                                    <td class="px-4 py-3">{{ program.venue }}</td>
                                    <td class="px-4 py-3">
                                        <span class="badge " :class="{'badge-warning':program.status == 0, 'badge-error':program.status == 4}">
                                            {{ getStatusName(program.status) }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 gap-2 flex flex-row">
                                        <button type="button" @click="openInfoModal(program)" class="text-blue-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>


                                        </button>
                                        <button v-if="program.status == 0" type="button"
                                            class="text-green-600" @click="openResponseModal(program.id, 1)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 12.75 6 6 9-13.5" />
                                            </svg>

                                        </button>
                                        <button v-if="program.status == 0" type="button"
                                            class="text-red-600" @click="openResponseModal(program.id, 4)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>

                                        </button>
                                        <button v-else type="button" @click="openResponseModal(program.id, 0)"
                                             class="text-red-600">

                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18 18 6M6 6l12 12" />
                                            </svg>


                                        </button>
                                    </td>

                                </tr>
                                <tr v-if="program_requests.length <= 0">
                                    <td colspan="7" class="px-4 py-3 text-center">No data found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div v-else class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="flex justify-between items-center p-4">
                        <div class="text-2xl p-2 mb-2">
                            Programs
                        </div>
                        <label for="ReportModal" class="text-blue-700 hover:text-blue-900 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Zm3.75 11.625a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>

                        </label>
                    </div>
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="w-full md:w-1/2">
                            <div class="flex items-center">
                                <label for="simple-search" class="sr-only">Search</label>
                                <div class="relative w-full">
                                    <button type="button"
                                        class="absolute inset-y-0 right-0 flex items-center btn bg-blue-800 rounded-none h-full rounded-r"
                                        @click="filtersearchInputRequest()">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-100 dark:text-gray-400"
                                            fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <input type="text" id="searchInputRequest" v-model="searchInputRequest"
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
                                    <th scope="col" class="px-4 py-3">Title</th>
                                    <th scope="col" class="px-4 py-3">Start</th>
                                    <th scope="col" class="px-4 py-3">End</th>
                                    <th scope="col" class="px-4 py-3">No. of Beneficiaries</th>
                                    <th scope="col" class="px-4 py-3">Date Created</th>
                                    <th scope="col" class="px-4 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-200 cursor-pointer"
                                    v-for="program in approved_programs" :key="program.id"
                                    @click="gotoProgramRoute(program.id)">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ program.title }}
                                    </th>
                                    <td class="px-4 py-3">{{ program.start_date }}</td>
                                    <td class="px-4 py-3">{{ program.end_date }}</td>
                                    <td class="px-4 py-3">{{ program.status < 2 ? '--' :
                                        program.program_beneficiary.length }}</td>
                                    <td class="px-4 py-3">{{ formattedDate(program.created_at) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="badge"
                                            :class="{ 'badge-primary': program.status == 1, 'badge-warning': program.status == 2, 'badge-success': program.status == 3 }">
                                            {{ getStatusName(program.status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr v-if="approved_programs.length <= 0">
                                    <td colspan="7" class="px-4 py-3 text-center">No data found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!--Modal for request-->
            <input class="modal-state" id="addEditModal" type="checkbox" v-model="isModalOpen" />
            <div class="modal !h-full !items-start overflow-y-auto" style="z-index: 9999">
                <label class="modal-overlay" for="addEditModal"></label>
                <div class="relative p-4 w-80 max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-2 md:p-2 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Edit Program Info
                            </h3>
                            <button @click="cancelEdit" type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5 space-y-4">
                            <div class="">
                                <div class="relative">
                                    <div class="relative mb-2">
                                        <input disabled v-model="form.title" type="text" id="floating_title"
                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " />
                                        <label for=" floating_title"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Title</label>
                                    </div>
                                    <div class="relative mb-2">
                                        <textarea disabled v-model="form.purpose"
                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " id="floating_purpose"></textarea>

                                        <label for="floating_purpose"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Purpose</label>
                                    </div>

                                    <div class="relative mb-2">
                                        <input disabled v-model="form.venue" type="text" id="floating_venue"
                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " />
                                        <label for="floating_venue"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Venue</label>
                                    </div>

                                    <div class="relative mb-2">
                                        <input disabled v-model="form.start_date" type="date" id="floating_start_date"
                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " />
                                        <label for="floating_start_date"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Start
                                            Date</label>
                                    </div>

                                    <div class="relative mb-2">
                                        <input disabled v-model="form.end_date" type="date" id="floating_end_date"
                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " />
                                        <label for="floating_end_date"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">End
                                            Date</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button @click="cancelEdit" type="button"
                                class="py-2.5 px-5 ms-3 mx-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                Close
                            </button>

                        </div>
                    </div>
                </div>
            </div>

        </section>

        <!--Report Modal-->

        <input class="modal-state" id="ReportModal" type="checkbox" />
        <div class="modal" style="z-index: 9999">
            <label class="modal-overlay"></label>
            <div class="modal-content flex flex-col gap-5">
                <label for="ReportModal" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</label>
                <h2 class="text-xl">Program Report</h2>
                <div>
                    <label for="date_from">Date From</label>
                    <input type="date" class="input input-ghost-primary" v-model="date_from">
                </div>
                <div>
                    <label for="date_from">Date To</label>
                    <input type="date" class="input" v-model="date_to">
                </div>
                <div v-if="result" class="flex justify-between gap-5 justify-center items-center">
                    <div class="inline">
                        <span class="me-2 font-bold">Found:</span>
                        <span>{{ foundData.length }} Program</span>
                    </div>
                    <div class="flex flex-row justify-center items-center">
                        <select v-model="exportSelected">
                            <option selected value="0">Export to Excel</option>
                            <option value="1">Export to PDF</option>
                        </select>
                        <a type="button" target="_blank"
                            :href="exportSelected == 1 ? route('admin.programReport', { from: date_from, to: date_to }) : route('admin.exportToExcelProgram', { from: date_from, to: date_to })"
                            :class="{ 'disabled opcacity-80 pointer-events-none': foundData <= 0, 'text-red-700 hover:text-red-900': exportSelected == 1, 'text-green-700 hover:text-green-900': exportSelected == 0 }"><svg
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button @click="findProgram" class="btn btn-error btn-block"
                        :class="{ 'disabled opacity-80 pointer-events-none': !canFind || processing }">Find</button>
                    <label for="ReportModal" class="btn btn-block" @click="cancelReport()">Cancel</label>
                </div>
            </div>
        </div>

        <!--Respond Request Modal-->
        <input class="modal-state" id="deleteModal" type="checkbox" v-model="isRespondRequestOpen" />
        <div class="modal" style="z-index: 9999">
            <label class="modal-overlay"></label>
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button" @click="closeResponseModal()"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                            Are you sure you want to {{ program_info.status == 1 ? 'approved' : program_info.status == 4 ? 'reject' : 'cancel rejection of ' }} this request?
                        </h3>
                        <div v-if="program_info.status == 4" class="my-2 block">
                            <label for="reason" class="mb-1">Reason</label>
                            <textarea id="reason" class="min-w-full" v-model="program_info.reason"></textarea>
                            <span v-if="error_msg" class="text-red-700">{{ error_msg }}</span>
                        </div>

                        <ProcessingButton type="button" :process="form.processing" :text="'Confirm'"
                            :class="{'bg-red-600 hover:bg-red-800' : program_info.status == 4, 'bg-green-600 hover:bg-green-800' : program_info.status == 1 || program_info.status == 0}" @click="respondProgramRequest()" />
                        <button type="button" @click="closeResponseModal()"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            No, cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </Adminpage>
</template>
