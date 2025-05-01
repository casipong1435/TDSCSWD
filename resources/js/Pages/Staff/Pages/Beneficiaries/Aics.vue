<script setup>
import { Head, Link, router, usePage, useForm } from '@inertiajs/vue3';
import ProcessingButton from '@/Components/ProcessingButton.vue';
import Staffpage from '../../StaffLayout/Staffpage.vue';
import { ref, onMounted, watch, reactive } from 'vue';
import axios from 'axios';


defineProps({
    errors: Object,
    aics_staff: Array,
    aics_mayor: Array,
    requested_beneficiaries: Array,
    beneficiaries: Array
});

const { props } = usePage();

const inList = ref(true);
const searchInputRequest = ref('');
const isDeleteModalOpen = ref(false);
const isStaffModalOpen = ref(false);
const gisID = ref(null);
const staffID = ref(null);
const staffStatus = ref(null);
const processing = ref(false);
const isAddModal = ref(false);
const isArchiveModal = ref(false);

const form = useForm({
    aics_staff: [],
    aics_mayor: [],
});

const addForm = useForm({
    position: null,
    name: null,
    role: null
});

onMounted(() => {
    form.aics_staff = props.aics_staff;
    form.aics_mayor = props.aics_mayor;
});

function gotoAicsEditForm(id) {
    router.get(route('staff.aics_edit_form', id));
}


function openDeleteModal(id) {
    isDeleteModalOpen.value = true;
    gisID.value = id;
}

const deleteBeneficiaryData = () => {
    router.delete(route('staff.delete-aics-beneficiary', gisID.value), {}, {
        onSuccess: (page) => {
            Swal.fire({
                toast: true,
                icon: "success",
                position: "top-end",
                showConfirmation: true,
                title: page.props.flash.success,
            })
            //form.reset();
        },
        onError: (page) => {
            Swal.fire({
                toast: true,
                icon: "success",
                position: "top-end",
                showConfirmation: true,
                title: page.props.flash.error,
            })
        }
    });
};

const addStaff = () => {
    isAddModal.value = true;
    addForm.role = 1;
};

const addMayor = () => {
    isAddModal.value = true;
    addForm.role = 0;
};

function cancelAdd() {
    isAddModal.value = false;
    addForm.role = null;
    addForm.name = null;
    addForm.position = null;
}

function openArchiveStaff(id, status) {
    isArchiveModal.value = true;
    staffID.value = id;
    staffStatus.value = status;
}


function cancelStaffEdit() {
    isStaffModalOpen.value = false;
    form.aics_staff = props.aics_staff;
    form.aics_mayor = props.aics_mayor;
}

const submitStaffEdit = () => {
    form.put(route('staff.edit-aics-staff'), {
        onSuccess: (page) => {
            Swal.fire({
                toast: true,
                icon: "success",
                position: "top-end",
                showConfirmation: true,
                title: page.props.flash.success,
            }),
                isStaffModalOpen.value = false;
                window.location.reload();
        }
    })
};

const submitStaffArchive = () => {
    processing.value = true;
    const status = staffStatus.value == 1 ? 0 : 1;
    router.put(route('staff.archive-aics-staff', {id: staffID.value, status:status}), {}, {
        onSuccess: (page) => {
            Swal.fire({
                toast: true,
                icon: "success",
                position: "top-end",
                showConfirmation: true,
                title: page.props.flash.success,
            }),
                isArchiveModal.value = false;
            processing.value = false;
        }
    })
};

const createAicsPersonnel = () => {
    processing.value = true;
    addForm.post(route('staff.add-aics-staff'), {
        onSuccess: (page) => {
            Swal.fire({
                toast: true,
                icon: "success",
                position: "top-end",
                showConfirmation: true,
                title: page.props.flash.success,
            }),
                isAddModal.value = false;
            processing.value = false;
        },
        onError: () => processing.value = false
    })
};

function filtersearchInput() {
    router.get(route('staff.aics'),
        { searchInputRequest: searchInputRequest.value },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true
        })
}

watch(searchInputRequest, (value) => {
    if (value == "") {
        router.get(route('staff.aics'),
            { searchInputRequest: value },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true
            })
    }
});

