<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import Publicpage from '../PublicLayout/Publicpage.vue';

// Receiving data from Inertia props
const { benefeciary, request } = usePage().props;

// Logic for messages and notes based on status
let message = '';
let note = '';

// Checking the beneficiary approval status
if (benefeciary.approved_status == 3) {
  message = 'Pending: Your application is pending. You can wait or inform your barangay focal for approval.';
  
} else if (benefeciary.approved_status == 0) {
  message = 'Good News! Your application was approved by your barangay focal officer. Please wait for the endorsement of your barangay focal officer';
  note = 'Note: Endorsement is necessary and will undergo approval from focal to CSWD to become an official beneficiary.';
}
else if (benefeciary.approved_status == 4) {
  message = 'Sorry! Your application was rejected by your barangay focal officer. You are now an official beneficiary of CSWD Tangub City.';
  note = 'Note: This account and its data will be deleted after 10 days!';
}

// Checking the request status
if (request) {
  switch (request.status) {
    case 0:
      message = 'Congratulations! You are being endorsed by your barangay focal officer.';
      break;
    case 1:
      message = 'Congratulations! The endorsement was successfully checked by your barangay officer and forwarded to CSWD Staff for verification.';
      break;
    case 2:
      message = 'Congratulations! The endorsement was successfully verified by the CSWD Staff and forwarded to CSWD Admin for Approval.';
      break;
    case 3:
      message = 'Congratulations! The endorsement was approved by the CSWD Admin. You are now an official beneficiary of CSWD Tangub City. Please standby for future assistance distribution.';
      break;
    case 4:
      message = `Sorry! The endorsement was rejected by ${request.rejected_by}. Reason: ${request.reason}.`;
      break;
  }
}
</script>

<style scoped>
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
    <div class="font-bold text-2xl mb-4">
      My Dashboard
    </div>

    <!-- Dashboard message container -->
    <div class="bg-white shadow-lg rounded-lg p-6">
      <h2 class="text-xl font-semibold text-gray-800">Welcome to CSWD Tangub City</h2>

      <!-- Display the message based on the beneficiary status or request status -->
      <div class="mt-6">
        <p class="text-lg text-gray-600">{{ message }}</p>

        <!-- Display additional note if applicable -->
        <div v-if="note" class="mt-4 text-sm text-gray-500 italic">
          <p>{{ note }}</p>
        </div>
      </div>

      <div class="mt-6">
        <!-- Use Tailwind's button styles to make the dashboard visually appealing -->
        <a :href="route('beneficiary.program')"
          v-if="benefeciary.approved_status == 1"
          class="px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600"
        >
          View Benefits
        </a>
        <span
          v-else
          class="px-6 py-2 bg-gray-400 text-white rounded-md"
        >
          Contact Barangay Focal
        </span>
      </div>
    </div>
  </Publicpage>
</template>
