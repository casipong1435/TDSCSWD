<script setup>
import "leaflet/dist/leaflet.css";
import { LMap, LTileLayer, LPolygon, LMarker, LPopup } from "@vue-leaflet/vue-leaflet";
import { reactive, onMounted, ref } from "vue";
import L from "leaflet";
import Adminpage from "../../AdminLayout/Adminpage.vue";
import { router, usePage } from "@inertiajs/vue3";

const {props} = usePage();

const barangayBeneficiaries = ref(props.barangays);

const map_attributes = reactive({
  'zoom': 14,
  'center': [8.06667, 123.75],
});

defineProps({
  barangays: Array
});
const cityBoundary = ref([]);

onMounted(() => {
  fetchCityBoundary();
});

function fetchCityBoundary() {
  const url =
    "https://overpass-api.de/api/interpreter?data=[out:json][timeout:25];nwr['name'='Tangub'];out geom;";

  fetch(url)
    .then((response) => response.json())
    .then((data) => {
      // Convert data to polygon structure suitable for LPolygon
      cityBoundary.value = convertToLatLngArray(data);
      //console.log(cityBoundary.value);
    })
    .catch((error) => {
      console.error("Error fetching city boundary:", error);
    });
}

// Convert Overpass API response into LatLng format for Leaflet polygons
function convertToLatLngArray(data) {
  const polygons = [];

  data.elements.forEach((element) => {
    if (element.type === "relation" && element.members) {
      const polygon = element.members
        .filter((member) => member.geometry)
        .map((member) =>
          member.geometry.map((coord) => [coord.lat, coord.lon])
        );

      polygons.push(polygon.flat()); // Flatten to get an array of coordinates
    }
  });

  return polygons;
}

const showPopup = (barangay) => {
  router.get(route('admin.beneficiary_barangay', barangay.id));
};

const customIcon = L.icon({
  iconUrl: '/assets/images/barangay_icon.png',  // Path to your local image
  iconSize: [32, 32],
  iconAnchor: [16, 32],
  popupAnchor: [0, -32]
});

let sum = 0;

const getTotalBenefeciary = (senior, pwd, women) => {
  return sum = senior + pwd + women;
};


const getTotalPercent = (count, senior, pwd, women) => {
  const totalCount = Number(count);
  const totalSum = Number(senior) + Number(pwd) + Number(women);

  // Check if sum is a valid number and greater than 0 to avoid division by zero
  if (isNaN(totalCount) || isNaN(totalSum) || totalSum === 0) {
    //console.log('Invalid count or sum');
    return 0;  // Return 0 or some default value when division is not possible
  }

  // Calculate percentage
  const percent = (Number(totalCount) / totalSum) * 100;
    
  console.log(percent);  // To see the calculated percentage
  return parseInt(percent, 10);  // Convert the result to an integer (optional)

};

// Utility function to calculate the percentage for progress bars
const calculatePercentage = (count, barangay) => {
  const total =
    barangay.senior_count + barangay.pwd_count + barangay.women_count;
    //console.log(total);
  return total == 0 ? 0 : ((count / total) * 100).toFixed(2);
};

</script>

<style>
/* Optional: Customize the scroll behavior */
div::-webkit-scrollbar {
  width: 8px;
}

div::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.2);
  border-radius: 4px;
}
</style>

<template>
  <Adminpage>
    <div class="text-2xl p-2 text-center">
      Tangub City Barangay Land Mapping
    </div>
    <div class="mb-2 p-2 flex items-center justify-center">
      <div class="h-[500px] w-full relative">
        <l-map ref="map" v-model:zoom="map_attributes.zoom" :center="map_attributes.center">
          <l-tile-layer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png" layer-type="base"
            name="OpenStreetMap"></l-tile-layer>
          <l-polygon v-for="(coords, index) in cityBoundary" :key="index" :lat-lngs="coords" color="blue" :weight="2" />


          <l-marker v-for="(barangay, index) in barangays" :key="index" :lat-lng="[barangay.lat, barangay.long]"
            :icon="customIcon">
            <l-popup class="bg-blue w-100 flex justify-center items-center flex-col">
              <div class="mb-2 font-bold border-b ">{{ "Barangay " + barangay.barangay_name }}</div>
              <div class="mb-2">Senior: {{ barangay.senior_count + ' (' + getTotalPercent(barangay.senior_count, barangay.senior_count, barangay.pwd_count, barangay.women_count) + ' %)'
                }}</div>
              <div class="mb-2">PWD: {{ barangay.pwd_count + ' (' + getTotalPercent(barangay.pwd_count, barangay.senior_count, barangay.pwd_count, barangay.women_count) + ' %)' }}</div>
              <div class="mb-2">Women: {{ barangay.women_count + ' (' + getTotalPercent(barangay.women_count, barangay.senior_count, barangay.pwd_count, barangay.women_count) + ' %)' }}
              </div>
              <div class="mb-2">Total: {{ getTotalBenefeciary(barangay.senior_count, barangay.pwd_count,
                barangay.women_count) }}</div>
              <div>
                <button type="button"
                  class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs p-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                  @click="showPopup(barangay)">More Details
                </button>
              </div>
            </l-popup>
          </l-marker>
        </l-map>
      </div>
    </div>

    <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
      <h3 class="font-bold text-xl text-gray-800">Beneficiaries by Barangay</h3>
      <div class="mt-4 max-h-[500px] overflow-y-auto space-y-4">
        <div v-for="barangay in barangayBeneficiaries" :key="barangay.id" class="bg-gray-100 rounded-md p-4">
          <h4 class="text-lg font-semibold mb-2">{{ barangay.barangay_name }}</h4>
          <div class="mb-2">
            <div class="flex justify-between text-sm font-medium">
              <span>Seniors</span>
              <span>{{ barangay.senior_count }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
              <div class="bg-blue-500 h-3 rounded-full"
                :style="{ width: calculatePercentage(barangay.senior_count, barangay) + '%' }"></div>
            </div>
          </div>
          <div class="mb-2">
            <div class="flex justify-between text-sm font-medium">
              <span>PWDs</span>
              <span>{{ barangay.pwd_count }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
              <div class="bg-green-500 h-3 rounded-full"
                :style="{ width: calculatePercentage(barangay.pwd_count, barangay) + '%' }"></div>
            </div>
          </div>
          <div class="mb-2">
            <div class="flex justify-between text-sm font-medium">
              <span>Women</span>
              <span>{{ barangay.women_count }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
              <div class="bg-pink-500 h-3 rounded-full"
                :style="{ width: calculatePercentage(barangay.women_count, barangay) + '%' }"></div>
            </div>
          </div>
          <div>
            <div class="flex justify-between text-sm font-medium">
              <span>Total</span>
              <span>{{ barangay.senior_count + barangay.pwd_count + barangay.women_count }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Adminpage>
</template>