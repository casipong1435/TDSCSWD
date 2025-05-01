<script setup>
import { Head, usePage, router} from '@inertiajs/vue3';
import Focalpage from '../../FocalLayout/Focalpage.vue';
import { ref, onMounted, watch, reactive } from 'vue';

defineProps({
    benefeciaries: Object,
    endorsed_benefeciaries: Object,
    errors: Object,
    focal_barangay: String,
    received_gifts_benefeciaries: Object
});

const searchInput = ref('');
const searchReceivedInput = ref('');
const focal_role = ref(null);
const tab = ref(0);
const isAllSelected = ref(false);

onMounted(() => {
    focal_role.value = usePage().props.auth.user.focal_role;
})

// Format the date into "Month Day, Year"
function formattedDate(dateCreated) {
    const date = new Date(dateCreated);
    const stringifyDate = date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    return stringifyDate;
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
};

const getFullName = (first_name, middle_name, last_name) => {
    return first_name + " " + (middle_name != null ? middle_name + " " : "") + last_name;
};


function filtersearchInput(){
    router.get(route('focal.birthday'),
        { searchInput: searchInput.value },
        {
            preserveState: true,
            replace: true
        })
}

watch(searchInput, (value) => {
    if (value == ""){
        router.get(route('focal.birthday'),
        { searchInput: value },
        {
            preserveState: true,
            replace: true
        })
    }
});


function filtersearchReceivedInput(){
    router.get(route('focal.birthday'),
        { searchReceivedInput: searchInput.value },
        {
            preserveState: true,
            replace: true
        })
}

watch(searchReceivedInput, (value) => {
    if (value == ""){
        router.get(route('focal.birthday'),
        { searchReceivedInput: value },
        {
            preserveState: true,
            replace: true
        })
    }
});



const endorseForm = reactive({
    beneficiaries: [],
    month: null,
    year: null,
    barangay_id: usePage().props.auth.user.barangay_id
});

const resetEndorseForm = () => {
    endorseForm.beneficiaries = [];
};

const handleSelectAll = () => {
    if (isAllSelected.value) {
        endorseForm.beneficiaries = [];
    } else {
        endorseForm.beneficiaries = usePage().props.benefeciaries.map(
                (beneficiary) => beneficiary.id
            );
        console.log(endorseForm.beneficiaries);

    }
    isAllSelected.value = !isAllSelected.value;
};

const endorseBeneficiary = () => {
    router.post(route("focal.endorse_birthday_transitioning"), endorseForm, {
        onSuccess: (page) => {
            resetEndorseForm(),
                Swal.fire({
                    toast: true,
                    icon: "success",
                    position: "top-end",
                    showConfirmation: true,
                    title: page.props.flash.success,
                })
        },
    });
};

const retryEndorseBeneficiary = (id) => {
    router.put(route("focal.retry_birthday_endorse_transitioning", id));
};

const cancelEndorseBeneficiary = (id) => {
    router.delete(route("focal.cancel_birthday_endorse_transitioning", id));
};

</script>

<template>

    <Head title="Focal Dashboard" />
    <Focalpage>
        <section class="bg-gray-50 dark:bg-gray-900 p-4 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="tabs mb-2">
                    <div class="tab tab-bordered px-6" :class="{ 'tab-active': tab == 0 }" @click="tab = 0">
                        Celebrants
                    </div>
                    <div class="tab tab-bordered px-6" :class="{ 'tab-active': tab == 1 }" @click="tab = 1">
                        Endorsed
                    </div>
                    <div class="tab tab-bordered px-6" :class="{ 'tab-active': tab == 2 }" @click="tab = 2">
                        Receiving
                    </div>
                </div>
                <div v-if="tab == 0" class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="text-2xl p-2 mb-2">{{ benefeciaryCategoryName(focal_role) + " Beneficiaries (Brgy. " +
                        usePage().props.focal_barangay + " )" }}</div>
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        
                        <div class="relative w-full md:w-2/4">
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

                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                            <button type="button" :disabled="endorseForm.beneficiaries.length <= 0" :class="{'disabled opacity-50':endorseForm.beneficiaries.length <= 0}"
                                class="flex items-center justify-center text-white bg-blue-800 hover:bg-blue-900 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800"
                                @click="endorseBeneficiary">
                                Endorse
                            </button>
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
                                    <th scope="col" class="px-4 py-3">
                                        <span class="flex flex-row gap-2">
                                            <input type="checkbox" id="select-all" @change="handleSelectAll"
                                            :checked="isAllSelected">
                                            <label for="select-all">All</label>
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="benefeciary in benefeciaries" :key="benefeciary.id"
                                    class="border-b dark:border-gray-700">
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
                                    <td class="px-4 py-3"><input type="checkbox" :value="benefeciary.id" v-model="endorseForm.beneficiaries"></td>
                                </tr>
                                <tr v-if="benefeciaries.length <= 0">
                                    <td colspan="6" class="px-4 py-3 text-center">No Data Found!</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div v-if="tab == 1" class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
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
                                    <th scope="col" class="px-4 py-3">All</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="benefeciary in endorsed_benefeciaries" :key="benefeciary.id"
                                    class="border-b dark:border-gray-700">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{
                                            getFullName(benefeciary.benefeciary.first_name, benefeciary.benefeciary.middle_name,
                                                benefeciary.benefeciary.last_name)
                                        }}</th>
                                    <td class="px-4 py-3">{{ benefeciary.benefeciary.age }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.benefeciary.sex }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.benefeciary.date_of_birth }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.benefeciary.street }}</td>
                                    <td class="px-4 py-3 flex gap-2 items-center justify-start">
                                        <button v-if="benefeciary.status == 2" type="button" class="text-blue-600" @click="retryEndorseBeneficiary(benefeciary.id)"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                            </svg>
                                        </button>
                                        <button type="button" class="text-red-600" @click="cancelEndorseBeneficiary(benefeciary.id)"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18 18 6M6 6l12 12" />
                                            </svg>

                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="endorsed_benefeciaries.length <= 0">
                                    <td colspan="6" class="px-4 py-3 text-center">No Data Found!</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div v-if="tab == 2" class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="text-2xl p-2 mb-2">Receiving Beneficiaries</div>

                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                        
                        <div class="relative w-full md:w-2/4">
                                    <button type="button" class="absolute inset-y-0 right-0 flex items-center btn bg-blue-800 rounded-none h-full rounded-r" @click="filtersearchReceivedInput()">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-100 dark:text-gray-400"
                                            fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <input type="text" id="searchReceivedInput" v-model="searchReceivedInput"
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
                                    <th scope="col" class="px-4 py-3">Date Approved</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="benefeciary in received_gifts_benefeciaries" :key="benefeciary.id"
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
                                    <td class="px-4 py-3">
                                        <span class="badge badge-success">{{ formattedDate(benefeciary.date_approved) }}</span>
                                    </td>
                                </tr>
                                <tr v-if="received_gifts_benefeciaries.length <= 0">
                                    <td colspan="6" class="px-4 py-3 text-center">No Data Found!</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </Focalpage>
</template>
