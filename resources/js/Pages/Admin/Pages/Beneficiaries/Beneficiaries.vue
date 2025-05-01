<script setup>
import Adminpage from '../../AdminLayout/Adminpage.vue';
import ProcessingButton from '@/Components/ProcessingButton.vue';
import { Head, router, usePage, Link } from '@inertiajs/vue3';
import { ref, watch, reactive, computed } from 'vue';
import axios from 'axios';

defineProps({
    barangay_id: String,
    monthly_income: Array,
    barangays: Array,
    beneficiaries: Array,
    archived_beneficiaries: Array
});

const searchInput = ref('');
const EditModal = ref(false);
const imagePreviewUrl = ref(null);
const processing = ref(false);
const tab = ref(0);

const dropdownBarangayOpen = ref(false);
const dropdownCategoryOpen = ref(false);
const selectedBarangayItems = ref([]);
const selectedCategoryItems = ref([]);
const isArchiveModal = ref(false);
const beneficiaryStatus = ref(null);
const beneficiaryID = ref(null);
const searchedBarangay = ref('');



const filteredBarangays = computed(() => {
  if (searchedBarangay.value === '') return usePage().props.barangays;
  return usePage().props.barangays.filter(barangay =>
    barangay.barangay_name.toLowerCase().includes(searchedBarangay.value.toLowerCase())
  );
});

function openArchiveModal(id, status) {
    isArchiveModal.value = true;
    beneficiaryStatus.value = status;
    beneficiaryID.value = id;
}

