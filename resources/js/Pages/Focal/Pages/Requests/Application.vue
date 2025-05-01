<script setup>
import Focalpage from "../../FocalLayout/Focalpage.vue";
import InputError from "@/Components/InputError.vue";
import ProcessingButton from "@/Components/ProcessingButton.vue";
import Pagination from "@/Components/Pagination.vue";
import { Head, usePage, router } from "@inertiajs/vue3";
import { onMounted, reactive, ref, watch, computed } from "vue";
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';


defineProps({
    applicants: Object,
    rejected_applicants: Array,
    focal_barangay: String,
    barangays: Array,
    monthly_income: Array,
});


let imageExists = ref(false);
const imagePreviewUrl = ref(null);
const focal_role = ref(null);
const EditModal = ref(false);

const processing = ref(false);
const inList = ref(true);
const isOpenResponseModal = ref(false);
const deleteModal = ref(false);

const searchedApplicants = ref('');
const searchedRejectedApplicants = ref('');

const filteredApplicants = ref(computed(() => {
    if (searchedApplicants.value === '') return usePage().props.applicants;
    return usePage().props.applicants.filter(beneficiary =>
    (beneficiary.first_name + ' ' + beneficiary.last_name).toLowerCase().includes(searchedApplicants.value.toLowerCase())
)}));

const filteredRejectedApplicants = ref(computed(() => {
    if (searchedRejectedApplicants.value === '') return usePage().props.rejected_applicants;
    return usePage().props.applicants.filter(beneficiary =>
    (beneficiary.first_name + ' ' + beneficiary.last_name).toLowerCase().includes(searchedRejectedApplicants.value.toLowerCase())
)}));


onMounted(() => {
    focal_role.value = usePage().props.auth.user.focal_role;
});

const form = reactive({
    benefeciary_type: usePage().props.auth.user.focal_role,

    //NAME
    first_name: null,
    last_name: null,
    middle_name: null,
    extension_name: null,

    //BIO DETAILS
    date_of_birth: null,
    age: null,
    civil_status: null,
    sex: null,
    educational_attainment: null,
    religion: null,
    occupation: null,
    monthly_income: null,

    //ADDRESS
    street: null,
    barangay_id: usePage().props.auth.user.barangay_id,
    city: "Tangub City",
    province: "Misamis Occidental",
    region: 10,
    contact_number: null,

    //SENIOR
    senior_id_number: null,
    socpen_benefeciary: null,
    date_sic_issued: null,
    sic_status: null,

    //MISC
    pantawid_benefeciary: null,
    indigenous_person: null,
    lgbtq: null,
    remarks: null,

    //WOMEN
    kalipi_id_number: null,
    date_kic_issued: null,
    kic_status: null,

    //PWD
    pwd_id_number: null,
    date_pic_issued: null,
    pic_status: null,

    //IMAGE
    image: null,
});

// Subtract 60 years from the current date

let benefeciary_id = ref("");
let benefeciary_type = ref("");

const getFullName = (first_name, middle_name, last_name) => {
    return (
        first_name +
        " " +
        (middle_name != null ? middle_name + " " : "") +
        last_name
    );
};

const benefeciaryCategoryName = (type) => {
    switch (type) {
        case 1:
            return "Senior";
        case 2:
            return "PWD";
        case 3:
            return "Women";
    }
};


const openEditModal = (benefeciary, benefeciary_type) => {
    EditModal.value = true;
    getBenefeciaryData(benefeciary, benefeciary_type);
};

const openDeleteModal = (id, type) => {
    deleteModal.value = true;
    setBenefeciaryData(id, type);
};

const openResponseModal = (id, status) => {
    isOpenResponseModal.value = true;
    response.status = status;
    response.id = id;
};

const closeResponseModal = (id, status) => {
    isOpenResponseModal.value = false;
    response.status = null;
    response.id = null;
    response.reason = null;
};

