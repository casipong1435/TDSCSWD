<script setup>
import Focalpage from '../FocalLayout/Focalpage.vue';
import InputError from '@/Components/InputError.vue';
import ProcessingButton from '@/Components/ProcessingButton.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, usePage, router, useForm } from '@inertiajs/vue3';
import { onMounted, reactive, ref, watch } from 'vue';
import dayjs from 'dayjs';

let imageExists = ref(false);
const imagePreviewUrl = ref(null);
const searchInput = ref('');
const focal_role = ref(null);
const EditModal = ref(false);
const deleteModal = ref(false);
const processing = ref(false);

defineProps({
    benefeciaries: Object,
    errors: Object,
    monthly_income: Array,
    focal_barangay: String,
    barangays: Array
});

onMounted(() => {
    focal_role.value = usePage().props.auth.user.focal_role;
})

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
})

const currentDate = new Date();

// Subtract 60 years from the current date
const sixtyYearsAgoDate = new Date(currentDate.setFullYear(currentDate.getFullYear() - 60));


// Format the date (optional)
const formatDate = (date) => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-based
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
};

const senior_date = formatDate(sixtyYearsAgoDate);

let benefeciary_id = ref('');
let benefeciary_type = ref('');


const getFullName = (first_name, middle_name, last_name) => {
    return first_name + " " + (middle_name != null ? middle_name + " " : "") + last_name;
}

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
}


const openEditModal = (benefeciary, benefeciary_type) => {
    EditModal.value = true;
    getBenefeciaryData(benefeciary, benefeciary_type);

}

const openDeleteModal = (id, type) => {
    deleteModal.value = true;
    setBenefeciaryData(id, type);
}

function filtersearchInput() {
    router.get(route('focal.beneficiaries'),
        { searchInput: searchInput.value },
        {
            preserveState: true,
            replace: true
        })
}

watch(searchInput, (value) => {
    if (value == "") {
        router.get(route('focal.beneficiaries'),
            { searchInput: value },
            {
                preserveState: true,
                replace: true
            })
    }
});

watch(
    () => form.date_of_birth,
    (newDateOfBirth) => {

        if (newDateOfBirth) {
            const today = new dayjs();

            const birthDate = new dayjs(newDateOfBirth);
            form.age = today.diff(birthDate, 'year');
        } else {
            form.age = null;
        }
    }
);

const resetFields = () => {

    form.benefeciary_type = focal_role.value;

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
    form.barangay_id = usePage().props.auth.user.barangay_id;
    form.city = 'Tangub City';
    form.province = 'Misamis Occidental';
    form.region = 10;
    form.contact_number = null;

    //SENIOR
    form.senior_id_number = null;
    form.socpen_benefeciary = null;
    form.date_sic_issued = null;
    form.sic_status = null;


    //MISC
    form.pantawid_benefeciary = null;
    form.indigenous_person = null;
    form.lgbtq = null;
    form.remarks = null;

    //WOMEN
    form.kalipi_id_number = null;
    form.date_kic_issued = null;
    form.kic_status = null;

    //PWD
    form.pwd_id_number = null;
    form.date_pic_issued = null;
    form.pic_status = null;

    //IMAGE
    form.image = null;
    imagePreviewUrl.value = null;

}

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

    switch (form.benefeciary_type) {
        //Senior
        case 1:
            form.socpen_benefeciary = benefeciary?.senior?.socpen_benefeciary?? null;
            form.senior_id_number = benefeciary?.senior?.senior_id_number?? null;
            form.date_sic_issued = benefeciary?.senior?.date_sic_issued?? null;
            form.sic_status = benefeciary?.senior?.sic_status?? null;
            break;
        //PWD
        case 2:
            form.pwd_id_number = benefeciary?.pwd?.pwd_id_number?? null;
            form.date_pic_issued = benefeciary?.pwd?.date_pic_issued?? null;
            form.pic_status = benefeciary?.pwd?.pic_status?? null;
            break;
        //Women
        case 3:
            form.kalipi_id_number = benefeciary?.women?.kalipi_id_number?? null;
            form.date_kic_issued = benefeciary?.women?.date_kic_issued?? null;
            form.kic_status = benefeciary?.women?.kic_status?? null;
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

}

