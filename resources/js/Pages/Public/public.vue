<script setup>
import { ref, computed, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';

const activeTab = ref(0);
const selectedProgram = ref(null);
const searchInput = ref('');
const program_beneficiary = ref([]);
const program_title = ref('');
const program_purpose = ref('');
const program_venue = ref('');
const program_start_date = ref('');
const program_end_date = ref('');
const searchedBeneficiary = ref([]);
const showDetail = ref(false);

defineProps({
    beneficiaries: Object,
    programs: Object
})

function filterSearchInput() {
    const { beneficiaries } = usePage().props;

    searchedBeneficiary.value = beneficiaries.filter(beneficiary => {
        const searchValue = searchInput.value.toLowerCase();

        // Combine first_name and last_name into a full name
        const fullName = `${beneficiary.first_name ?? ''} ${beneficiary.last_name ?? ''}`.toLowerCase();

        return (
            fullName.includes(searchValue) || // Check the combined full name
            (beneficiary.middle_name?.toLowerCase().includes(searchValue) || false) ||
            (beneficiary.extension_name?.toLowerCase().includes(searchValue) || false)
        );
    });
}

watch(searchInput, (value) => {
    if (value == "") {
        searchedBeneficiary.value = [];
    }
});

const getFullName = (first_name, middle_name, last_name, extension_name) => {
    return first_name + " " + (middle_name != null ? middle_name + " " : "") + last_name + ' ' + (extension_name != null ? extension_name + " " : "");
};

function showDetails(program) {

    showDetail.value = true;
    getBeneficiaryListOfProgram(program);
}

function getCategoryName(category) {
    switch (category) {
        case 1:
            return 'Senior';
        case 2:
            return 'PWD';
        case 3:
            return 'Women';
    }
}

function getBeneficiaryListOfProgram(program) {
    program_beneficiary.value = program.program_beneficiary;
    program_title.value = program.title;
    program_purpose.value = program.purpose;
    program_venue.value = program.venue;
    program_start_date.value = program.start_date;
    program_end_date.value = program.end_date;
}

</script>

<template>

    <div class="container mx-auto p-6">
        <!-- Title -->
        <div class="text-center mb-12">
            <div class="flex justify-center items-center gap-5">
                <img src="assets/images/cswd.png" alt="" class="h-20 w-20">
                <h1 class="text-3xl font-bold text-blue-800 mb-1">
                    CSWD Tangub City
                </h1>
            </div>

            <p class="text-gray-600">Empowering the community through services and programs.</p>
        </div>

        <!-- Tab Navigation -->
        <div class="flex flex-wrap justify-center space-x-0 md:space-x-4 mb-6">
            <button
                class="py-2 px-4 bg-blue-800 text-white rounded-md hover:bg-blue-600 transition mb-4 sm:mb-0 lg:w-50"
                @click="activeTab = 0">
                Search Beneficiary
            </button>
            <button class="py-2 px-4 bg-red-800 text-white rounded-md hover:bg-red-600 transition sm:w-50"
                @click="activeTab = 1">
                View Programs
            </button>
        </div>

        <!-- Tab Content -->
        <div v-if="activeTab == 0">
            <!-- Search Beneficiary -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">Search for Beneficiary</h2>
                <div class="relative">
                    <input v-model="searchInput" type="text" placeholder="Search Beneficiary"
                        class="w-full mb-4  border rounded-md focus:ring focus:ring-blue-300" />
                    <div class="btn btn-primary pe-4 py-1 absolute right-0 rounded-r rounded-none shadow border-2 border-blue-600"
                        style="top: 1px;" @click="filterSearchInput">Search</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full bg-white border rounded-md shadow-md">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 border">Name</th>
                                <th class="px-4 py-2 border">Sex</th>
                                <th class="px-4 py-2 border">Barangay</th>
                                <th class="px-4 py-2 border">Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="beneficiary in searchedBeneficiary" :key="beneficiary.id"
                                class="hover:bg-gray-100">
                                <td class="px-4 py-2 border">{{ getFullName(beneficiary.first_name,
                                    beneficiary.middle_name, beneficiary.last_name, beneficiary.extension_name) }}</td>
                                <td class="px-4 py-2 border">{{ beneficiary.sex }}</td>
                                <td class="px-4 py-2 border">{{ beneficiary.barangay.barangay_name }}</td>
                                <td class="px-4 py-2 border">{{ getCategoryName(beneficiary.benefeciary_type) }}</td>
                            </tr>
                            <tr v-if="searchedBeneficiary.length <= 0">
                                <td class="px-4 py-2 border text-center" colspan="6">No Result</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="activeTab == 1">
            <!-- View Programs -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">CSWD Programs</h2>
                <div class="overflow-x-auto">
                    <table class="w-full bg-white border rounded-md shadow-md">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 border">Title</th>
                                <th class="px-4 py-2 border">Purpose</th>
                                <th class="px-4 py-2 border">Venue</th>
                                <th class="px-4 py-2 border">Start Date</th>
                                <th class="px-4 py-2 border">End Date</th>
                                <th class="px-4 py-2 border">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="program in programs.data" :key="program.id" class="hover:bg-gray-100"
                                >
                                <td class="px-4 py-2 border">{{ program.title }}</td>
                                <td class="px-4 py-2 border">{{ program.purpose }}</td>
                                <td class="px-4 py-2 border">{{ program.venue }}</td>
                                <td class="px-4 py-2 border">{{ program.start_date }}</td>
                                <td class="px-4 py-2 border">{{ program.end_date }}</td>
                                <td class="px-4 py-2 border text-center">
                                    <button
                                        class="py-1 px-3 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition"
                                        @click="showDetails(program)">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="programs.length <= 0">
                                <td class="px-4 py-2 border text-center" colspan="6">
                                    No Programs Yet!
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <Pagination :data="programs" />
            </div>

            <!-- Program Details Modal -->
            <div v-if="showDetail" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 overflow-auto">
                <div class="bg-white rounded-lg !h-full !items-start overflow-y-auto shadow-lg p-6 w-11/12 sm:w-2/3 max-w-4xl relative">
                    <button type="button" @click="showDetail = false" class="absolute top-5 right-5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <h3 class="text-xl font-bold mb-4">Program Details</h3>
                    <p><strong>Title:</strong> {{ program_title }}</p>
                    <p><strong>Purpose:</strong> {{ program_purpose }}</p>
                    <p><strong>Venue:</strong> {{ program_venue }}</p>
                    <p><strong>Start Date:</strong> {{ program_start_date }}</p>
                    <p><strong>End Date:</strong> {{ program_end_date }}</p>
                    <h4 class="mt-6 mb-2 font-bold text-center">Beneficiaries:</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full bg-white border rounded-md shadow-md">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 border">Name</th>
                                    <th class="px-4 py-2 border">Sex</th>
                                    <th class="px-4 py-2 border">Barangay</th>
                                    <th class="px-4 py-2 border">Category</th>
                                </tr>
                            </thead>
                            <tbody style="min-width: 100px; overflow-y: scroll;">
                                <tr v-for="beneficiary in program_beneficiary" :key="beneficiary.id"
                                    class="hover:bg-gray-100">
                                    <td class="px-4 py-2 border">{{ getFullName(beneficiary.beneficiary.first_name,
                                        beneficiary.beneficiary.middle_name, beneficiary.beneficiary.last_name, beneficiary.beneficiary.extension_name) }}
                                    </td>
                                    <td class="px-4 py-2 border">{{ beneficiary.beneficiary.sex }}</td>
                                    <td class="px-4 py-2 border">{{ beneficiary.beneficiary.barangay.barangay_name }}</td>
                                    <td class="px-4 py-2 border">{{ getCategoryName(beneficiary.beneficiary.benefeciary_type) }}
                                    </td>
                                </tr>
                                <tr v-if="program_beneficiary.length <= 0">
                                    <td class="px-4 py-2 border text-center" colspan="6">No Result</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>