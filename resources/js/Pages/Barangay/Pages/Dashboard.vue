<script setup>
import Beneficiaries from '@/Pages/Focal/Pages/Beneficiaries.vue';
import Barangaypage from '../BarangayLayout/Barangaypage.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Chart } from 'chart.js/auto';
import { onMounted, ref, onUnmounted } from 'vue';

const chartCanvas = ref(null); // Reference to the canvas element
let myChart = null; // Variable to store the chart instance

defineProps({
    beneficiaries: Array,
    latest_beneficiaries: Array,
    programs: Array,
    
});

function getBenefeciaryType(type){
    switch (type){
        case 1:
            return 'Senior';
        case 2:
            return 'PWD';
        case 3:
            return 'Women';
    }
}

// Chart Initialization
onMounted(() => {
  const totalBeneficiaries = usePage().props.beneficiaries[2] + usePage().props.beneficiaries[0] + usePage().props.beneficiaries[1];

  const chartData = {
    labels: ["Women", "Seniors", "PWDs"],
    datasets: [
      {
        data: [
          ((usePage().props.beneficiaries[2] / totalBeneficiaries) * 100).toFixed(2),
          ((usePage().props.beneficiaries[0] / totalBeneficiaries) * 100).toFixed(2),
          ((usePage().props.beneficiaries[1] / totalBeneficiaries) * 100).toFixed(2),
        ],
        backgroundColor: ["#f472b6", "#60a5fa", "#34d399"],
        hoverOffset: 4,
      },
    ],
  };

  new Chart(document.getElementById("beneficiariesChart"), {
    type: "pie",
    data: chartData,
    options: {
      plugins: {
        tooltip: {
          callbacks: {
            label: function (context) {
              return `${context.label}: ${context.raw}%`;
            },
          },
        },
      },
    },
  });
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


</script>

<template>

    <Head title="Barangay Dashboard" />
    <Barangaypage>
        <div class="mb-4 text-2xl">Summary</div>
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 p-6 bg-gray-100 min-h-3/4">
            <!-- Grid 1: Totals -->
            <div class="lg:col-span-1 grid grid-cols-1 gap-4">
                <!-- Total Women -->
                <div class="p-4 bg-white shadow rounded-lg flex items-center">
                    <div class="w-12 h-12 bg-pink-500 text-white rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 21v-2a4 4 0 00-3-3.87M4 21v-2a4 4 0 013-3.87m9-4a4 4 0 10-8 0v5a4 4 0 108 0v-5z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-700 font-bold text-lg">Total Women</h2>
                        <p class="text-gray-500 text-sm">Count: {{ beneficiaries[2] }}</p>
                    </div>
                </div>

                <!-- Total Seniors -->
                <div class="p-4 bg-white shadow rounded-lg flex items-center">
                    <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 21v-2a4 4 0 00-3-3.87m-6 5v-2a4 4 0 013-3.87M12 5v4m0 4h4m-4-4H8" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-700 font-bold text-lg">Total Seniors</h2>
                        <p class="text-gray-500 text-sm">Count: {{ beneficiaries[0] }}</p>
                    </div>
                </div>

                <!-- Total PWDs -->
                <div class="p-4 bg-white shadow rounded-lg flex items-center">
                    <div class="w-12 h-12 bg-green-500 text-white rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 9.75v5.25a2.25 2.25 0 002.25 2.25H18M15 6h3m-3 3h3m-3 3h3m-6 3h.75a.75.75 0 00.75-.75V9.75m0 0a4.5 4.5 0 00-9 0m4.5 4.5H3m0 0v6h2.25m-2.25-6h3" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-gray-700 font-bold text-lg">Total PWDs</h2>
                        <p class="text-gray-500 text-sm">Count: {{ beneficiaries[1] }}</p>
                    </div>
                </div>
            </div>

            <!-- Grid 2: Additional Space -->
            <div class="lg:col-span-3 bg-white shadow rounded-lg p-6">
                <h1 class="text-xl font-bold text-gray-700 ">Main Dashboard</h1>
                <div class="w-full grid grid-cols-2 gap-2">
                    <div class="w-80 h-80 p-6 col-span-2 lg:col-span-1">
                        <canvas id="beneficiariesChart"></canvas>
                    </div>
                    <div class="p-6 col-span-2 lg:col-span-1 rounded bg-white shadow">
                        <div class="text-xl text-center">Latest Beneficiaries</div>
                        <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Name</th>
                                    <th scope="col" class="px-4 py-3">Category</th>
                                    <th scope="col" class="px-4 py-3">Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="beneficiary in latest_beneficiaries" :key="beneficiary.id" class="border-b dark:border-gray-700">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{
                                            beneficiary.first_name + ' ' + beneficiary.last_name
                                        }}</th>
                                    <td class="px-4 py-3">{{ getBenefeciaryType(beneficiary.benefeciary_type) }}</td>
                                    <td class="px-4 py-3">{{ formattedDate(beneficiary.created_at) }}</td>
                                </tr>
                                <tr v-if="latest_beneficiaries.length <= 0">
                                    <td colspan="3" class="px-4 py-3 text-center">No Data Found!</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Programs Table -->
    <div class="col-span-1 lg:col-span-3 bg-white shadow rounded-lg p-6 overflow-x-auto">
      <h2 class="text-lg font-semibold text-gray-700">Ongoing Programs</h2>
      <table class="min-w-full table-auto mt-4">
        <thead>
          <tr class="text-left text-sm font-semibold text-gray-500">
            <th class="py-2 px-4">Title</th>
            <th class="py-2 px-4">Purpose</th>
            <th class="py-2 px-4">Start Date</th>
            <th class="py-2 px-4">End Date</th>
            <th class="py-2 px-4">No. of Beneficiaries</th>
          </tr>
        </thead>
        <tbody class="text-sm text-gray-700">
          <tr v-for="program in programs" :key="program.id" class="border-t">
            <td class="py-2 px-4">{{ program.title }}</td>
            <td class="py-2 px-4">{{ program.purpose }}</td>
            <td class="py-2 px-4">{{ program.start_date }}</td>
            <td class="py-2 px-4">{{ program.end_date }}</td>
            <td class="py-2 px-4">{{ program.beneficiary_count }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    </Barangaypage>
</template>