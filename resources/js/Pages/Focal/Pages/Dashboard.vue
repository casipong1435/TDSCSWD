<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import Focalpage from '../FocalLayout/Focalpage.vue';
import { onMounted, ref } from 'vue';
import Chart from 'chart.js/auto';


defineProps({
    beneficiaries: Array,
    programs: Array,
    birthdays: Array
});

const auth_user = usePage().props.auth.user;
// Chart Initialization
// Get the current year
const currentYear = new Date().getFullYear();

// Define available years starting from the past
const availableYears = ref([currentYear, currentYear - 1, currentYear - 2]);

// Add the current year to the list if it's not already present
if (!availableYears.value.includes(currentYear)) {
  availableYears.value.push(currentYear); // Adds current year without removing past years
}

// Set the default selected year to the current year
const selectedYear = ref(currentYear);

const monthlyData = ref({});
const chartInstance = ref(null);

// Utility function to group beneficiaries by month and year
const groupByMonth = () => {
    const year = selectedYear.value;
    const groupedData = {};

    usePage().props.beneficiaries.forEach((beneficiary) => {
        const date = new Date(beneficiary.created_at);
        if (date.getFullYear() === year) {
            const month = date.getMonth(); // 0 for January, 1 for February, etc.
            if (!groupedData[month]) {
                groupedData[month] = 0;
            }
            groupedData[month]++;
        }
    });

    monthlyData.value = groupedData;
};

// Update the chart data when the year is changed
const updateChartData = () => {
    groupByMonth();
    renderChart();
};

// Initialize chart on mounted
const renderChart = () => {
    const ctx = document.getElementById("lineChart").getContext("2d");
    const labels = [
        "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];

    const chartData = labels.map((month, index) => monthlyData.value[index] || 0);

    if (chartInstance.value) {
        chartInstance.value.destroy(); // Destroy previous chart instance
    }

    chartInstance.value = new Chart(ctx, {
        type: "line",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Beneficiaries Growth",
                    data: chartData,
                    backgroundColor: "rgba(99, 102, 241, 0.2)", // Indigo-500
                    borderColor: "rgba(99, 102, 241, 1)",
                    borderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    });
};

onMounted(() => {
    updateChartData(); // Initialize chart when component is mounted
});

function getFocalName() {
    switch (auth_user.focal_role) {
        case 1:
            return 'Senior';
        case 2:
            return 'PWD';
        case 3:
            return 'Women';
    }
}

const monthNames = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];

const currentMonth = new Date().getMonth(); // Get the zero-based month index
const currentMonthName = monthNames[currentMonth];


</script>

<style>
/* Optional for responsive handling of canvas in smaller screens */
canvas {
    max-height: 300px;
}
</style>

<template>

    <Head title="Focal Dashboard" />
    <Focalpage>
        <div class="mb-4 text-2xl">Summary</div>
        <div class="p-6 bg-gray-100 min-h-screen grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Total Stats Card -->
            <div class="col-span-1 bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-700">Total {{ getFocalName() }} Beneficiaries</h2>
                <p class="text-4xl font-bold text-indigo-500 mt-4">{{ beneficiaries.length }}</p>
                <p class="text-sm text-gray-500 mt-1">Current Focal Type: {{ getFocalName() }}</p>
            </div>

            <!-- Line Chart -->
            <div class=" col-span-2 bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-700">Growth of Beneficiaries</h2>
                <div class="flex justify-end items-center gap-x-2">
                    <label for="year" class="text-sm">Select Year: </label>
                    <select v-model="selectedYear" @change="updateChartData"
                        class="block mt-2 p-2 border border-gray-300 rounded-lg" id="year">
                        <option v-for="year in availableYears" :key="year" :value="year">{{ year }}</option>
                    </select>
                </div>
                <canvas id="lineChart" class="mt-4"></canvas>
            </div>

            <!-- Birthdays List -->
            <div class="col-span-1 bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-700">{{ currentMonthName }} Birthdays</h2>
                <ul class="mt-4 space-y-3">
                    <li v-for="birthday in birthdays" :key="birthday.id" class="flex justify-between items-center">
                        <span class="text-gray-700">{{ birthday.first_name + ' ' + birthday.last_name }}</span>
                        <span class="text-sm text-gray-500">{{ birthday.age }} years</span>
                    </li>
                    <span v-if="birthdays.length <= 0" class="text-center text-sm">No birthday celebrants this month</span>
                </ul>
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
        </div>
    </Focalpage>
</template>
