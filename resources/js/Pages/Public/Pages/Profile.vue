<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { reactive, onMounted, ref } from 'vue';

import Publicpage from '../PublicLayout/Publicpage.vue';

const {benefeciary, barangays, request_info, monthly_income} = usePage().props;
const imagePreviewUrl = ref(null);
const isStatusInfoModal = ref(false);

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
});


let imageExists;

onMounted(() => {
    form.benefeciary_type = benefeciary.benefeciary_type;
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

    switch (benefeciary.benefeciary_type) {
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
        imagePreviewUrl.value = '/profile_images/' + benefeciary.image;
    } else {
        form.image = null;
        imagePreviewUrl.value = null;
    }
});

const checkImageIfExist = (image) => {
    return image ? true : false;
};

const getRequestStatusName = () => {
    switch (request_info.status) {
        case 0:
            return 'Pending';
        case 1:
            return 'Checked';
        case 2:
            return 'Verified';
        case 3:
            return 'Approved';
        case 4:
            return 'Rejected';
    }
};

const getStatusName = () => {
    switch (benefeciary.approved_status) {
        case 0:
            return 'To be Endorsed';
        case 1:
            return 'Officialy Approved';
        case 2:
            return 'Archived';
        case 3:
            return 'Pending';
        case 5:
            return 'Rejected';
    }
};

function viewStatusInfo() {
    isStatusInfoModal.value = true;
}

</script>

<style>
/* Optional: Customize the scroll behavior */
div::-webkit-scrollbar {
    height: 8px;
}

div::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 4px;
}
</style>

