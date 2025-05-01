<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import Publicpage from '../PublicLayout/Publicpage.vue';
import { ref } from 'vue';

defineProps({
    programs: Object
})

const program_beneficiary = ref([]);
const program_title = ref('');
const program_purpose = ref('');
const program_venue = ref('');
const program_start_date = ref('');
const program_end_date = ref('');

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

const showDetail = ref(false);

const getFullName = (first_name, middle_name, last_name, extension_name) => {
    return first_name + " " + (middle_name != null ? middle_name + " " : "") + last_name + ' ' + (extension_name != null ? extension_name + " " : "");
};

function showDetails(program) {
    console.log(program);
    showDetail.value = true;
    getBeneficiaryListOfProgram(program);
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
        
         <!-- View Programs -->
         <div class="bg-white shadow-lg rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4">My Programs</h2>
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
                                <td class="px-4 py-2 border">{{ program.program.title }}</td>
                                <td class="px-4 py-2 border">{{ program.program.purpose }}</td>
                                <td class="px-4 py-2 border">{{ program.program.venue }}</td>
                                <td class="px-4 py-2 border">{{ program.program.start_date }}</td>
                                <td class="px-4 py-2 border">{{ program.program.end_date }}</td>
                                <td class="px-4 py-2 border text-center">
                                    <button
                                        class="py-1 px-3 text-sm bg-blue-500 text-white rounded-md hover:bg-blue-600 transition"
                                        @click="showDetails(program.program)">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="programs.data.length <= 0">
                                <td class="px-4 py-2 border text-center" colspan="6">
                                    No Programs Yet!
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Program Details Modal -->
            <div v-if="showDetail" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 overflow-auto" style="z-index: 9999">
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
                                    <th class="px-4 py-2 border">Age</th>
                                    <th class="px-4 py-2 border">Sex</th>
                                    <th class="px-4 py-2 border">Barangay</th>
                                    <th class="px-4 py-2 border">Category</th>
                                </tr>
                            </thead>
                            <tbody style="min-width: 100px; overflow-y: scroll;">
                                <tr v-for="beneficiary in program_beneficiary" :key="beneficiary.id"
                                    class="hover:bg-gray-100" :class="{'bg-green-100':beneficiary.beneficiary.user_id == $page.props.auth.user.id}">
                                    <td class="px-4 py-2 border">{{ getFullName(beneficiary.beneficiary.first_name,
                                        beneficiary.beneficiary.middle_name, beneficiary.beneficiary.last_name, beneficiary.beneficiary.extension_name) }}
                                    </td>
                                    <td class="px-4 py-2 border">{{ beneficiary.beneficiary.age }}</td>
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
    </Publicpage>
</template>