const getBenefeciaryData = (benefeciary, benefeciaryType) => {
    setBenefeciaryData(benefeciary.id, benefeciaryType);
    form.benefeciary_type = benefeciaryType;
    //NAME
    form.first_name = benefeciary.first_name;
    form.last_name = benefeciary.last_name;
    form.middle_name = benefeciary.middle_name;
    form.extension_name = benefeciary.extension_name;

    //BIO DETAILS
    form.date_of_birth = benefeciary.date_of_birth;
    form.age = benefeciary.age;
    form.civil_status = benefeciary.civil_status;
    form.sex = benefeciary.sex;
    form.educational_attainment = benefeciary.educational_attainment;
    form.religion = benefeciary.religion;
    form.occupation = benefeciary.occupation;
    form.monthly_income = benefeciary.monthly_income_id;

    //ADDRESS
    form.street = benefeciary.street;
    form.barangay_id = benefeciary.barangay_id;
    form.city = benefeciary.city;
    form.province = benefeciary.province;
    form.region = benefeciary.region;
    form.contact_number = benefeciary.contact_number;

    switch (benefeciaryType) {
        //Senior
        case 1:
            form.socpen_benefeciary = benefeciary?.senior?.socpen_benefeciary ?? null;
            form.senior_id_number = benefeciary?.senior?.senior_id_number ?? null;
            form.date_sic_issued = benefeciary?.senior?.date_sic_issued ?? null;
            form.sic_status = benefeciary?.senior?.sic_status ?? null;
            break;
        //PWD
        case 2:
            form.pwd_id_number = benefeciary?.pwd?.pwd_id_number ?? null;
            form.date_pic_issued = benefeciary?.pwd?.date_pic_issued ?? null;
            form.pic_status = benefeciary?.pwd?.pic_status ?? null;
            break;
        //Women
        case 3:
            form.kalipi_id_number = benefeciary?.women?.kalipi_id_number ?? null;
            form.date_kic_issued = benefeciary?.women?.date_kic_issued ?? null;
            form.kic_status = benefeciary?.women?.kic_status ?? null;
            break;
    }

    form.pantawid_benefeciary = benefeciary.pantawid_benefeciary;
    form.indigenous_person = benefeciary.indigenous_person;
    form.lgbtq = benefeciary.lgbtq;
    form.remarks = benefeciary.remarks;
    form.image = benefeciary.image;

    imageExists = checkImageIfExist(form.image);

    if (imageExists) {
        imagePreviewUrl.value = "/profile_images/" + benefeciary.image;
    } else {
        form.image = null;
        imagePreviewUrl.value = null;
    }
};

const checkImageIfExist = (image) => {
    return image ? true : false;
};

const setBenefeciaryData = (id, type) => {
    benefeciary_id = id;
    benefeciary_type = type;
};

const cancelEdit = () => {
    EditModal.value = false;
};

const response = reactive({
    status: null,
    id: null,
    reason: null
});



const deleteBenefeciary = () => {
    try {
        router.delete(
            route("focal.delete-beneficiary", {
                id: benefeciary_id,
                type: benefeciary_type,
            }),
            {
                onProgress: () => {
                    processing.value = true;
                },
                onSuccess: (page) => {
                    (deleteModal.value = false),
                        Swal.fire({
                            toast: true,
                            icon: "success",
                            position: "top-end",
                            showConfirmation: false,
                            title: page.props.flash.success,
                        }),
                        (processing.value = false);
                },
                onError: () => {
                    processing.value = false;
                },
            }
        );
    } catch (err) {
        console.log(err);
    }
};

const respondApplication = () => {
    try {
        router.put(
            route("focal.respondApplication"), response, {
            onProgress: () => {
                processing.value = true;
            },
            onSuccess: (page) => {
                    if(page.props.flash.success){
                        Swal.fire({
                            toast: true,
                            icon: "success",
                            position: "top-end",
                            showConfirmation: false,
                            title: page.props.flash.success,
                        });  
                    }else{
                        Swal.fire({
                            toast: true,
                            icon: "success",
                            position: "top-end",
                            showConfirmation: false,
                            title: page.props.flash.error,
                        }); 
                    }
                    isOpenResponseModal.value = false;
                    processing.value = false;
            },
            onError: () => {
                processing.value = false;
            },
        }
        );
    } catch (err) {
        console.log(err);
    }
};

dayjs.extend(relativeTime);

// Function to calculate "ago"
function timeAgo(createdAt) {
  return dayjs(createdAt).fromNow();
}

</script>

