<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import Staffpage from '../StaffLayout/Staffpage.vue';
import { ref, onMounted, watch } from "vue";
import Chart from "chart.js/auto";

const { props } = usePage();
const aicsData = ref(props.aicsData);
const totalSeniors = ref(props.totalSeniors);
const totalPWDs = ref(props.totalPWDs);
const totalWomen = ref(props.totalWomen);
const totalBeneficiaries = ref(props.totalBeneficiaries);
const ongoingPrograms = ref(props.ongoingPrograms);
const barangayBeneficiaries = ref(props.barangays);
const years = ref(Object.keys(props.aicsData)); // Dynamic years
const selectedYear = ref(new Date().getFullYear()); // Default to current year
let chartInstance = null;

const renderChart = (year) => {
    const ctx = document.getElementById("barChart").getContext("2d");
    const data = aicsData.value[year];

    const labels = Object.keys(data);
    const dataset = Object.entries(data).map(([label, value], index) => ({
        label,
        data: [value],
        backgroundColor: [
            "rgba(99, 102, 241, 0.5)",
            "rgba(34, 197, 94, 0.5)",
            "rgba(239, 68, 68, 0.5)",
            "rgba(16, 185, 129, 0.5)",
            "rgba(234, 179, 8, 0.5)",
        ][index],
    }));

    // Destroy previous instance
    if (chartInstance) chartInstance.destroy();

    // Create a new instance
    chartInstance = new Chart(ctx, {
        type: "bar",
        data: {
            labels: [year], // Display year as label
            datasets: dataset,
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: "top" },
            },
        },
    });
};

// Watch for year selection changes
watch(selectedYear, (newYear) => {
    renderChart(newYear);
});

onMounted(() => {
    renderChart(selectedYear.value);
});

// Utility function to calculate the percentage for progress bars
const calculatePercentage = (count, barangay) => {
    const total =
        barangay.seniors_count + barangay.pwds_count + barangay.women_count;
    return total === 0 ? 0 : ((count / total) * 100).toFixed(2);
};

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

    <Head title="Staff Dashboard" />
    <Staffpage>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-4">
            <!-- Overview Grid -->
            <div class="bg-white shadow-lg col-span-2 lg:col-span-1 rounded-lg p-6">
                <h3 class="font-bold text-xl text-gray-800">Summary</h3>
                <div class="grid grid-cols-2 gap-6 mt-6">
                    <div class="p-6 bg-blue-50 rounded-lg text-center shadow">
                        <p class="text-sm font-medium text-blue-600">Total Seniors</p>
                        <p class="text-2xl font-bold text-blue-800">{{ totalSeniors }}</p>
                    </div>
                    <div class="p-6 bg-green-50 rounded-lg text-center shadow">
                        <p class="text-sm font-medium text-green-600">Total PWDs</p>
                        <p class="text-2xl font-bold text-green-800">{{ totalPWDs }}</p>
                    </div>
                    <div class="p-6 bg-pink-50 rounded-lg text-center shadow">
                        <p class="text-sm font-medium text-pink-600">Total Women</p>
                        <p class="text-2xl font-bold text-pink-800">{{ totalWomen }}</p>
                    </div>
                    <div class="p-6 bg-yellow-50 rounded-lg text-center shadow">
                        <p class="text-sm font-medium text-yellow-600">Total Beneficiaries</p>
                        <p class="text-2xl font-bold text-yellow-800">{{ totalBeneficiaries }}</p>
                    </div>
                </div>
            </div>

            <!-- Bar Chart -->
            <div class="bg-white shadow-lg col-span-2 lg:col-span-1 rounded-lg p-6">
                <h3 class="font-bold text-xl text-gray-800">AICS Beneficiaries</h3>

                <!-- Year Selection -->
                <div class="mt-6 flex items-center gap-4">
                    <label for="year" class="text-sm font-medium text-gray-700">Select Year:</label>
                    <select id="year" v-model="selectedYear" class="p-2 border border-gray-300 rounded-md bg-gray-50">
                        <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                    </select>
                </div>

                <!-- Chart -->
                <div class="mt-6">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

            <div class="bg-white shadow-lg rounded-lg p-6 mt-6 col-span-2">
                <h3 class="font-bold text-xl text-gray-800">Beneficiaries by Barangay</h3>
                <div class="mt-4 overflow-x-auto">
                    <div class="flex gap-6 min-w-max">
                        <div v-for="barangay in barangayBeneficiaries" :key="barangay.id"
                            class="bg-gray-100 rounded-md p-4 w-64">
                            <h4 class="text-lg font-semibold mb-2">{{ barangay.barangay_name }}</h4>
                            <div class="mb-2">
                                <div class="flex justify-between text-sm font-medium">
                                    <span>Seniors</span>
                                    <span>{{ barangay.seniors_count }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-blue-500 h-3 rounded-full"
                                        :style="{ width: calculatePercentage(barangay.seniors_count, barangay) + '%' }">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="flex justify-between text-sm font-medium">
                                    <span>PWDs</span>
                                    <span>{{ barangay.pwds_count }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-green-500 h-3 rounded-full"
                                        :style="{ width: calculatePercentage(barangay.pwds_count, barangay) + '%' }">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2">
                                <div class="flex justify-between text-sm font-medium">
                                    <span>Women</span>
                                    <span>{{ barangay.women_count }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-pink-500 h-3 rounded-full"
                                        :style="{ width: calculatePercentage(barangay.women_count, barangay) + '%' }">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between text-sm font-medium">
                                    <span>Total</span>
                                    <span>{{ barangay.seniors_count + barangay.pwds_count + barangay.women_count
                                        }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ongoing Programs -->
            <div class="col-span-2 bg-white shadow-lg rounded-lg p-6">
                <h3 class="font-bold text-xl text-gray-800">Ongoing Programs</h3>
                <div class="overflow-x-auto mt-6">
                    <table class="w-full text-sm border border-gray-200 rounded-md">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="p-3 text-gray-700 border">Title</th>
                                <th class="p-3 text-gray-700 border">Purpose</th>
                                <th class="p-3 text-gray-700 border">Venue</th>
                                <th class="p-3 text-gray-700 border">Start Date</th>
                                <th class="p-3 text-gray-700 border">End Date</th>
                                <th class="p-3 text-gray-700 border">No. of Beneficiaries</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="program in ongoingPrograms" :key="program.id" class="even:bg-gray-50">
                                <td class="p-3 border text-gray-600">{{ program.title }}</td>
                                <td class="p-3 border text-gray-600">{{ program.purpose }}</td>
                                <td class="p-3 border text-gray-600">{{ program.venue }}</td>
                                <td class="p-3 border text-gray-600">{{ program.start_date }}</td>
                                <td class="p-3 border text-gray-600">{{ program.end_date }}</td>
                                <td class="p-3 border text-gray-600">{{ program.program_beneficiary_count }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </Staffpage>
</template>
