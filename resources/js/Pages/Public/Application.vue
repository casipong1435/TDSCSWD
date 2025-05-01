<script setup>
import InputError from "@/Components/InputError.vue";
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, watch, reactive } from 'vue';
import dayjs from "dayjs";

defineProps({
    monthly_income: Array,
    barangays: Array,
    errors: Object
});

const imagePreviewUrl = ref(null);
const error_msg = ref(null);
const isSenior = ref(false);
const form_progress = ref(0);
const isShowPassword = ref(false);

const form = reactive({

    'benefeciary_type': null,

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
    'barangay_id': null,
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
    'username': null,
    'password': null,
    'password_confirmation': null
});

const handleImageUpload = (event) => {
    form.image = event.target.files[0];
    //console.log(form.image);
    imagePreviewUrl.value = URL.createObjectURL(form.image);
};

const currentDate = new Date();

// Subtract 60 years from the current date
const sixtyYearsAgoDate = new Date(
    currentDate.setFullYear(currentDate.getFullYear() - 60)
);


watch(
    () => form.date_of_birth,
    (newDateOfBirth) => {
        if (newDateOfBirth) {
            const today = new dayjs();

            const birthDate = new dayjs(newDateOfBirth);
            form.age = today.diff(birthDate, "year");
            if (form.age >= 60) {
                form.benefeciary_type = 1;
                isSenior.value = true;
            } else {
                isSenior.value = false;
                form.benefeciary_type = null;
            }
        }
    }
);

const validateApplicationDetails = () => {
    router.post(route('public.validateApplicationDetails'), form, {
        onSuccess: (page) => {
            if (page.props.flash.success) {
                form_progress.value = 1;
            } else {
                error_msg.value = page.props.flash.error;
            }
        }
    })
};

const apply = () => {
    router.post(route('public.apply'), form, {
        onSuccess: (page) => {
            if (page.props.flash.success) {
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmation: false,
                    title: page.props.flash.success,
                }),
                    window.location.href = route('login');
            } else {
                error_msg.value = page.props.flash.error;
            }
        }
    })
};

</script>

<style scoped>
/* Custom styles for the dropdown scrollbar (optional) */
.max-h-40::-webkit-scrollbar {
    width: 6px;
}

.max-h-40::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 3px;
}
</style>

