<script setup>
import { Head } from '@inertiajs/vue3';
import Staffpage from '../StaffLayout/Staffpage.vue';
import ProcessingButton from '@/Components/ProcessingButton.vue';
import { ref, watch, reactive } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import axios from 'axios';

const searchInput = ref('');
const searchInputRequest = ref('');
const inList = ref(true);
const isAdd = ref(true);
const isModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const processing = ref(false);
const error_msg = ref(null);
const isStatusInfoModal = ref(false);

defineProps({
    errors: Object,
    program_requests: Array,
    approved_programs: Array
});

function formattedDate(dateCreated) {
    const date = new Date(dateCreated);
    const stringifyDate = date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    return stringifyDate;
}

const openAddModal = () => {
    isAdd.value = true;
    isModalOpen.value = true;
};

const programID = ref(null);
const openEditModal = (program) => {
    isAdd.value = false;
    isModalOpen.value = true;
    programID.value = program.id;
    getProgramData(program);
};

const action_status = ref(null);

const openDeleteModal = (id, status) => {
    isDeleteModalOpen.value = true;
    programID.value = id;
    action_status.value = status
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
    reason: null,

});

const program_info = reactive({
    reason: null,
    date_rejected: null,
    status: null
});

const AddProgram = () => {
    form.post(route('staff.create-program'), {
        onSuccess: (page) => {
            resetFields(),
                isModalOpen.value = false,
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmation: false,
                    title: page.props.flash.success,
                })
        },
    })
};

const SaveProgram = () => {
    form.put(route('staff.update-program', programID.value), {
        onSuccess: (page) => {
            resetFields(),
                isModalOpen.value = false,
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmation: false,
                    title: page.props.flash.success,
                })
        },
    })
};