const submitArchive = () => {
    processing.value = true;
    const status = beneficiaryStatus.value == 1 ? 2 : 1;
    router.put(route('admin.archive-beneficiary', { id: beneficiaryID.value, status: status }), {}, {
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

const getFullName = (first_name, middle_name, last_name) => {
    return first_name + " " + (middle_name != null ? middle_name + " " : "") + last_name;
}

// Toggle the dropdown visibility
const toggleBarangayDropdown = () => {
    dropdownBarangayOpen.value = !dropdownBarangayOpen.value;
};

const toggleCategoryDropdown = () => {
    dropdownCategoryOpen.value = !dropdownCategoryOpen.value;
};

const isAllBarangaySelected = ref(false);

const handleSelectAllBarangay = () => {
    if (isAllBarangaySelected.value) {
        selectedBarangayItems.value = [];
    } else {
        selectedBarangayItems.value = usePage().props.barangays.map((barangay) => barangay.id);
        console.log(selectedBarangayItems.value);
    }
    isAllBarangaySelected.value = !isAllBarangaySelected.value;

};

function filter() {
    router.get(route('admin.report.beneficiaries'),
        {
            barangay_filter: selectedBarangayItems.value,
            beneficiary_filter: selectedCategoryItems.value,
            ages: ages.value
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true
        })
}

function filtersearchInput() {
    router.get(route('admin.report.beneficiaries'),
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
        router.get(route('admin.report.beneficiaries'),
            { searchInput: value },
            {
                preserveState: true,
                replace: true
            })
    }
});


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

const openEditModal = (benefeciary) => {
    EditModal.value = true;
    getBenefeciaryData(benefeciary);

};
let imageExists;
const getBenefeciaryData = (benefeciary) => {
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

const barangay_ids = ref([]);
const category = ref(null);
const foundData = ref(0);
const result = ref(false);
const canFind = ref(false);


watch([barangay_ids, category], ([value1, value2]) => {
    canFind.value = value1 && value2;
});

const findBeneficiaries = async () => {
    processing.value = true;
    try {
        const response = await axios.get(route('admin.findBeneficiaries'), {
            params: {
                barangay_ids: JSON.stringify(barangay_ids.value),
                category: category.value,
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


function cancelReport() {
    barangay_ids.value = [];
    category.value = null;
    result.value = false;
    searchedBarangay.value = '';
}


const dropdownModalBarangayOpen = ref(false);

const toggleModalBarangayDropdown = () => {
    dropdownModalBarangayOpen.value = !dropdownModalBarangayOpen.value;
};

const isAllModalBarangaySelected = ref(false);

const handleModalSelectAllBarangay = () => {
    if (isAllModalBarangaySelected.value) {
        barangay_ids.value = [];
    } else {
        barangay_ids.value = usePage().props.barangays.map((barangay) => barangay.id);
        console.log(barangay_ids.value);
    }
    isAllModalBarangaySelected.value = !isAllModalBarangaySelected.value;

};

const ageInput = ref('');
const ages = ref([]);
const showInput = ref(false);

const addAge = () => {
    const trimmedAge = ageInput.value.trim();
    if (trimmedAge && !ages.value.includes(trimmedAge)) {
        ages.value.push(trimmedAge);
        ageInput.value = ''; // Clear input after adding
    }
};

const removeAge = (index) => {
    ages.value.splice(index, 1); // Remove the age at the specified index
};

const toggleInput = () => {
    showInput.value = !showInput.value;
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

    <Head title="Beneficiary List" />

    <Adminpage>
        <section class="bg-gray-50 dark:bg-gray-900 p-4 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="tabs mb-2">
                    <div class="tab tab-bordered px-6" :class="{ 'tab-active': tab == 0 }" @click="tab = 0">
                        Beneficiaries
                    </div>
                    <div class="tab tab-bordered px-6" :class="{ 'tab-active': tab == 1 }" @click="tab = 1">
                        Archived Beneficiaries
                    </div>
                </div>
                <div v-if="tab == 0"
                    class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-3">

                    <div class="flex md:justify-between md:items-center mb-4 flex-col lg:flex-row gap-5">
                        <div class="text-2xl">Beneficiaries List</div>
                        <label for="isReportModalOpen"
                            class="py-2.5 px-3 me-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 w-full md:w-36 flex justify-center items-center cursor-pointer">
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
                    <div class="flex md:justify-between md:items-center mb-4 flex-col lg:flex-row gap-5">

                        <div class="relative mb-4 lg:mb-0 w-full lg:w-2/5">
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
                        <label for="filterDrawer"
                            class="cursor-pointer text-white bg-blue-700 w-9 h-9 rounded flex items-center justify-center hover:bg-blue-800">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                            </svg>

                        </label>

                    </div>
                    <div class="grid grid-cols-1 gap-2 p-2 ms-auto flex justify-center items-center">
                        <div class="col-span-2 py-2">
                            <div class=" p-3 rounded gap-y-3">
                                <div class="overflow-x-auto">

                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="px-4 py-3">Name</th>
                                                <th scope="col" class="px-4 py-3">Sex</th>
                                                <th scope="col" class="px-4 py-3">Age</th>
                                                <th scope="col" class="px-4 py-3">Barangay</th>
                                                <th scope="col" class="px-4 py-3">Category</th>
                                                <th scope="col" class="px-4 py-3">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="border-b dark:border-gray-700"
                                                v-for="beneficiary in beneficiaries" :key="beneficiary.id">

                                                <th scope="row"
                                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {{ getFullName(beneficiary.first_name, beneficiary.middle_name,
                                                        beneficiary.last_name) }}
                                                </th>
                                                <td class="px-4 py-3">{{ beneficiary.sex }}</td>
                                                <td class="px-4 py-3">{{ beneficiary.age }}</td>
                                                <td class="px-4 py-3">{{ beneficiary.barangay.barangay_name }}</td>
                                                <td class="px-4 py-3">{{
                                                    benefeciaryCategoryName(beneficiary.benefeciary_type) }}</td>
                                                <td class="px-4 py-3 gap-2 flex flex-row">
                                                    <button type="btn" class="text-blue-700"
                                                        @click="openEditModal(beneficiary)"><svg
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                        </svg>

                                                    </button>
                                                    <button type="button" class="text-yellow-500"
                                                        @click="openArchiveModal(beneficiary.id, beneficiary.approved_status)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                                        </svg>

                                                    </button>
                                                </td>
                                            </tr>
                                            <tr v-if="beneficiaries.length <= 0">
                                                <td colspan="6" class="px-4 py-3 text-center">No data found</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div v-if="tab == 1"
                    class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden p-3">

                    <div class="mb-4 flex-col lg:flex-row gap-5">
                        <div class="text-2xl">Archived Beneficiaries</div>
                    </div>

                    <div class="grid grid-cols-1 gap-2 p-2 ms-auto flex justify-center items-center">
                        <div class="col-span-2 py-2">
                            <div class=" p-3 rounded gap-y-3">
                                <div class="overflow-x-auto">

                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="px-4 py-3">Name</th>
                                                <th scope="col" class="px-4 py-3">Sex</th>
                                                <th scope="col" class="px-4 py-3">Age</th>
                                                <th scope="col" class="px-4 py-3">Barangay</th>
                                                <th scope="col" class="px-4 py-3">Category</th>
                                                <th scope="col" class="px-4 py-3">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="border-b dark:border-gray-700"
                                                v-for="beneficiary in archived_beneficiaries" :key="beneficiary.id">

                                                <th scope="row"
                                                    class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {{ getFullName(beneficiary.first_name, beneficiary.middle_name,
                                                        beneficiary.last_name) }}
                                                </th>
                                                <td class="px-4 py-3">{{ beneficiary.sex }}</td>
                                                <td class="px-4 py-3">{{ beneficiary.age }}</td>
                                                <td class="px-4 py-3">{{ beneficiary.barangay.barangay_name }}</td>
                                                <td class="px-4 py-3">{{
                                                    benefeciaryCategoryName(beneficiary.benefeciary_type) }}</td>
                                                <td class="px-4 py-3 gap-2 flex flex-row">
                                                    <button type="btn" class="text-blue-700"
                                                        @click="openEditModal(beneficiary)"><svg
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                        </svg>

                                                    </button>
                                                    <button type="button" class="text-yellow-500"
                                                        @click="openArchiveModal(beneficiary.id, beneficiary.approved_status)">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                                        </svg>

                                                    </button>
                                                </td>
                                            </tr>
                                            <tr v-if="archived_beneficiaries.length <= 0">
                                                <td colspan="6" class="px-4 py-3 text-center">No data found</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
                        <div
                            class="flex items-center justify-between p-2 md:p-2 border-b rounded-t dark:border-gray-600">
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
                                                        <div
                                                            class="flex flex-col items-center justify-center pt-5 pb-6">

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
                                            <select disabled v-model="form.benefeciary_type"
                                                id="floating_benefeciary_type"
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
                                            <input disabled v-model="form.first_name" type="text"
                                                id="floating_first_name"
                                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                placeholder=" " />
                                            <label for="floating_first_name"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">First
                                                Name</label>
                                        </div>

                                        <div class="relative mb-2">
                                            <input disabled v-model="form.middle_name" type="text"
                                                id="floating_middle_name"
                                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                placeholder=" " />
                                            <label for="floating_middle_name"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Middle
                                                Name (Optional)</label>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2">
                                            <div class="relative">
                                                <input disabled v-model="form.last_name" type="text"
                                                    id="floating_last_name"
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
                                            <input disabled v-model="form.occupation" type="text"
                                                id="floating_occupation"
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
                                        <input disabled v-model="form.pwd_id_number" type="text"
                                            id="floating_pwd_id_number"
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
                                            <input disabled v-model="form.socpen_benefeciary"
                                                id="not_socpen_benefeciary" type="radio" value="0"
                                                name="socpen_benefeciary"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <label for="not_socpen_benefeciary"
                                                class="w-full text-sm ms-2 font-medium text-gray-900 dark:text-gray-300">No
                                            </label>
                                        </div>
                                    </div>

                                    <div class="relative mb-2">
                                        <div class="mb-2">Pantawid Benefeciary?</div>
                                        <div class="flex items-center mb-4">
                                            <input disabled v-model="form.pantawid_benefeciary"
                                                id="pantawid_benefeciary" type="radio" value="1"
                                                name="pantawid_benefeciary"
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

            <!--Report Modal-->

            <input class="modal-state" id="isReportModalOpen" type="checkbox" />
            <div class="modal" style="z-index: 9999">
                <label class="modal-overlay"></label>
                <div class="modal-content flex flex-col gap-5">
                    <label for="isReportModalOpen"
                        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</label>
                    <h2 class="text-xl">Beneficiary Report</h2>
                    <div>
                        <label for="barangay_ids">Select Barangay</label>
                        <div class="relative w-full md:max-w-sm col-span-2 md:col-span-1">
                            <!-- Trigger Button -->
                            <button @click="toggleModalBarangayDropdown"
                                class="w-full px-4 py-2 text-left border border-gray-300 rounded-md shadow-sm bg-white focus:outline-none">
                                <span class="flex items-center justify-center text-sm">
                                    <span>Select Barangay </span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                    </svg>
                                </span>
                            </button>

                            <!-- Dropdown -->
                            <div v-if="dropdownModalBarangayOpen"
                                class="absolute z-10 w-full mt-2 bg-white border border-gray-300 rounded-md shadow-lg">
                                <div class="p-2 gap-y-2 max-h-40 overflow-y-auto">
                                    <div class="mb-2">
                                        <input type="search" class="p-2 min-w-full" placeholder="Search.." v-model="searchedBarangay">
                                    </div>
                                    <label class="flex items-center space-x-2 cursor-pointer" for="all">
                                        <input type="checkbox" id="all" :checked="isAllModalBarangaySelected"
                                            @change="handleModalSelectAllBarangay()"
                                            class="text-blue-600 rounded focus:ring-2 focus:ring-blue-500" />
                                        <span class="text-gray-700">All</span>
                                    </label>
                                    <label v-for="(barangay, index) in filteredBarangays" :key="index"
                                        class="flex items-center space-x-2 cursor-pointer" :for="index">
                                        <input type="checkbox" :id="index" :value="barangay.id" v-model="barangay_ids"
                                            class="text-blue-600 rounded focus:ring-2 focus:ring-blue-500" />
                                        <span class="text-gray-700">{{ barangay.barangay_name }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="category">Select Category</label>
                        <select class="select" id="category" v-model="category">
                            <option value="1">Senior</option>
                            <option value="2">PWD</option>
                            <option value="3">Women</option>
                        </select>
                    </div>
                    <div v-if="result" class="flex justify-between gap-5 justify-center items-center">
                        <div class="inline">
                            <span class="me-2 font-bold">Found:</span>
                            <span>{{ foundData }} Beneficiaries</span>
                        </div>
                        <div class="">
                            <a target="_blank" type="button"
                                :href="category == 1 ? route('admin.exportToExcelSenior', JSON.stringify(barangay_ids)) : category == 2 ? route('admin.exportToExcelPwd', JSON.stringify(barangay_ids)) : route('admin.exportToExcelWomen', JSON.stringify(barangay_ids))"
                                class=" text-green-700 hover:text-green-900"
                                :class="{ 'disabled opcacity-80 pointer-events-none': foundData <= 0 }">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
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


            <!--Archive Beneficiary-->
            <input class="modal-state" id="archiveModal" type="checkbox" v-model="isArchiveModal" />
            <div class="modal" style="z-index: 9999;">
                <div class="relative p-4 w-full max-w-md max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <label for="archiveModal" style="cursor: pointer;"
                            class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </label>
                        <div class="p-4 md:p-5 text-center">
                            <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want
                                to
                                {{ beneficiaryStatus == 1 ? 'Archive' : 'Unarchive' }} this beneficiary?</h3>

                            <ProcessingButton :process="processing" type="button" :text="'Confirm'"
                                @click="submitArchive" class="bg-yellow-400 hover:bg-yellow-500" />
                            <label for="archiveModal" style="cursor: pointer;"
                                class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No,
                                cancel</label>

                        </div>
                    </div>
                </div>
            </div>


        </section>

        <input type="checkbox" id="filterDrawer" class="drawer-toggle" />
        <label class="overlay"></label>
        <div class="drawer drawer-right">
            <div class="drawer-content pt-10 flex flex-col h-full">
                <label for="filterDrawer" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" @click="searchedBarangay = ''">✕</label>
                <div>
                    <h2 class="text-xl font-medium">Filter Beneficiary</h2>
                    <div class="gap-2 grid grid-cols-1 mt-4">

                        <div class="relative w-full md:max-w-sm col-span-1">
                            <!-- Trigger Button -->
                            <button @click="toggleBarangayDropdown"
                                class="w-full px-4 py-2 text-left border border-gray-300 rounded-md shadow-sm bg-white focus:outline-none">
                                <span class="flex items-center justify-center text-sm">
                                    <span>Select Barangay </span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                    </svg>
                                </span>
                            </button>

                            <!-- Dropdown -->
                            <div v-if="dropdownBarangayOpen"
                                class="absolute z-10 w-full mt-2 bg-white border border-gray-300 rounded-md shadow-lg">
                                <div class="p-2 gap-y-2 max-h-40 overflow-y-auto">
                                    <div class="mb-2">
                                        <input type="search" class="p-2 min-w-full" placeholder="Search.." v-model="searchedBarangay">
                                    </div>
                                    <label class="flex items-center space-x-2 cursor-pointer" for="all">
                                        <input type="checkbox" id="all" :checked="isAllBarangaySelected"
                                            @change="handleSelectAllBarangay()"
                                            class="text-blue-600 rounded focus:ring-2 focus:ring-blue-500" />
                                        <span class="text-gray-700">All</span>
                                    </label>
                                    <label v-for="(barangay, index) in filteredBarangays" :key="index"
                                        class="flex items-center space-x-2 cursor-pointer" :for="index">
                                        <input type="checkbox" :id="index" :value="barangay.id"
                                            v-model="selectedBarangayItems"
                                            class="text-blue-600 rounded focus:ring-2 focus:ring-blue-500" />
                                        <span class="text-gray-700">{{ barangay.barangay_name }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="relative w-full md:max-w-sm col-span-1">
                            <!-- Trigger Button -->
                            <button @click="toggleCategoryDropdown"
                                class="w-full px-4 py-2 text-left border border-gray-300 rounded-md shadow-sm bg-white focus:outline-none">
                                <span class="flex items-center justify-center text-sm">
                                    <span>Select Category </span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                    </svg>
                                </span>
                            </button>

                            <!-- Dropdown -->
                            <div v-if="dropdownCategoryOpen"
                                class="absolute z-10 w-full mt-2 bg-white border border-gray-300 rounded-md shadow-lg">
                                <div class="p-2 max-h-40 overflow-y-auto">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" value="1" v-model="selectedCategoryItems"
                                            class="text-blue-600 rounded focus:ring-2 focus:ring-blue-500" />
                                        <span class="text-gray-700">Senior</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" value="2" v-model="selectedCategoryItems"
                                            class="text-blue-600 rounded focus:ring-2 focus:ring-blue-500" />
                                        <span class="text-gray-700">PWD</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="checkbox" value="3" v-model="selectedCategoryItems"
                                            class="text-blue-600 rounded focus:ring-2 focus:ring-blue-500" />
                                        <span class="text-gray-700">Women</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Dropdown button -->
                        <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600" @click="toggleInput">
                            {{ showInput ? 'Hide' : 'Show' }} Age Input
                        </button>

                        <!-- Input and chips container -->
                        <div v-if="showInput" class="mt-4 border p-4 rounded bg-gray-50">
                            <div>
                                <input v-model="ageInput" @keydown.space.prevent="addAge"
                                    :disabled="ages.includes(ageInput.trim())"
                                    class="px-3 py-2 border rounded w-full disabled:bg-gray-300"
                                    placeholder="Type age and press space..." />
                            </div>
                            <div class="mt-2 flex flex-wrap gap-2">
                                <span v-for="(age, index) in ages" :key="index"
                                    class="flex items-center px-3 py-1 bg-blue-200 text-blue-800 rounded-full">
                                    {{ age }}
                                    <button @click="removeAge(index)" class="ml-2 text-red-500 hover:text-red-700"
                                        title="Remove">
                                        ×
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="h-full flex flex-row justify-end items-end gap-2">
                    <label for="filterDrawer" class="btn btn-ghost" @click="searchedBarangay = ''">Cancel</label>
                    <button class="btn btn-primary" type="button" @click="filter()">Filter</button>
                </div>
            </div>
        </div>
    </Adminpage>
</template>