<template>

    <Head title="Focal Dashboard" />
    <Focalpage>
        <section class="bg-gray-50 dark:bg-gray-900 p-4 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="tabs mb-2">
                    <div class="tab tab-bordered px-6" :class="{ 'tab-active': inList }" @click="inList = true">
                        Applicants
                    </div>
                    <div class="tab tab-bordered px-6" :class="{ 'tab-active': !inList }" @click="inList = false">
                        Rejected
                    </div>
                </div>
                <div v-if="inList" class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="text-2xl p-2 mb-2">
                        {{
                            "Application for " +
                            benefeciaryCategoryName(focal_role) +
                            " Beneficiaries (Brgy. " +
                            usePage().props.focal_barangay +
                            " )"
                        }}
                    </div>
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="relative w-full md:w-2/4">
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
                            <input type="text" id="searchInput" v-model="searchedApplicants"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Search">
                        </div>

                    </div>
                    <div class="breadcrumbs text-sm">
                        <ul>
                            <li>
                                <a>Applicants</a>
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
                                    <th scope="col" class="px-4 py-3">Name</th>
                                    <th scope="col" class="px-4 py-3">Age</th>
                                    <th scope="col" class="px-4 py-3">Sex</th>
                                    <th scope="col" class="px-4 py-3">Birthdate</th>
                                    <th scope="col" class="px-4 py-3">Purok</th>
                                    <th scope="col" class="px-4 py-3">Created</th>
                                    <th scope="col" class="px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="benefeciary in filteredApplicants" :key="benefeciary.id"
                                    class="border-b dark:border-gray-700">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{
                                            getFullName(
                                                benefeciary.first_name,
                                                benefeciary.middle_name,
                                                benefeciary.last_name
                                            )
                                        }}
                                    </th>
                                    <td class="px-4 py-3">{{ benefeciary.age }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.sex }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.date_of_birth }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.street }}</td>
                                    <td class="px-4 py-3">{{ timeAgo(benefeciary.created_at) }}</td>
                                    <td class="px-4 py-3 gap-2 flex flex-row">
                                        <button type="btn" class="text-blue-700" @click="
                                            openEditModal(
                                                benefeciary,
                                                benefeciary.benefeciary_type
                                            )
                                            "><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>

                                        </button>
                                        <button type="btn" class="text-green-700"
                                            @click="openResponseModal(benefeciary.id, 0)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 12.75 6 6 9-13.5" />
                                            </svg>


                                        </button>
                                        <button type="btn" class="text-red-700"
                                            @click="openResponseModal(benefeciary.id, 4)"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18 18 6M6 6l12 12" />
                                            </svg>


                                        </button>
                                    </td>

                                </tr>
                                <tr v-if="filteredApplicants.length <= 0">
                                    <td colspan="7" class="px-4 py-3 text-center">
                                        No Data Found!
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div v-else class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="text-2xl p-2 mb-2">
                        {{
                            benefeciaryCategoryName(focal_role) +
                            " Rejected Application (Brgy. " +
                            usePage().props.focal_barangay +
                            " )"
                        }}
                    </div>
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        <div class="relative w-full md:w-2/4">
                            <button type="button"
                                class="absolute inset-y-0 right-0 flex items-center btn bg-blue-800 rounded-none h-full rounded-r"
                                @click="filtersearchInputRejected()">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-100 dark:text-gray-400"
                                    fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <input type="text" id="searchInput" v-model="searchedRejectedApplicants"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Search">
                        </div>

                    </div>
                    <div class="breadcrumbs text-sm">
                        <ul>
                            <li>
                                <a>Applicants</a>
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
                                    <th scope="col" class="px-4 py-3">Name</th>
                                    <th scope="col" class="px-4 py-3">Age</th>
                                    <th scope="col" class="px-4 py-3">Sex</th>
                                    <th scope="col" class="px-4 py-3">Birthdate</th>
                                    <th scope="col" class="px-4 py-3">Purok</th>
                                    <th scope="col" class="px-4 py-3">Date Rejected</th>
                                    <th scope="col" class="px-4 py-3">Reason</th>
                                    <th scope="col" class="px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="benefeciary in filteredRejectedApplicants" :key="benefeciary.id"
                                    class="border-b dark:border-gray-700">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{
                                            getFullName(
                                                benefeciary.first_name,
                                                benefeciary.middle_name,
                                                benefeciary.last_name
                                            )
                                        }}
                                    </th>
                                    <td class="px-4 py-3">{{ benefeciary.age }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.sex }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.date_of_birth }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.street }}</td>
                                    <td class="px-4 py-3">{{ new Date(benefeciary.date_rejected).toLocaleDateString() }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.reason }}</td>
                                    <td class="px-4 py-3 gap-2 flex flex-row">
                                        <button type="btn" class="text-blue-700" @click="
                                            openEditModal(
                                                benefeciary,
                                                benefeciary.benefeciary_type
                                            )
                                            "><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>

                                        </button>
                                        <button type="btn" class="text-red-700"
                                            @click="openResponseModal(benefeciary.id, 3)"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18 18 6M6 6l12 12" />
                                            </svg>

                                        </button>
                                        <button type="btn" class="text-red-700" @click="openResponseModal(benefeciary.id, 5)"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>

                                        </button>
                                    </td>

                                </tr>
                                <tr v-if="filteredRejectedApplicants.length <= 0">
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

        <!--Response Modal-->
        <input class="modal-state" id="isOpenResponseModal" type="checkbox" v-model="isOpenResponseModal" />
        <div class="modal" style="z-index: 9999;">
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
                        <h3 v-if="response.status == 0" class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                            Are you sure you want to approve this
                            application?
                        </h3>

                        <div v-else-if="response.status == 4">
                            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                            Are you sure you want to reject this
                            application?
                            </h3>
                            <div class="mt-4 px-2 mb-2">
                                <label for="reason" class="text-start mb-1">Reason</label>
                                <textarea  id="reason" v-model="response.reason" class="min-w-full"></textarea>
                            </div>
                        </div>

                        <h3 v-else-if="response.status == 5" class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                            Are you sure you want to remove this
                            application?
                        </h3>

                        <h3 v-else class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">
                            Are you sure you want to cancel this
                            application rejection?
                        </h3>
                        <ProcessingButton type="button" :process="processing" :text="'Confirm'"
                            @click="respondApplication()"
                            :class="{ 'bg-red-600 hover:bg-red-800': response.status == 4 || response.status == 5, 'bg-green-600 hover:bg-green-800': response.status == 0 || response.status == 3,  }" />
                        <button type="button" @click="closeResponseModal()"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            No, cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <input class="modal-state" id="deleteModal" type="checkbox" v-model="deleteModal" />
        <div class="modal">
            <label class="modal-overlay"></label>
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button" @click="deleteModal = false"
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
                            Are you sure you want to approve this application?
                        </h3>
                        <ProcessingButton type="button" :process="processing" :text="'Confirm'"
                            class="bg-red-600 hover:bg-red-800" @click="deleteBenefeciary()" />
                        <button type="button" @click="deleteModal = false"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            No, cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add modal -->
        <input class="modal-state" id="AddorEditModal" type="checkbox" v-model="EditModal" />
        <div class="modal !h-full !items-start overflow-y-auto" style="z-index: 9999">
            <label class="modal-overlay" for="AddorEditModal"></label>
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-2 md:p-2 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Benefeciary Info
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

                        <div class="grid grid-cols-10 gap-5 w-full">
                            <div class="col-span-10 grid md:grid-cols-2 ">
                                <div class="relative">
                                    <div class=" relative mb-2 lg:px-5">
                                        <div class="relative flex items-center justify-center w-full">
                                            <span
                                                class="flex flex-col items-center justify-center w-full h-auto md:w-50 p-4 border-2 border-gray-300 border-dashed rounded-lg  bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700  dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                                <div v-if="!imagePreviewUrl"
                                                    class=" flex flex-col items-center justify-center pt-5 pb-6">
                                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">

                                                        <p
                                                            class="mb-2 text-sm text-center text-gray-500 dark:text-gray-400">
                                                            <span class="font-semibold">No Image Added</span>
                                                        </p>
                                                    </div>
                                                </div>

                                                <div v-else class=" flex items-center justify-center">
                                                    <img :src="imagePreviewUrl"
                                                        class="w-auto rounded object-cover h-48 w-96 cursor-pointer "
                                                        alt="Image preview">
                                                </div>

                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="relative">
                                    <div class="relative mb-2">
                                        <select disabled v-model="form.benefeciary_type" id="floating_benefeciary_type"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 pt-4 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="1">Senior</option>
                                            <option value="2">PWD</option>
                                            <option value="3">Women</option>
                                        </select>
                                        <label for="floating_benefeciary_type"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Beneficiary
                                            Type</label>
                                    </div>
                                    <div class="relative mb-2">
                                        <input disabled v-model="form.first_name" type="text" id="floating_first_name"
                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " />
                                        <label for="floating_first_name"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">First
                                            Name</label>
                                    </div>

                                    <div class="relative mb-2">
                                        <input disabled v-model="form.middle_name" type="text" id="floating_middle_name"
                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " />
                                        <label for="floating_middle_name"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Middle
                                            Name (Optional)</label>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="relative">
                                            <input disabled v-model="form.last_name" type="text" id="floating_last_name"
                                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                placeholder=" " />
                                            <label for="floating_last_name"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Last
                                                Name</label>
                                        </div>

                                        <div class="relative">
                                            <input disabled v-model="form.extension_name" type="text"
                                                id="floating_extension_name"
                                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                placeholder=" " />
                                            <label for="floating_extension_name"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Suffix
                                                (Optional)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-10 grid grid-cols-2 gap-2 w-full">
                                <div class="md:grid grid-cols-2 gap-2">
                                    <div class="relative mb-2">
                                        <input disabled v-model="form.date_of_birth" type="date"
                                            id="floating_date_of_birth"
                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                                        <label for="floating_date_of_birth"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Date
                                            of Birth</label>
                                    </div>

                                    <div class="relative mb-2">
                                        <input disabled v-model="form.age" type="number" id="floating_age"
                                            class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder="Age" />

                                    </div>
                                </div>

                                <div class="md:grid grid-cols-2 gap-2">

                                    <div class="relative mb-2">
                                        <select disabled v-model="form.civil_status" id="floating_civil_status"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 pt-4 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="Single">Single</option>
                                            <option value="Married">Married</option>
                                            <option value="Widowed">Widowed</option>
                                            <option value="Divorced">Divorced</option>
                                        </select>
                                        <label for="floating_civil_status"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Civil
                                            Status</label>
                                    </div>

                                    <div class="relative mb-2">
                                        <select disabled v-model="form.sex" id="floating_sex"
                                            class="bg-gray-50 border border-gray-300 pt-4 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                        <label for="floating_sex"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Sex</label>
                                    </div>

                                </div>

                                <div class="relative mb-2">
                                    <input disabled v-model="form.religion" type="text" id="floating_religion"
                                        class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder="" />
                                    <label for="floating_religion"
                                        class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Religion</label>
                                </div>


                                <div class="relative">
                                    <select disabled v-model="form.educational_attainment"
                                        id="floating_educational_attainment"
                                        class="bg-gray-50 border border-gray-300 pt-4 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option value="None">None</option>
                                        <option value="Elementary Level">Elementary Level</option>
                                        <option value="High School Level">High School Level</option>
                                        <option value="College Level">College Level</option>
                                        <option value="Masteral">Masteral</option>
                                        <option value="Doctorate">Doctorate</option>
                                    </select>
                                    <label for="floating_educational_attainment"
                                        class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Educational
                                        Attainment</label>
                                </div>


                                <div class="grid grid-cols-2 gap-2">
                                    <div class="relative">
                                        <input disabled v-model="form.occupation" type="text" id="floating_occupation"
                                            class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder="" />
                                        <label for="floating_occupation"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Occupation</label>
                                    </div>


                                    <div class="relative">
                                        <select disabled v-model="form.monthly_income" id="floating_monthly_income"
                                            class="bg-gray-50 border border-gray-300 pt-4 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option v-for="income in monthly_income" :value="income.id"
                                                :key="income.id">{{
                                                    income.income_range }}</option>

                                        </select>
                                        <label for="floating_monthly_income"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Monthly
                                            Income</label>
                                    </div>

                                </div>

                                <div class="relative">
                                    <input disabled v-model="form.contact_number" type="text"
                                        id="floating_contact_number"
                                        class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder="" />
                                    <label for="floating_contact_number"
                                        class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Contact
                                        Number</label>
                                </div>



                            </div>


                        </div>

                        <div class="grid grid-cols-10 gap-5 w-full">
                            <div class="text-center">
                                <p>Address</p>
                            </div>
                            <div class="col-span-10 grid grid-cols-2 gap-2 w-full">
                                <div class="relative mb-2">
                                    <select disabled v-model="form.barangay_id" id="floating_barangay"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 pt-4 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option v-for="barangay in usePage().props.barangays" :key="barangay.id"
                                            :value="barangay.id" :disabled="form.barangay_id">{{
                                                barangay.barangay_name }}</option>

                                    </select>
                                    <label for="floating_barangay"
                                        class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Barangay</label>
                                </div>


                                <div class="relative">
                                    <input disabled v-model="form.street" type="text" id="floating_street"
                                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " />
                                    <label for="floating_street"
                                        class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                        Street/Purok</label>
                                </div>


                                <div class="relative">
                                    <input disabled v-model="form.city" type="text" id="floating_city"
                                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " />
                                    <label for="floating_city"
                                        class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                        City/Municipality</label>
                                </div>
                                <div class="relative">
                                    <input disabled v-model="form.province" type="text" id="floating_province"
                                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " />
                                    <label for="floating_city"
                                        class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                        Province</label>
                                </div>


                            </div>
                        </div>


                        <!---THIS IS WHERE SPECIFIC DATA LOCATED-->
                        <div class="my-2 text-xl">Additional Information</div>
                        <div class="grid grid-cols-10 gap-5 w-full">
                            <div class="col-span-10 grid grid-cols-2 gap-2 w-full">
                                <!--Senior-->
                                <div v-if="form.benefeciary_type == 1" class="relative">
                                    <input disabled v-model="form.senior_id_number" type="text"
                                        id="floating_senior_id_number"
                                        class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder="" />
                                    <label for="floating_senior_id_number"
                                        class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Senior
                                        ID Number</label>
                                </div>

                                <!--PWD-->
                                <div v-else-if="form.benefeciary_type == 2" class="relative">
                                    <input disabled v-model="form.pwd_id_number" type="text" id="floating_pwd_id_number"
                                        class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder="" />
                                    <label for="floating_pwd_id_number"
                                        class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">PWD
                                        ID Number</label>
                                </div>
                                <!--Women-->
                                <div v-else class="relative">
                                    <input disabled v-model="form.kalipi_id_number" type="text"
                                        id="floating_kalipi_id_number"
                                        class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder="" />
                                    <label for="floating_kalipi_id_number"
                                        class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Kalipi
                                        ID Number</label>
                                </div>

                                <div class="grid grid-cols-2 gap-2">
                                    <div v-if="form.benefeciary_type == 1" class="relative">
                                        <input disabled v-model="form.date_sic_issued" type="date"
                                            id="floating_date_sic_issued"
                                            class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder="" />
                                        <label for="floating_date_sic_issued"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Date
                                            Issued</label>
                                    </div>

                                    <div v-else-if="form.benefeciary_type == 2" class="relative">
                                        <input disabled v-model="form.date_pic_issued" type="date"
                                            id="floating_date_pic_issued"
                                            class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder="" />
                                        <label for="floating_date_pic_issued"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Date
                                            Issued</label>
                                    </div>

                                    <div v-else class="relative">
                                        <input disabled v-model="form.date_kic_issued" type="date"
                                            id="floating_date_kic_issued"
                                            class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder="" />
                                        <label for="floating_date_kic_issued"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Date
                                            Issued</label>
                                    </div>

                                    <div v-if="form.benefeciary_type == 1" class="relative">
                                        <select disabled v-model="form.sic_status" id="floating_sic_status"
                                            class="bg-gray-50 border border-gray-300 pt-4 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="0">New</option>
                                            <option value="1">Renewal</option>
                                            <option value="2">Terminated</option>
                                        </select>
                                        <label for="floating_sic_status"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">ID
                                            Status</label>
                                    </div>

                                    <div v-else-if="form.benefeciary_type == 2" class="relative">
                                        <select disabled v-model="form.pic_status" id="floating_pic_status"
                                            class="bg-gray-50 border border-gray-300 pt-4 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="0">New</option>
                                            <option value="1">Renewal</option>
                                            <option value="2">Terminated</option>
                                        </select>
                                        <label for="floating_pic_status"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">ID
                                            Status</label>
                                    </div>

                                    <div v-else class="relative">
                                        <select disabled v-model="form.kic_status" id="floating_kic_status"
                                            class="bg-gray-50 border border-gray-300 pt-4 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="0">New</option>
                                            <option value="1">Renewal</option>
                                            <option value="2">Terminated</option>
                                        </select>
                                        <label for="floating_kic_status"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">ID
                                            Status</label>
                                    </div>

                                </div>
                            </div>

                        </div>


                        <div class="my-2 text-xl">Is the Benefeciary?</div>
                        <div class="grid grid-cols-10 gap-5 w-full">
                            <div class="col-span-10 grid grid-cols-2 gap-2 w-full">
                                <div v-if="form.benefeciary_type == 1" class="relative mb-2">
                                    <div class="mb-2">SOCPEN Benefeciary?</div>
                                    <div class="flex items-center mb-4">
                                        <input disabled v-model="form.socpen_benefeciary" id="socpen_benefeciary"
                                            type="radio" value="1" name="socpen_benefeciary"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="socpen_benefeciary"
                                            class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes
                                        </label>

                                    </div>
                                    <div class="flex items-center">
                                        <input disabled v-model="form.socpen_benefeciary" id="not_socpen_benefeciary"
                                            type="radio" value="0" name="socpen_benefeciary"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="not_socpen_benefeciary"
                                            class="w-full text-sm ms-2 font-medium text-gray-900 dark:text-gray-300">No
                                        </label>
                                    </div>
                                </div>

                                <div class="relative mb-2">
                                    <div class="mb-2">Pantawid Benefeciary?</div>
                                    <div class="flex items-center mb-4">
                                        <input disabled v-model="form.pantawid_benefeciary" id="pantawid_benefeciary"
                                            type="radio" value="1" name="pantawid_benefeciary"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="pantawid_benefeciary"
                                            class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes
                                        </label>

                                    </div>
                                    <div class="flex items-center">
                                        <input disabled v-model="form.pantawid_benefeciary"
                                            id="not_pantawid_benefeciary" type="radio" value="0"
                                            name="pantawid_benefeciary"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="not_pantawid_benefeciary"
                                            class="w-full text-sm ms-2 font-medium text-gray-900 dark:text-gray-300">No
                                        </label>
                                    </div>
                                </div>


                                <div class="relative mb-2">
                                    <div class="mb-2">Indigenous Person?</div>
                                    <div class="flex items-center mb-4">
                                        <input disabled v-model="form.indigenous_person" id="indigenous_person"
                                            type="radio" value="1" name="indigenous_person"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="indigenous_person"
                                            class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes
                                        </label>

                                    </div>
                                    <div class="flex items-center">
                                        <input disabled v-model="form.indigenous_person" id="not_indigenous_person"
                                            type="radio" value="0" name="indigenous_person"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="not_indigenous_person"
                                            class="w-full text-sm ms-2 font-medium text-gray-900 dark:text-gray-300">No
                                        </label>
                                    </div>
                                </div>

                                <div class="relative mb-2">
                                    <div class="mb-2">LGBTQ+?</div>
                                    <div class="flex items-center mb-4">
                                        <input disabled v-model="form.lgbtq" id="lgbtq" type="radio" value="1"
                                            name="lgbtq"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="lgbtq"
                                            class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes
                                        </label>

                                    </div>
                                    <div class="flex items-center">
                                        <input disabled v-model="form.lgbtq" id="not_lgbtq" type="radio" value="0"
                                            name="lgbtq"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                        <label for="not_lgbtq"
                                            class="w-full text-sm ms-2 font-medium text-gray-900 dark:text-gray-300">No
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <label for="remarks" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Remarks</label>
                        <textarea disabled v-model="form.remarks" id="remarks" rows="4"
                            class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Write your thoughts here..."></textarea>

                    </div>
                    <!-- Modal footer -->
                    <div class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                        <button @click="cancelEdit" type="button"
                            class="py-2.5 px-5 ms-3 mx-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </Focalpage>
</template>