<template>

    <Head title="Application Form" />
    <section class="bg-gray-50 dark:bg-gray-900 p-4 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">

            <div class="text-center mb-12">
                <div class="flex justify-center items-center gap-5 mb-5">
                    <img src="assets/images/cswd.png" alt="" class="h-20 w-20">
                    <h1 class="text-3xl font-bold text-blue-800 mb-1">
                        CSWD Tangub City
                    </h1>
                </div>
                <p class="text-gray-600">Empowering the community through services and programs.</p>
                <div class="mt-5 font-bold text-2xl">
                    Application Form
                </div>
            </div>

            <div v-if="form_progress == 0"
                class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-3">

                <div v-if="error_msg != null" id="alert-3"
                    class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                    role="alert">
                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <div class="ms-3 text-sm font-medium">
                        <InputError class="mt-2 text-white" :message="error_msg" />
                    </div>
                    <button type="button"
                        class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"
                        data-dismiss-target="#alert-3" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>
                <div v-show="Object.keys(errors).length > 0 || usePage().props.errors.length > 0" id="alert-2"
                    class="flex items-center p-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                    role="alert">
                    <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="sr-only">Info</span>
                    <div class="ms-3 text-sm font-medium">
                        <InputError class="mt-2 text-white" :message="errors.benefeciary_type" />
                        <InputError class="mt-2 text-white" :message="errors.image" />
                        <InputError class="mt-2 text-white" :message="errors.first_name" />
                        <InputError class="mt-2 text-white" :message="errors.last_name" />
                        <InputError class="mt-2 text-white" :message="errors.date_of_birth" />
                        <InputError class="mt-2 text-white" :message="errors.civil_status" />
                        <InputError class="mt-2 text-white" :message="errors.sex" />
                        <InputError class="mt-2 text-white" :message="errors.religion" />
                        <InputError class="mt-2 text-white" :message="errors.educational_attainment" />
                        <InputError class="mt-2 text-white" :message="errors.monthly_income" />
                        <InputError class="mt-2 text-white" :message="errors.senior_id_number" />
                        <InputError class="mt-2 text-white" :message="errors.kalipi_id_number" />
                        <InputError class="mt-2 text-white" :message="errors.pwd_id_number" />
                        <InputError class="mt-2 text-white" :message="errors.sic_status" />
                        <InputError class="mt-2 text-white" :message="errors.date_sic_issued" />
                        <InputError class="mt-2 text-white" :message="errors.kic_status" />
                        <InputError class="mt-2 text-white" :message="errors.date_kic_issued" />
                        <InputError class="mt-2 text-white" :message="errors.pic_status" />
                        <InputError class="mt-2 text-white" :message="errors.date_pic_issued" />
                        <InputError class="mt-2 text-white" :message="errors.contact_number" />
                        <InputError class="mt-2 text-white" :message="errors.barangay_id" />
                        <InputError class="mt-2 text-white" :message="errors.street" />
                        <InputError class="mt-2 text-white" :message="errors.socpen_benefeciary" />
                        <InputError class="mt-2 text-white" :message="errors.pantawid_benefeciary" />
                        <InputError class="mt-2 text-white" :message="errors.indigenous_person" />
                        <InputError class="mt-2 text-white" :message="errors.lgbtq" />
                    </div>
                    <button type="button"
                        class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"
                        data-dismiss-target="#alert-2" aria-label="Close">
                        <span class="sr-only">Close</span>
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-10 gap-5 w-full">
                    <div class="col-span-10 grid md:grid-cols-2 ">
                        <div class="relative">
                            <div class="relative mb-2 lg:px-5">
                                <div class="relative flex items-center justify-center w-full">
                                    <label for="image-file"
                                        class="flex flex-col items-center justify-center w-full h-auto md:w-50 p-4 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                                        :class="{ 'bg-red-50': errors.image }">
                                        <div v-if="!imagePreviewUrl"
                                            class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400"
                                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 20 16">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                </svg>
                                                <p class="mb-2 text-sm text-center text-gray-500 dark:text-gray-400">
                                                    <span class="font-semibold">Click to upload image *</span>
                                                </p>
                                            </div>
                                        </div>

                                        <div v-else class="flex items-center justify-center">
                                            <img :src="imagePreviewUrl"
                                                class="w-auto rounded object-cover h-48 w-96 cursor-pointer"
                                                alt="Image preview" />
                                        </div>

                                        <input @input="handleImageUpload" id="image-file" type="file" class="hidden"
                                            accept=".jpg,.jpeg,.png" />
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <div class="relative mb-2">
                                <select :disabled="isSenior" v-model="form.benefeciary_type"
                                    id="floating_benefeciary_type"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 pt-4 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="1">Senior</option>
                                    <option value="2">PWD</option>
                                    <option value="3">Women</option>
                                </select>
                                <label for="floating_benefeciary_type"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Beneficiary
                                    Type *</label>
                            </div>
                            <div class="relative mb-2">
                                <input v-model="form.first_name" type="text" id="floating_first_name"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="floating_first_name"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">First
                                    Name *</label>
                            </div>

                            <div class="relative mb-2">
                                <input v-model="form.middle_name" type="text" id="floating_middle_name"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " />
                                <label for="floating_middle_name"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Middle
                                    Name (Optional)</label>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <div class="relative">
                                    <input v-model="form.last_name" type="text" id="floating_last_name"
                                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " />
                                    <label for="floating_last_name"
                                        class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Last
                                        Name</label>
                                </div>

                                <div class="relative">
                                    <input v-model="form.extension_name" type="text" id="floating_extension_name"
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
                                <input v-model="form.date_of_birth" type="date" id="floating_date_of_birth"
                                    class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                                <label for="floating_date_of_birth"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Date
                                    of Birth *</label>
                            </div>

                            <div class="relative mb-2">
                                <input disabled v-model="form.age" type="number" id="floating_age"
                                    class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder="Age" />

                            </div>
                        </div>

                        <div class="md:grid grid-cols-2 gap-2">

                            <div class="relative mb-2">
                                <select v-model="form.civil_status" id="floating_civil_status"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 pt-4 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Widowed">Widowed</option>
                                    <option value="Divorced">Divorced</option>
                                </select>
                                <label for="floating_civil_status"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Civil
                                    Status *</label>
                            </div>

                            <div class="relative mb-2">
                                <select v-model="form.sex" id="floating_sex"
                                    class="bg-gray-50 border border-gray-300 pt-4 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                                <label for="floating_sex"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Sex
                                    *</label>
                            </div>

                        </div>

                        <div class="relative mb-2">
                            <input v-model="form.religion" type="text" id="floating_religion"
                                class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" />
                            <label for="floating_religion"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Religion
                                *</label>
                        </div>


                        <div class="relative">
                            <select v-model="form.educational_attainment" id="floating_educational_attainment"
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
                                Attainment *</label>
                        </div>


                        <div class="grid grid-cols-2 gap-2">
                            <div class="relative">
                                <input v-model="form.occupation" type="text" id="floating_occupation"
                                    class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder="" />
                                <label for="floating_occupation"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Occupation
                                    *</label>
                            </div>


                            <div class="relative">
                                <select v-model="form.monthly_income" id="floating_monthly_income"
                                    class="bg-gray-50 border border-gray-300 pt-4 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option v-for="income in monthly_income" :value="income.id" :key="income.id">{{
                                        income.income_range }}</option>

                                </select>
                                <label for="floating_monthly_income"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Monthly
                                    Income *</label>
                            </div>

                        </div>

                        <div class="relative">
                            <input v-model="form.contact_number" type="text" id="floating_contact_number"
                                class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" />
                            <label for="floating_contact_number"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Contact
                                Number *</label>
                        </div>



                    </div>


                </div>

                <div class="grid grid-cols-10 gap-5 w-full">
                    <div class="text-center">
                        <p>Address</p>
                    </div>
                    <div class="col-span-10 grid grid-cols-2 gap-2 w-full">
                        <div class="relative mb-2">
                            <select v-model="form.barangay_id" id="floating_barangay"
                                class="bg-gray-50 border border-gray-300 text-gray-900 pt-4 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option v-for="barangay in usePage().props.barangays" :key="barangay.id"
                                    :value="barangay.id" :="form.barangay_id">{{
                                        barangay.barangay_name }}</option>

                            </select>
                            <label for="floating_barangay"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Barangay
                                *</label>
                        </div>


                        <div class="relative">
                            <input v-model="form.street" type="text" id="floating_street"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " />
                            <label for="floating_street"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                Street/Purok *</label>
                        </div>


                        <div class="relative">
                            <input v-model="form.city" type="text" id="floating_city"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " />
                            <label for="floating_city"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                City/Municipality *</label>
                        </div>
                        <div class="relative">
                            <input v-model="form.province" type="text" id="floating_province"
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " />
                            <label for="floating_city"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                Province *</label>
                        </div>


                    </div>
                </div>


                <!---THIS IS WHERE SPECIFIC DATA LOCATED-->
                <div class="my-2 text-xl">Additional Information</div>
                <div class="grid grid-cols-10 gap-5 w-full">
                    <div class="col-span-10 grid grid-cols-2 gap-2 w-full">
                        <!--Senior-->
                        <div v-if="form.benefeciary_type == 1" class="relative">
                            <input v-model="form.senior_id_number" type="text" id="floating_senior_id_number"
                                class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" />
                            <label for="floating_senior_id_number"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Senior
                                ID Number</label>
                        </div>

                        <!--PWD-->
                        <div v-else-if="form.benefeciary_type == 2" class="relative">
                            <input v-model="form.pwd_id_number" type="text" id="floating_pwd_id_number"
                                class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" />
                            <label for="floating_pwd_id_number"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">PWD
                                ID Number</label>
                        </div>
                        <!--Women-->
                        <div v-else class="relative">
                            <input v-model="form.kalipi_id_number" type="text" id="floating_kalipi_id_number"
                                class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" />
                            <label for="floating_kalipi_id_number"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Kalipi
                                ID Number</label>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div v-if="form.benefeciary_type == 1" class="relative">
                                <input v-model="form.date_sic_issued" type="date" id="floating_date_sic_issued"
                                    class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder="" />
                                <label for="floating_date_sic_issued"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Date
                                    Issued</label>
                            </div>

                            <div v-else-if="form.benefeciary_type == 2" class="relative">
                                <input v-model="form.date_pic_issued" type="date" id="floating_date_pic_issued"
                                    class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder="" />
                                <label for="floating_date_pic_issued"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Date
                                    Issued</label>
                            </div>

                            <div v-else class="relative">
                                <input v-model="form.date_kic_issued" type="date" id="floating_date_kic_issued"
                                    class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder="" />
                                <label for="floating_date_kic_issued"
                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Date
                                    Issued</label>
                            </div>

                            <div v-if="form.benefeciary_type == 1" class="relative">
                                <select v-model="form.sic_status" id="floating_sic_status"
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
                                <select v-model="form.pic_status" id="floating_pic_status"
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
                                <select v-model="form.kic_status" id="floating_kic_status"
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
                                <input v-model="form.socpen_benefeciary" id="socpen_benefeciary" type="radio" value="1"
                                    name="socpen_benefeciary"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="socpen_benefeciary"
                                    class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes
                                </label>

                            </div>
                            <div class="flex items-center">
                                <input v-model="form.socpen_benefeciary" id="not_socpen_benefeciary" type="radio"
                                    value="0" name="socpen_benefeciary"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="not_socpen_benefeciary"
                                    class="w-full text-sm ms-2 font-medium text-gray-900 dark:text-gray-300">No
                                </label>
                            </div>
                        </div>

                        <div class="relative mb-2">
                            <div class="mb-2">Pantawid Benefeciary?</div>
                            <div class="flex items-center mb-4">
                                <input v-model="form.pantawid_benefeciary" id="pantawid_benefeciary" type="radio"
                                    value="1" name="pantawid_benefeciary"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="pantawid_benefeciary"
                                    class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes
                                </label>

                            </div>
                            <div class="flex items-center">
                                <input v-model="form.pantawid_benefeciary" id="not_pantawid_benefeciary" type="radio"
                                    value="0" name="pantawid_benefeciary"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="not_pantawid_benefeciary"
                                    class="w-full text-sm ms-2 font-medium text-gray-900 dark:text-gray-300">No
                                </label>
                            </div>
                        </div>


                        <div class="relative mb-2">
                            <div class="mb-2">Indigenous Person?</div>
                            <div class="flex items-center mb-4">
                                <input v-model="form.indigenous_person" id="indigenous_person" type="radio" value="1"
                                    name="indigenous_person"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="indigenous_person"
                                    class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes
                                </label>

                            </div>
                            <div class="flex items-center">
                                <input v-model="form.indigenous_person" id="not_indigenous_person" type="radio"
                                    value="0" name="indigenous_person"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="not_indigenous_person"
                                    class="w-full text-sm ms-2 font-medium text-gray-900 dark:text-gray-300">No
                                </label>
                            </div>
                        </div>

                        <div class="relative mb-2">
                            <div class="mb-2">LGBTQ+?</div>
                            <div class="flex items-center mb-4">
                                <input v-model="form.lgbtq" id="lgbtq" type="radio" value="1" name="lgbtq"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="lgbtq"
                                    class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes
                                </label>

                            </div>
                            <div class="flex items-center">
                                <input v-model="form.lgbtq" id="not_lgbtq" type="radio" value="0" name="lgbtq"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="not_lgbtq"
                                    class="w-full text-sm ms-2 font-medium text-gray-900 dark:text-gray-300">No
                                </label>
                            </div>
                        </div>

                    </div>



                </div>
                <div class="flex justify-end flex-row gap-2 w-full">
                    <button type="button" class="btn btn-primary" @click="validateApplicationDetails">Next</button>
                    <a class="btn btn-gray-50" :href="route('login')">Cancel</a>
                </div>
            </div>

            <div v-else class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-3">
                <div class=" flex justify-center items-center">
                    <div class="mt-4 shadow" style="width: 350px;">
                        <div class="text-center font-bold text-2xl mb-2">Create Account</div>
                        <div class="text-center mb-4">
                            <p>You need to create an account to monitor your application and future benefits.</p>
                        </div>
                        <div class="flex flex-col gap-3 items-center justify-center px-2 w-full">
                            <div class="w-full">
                                <label for="username">Username</label>
                                <input id="username" type="text" class="rounded min-w-full" v-model="form.username">
                                <InputError :message="errors.username" />
                            </div>
                            <div class="w-full">
                                <label for="username">Password</label>
                                <input id="username" :type="isShowPassword ? 'text' : 'password'" class="rounded min-w-full" v-model="form.password">
                                <InputError :message="errors.password" />
                            </div>
                            <div class="w-full">
                                <label for="username">Confirm Password</label>
                                <input id="username" :type="isShowPassword ? 'text' : 'password'" class="rounded min-w-full"
                                    v-model="form.password_confirmation">
                                <InputError :message="errors.password_confirmation" />
                                <InputError :message="error_msg" />
                            </div>
                        </div>
                        <div class="my-2 flex flex-row px-2 items-center">
                            <input type="checkbox" id="show-password" :checked="isShowPassword" @change="isShowPassword = !isShowPassword" class="me-2">
                            <label for="show-password">Show Password</label>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center mt-4 gap-2 items-center">
                    <button type="button" class="btn btn-primary" @click="apply">Submit</button>
                    <button type="button" class="btn btn-gray-50" @click="form_progress = 0">Go Back</button>
                </div>
            </div>
        </div>

    </section>
</template>