const checkImageIfExist = (image) => {
    return image ? true : false;
}

const setBenefeciaryData = (id, type) => {
    benefeciary_id = id;
    benefeciary_type = type;
}

const cancelEdit = () => {
    resetFields();
    EditModal.value = false;
}

const saveEditBenefeciaryData = () => {
    processing.value = true;
    try {
        router.post(
            route('focal.update-beneficiary', benefeciary_id),
            {
                _method: 'put',
                ...form,
            },
            {
                onSuccess: page => {
                    EditModal.value = false,
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            position: 'top-end',
                            showConfirmation: false,
                            title: page.props.flash.success
                        }),
                        processing.value = false
                },
                onError: () => {
                    processing.value = false
                },
            }
        );
    } catch (err) {
        console.log(err);
    }
}


const deleteBenefeciary = () => {
    processing.value = true;
    try {
        router.delete(route('focal.delete-beneficiary', { id: benefeciary_id, type: benefeciary_type }), {
            onSuccess: page => {
                deleteModal.value = false,
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        position: 'top-end',
                        showConfirmation: false,
                        title: page.props.flash.success
                    }),
                    processing.value = false
            },
            onError: () => {
                processing.value = false
            },
        })
    } catch (err) {
        console.log(err);
    }
};

const handleImageUpload = (event) => {
    form.image = event.target.files[0];
    //console.log(form.image);
    imagePreviewUrl.value = URL.createObjectURL(form.image);
};
</script>

