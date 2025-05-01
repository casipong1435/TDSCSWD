<script setup>
import Barangaypage from '../../BarangayLayout/Barangaypage.vue';
import ProcessingButton from '@/Components/ProcessingButton.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, reactive, watch } from 'vue';

const tab = ref(0);
const searchInput = ref('');
const EditModal = ref(false);
const imagePreviewUrl = ref(null);
const RequestModal = ref(false);
const responseStatus = ref(null);
const processing = ref(false);

defineProps({
    barangays: Array,
    monthly_income: Array,
    endorsed_benefeciaries: Array,
    transitioned_benefeciaries: Array,
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

const getFullName = (first_name, middle_name, last_name) => {
    return first_name + " " + (middle_name != null ? middle_name + " " : "") + last_name;
};

const benefeciaryCategoryName = (type) => {
    switch (type) {
        case 1:
            return "Senior";
            break;
        case 2:
            return "PWD";
            break;
        case 3:
            return "Women";
            break;
    }
};

const form = reactive({

    'benefeciary_type': usePage().props.auth.user.focal_role,

    //NAME
    'first_name': null,
    'last_name': null,
    'middle_name': null,
    'extension_name': null,

    //BIO DETAILS
    'date_of_birth': null,
    'age': null,
    'civil_status': null,
    'sex': null,
    'educational_attainment': null,
    'religion': null,
    'occupation': null,
    'monthly_income': null,


    //ADDRESS
    'street': null,
    'barangay_id': usePage().props.auth.user.barangay_id,
    'city': 'Tangub City',
    'province': 'Misamis Occidental',
    'region': 10,
    'contact_number': null,

    //SENIOR
    'senior_id_number': null,
    'socpen_benefeciary': null,
    'date_sic_issued': null,
    'sic_status': null,


    //MISC
    'pantawid_benefeciary': null,
    'indigenous_person': null,
    'lgbtq': null,
    'remarks': null,



    //WOMEN
    'kalipi_id_number': null,
    'date_kic_issued': null,
    'kic_status': null,

    //PWD
    'pwd_id_number': null,
    'date_pic_issued': null,
    'pic_status': null,

    //IMAGE
    'image': null,
});

const openEditModal = (benefeciary) => {
    EditModal.value = true;
    getBenefeciaryData(benefeciary);

}

watch(searchInput, (value) => {
    router.get(route('barangay.senior'),
        { searchInput: value },
        {
            preserveState: true,
            replace: true
        })
});

let imageExists;
const requestID = ref(null);

const getBenefeciaryData = (benefeciary) => {
    form.benefeciary_type = benefeciary.benefeciary.benefeciary_type;
    //NAME
    form.first_name = benefeciary.benefeciary.first_name;
    form.last_name = benefeciary.benefeciary.last_name;
    form.middle_name = benefeciary.benefeciary.middle_name;
    form.extension_name = benefeciary.benefeciary.extension_name;

    //BIO DETAILS
    form.date_of_birth = benefeciary.benefeciary.date_of_birth;
    form.age = benefeciary.benefeciary.age;
    form.civil_status = benefeciary.benefeciary.civil_status;
    form.sex = benefeciary.benefeciary.sex;
    form.educational_attainment = benefeciary.benefeciary.educational_attainment;
    form.religion = benefeciary.benefeciary.religion;
    form.occupation = benefeciary.benefeciary.occupation;
    form.monthly_income = benefeciary.benefeciary.monthly_income_id;

    //ADDRESS
    form.street = benefeciary.benefeciary.street;
    form.barangay_id = benefeciary.benefeciary.barangay_id;
    form.city = benefeciary.benefeciary.city;
    form.province = benefeciary.benefeciary.province;
    form.region = benefeciary.benefeciary.region;
    form.contact_number = benefeciary.benefeciary.contact_number;

    switch (benefeciary.benefeciary.benefeciary_type) {
        //Senior
        case 1:
            form.socpen_benefeciary = benefeciary?.benefeciary?.senior?.socpen_benefeciary ?? '';
            form.senior_id_number = benefeciary?.benefeciary?.senior?.senior_id_number ?? '';
            form.date_sic_issued = benefeciary?.benefeciary?.senior?.date_sic_issued ?? '';
            form.sic_status = benefeciary?.benefeciary?.senior?.sic_status ?? '';
            break;
        //PWD
        case 2:
            form.pwd_id_number = benefeciary.benefeciary.pwd.pwd_id_number;
            form.date_pic_issued = benefeciary.benefeciary.pwd.date_pic_issued;
            form.pic_status = benefeciary.benefeciary.pwd.pic_status;
            break;
        //Women
        case 3:
            form.kalipi_id_number = benefeciary.benefeciary.women.kalipi_id_number;
            form.date_kic_issued = benefeciary.benefeciary.women.date_kic_issued;
            form.kic_status = benefeciary.benefeciary.women.kic_status;
            break;
    }

    form.pantawid_benefeciary = benefeciary.benefeciary.pantawid_benefeciary;
    form.indigenous_person = benefeciary.benefeciary.indigenous_person;
    form.lgbtq = benefeciary.benefeciary.lgbtq;
    form.remarks = benefeciary.benefeciary.remarks;
    form.image = benefeciary.benefeciary.image;

    imageExists = checkImageIfExist(form.image);

    if (imageExists) {
        imagePreviewUrl.value = '/profile_images/' + benefeciary.benefeciary.image;
    } else {
        form.image = null;
        imagePreviewUrl.value = null;
    }
};


const checkImageIfExist = (image) => {
    return image ? true : false;
};

const resetFields = () => {

    form.benefeciary_type = null;

    //NAME
    form.first_name = null;
    form.last_name = null;
    form.middle_name = null;
    form.extension_name = null;

    //BIO DETAILS
    form.date_of_birth = null;
    form.age = null;
    form.civil_status = null;
    form.sex = null;
    form.educational_attainment = null;
    form.religion = null;
    form.occupation = null;
    form.monthly_income = null;


    //ADDRESS
    form.street = null;
    form.barangay_id = null;
    form.city = 'Tangub City';
    form.province = 'Misamis Occidental';
    form.region = 10;
    form.contact_number = null;

    //SENIOR
    form.senior_id_number = null;
    form.socpen_benefeciary = null;


    //MISC
    form.pantawid_benefeciary = null;
    form.indigenous_person = null;
    form.lgbtq = null;
    form.remarks = null;
    form.date_issued = null;
    form.status = null;

    //WOMEN
    form.kalipi_id_number = null;

    //PWD
    form.pwd_id_number = null;

    //IMAGE
    form.image = null;
    imagePreviewUrl.value = null;

}

const cancelEdit = () => {
    resetFields();
    EditModal.value = false;
};

function openAcceptRequestModal(id) {
    RequestModal.value = true;
    responseStatus.value = 2;
    requestID.value = id;
}

function openRejectRequestModal(id) {
    RequestModal.value = true;
    responseStatus.value = 3;
    requestID.value = id;
}

const respondRequest = () => {
    processing.value = true;
    router.put(route('barangay.put-respond-transition', { id:requestID.value, status: responseStatus.value }), {}, {
        onSuccess: page => {
            Swal.fire({
                toast: true,
                icon: 'success',
                position: 'top-end',
                showConfirmation: false,
                title: page.props.flash.success
            });
            RequestModal.value = false;
            processing.value = false;
        },
        onError: () => {
            processing.value = false;
        }
    });
};

function cancelModal() {
    processing.value = false;
    RequestModal.value = false;

}

</script>

<template>

    <Head title="Transitioning" />
    <Barangaypage>
        <section class="bg-gray-50 dark:bg-gray-900 p-4 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="tabs mb-2">
                    <div class="tab tab-bordered px-6" :class="{ 'tab-active': tab == 0 }" @click="tab = 0">
                        Endorsed
                    </div>
                    <div class="tab tab-bordered px-6" :class="{ 'tab-active': tab == 1 }" @click="tab = 1">
                        Transitioned
                    </div>
                </div>
                <div v-if="tab == 0" class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="text-2xl p-2 mb-2">Endorsed</div>
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
                                    <th scope="col" class="px-4 py-3">Category</th>
                                    <th scope="col" class="px-4 py-3">Status</th>
                                    <th scope="col" class="px-4 py-3 flex gap-2">
                                        Action
                                    </th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="benefeciary in endorsed_benefeciaries" :key="benefeciary.id"
                                    class="border-b dark:border-gray-700">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{
                                            getFullName(benefeciary.benefeciary.first_name,
                                                benefeciary.benefeciary.middle_name,
                                                benefeciary.benefeciary.last_name)
                                        }}</th>
                                    <td class="px-4 py-3">{{ benefeciary.benefeciary.age }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.benefeciary.sex }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.benefeciary.date_of_birth }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.benefeciary.street }}</td>
                                    <td class="px-4 py-3">{{
                                        benefeciaryCategoryName(benefeciary.benefeciary.benefeciary_type) }}</td>
                                    <td class="px-4 py-3">
                                        <span class="badge"
                                            :class="{ 'badge-warning': benefeciary.status == 1, 'badge-error': benefeciary.status == 3 }">
                                            {{ benefeciary.status == 1 ? 'Pending' : 'Rejected' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 flex gap-2 items-center justify-start" v-if="benefeciary.status == 1">
                                        <button type="button" class="text-blue-600"
                                            @click="openEditModal(benefeciary)"><svg xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </button>
                                        <button type="button" class="text-green-600" @click="openAcceptRequestModal(benefeciary.id)"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>

                                        </button>
                                        <button type="button" class="text-red-600" @click="openRejectRequestModal(benefeciary.id)"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="endorsed_benefeciaries.length <= 0">
                                    <td colspan="7" class="px-4 py-3 text-center">No Data Found!</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div v-if="tab == 1" class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="text-2xl p-2 mb-2">Transitioned</div>

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
                                    <th scope="col" class="px-4 py-3">From</th>
                                    <th scope="col" class="px-4 py-3">Date Approved</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="benefeciary in transitioned_benefeciaries" :key="benefeciary.id"
                                    class="border-b dark:border-gray-700 hover:bg-gray-200" @click="openEditModal(benefeciary)" style="cursor: pointer">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{
                                            getFullName(benefeciary.benefeciary.first_name,
                                                benefeciary.benefeciary.middle_name,
                                                benefeciary.benefeciary.last_name)
                                        }}</th>
                                    <td class="px-4 py-3">{{ benefeciary.benefeciary.age }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.benefeciary.sex }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.benefeciary.date_of_birth }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.benefeciary.street }}</td>
                                    <td class="px-4 py-3">{{ benefeciaryCategoryName(benefeciary.from_benefeciary_type)
                                        }}</td>
                                    <td class="px-4 py-3">
                                        <span class="badge badge-success">{{ formattedDate(benefeciary.date_approved)
                                            }}</span>
                                    </td>
                                    
                                </tr>
                                <tr v-if="transitioned_benefeciaries.length <= 0">
                                    <td colspan="7" class="px-4 py-3 text-center">No Data Found!</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>


        <!--Modal View Data-->
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
                                                class="flex flex-col items-center justify-center w-full h-auto md:w-50 p-4 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                                <div v-if="!imagePreviewUrl"
                                                    class=" flex flex-col items-center justify-center pt-5 pb-6">
                                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                        <svg class="w-8 h-8 mb-4 text-gray-500  dark:text-gray-400"
                                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                            fill="none" viewBox="0 0 20 16">
                                                            <path stroke="currentColor" stroke-linecap="round"
                                                                stroke-linejoin="round" stroke-width="2"
                                                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                        </svg>
                                                        <p
                                                            class="mb-2 text-sm text-center text-gray-500 dark:text-gray-400">
                                                            <span class="font-semibold">Click to upload image</span>
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

        <!--Respond Request Modal-->
        <input class="modal-state" id="deleteModal" type="checkbox" v-model="RequestModal" />
        <div class="modal" style="z-index: 9999">
            <label class="modal-overlay"></label>
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button" @click="cancelModal()"
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
                            Are you sure you want to {{ responseStatus == 2 ? 'accept' : 'reject' }} this request?
                        </h3>
                        <ProcessingButton type="button" :process="processing" :text="'Confirm'" class="btn"
                            :class="{ 'bg-green-600 hover:bg-green-800': responseStatus == 2, 'bg-red-600 hover:bg-red-800': responseStatus == 3 }"
                            @click="respondRequest()" />
                        <button type="button" @click="cancelModal()"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            No, cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </Barangaypage>
</template>