const foundData = ref(0);
const result = ref(false);
const canFind = ref(false);
const exportSelected = ref(0);

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
        const response = await axios.get(route('staff.findAicsBeneficiaries'), {
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
    <Staffpage>
        <section class="bg-gray-50 dark:bg-gray-900 p-4 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="flex justify-between items-center">
                    <div class="tabs mb-2">
                        <div class="tab tab-bordered px-6" :class="{ 'tab-active': inList }" @click="inList = true">
                            Requests
                        </div>
                        <div class="tab tab-bordered px-6" :class="{ 'tab-active': !inList }" @click="inList = false">
                            AICS Beneficiary
                        </div>
                    </div>
                    <label for="staffModal" class="flex items-center cursor-pointer text-blue-600">
                        <span class="me-2">Staffs</span>
                        <span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                            </svg></span>
                    </label>
                </div>
                <div v-if="inList" class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">

                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="text-2xl p-2 mb-2">
                            AICS Requests
                        </div>
                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <Link :href="route('staff.aics_form')"
                                class="flex items-center justify-center text-white bg-blue-800 hover:bg-blue-900 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                            + Add Beneficiary
                            </Link>
                        </div>
                    </div>
                    <div class="breadcrumbs text-sm">
                        <ul>
                            <li>
                                <a>AICS Requests</a>
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
                                    <th scope="col" class="px-4 py-3">GIS no.</th>
                                    <th scope="col" class="px-4 py-3">Name</th>
                                    <th scope="col" class="px-4 py-3">Sex</th>
                                    <th scope="col" class="px-4 py-3">Birthdate</th>
                                    <th scope="col" class="px-4 py-3">Type of Assistance</th>
                                    <th scope="col" class="px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="benefeciary in requested_beneficiaries" :key="benefeciary.id"
                                    class="border-b dark:border-gray-700">
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
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </button>
                                        <button type="btn" class="text-red-700" @click="
                                            openDeleteModal(benefeciary.id)
                                            "><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>

                                        </button>
                                    </td>

                                </tr>
                                <tr v-if="requested_beneficiaries.length <= 0">
                                    <td colspan="7" class="px-4 py-3 text-center">
                                        No Data Found!
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div v-else class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
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
                                    <th scope="col" class="px-4 py-3">ID</th>
                                    <th scope="col" class="px-4 py-3">Name</th>
                                    <th scope="col" class="px-4 py-3">Status</th>
                                    <th scope="col" class="px-4 py-3">Date Created</th>
                                    <th scope="col" class="px-4 py-3">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="benefeciary in beneficiaries" :key="benefeciary.id"
                                    class="border-b dark:border-gray-700">
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
                                        <button type="btn" class="text-red-700" @click="
                                            openDeleteModal(benefeciary.id)
                                            "><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
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

        <!--Delete Modal-->
        <input class="modal-state" id="deleteModal" type="checkbox" v-model="isDeleteModalOpen" />
        <div class="modal" style="z-index: 9999;">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <label for="deleteModal" style="cursor: pointer;"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </label>
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to
                            remove this benefeciary?</h3>

                        <ProcessingButton :process="processing" type="button" :text="'Confirm'"
                            @click="deleteBeneficiaryData" class="bg-red-600 hover:bg-red-800" />
                        <label for="deleteModal" style="cursor: pointer;"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No,
                            cancel</label>

                    </div>
                </div>
            </div>
        </div>

        <!--Staff Modal-->
        <input class="modal-state" id="staffModal" type="checkbox" v-model="isStaffModalOpen" />
        <div class="modal" style="z-index: 9999;">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <label style="cursor: pointer;" @click="cancelStaffEdit()"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </label>
                    <div class="p-4 md:p-5">
                        <div class="text-xl font-bold mb-5">
                            Staff List
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex justify-between border-b border-blue-300 py-3">
                                <span>Staff</span>
                                <button type="button" class="text-blue-700 hover:text-blue-900" @click="addStaff"><svg
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-3 gap-2" v-for="staff in aics_staff" :key="staff.id">
                                <div class="col-span-2">
                                    <input type="text" placeholder="Name"
                                        class="w-full border-t-0 border-l-0 border-r-0 focus:outline-none focus:ring-0"
                                        v-model="staff.name" required>
                                </div>
                                <div class="col-span-1 flex flex-row">
                                    <input type="text" placeholder="Position"
                                        class="w-full border-t-0 border-l-0 border-r-0 focus:outline-none focus:ring-0"
                                        v-model="staff.position" required>
                                        <button type="button" class="text-yellow-500"
                                        @click="openArchiveStaff(staff.id, staff.status)">
                                        <svg v-if="staff.status == 0" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                        </svg>
                                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                        </svg>


                                    </button>
                                </div>
                            </div>
                            <div class="flex justify-between border-b border-blue-300 py-3 mt-4">
                                <span>Mayor</span>
                                <button type="button" class="text-blue-700 hover:text-blue-900" @click="addMayor"><svg
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-3 gap-2" v-for="mayor in aics_mayor" :key="mayor.id">
                                <div class="col-span-2">
                                    <input type="text" placeholder="Name"
                                        class="w-full border-t-0 border-l-0 border-r-0 focus:outline-none focus:ring-0"
                                        v-model="mayor.name" required>
                                </div>
                                <div class="col-span-1 flex flex-row">
                                    <input type="text" placeholder="Position"
                                        class="w-full border-t-0 border-l-0 border-r-0 focus:outline-none focus:ring-0"
                                        v-model="mayor.position" required>
                                    <button type="button" class="text-yellow-500"
                                        @click="openArchiveStaff(mayor.id, mayor.status)">
                                        <svg v-if="mayor.status == 0" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                        </svg>
                                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                        </svg>


                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end mt-3">
                            <ProcessingButton :process="processing" type="button" :text="'Save'"
                                @click="submitStaffEdit" class="bg-blue-600 hover:bg-blue-800" />
                            <label style="cursor: pointer;" @click="cancelStaffEdit"
                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No,
                                cancel</label>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--AICS Report-->

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
                        <a type="button" target="_blank"
                            :href="exportSelected == 1 ? route('staff.exportToPdfAics', { data: JSON.stringify(requestData) }) : route('staff.exportToExcelAics', { data: JSON.stringify(requestData) })"
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
                    <button @click="findBeneficiaries" class="btn btn-error btn-block"
                        :class="{ 'disabled opacity-80 pointer-events-none': !canFind || processing }">Find</button>
                    <label for="isReportModalOpen" class="btn btn-block" @click="cancelReport()">Cancel</label>
                </div>
            </div>
        </div>

        <!--Archive Staff-->
        <input class="modal-state" id="archiveModal" type="checkbox" v-model="isArchiveModal" />
        <div class="modal" style="z-index: 9999;">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <label for="archiveModal" style="cursor: pointer;"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </label>
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to
                            {{staffStatus != 1 ? 'Archive' : 'Unarchive' }} this person?</h3>

                        <ProcessingButton :process="processing" type="button" :text="'Confirm'"
                            @click="submitStaffArchive" class="bg-yellow-400 hover:bg-yellow-500" />
                        <label for="archiveModal" style="cursor: pointer;"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No,
                            cancel</label>

                    </div>
                </div>
            </div>
        </div>

        <!--Add Staff Modal-->
        <input class="modal-state" id="addModal" type="checkbox" v-model="isAddModal" />
        <div class="modal" style="z-index: 9999;">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <label for="addModal" style="cursor: pointer;"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </label>

                    <div class="p-4 md:p-5">
                        <div class="text-2xl mb-4">{{ addForm.role == 1 ? 'Add Staff' : 'Add Mayor' }}</div>
                        <div class="flex flex-col mb-2">
                            <label for="name">Name</label>
                            <input id="name" type="text" v-model="addForm.name" class="input min-w-full" :class="{'border-red-500':errors.name}">
                            <span class="text-red-700" v-if="errors.name">{{ errors.name }}</span>
                        </div>

                        <div class="flex flex-col mb-2">
                            <label for="position">Position</label>
                            <input id="position" type="text" v-model="addForm.position" class="input min-w-full" :class="{'border-red-500':errors.position}">
                            <span class="text-red-700" v-if="errors.position">{{ errors.position }}</span>
                        </div>

                        <div class="flex justify-end items-center mt-3">
                            <ProcessingButton :process="processing" type="button" :text="'Confirm'"
                                @click="createAicsPersonnel" class=" bg-green-600 hover:bg-green-800" />
                            <label @click="cancelAdd" style="cursor: pointer;"
                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No,
                                cancel</label>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </Staffpage>
</template>