<template>

    <Head title="Focal Dashboard" />
    <Focalpage>
        <section class="bg-gray-50 dark:bg-gray-900 p-4 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="text-2xl p-2 mb-2">{{ benefeciaryCategoryName(focal_role) + " Beneficiaries (Brgy. " +
                        usePage().props.focal_barangay + " )" }}</div>
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
                                    <th scope="col" class="px-4 py-3">Name</th>
                                    <th scope="col" class="px-4 py-3">Age</th>
                                    <th scope="col" class="px-4 py-3">Sex</th>
                                    <th scope="col" class="px-4 py-3">Birthdate</th>
                                    <th scope="col" class="px-4 py-3">Purok</th>
                                    <th scope="col" class="px-4 py-3">Status</th>
                                    <th scope="col" class="px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="benefeciary in benefeciaries.data" :key="benefeciary.id"
                                    class="border-b dark:border-gray-700" :class="{'bg-gray-200':benefeciary.approved_status == 2}">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{
                                            getFullName(benefeciary.first_name, benefeciary.middle_name,
                                                benefeciary.last_name)
                                        }}</th>
                                    <td class="px-4 py-3">{{ benefeciary.age }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.sex }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.date_of_birth }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.street }}</td>
                                    <td class="px-4 py-3 font-bold" :class="{'text-success': benefeciary.approved_status == 1, 'text-warning':benefeciary.approved_status == 2}">{{ benefeciary.approved_status == 1 ? 'Active' :'Archived' }}</td>
                                    
                                    <td class="px-4 py-3 gap-2 flex flex-row">
                                        <button type="btn" class="text-blue-700"
                                            @click="openEditModal(benefeciary, benefeciary.benefeciary_type)"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </button>
                                        <!--
                                        <button type="btn" class="text-red-700"
                                            @click="openDeleteModal(benefeciary.id, benefeciary.benefeciary_type)"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>

                                        </button>
                                        -->
                                    </td>

                                </tr>
                                <tr v-if="benefeciaries.data.length <= 0">
                                    <td colspan="8" class="px-4 py-3 text-center">No Data Found!</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <Pagination :data="benefeciaries" />
                </div>
            </div>
        </section>


        <!--Delete Modal-->
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
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to
                            remove this benefeciary?</h3>
                        <ProcessingButton type="button" :process="processing" :text="'Confirm'"
                            class="bg-red-600 hover:bg-red-800" @click="deleteBenefeciary()" />
                        <button type="button" @click="deleteModal = false"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No,
                            cancel</button>

                    </div>
                </div>
            </div>
        </div>

        <!--Add or Edit Modal-->
        <input class="modal-state" id="AddorEditModal" type="checkbox" v-model="EditModal" />
        <div class="modal !h-full !items-start overflow-y-auto" style="z-index: 9999">
            <label class="modal-overlay" for="AddorEditModal"></label>
            <form @submit.prevent="saveEditBenefeciaryData()">
                <div class="relative p-4 w-full max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-2 md:p-2 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Edit Benefeciary
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


                            <!--ALERT ERRORS-->
                            <div v-show="Object.keys(errors).length > 0" id="alert-2"
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
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                    </svg>
                                </button>
                            </div>


                            <div class="grid grid-cols-10 gap-5 w-full">
                                <div class="col-span-10 grid md:grid-cols-2 ">
                                    <div class="relative">
                                        <div class=" relative mb-2 lg:px-5">
                                            <div class="relative flex items-center justify-center w-full">
                                                <label for="image-file"
                                                    class="flex flex-col items-center justify-center w-full h-auto md:w-50 p-4 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600"
                                                    :class="{ 'bg-red-50': errors.image }">
                                                    <div v-if="!imagePreviewUrl"
                                                        class=" flex flex-col items-center justify-center pt-5 pb-6">
                                                        <div
                                                            class="flex flex-col items-center justify-center pt-5 pb-6">
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

                                                    <input @input="handleImageUpload" id="image-file" type="file"
                                                        class="hidden" />

                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="relative">
                                        <div class="relative mb-2">
                                            <select v-model="form.benefeciary_type" id="floating_benefeciary_type"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 pt-4 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.benefeciary_type }">
                                                <option value="1" v-if="focal_role == 1">Senior</option>
                                                <option value="2" v-else-if="focal_role == 2">PWD</option>
                                                <option value="3" v-else>Women</option>
                                            </select>
                                            <label for="floating_benefeciary_type"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                                :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.benefeciary_type }">Beneficiary
                                                Type</label>
                                        </div>
                                        <div class="relative mb-2">
                                            <input v-model="form.first_name" type="text" id="floating_first_name"
                                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.first_name }"
                                                placeholder=" " />
                                            <label for="floating_first_name"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                                :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.first_name }">First
                                                Name</label>
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
                                                    :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.last_name }"
                                                    placeholder=" " />
                                                <label for="floating_last_name"
                                                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                                    :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.last_name }">Last
                                                    Name</label>
                                            </div>

                                            <div class="relative">
                                                <input v-model="form.extension_name" type="text"
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
                                            <input v-model="form.date_of_birth" type="date" id="floating_date_of_birth"
                                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.date_of_birth }"
                                                placeholder=" " :max="form.benefeciary_type == 1 ? senior_date : ''" />
                                            <label for="floating_date_of_birth"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                                :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.date_of_birth }">Date
                                                of Birth</label>
                                        </div>

                                        <div class="relative mb-2">
                                            <input v-model="form.age" type="number" id="floating_age"
                                                :min="usePage().props.dateNow"
                                                class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                placeholder="Age" disabled />

                                        </div>
                                    </div>

                                    <div class="md:grid grid-cols-2 gap-2">

                                        <div class="relative mb-2">
                                            <select v-model="form.civil_status" id="floating_civil_status"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 pt-4 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.civil_status }">
                                                <option value="Single">Single</option>
                                                <option value="Married">Married</option>
                                                <option value="Widowed">Widowed</option>
                                                <option value="Divorced">Divorced</option>
                                            </select>
                                            <label for="floating_civil_status"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                                :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.civil_status }">Civil
                                                Status</label>
                                        </div>

                                        <div class="relative mb-2">
                                            <select v-model="form.sex" id="floating_sex"
                                                class="bg-gray-50 border border-gray-300 pt-4 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.sex }">
                                                <option v-if="focal_role != 3" value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                            <label for="floating_sex"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                                :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.sex }">Sex</label>
                                        </div>

                                    </div>

                                    <div class="relative mb-2">
                                        <input v-model="form.religion" type="text" id="floating_religion"
                                            class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.religion }"
                                            placeholder="" />
                                        <label for="floating_religion"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                            :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.religion }">Religion</label>
                                    </div>


                                    <div class="relative">
                                        <select v-model="form.educational_attainment"
                                            id="floating_educational_attainment"
                                            class="bg-gray-50 border border-gray-300 pt-4 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.educational_attainment }">
                                            <option value="None">None</option>
                                            <option value="Elementary Level">Elementary Level</option>
                                            <option value="High School Level">High School Level</option>
                                            <option value="College Level">College Level</option>
                                            <option value="Masteral">Masteral</option>
                                            <option value="Doctorate">Doctorate</option>
                                        </select>
                                        <label for="floating_educational_attainment"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                            :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.educational_attainment }">Educational
                                            Attainment</label>
                                    </div>


                                    <div class="grid grid-cols-2 gap-2">
                                        <div class="relative">
                                            <input v-model="form.occupation" type="text" id="floating_occupation"
                                                class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.occupation }"
                                                placeholder="" />
                                            <label for="floating_occupation"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                                :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.occupation }">Occupation</label>
                                        </div>


                                        <div class="relative">
                                            <select v-model="form.monthly_income" id="floating_monthly_income"
                                                class="bg-gray-50 border border-gray-300 pt-4 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.monthly_income }">
                                                <option v-for="income in monthly_income" :value="income.id"
                                                    :key="income.id">{{ income.income_range }}</option>

                                            </select>
                                            <label for="floating_monthly_income"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                                :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.monthly_income }">Monthly
                                                Income</label>
                                        </div>

                                    </div>

                                    <div class="relative">
                                        <input v-model="form.contact_number" type="text" id="floating_contact_number"
                                            class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.contact_number }"
                                            placeholder="" />
                                        <label for="floating_contact_number"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                            :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.contact_number }">Contact
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
                                        <select v-model="form.barangay_id" id="floating_barangay"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 pt-4 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.barangay }">
                                            <option v-for="barangay in usePage().props.barangays" :key="barangay.id"
                                                :value="barangay.id" :disabled="form.barangay_id">{{
                                                    barangay.barangay_name }}</option>

                                        </select>
                                        <label for="floating_barangay"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                            :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.barangay }">Barangay</label>
                                    </div>


                                    <div class="relative">
                                        <input v-model="form.street" type="text" id="floating_street"
                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.street }"
                                            placeholder=" " />
                                        <label for="floating_street"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                            :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.street }">
                                            Street/Purok</label>
                                    </div>


                                    <div class="relative">
                                        <input v-model="form.city" type="text" id="floating_city"
                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " disabled />
                                        <label for="floating_city"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                                            City/Municipality</label>
                                    </div>
                                    <div class="relative">
                                        <input v-model="form.province" type="text" id="floating_province"
                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " disabled />
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
                                        <input v-model="form.senior_id_number" type="text"
                                            id="floating_senior_id_number"
                                            class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.senior_id_number }"
                                            placeholder="" />
                                        <label for="floating_senior_id_number"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                            :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.senior_id_number }">Senior
                                            ID Number</label>
                                    </div>

                                    <!--PWD-->
                                    <div v-else-if="form.benefeciary_type == 2" class="relative">
                                        <input v-model="form.pwd_id_number" type="text" id="floating_pwd_id_number"
                                            class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.pwd_id_number }"
                                            placeholder="" />
                                        <label for="floating_pwd_id_number"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                            :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.pwd_id_number }">PWD
                                            ID Number</label>
                                    </div>
                                    <!--Women-->
                                    <div v-else class="relative">
                                        <input v-model="form.kalipi_id_number" type="text"
                                            id="floating_kalipi_id_number"
                                            class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.kalipi_id_number }"
                                            placeholder="" />
                                        <label for="floating_kalipi_id_number"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                            :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.kalipi_id_number }">Kalipi
                                            ID Number</label>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <div v-if="form.benefeciary_type == 1" class="relative">
                                            <input v-model="form.date_sic_issued" type="date"
                                                id="floating_date_sic_issued"
                                                class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.date_sic_issued }"
                                                placeholder="" />
                                            <label for="floating_date_sic_issued"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                                :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.date_sic_issued }">Date
                                                Issued</label>
                                        </div>

                                        <div v-else-if="form.benefeciary_type == 2" class="relative">
                                            <input v-model="form.date_pic_issued" type="date"
                                                id="floating_date_pic_issued"
                                                class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.date_pic_issued }"
                                                placeholder="" />
                                            <label for="floating_date_pic_issued"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                                :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.date_pic_issued }">Date
                                                Issued</label>
                                        </div>

                                        <div v-else class="relative">
                                            <input v-model="form.date_kic_issued" type="date"
                                                id="floating_date_kic_issued"
                                                class=" block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.date_kic_issued }"
                                                placeholder="" />
                                            <label for="floating_date_kic_issued"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                                :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.date_kic_issued }">Date
                                                Issued</label>
                                        </div>

                                        <div v-if="form.benefeciary_type == 1" class="relative">
                                            <select v-model="form.sic_status" id="floating_sic_status"
                                                class="bg-gray-50 border border-gray-300 pt-4 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.sic_status }">
                                                <option value="0">New</option>
                                                <option value="1">Renewal</option>
                                                <option value="2">Terminated</option>
                                            </select>
                                            <label for="floating_sic_status"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                                :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.sic_status }">ID
                                                Status</label>
                                        </div>

                                        <div v-else-if="form.benefeciary_type == 2" class="relative">
                                            <select v-model="form.pic_status" id="floating_pic_status"
                                                class="bg-gray-50 border border-gray-300 pt-4 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.pic_status }">
                                                <option value="0">New</option>
                                                <option value="1">Renewal</option>
                                                <option value="2">Terminated</option>
                                            </select>
                                            <label for="floating_pic_status"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                                :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.pic_status }">ID
                                                Status</label>
                                        </div>

                                        <div v-else class="relative">
                                            <select v-model="form.kic_status" id="floating_kic_status"
                                                class="bg-gray-50 border border-gray-300 pt-4 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                :class="{ 'bg-red-50 border-red-500 text-red-900 placeholder-red-700 focus:ring-red-500 focus:border-red-500': errors.kic_status }">
                                                <option value="0">New</option>
                                                <option value="1">Renewal</option>
                                                <option value="2">Terminated</option>
                                            </select>
                                            <label for="floating_kic_status"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1"
                                                :class="{ 'text-red-500 peer-focus:text-red-600 peer-focus:dark:text-red-500 ': errors.kic_status }">ID
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
                                            <input v-model="form.socpen_benefeciary" id="socpen_benefeciary"
                                                type="radio" value="1" name="socpen_benefeciary"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="socpen_benefeciary"
                                                class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes
                                            </label>

                                        </div>
                                        <div class="flex items-center">
                                            <input v-model="form.socpen_benefeciary" id="not_socpen_benefeciary"
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
                                            <input v-model="form.pantawid_benefeciary" id="pantawid_benefeciary"
                                                type="radio" value="1" name="pantawid_benefeciary"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="pantawid_benefeciary"
                                                class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes
                                            </label>

                                        </div>
                                        <div class="flex items-center">
                                            <input v-model="form.pantawid_benefeciary" id="not_pantawid_benefeciary"
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
                                            <input v-model="form.indigenous_person" id="indigenous_person" type="radio"
                                                value="1" name="indigenous_person"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="indigenous_person"
                                                class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes
                                            </label>

                                        </div>
                                        <div class="flex items-center">
                                            <input v-model="form.indigenous_person" id="not_indigenous_person"
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
                                            <input v-model="form.lgbtq" id="lgbtq" type="radio" value="1" name="lgbtq"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="lgbtq"
                                                class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes
                                            </label>

                                        </div>
                                        <div class="flex items-center">
                                            <input v-model="form.lgbtq" id="not_lgbtq" type="radio" value="0"
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
                            <textarea v-model="form.remarks" id="remarks" rows="4"
                                class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Write your thoughts here..."></textarea>

                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button @click="cancelEdit" type="button"
                                class="py-2.5 px-5 ms-3 mx-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                Cancel
                            </button>

                            <ProcessingButton type="submit" :text="'Save'" :process="processing" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </Focalpage>
</template>