<template>

    <Head title="My Dashboard" />
    <Publicpage>


        <div class="font-bold text-2xl mb-5">
            My Profile
        </div>


        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-3">

            <div v-if="request_info" class="flex justify-end gap-4 mb-4">
                <span>Status:</span>
                <label for="statusModal" class="badge cursor-pointer" :class="{'badge-warning' : request_info.status == 0 || request_info.status == 1 || request_info.status == 2, 'badge-success' : request_info.status == 3, 'badge-error' : request_info.status == 4 }">
                    <span>{{ getRequestStatusName() }}</span>
                </label>

            </div>
            <div v-else class="flex justify-end gap-4 mb-4">
                <span>Status:</span>
                <label for="statusModal" class="badge cursor-pointer" :class="{'badge-warning' : benefeciary.approved_status == 0 || benefeciary.approved_status == 3, 'badge-success' : benefeciary.approved_status == 1, 'badge-error' : benefeciary.approved_status == 5 }">
                    <span>{{ getStatusName() }}</span>
                </label>
                
            </div>
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

                                            <p class="mb-2 text-sm text-center text-gray-500 dark:text-gray-400">
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
                                <input disabled v-model="form.extension_name" type="text" id="floating_extension_name"
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
                            <input disabled v-model="form.date_of_birth" type="date" id="floating_date_of_birth"
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
                        <select disabled v-model="form.educational_attainment" id="floating_educational_attainment"
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
                                <option v-for="income in monthly_income" :value="income.id" :key="income.id">{{
                                    income.income_range }}</option>

                            </select>
                            <label for="floating_monthly_income"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Monthly
                                Income</label>
                        </div>

                    </div>

                    <div class="relative">
                        <input disabled v-model="form.contact_number" type="text" id="floating_contact_number"
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
                            <option v-for="barangay in barangays" :key="barangay.id"
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
                        <input disabled v-model="form.senior_id_number" type="text" id="floating_senior_id_number"
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
                        <input disabled v-model="form.kalipi_id_number" type="text" id="floating_kalipi_id_number"
                            class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder="" />
                        <label for="floating_kalipi_id_number"
                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Kalipi
                            ID Number</label>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div v-if="form.benefeciary_type == 1" class="relative">
                            <input disabled v-model="form.date_sic_issued" type="date" id="floating_date_sic_issued"
                                class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" />
                            <label for="floating_date_sic_issued"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Date
                                Issued</label>
                        </div>

                        <div v-else-if="form.benefeciary_type == 2" class="relative">
                            <input disabled v-model="form.date_pic_issued" type="date" id="floating_date_pic_issued"
                                class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder="" />
                            <label for="floating_date_pic_issued"
                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Date
                                Issued</label>
                        </div>

                        <div v-else class="relative">
                            <input disabled v-model="form.date_kic_issued" type="date" id="floating_date_kic_issued"
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
                            <input disabled v-model="form.socpen_benefeciary" id="socpen_benefeciary" type="radio"
                                value="1" name="socpen_benefeciary"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="socpen_benefeciary"
                                class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes
                            </label>

                        </div>
                        <div class="flex items-center">
                            <input disabled v-model="form.socpen_benefeciary" id="not_socpen_benefeciary" type="radio"
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
                            <input disabled v-model="form.pantawid_benefeciary" id="pantawid_benefeciary" type="radio"
                                value="1" name="pantawid_benefeciary"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="pantawid_benefeciary"
                                class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes
                            </label>

                        </div>
                        <div class="flex items-center">
                            <input disabled v-model="form.pantawid_benefeciary" id="not_pantawid_benefeciary"
                                type="radio" value="0" name="pantawid_benefeciary"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="not_pantawid_benefeciary"
                                class="w-full text-sm ms-2 font-medium text-gray-900 dark:text-gray-300">No
                            </label>
                        </div>
                    </div>


                    <div class="relative mb-2">
                        <div class="mb-2">Indigenous Person?</div>
                        <div class="flex items-center mb-4">
                            <input disabled v-model="form.indigenous_person" id="indigenous_person" type="radio"
                                value="1" name="indigenous_person"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="indigenous_person"
                                class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes
                            </label>

                        </div>
                        <div class="flex items-center">
                            <input disabled v-model="form.indigenous_person" id="not_indigenous_person" type="radio"
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
                            <input disabled v-model="form.lgbtq" id="lgbtq" type="radio" value="1" name="lgbtq"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="lgbtq"
                                class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes
                            </label>

                        </div>
                        <div class="flex items-center">
                            <input disabled v-model="form.lgbtq" id="not_lgbtq" type="radio" value="0" name="lgbtq"
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
                placeholder=""></textarea>

        </div>

        <input class="modal-state" id="statusModal" type="checkbox" v-model="isStatusInfoModal" />
        <div class="modal" style="z-index: 9999">
            <label class="modal-overlay"></label>
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">

                    <button type="button" @click="isStatusInfoModal = false"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="text-start p-2">
                        <h2 v-if="request_info" class="text-xl">Endorsement Status</h2>
                        <h2 v-else class="text-xl">Application Status</h2>
                    </div>
                    <div v-if="request_info" class="py-2 px-3 grid grid-cols-1 gap-2 text-sm">
                        <div class="flex flex-col py-2 gap-2">
                            <div class="flex justify-between items-center font-bold">
                                <span>Barangay Checking:</span>
                                <span class="badge"
                                    :class="{ 'badge-warning': request_info.status < 1, 'badge-success': request_info.status >= 1 && request_info.status < 4}">{{
                                        request_info.status < 1 ? 'Pending' : request_info.status >= 1 && request_info.status < 4 ? 'Checked' : '--' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span>Date Checked:</span>
                                <span>{{ request_info.status >= 1 && request_info.status < 4 ? request_info.date_checked : '--' }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col py-2 gap-2">
                            <div class="flex justify-between items-center font-bold">
                                <span>CSWD Staff Verification:</span>
                                <span class="badge"
                                    :class="{ 
                                        'badge-warning': request_info.status < 2, 
                                        'badge-success': request_info.status >= 2 && request_info.status < 4}">{{
                                        request_info.status < 2 ? 'Pending' : request_info.status >= 2 && request_info.status < 4 ? 'Verified' : '--' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span>Date Verified:</span>
                                <span>{{ request_info.status  >= 2 && request_info.status  < 4 ? request_info.date_verified : '--' }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col py-2 gap-2">
                            <div class="flex justify-between items-center font-bold">
                                <span>CSWD Admin Approval:</span>
                                <span class="badge"
                                    :class="{ 'badge-warning': request_info.status < 3, 'badge-success': request_info.status >= 3 && request_info.status < 4}">{{
                                        request_info.status < 3 ? 'Pending' : request_info.status >= 3 && request_info.status < 4 ? 'Approved' : '--' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span>Date Approved:</span>
                                <span>{{ request_info.status >= 3 && request_info.status < 4 ? request_info.date_approved : '--' }}</span>
                            </div>

                        </div>
                        <div v-if="request_info.status == 4" class="flex flex-col py-2 gap-2">
                            <div class="flex justify-between items-center font-bold">
                                <span>Status:</span>
                                <span class="badge badge-error">Rejected</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span>Date Rejected:</span>
                                <span>{{ request_info.date_rejected }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span>Rejected By:</span>
                                <span>{{ request_info.rejected_by }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span>Reason:</span>
                                <span class="font-bold">{{ request_info.reason }}</span>
                            </div>
                        </div>
                    </div>
                    <div v-else class="py-2 px-3 grid grid-cols-1 gap-2 text-sm">
                        
                        <div v-if="benefeciary.approved_status == 3 || benefeciary.approved_status == 0" class="flex flex-col py-2 gap-2">
                            <div class="flex justify-between items-center font-bold">
                                <span>Focal Approval:</span>
                                <span class="badge badge-warning"
                                    >{{
                                        benefeciary.approved_status == 3 ? 'Pending' : 'To be Endorsed' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span>Date Approved:</span>
                                <span>{{ benefeciary.date_approved  }}</span>
                            </div>

                            <div v-if="benefeciary.approved_status == 0" class="mt-4">
                                <b> Note: </b> Your account needs to be endorsed and approved before you became an official beneficiary of CSWD Tangub City.
                            </div>

                        </div>
                        <div v-else-if="benefeciary.approved_status == 5" class="flex flex-col py-2 gap-2">
                            <div class="flex justify-between items-center font-bold">
                                <span>Status:</span>
                                <span class="badge badge-error">Rejected</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span>Date Rejected:</span>
                                <span>{{ benefeciary.date_rejected }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span>Reason:</span>
                                <span>{{ benefeciary.reason }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end items-center p-3">
                        <button type="button" @click="isStatusInfoModal = false"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </Publicpage>
</template>