const deleteRequest = () => {
    form.delete(route('staff.delete-program', programID.value), {
        onSuccess: (page) => {
            if (page.props.flash.success) {
                resetFields(),
                    isDeleteModalOpen.value = false,
                    error_msg.value = null,
                    Swal.fire({
                        toast: true,
                        icon: "success",
                        position: "top-end",
                        showConfirmation: false,
                        title: page.props.flash.success,
                    })
            } else {
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

function gotoProgramRoute(id) {
    router.get(route('staff.program_info', id));
}

function filtersearchInput() {
    router.get(route('staff.programs'),
        { searchInput: searchInput.value },
        {
            preserveState: true,
            replace: true
        })
}

watch(searchInput, (value) => {
    if (value == "") {
        router.get(route('staff.programs'),
            { searchInput: value },
            {
                preserveState: true,
                replace: true
            })
    }
});

function filtersearchInputRequest() {
    router.get(route('staff.programs'),
        { searchInputRequest: searchInputRequest.value },
        {
            preserveState: true,
            replace: true
        })
}

watch(searchInputRequest, (value) => {
    if (value == "") {
        router.get(route('staff.programs'),
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
        const response = await axios.get(route('staff.findPrograms'), {
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

function openStatusModal(program) {
    isStatusInfoModal.value = true;
    program_info.reason = program.reason;
    program_info.date_rejected = program.date_rejected;
    program_info.status = program.status;
}

function closeStatusModal() {
    isStatusInfoModal.value = false;
    program_info.reason = null;
    program_info.date_rejected = null;
    program_info.status = null;
}

</script>

<template>

    <Head title="Staff Programs" />
    <Staffpage>
        <section class="bg-gray-50 dark:bg-gray-900 p-4 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="breadcrumbs text-sm">
                    <ul>
                        <li>
                            <a>Program</a>
                        </li>
                        <li></li>
                    </ul>
                </div>
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
                        Staff Program Request
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
                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <button type="button" @click="openAddModal"
                                class="flex items-center justify-center text-white bg-blue-800 hover:bg-blue-900 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                + Create Program
                            </button>
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
                                    :key="program.id"
                                    @click="program.status != 0 && program.status != 4 ? gotoProgramRoute(program.id) : ''"
                                    :class="{ 'hover:bg-gray-200 cursor-pointer': program.status != 0 && program.status != 4 }">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ program.title }}
                                    </th>
                                    <td class="px-4 py-3">{{ program.purpose }}</td>
                                    <td class="px-4 py-3">{{ program.start_date }}</td>
                                    <td class="px-4 py-3">{{ program.end_date }}</td>
                                    <td class="px-4 py-3">{{ program.venue }}</td>
                                    <td class="px-4 py-3">
                                        <span class="badge"
                                            :class="program.status == 0 ? 'badge-warning' : 'badge-error'">
                                            {{ getStatusName(program.status) }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 gap-2 flex flex-row">
                                        <button type="button" @click="openEditModal(program)" class="text-blue-600"
                                            v-if="program.status == 0">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>

                                        </button>
                                        <button v-if="program.status == 4" type="button"
                                            @click="openStatusModal(program)" class="text-blue-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                            </svg>

                                        </button>
                                        <button v-if="program.status == 0" type="button"
                                            @click="openDeleteModal(program.id, 0)" class="text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18 18 6M6 6l12 12" />
                                            </svg>


                                        </button>

                                        <button v-else type="button" @click="openDeleteModal(program.id, 4)"
                                            class="text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>


                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="program_requests.length <= 0">
                                    <td colspan="6" class="px-4 py-3 text-center">No data found</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div v-else class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="flex justify-between items-center p-4">
                        <div class="text-2xl">
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
                <form @submit.prevent="isAdd ? AddProgram() : SaveProgram()">
                    <div class="relative p-4 w-full max-w-2xl max-h-full">
                        <!-- Modal content -->
                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                            <!-- Modal header -->
                            <div
                                class="flex items-center justify-between p-2 md:p-2 border-b rounded-t dark:border-gray-600">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ isAdd ? "Create Program" : "Edit Program" }}
                                </h3>
                                <button @click="cancelEdit" type="button"
                                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                            </div>
                            <!-- Modal body -->
                            <div class="p-4 md:p-5 space-y-4">
                                <div class="" style="width: 280px">
                                    <div class="relative">
                                        <div class="relative mb-2">
                                            <input v-model="form.title" type="text" id="floating_title"
                                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                placeholder=" " :class="{
                                                    'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500':
                                                        errors.title,
                                                }" />
                                            <label for="floating_title"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Title</label>
                                        </div>
                                        <div class="relative mb-2">
                                            <textarea v-model="form.purpose"
                                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                placeholder=" " id="floating_purpose" :class="{
                                                    'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500':
                                                        errors.purpose,
                                                }"></textarea>

                                            <label for="floating_purpose"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                                :class="{
                                                    'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ':
                                                        errors.purpose,
                                                }">Purpose</label>
                                        </div>

                                        <div class="relative mb-2">
                                            <input v-model="form.venue" type="text" id="floating_venue"
                                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                :class="{
                                                    'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500':
                                                        errors.venue,
                                                }" placeholder=" " />
                                            <label for="floating_venue"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                                :class="{
                                                    'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ':
                                                        errors.venue,
                                                }">Venue</label>
                                        </div>

                                        <div class="relative mb-2">
                                            <input v-model="form.start_date" type="date" id="floating_start_date"
                                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                :class="{
                                                    'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500':
                                                        errors.start_date,
                                                }" placeholder=" " />
                                            <label for="floating_start_date"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Start
                                                Date</label>
                                        </div>

                                        <div class="relative mb-2">
                                            <input v-model="form.end_date" type="date" id="floating_end_date"
                                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                :class="{
                                                    'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500':
                                                        errors.end_date,
                                                }" placeholder=" " />
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
                                    Cancel
                                </button>

                                <ProcessingButton type="submit" :text="isAdd ? 'Submit' : 'Save'"
                                    :process="form.processing" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </section>

        <!--Delete Request Modal-->
        <input class="modal-state" id="deleteModal" type="checkbox" v-model="isDeleteModalOpen" />
        <div class="modal" style="z-index: 9999">
            <label class="modal-overlay"></label>
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button" @click="isDeleteModalOpen = false"
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
                            Are you sure you want to {{ action_status == 0 ? 'cancel' : 'delete' }} this request?
                        </h3>
                        <div class="my-2 block">
                            <label for="reason" class="mb-1">Reason</label>
                            <textarea id="reason" class="min-w-full" v-model="form.reason"></textarea>
                            <span v-if="error_msg" class="text-red-700">{{ error_msg }}</span>
                        </div>

                        <ProcessingButton type="button" :process="form.processing" :text="'Confirm'"
                            class="bg-red-600 hover:bg-red-800" @click="deleteRequest()" />
                        <button type="button" @click="isDeleteModalOpen = false"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            No, cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

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
                            :href="exportSelected == 1 ? route('staff.programReport', { from: date_from, to: date_to }) : route('staff.exportToExcelProgram', { from: date_from, to: date_to })"
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

        <input class="modal-state" id="statusInfo" type="checkbox" v-model="isStatusInfoModal" />
        <div class="modal" style="z-index: 9999">
            <label class="modal-overlay"></label>
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">

                    <button type="button" @click="cancelViewStatusInfo()"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 r ounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="text-start p-2">
                        <h2 class="text-xl">Request Status</h2>
                    </div>
                    <div class="py-2 px-3 grid grid-cols-1 gap-2 text-sm">

                        <div class="flex flex-col py-2 gap-2">
                            <div class="flex justify-between items-center font-bold">
                                <span>Status:</span>
                                <span class="badge"
                                    :class="{ 'badge-error': program_info.status == 4, 'badge-warning': program_info.status == 0 }">{{
                                        program_info.status == 4 ? 'Rejected' : 'Pending' }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span>Date {{ program_info.status == 0 ? 'Approved' : 'Rejected' }} :</span>
                                <span>{{ program_info.status == 0 ? '--' : program_info.date_rejected }}</span>
                            </div>

                            <div v-if="program_info.status == 4" class="flex justify-between items-center text-xs">
                                <span>Reason:</span>
                                <span>{{ program_info.reason }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end items-center p-3">
                        <button type="button" @click="closeStatusModal()"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Staffpage>
</template>
