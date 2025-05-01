<script setup>
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';
import ProcessingButton from '@/Components/ProcessingButton.vue';
import Staffpage from '../../StaffLayout/Staffpage.vue';
import { ref, reactive, watch } from 'vue';

defineProps({
    assistance_types: Array,
    aics_staff: Array,
    mayor:Object,
    errors: Object
});

const form = useForm({

    //AICS GIS
    date: null,
    name: null,
    birthdate: null,
    age: null,
    sex: null,
    address: null,
    civil_status: null,
    occupation: null,
    education: null,
    monthly_income: null,
    mobile_number: null,
    ik_name: null,
    ik_birthdate: null,
    ik_age: null,
    ik_sex: null,
    ik_address: null,
    ik_civil_status: null,
    ik_occupation: null,
    ik_education: null,
    relation_to_beneficiary: null,
    ik_monthly_income: null,
    ik_mobile_number: null,
    type_of_assistance: null,
    amount_provided: null,
    social_worker_assessment: null,
    interviewee: null,
    reviewee: null,

    //AICS COMPOSITIONS

    compositions: [],
});

function addRow() {
    form.compositions.push({
        comp_name: null,
        comp_age: null,
        comp_relation_to_client: null,
        comp_education: null,
        comp_occupation: null,
        comp_monthly_income: null,
    });
}

// Function to remove a row
function removeRow(index) {
    form.compositions.splice(index, 1);
}

function submitForm() {

    // Submit the combined data
    form.post(route('staff.create-aics-beneficiary'),{
        onSuccess: (page) => {
            Swal.fire({
                toast: true,
                icon: "success",
                position: "top-end",
                showConfirmation: true,
                title: page.props.flash.success,
            }),
            form.reset();
        }
    });
}

const interviewee_position = ref('');
const reviewee_position = ref('');

watch(() => form.interviewee, (id) => {
    if (id) {
        interviewee_position.value = usePage().props.aics_staff.find(item => item.id === id)?.position?? null;
    }
});

watch(() => form.reviewee, (id) => {
    if (id) {
        reviewee_position.value = usePage().props.aics_staff.find(item => item.id === id)?.position?? null;
    }
});
</script>

<template>

    <Head title="AICS" />
    <Staffpage>
        <section class="bg-gray-50 dark:bg-gray-900 p-4 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="p-12 border">
                    <div class="hidden lg:grid grid-cols-1 sm:grid-cols-3 items-center gap-6 ">
                        <!-- Left Logos -->
                        <div class="flex justify-center sm:justify-start items-center space-x-4">
                            <img src="/assets/images/tangubcity.png" alt="Tangub City Logo"
                                class="h-16 w-16 sm:h-24 sm:w-24 object-contain">
                            <img src="/assets/images/bagongpilinas.png" alt="Bagong Pilipinas Logo"
                                class="h-16 w-16 sm:h-24 sm:w-24 object-contain">
                        </div>

                        <!-- Center Text -->
                        <div class="text-center flex flex-col justify-center">
                            <span class="font-bold text-sm sm:text-lg">Republic of the Philippines</span>
                            <span class="text-xs sm:text-base">CITY OF TANGUB</span>
                            <span class="text-xs italic">God-Centered City</span>
                            <span class="font-bold text-xs sm:text-sm">City Social Welfare and Development Office</span>
                            <span class="text-xs">www.tangubcity.gov.ph</span>
                            <span class="text-xs">Tel # 322 0332</span>
                            <span class="text-xs text-blue-500 underline">cswd.tangubcity@yahoo.com</span>
                        </div>

                        <!-- Right Logo -->
                        <div class="flex justify-center sm:justify-end items-center">
                            <img src="/assets/images/cswd.png" alt="CSWD Logo"
                                class="h-16 w-16 sm:h-24 sm:w-24 object-contain">
                        </div>
                    </div>
                    <div class="text-2xl md:text-2lg sm:text-2xl text-center my-4 font-bold">GENERAL INTAKE SHEET</div>
                    <form @submit.prevent="submitForm()">
                        <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-2 lg:col-span-1 flex justify-start items-center">
                            <label for="gis_no" class="me-2 min-w-[80px]">GIS No.:</label>
                            <input type="text" id="gis_no"
                                class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:border-0 w-full lg:w-60" disabled>
                        </div>
                        <div class="col-span-2 lg:col-span-1 flex justify-start lg:justify-end items-center">
                            <label for="date" class="me-2">Date:</label>
                            <input type="date" id="date" v-model="form.date"
                                class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60">
                        </div>
                    </div>

                    <div class="my-8">
                        <span class="me-8">I.</span>
                        <span>
                            <span class="font-bold">
                                IMPORMASYON NG KINATAWAN
                            </span>
                            <span class="italic">
                                (Representative's Identifying Information)
                            </span>
                        </span>
                    </div>

                    <!--Impormasyon ng kinatawan-->
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-2 md:col-span-1">
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="ik_name">Name</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <input type="text" id="ik_name" v-model="form.ik_name"
                                        class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60 focus:ring-0">
                                </span>
                            </div>
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="ik_age">Age</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <input type="text" id="ik_age" v-model="form.ik_age"
                                        class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60 focus:ring-0">
                                </span>
                            </div>
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="ik_address">Address</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <input type="text" id="ik_address" v-model="form.ik_address"
                                        class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60 focus:ring-0">
                                </span>
                            </div>
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="ik_occupation">Occupation</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <input type="text" id="ik_occupation" v-model="form.ik_occupation"
                                        class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60 focus:ring-0">
                                </span>
                            </div>
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="ik_monthly_income">Monthly Income</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <input type="text" id="ik_monthly_income" v-model="form.ik_monthly_income"
                                        class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60 focus:ring-0">
                                </span>
                            </div>
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="ik_relation">Relation to Beneficiary</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <input type="text" id="ik_relation" v-model="form.relation_to_beneficiary"
                                        class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60">
                                </span>
                            </div>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="ik_birthdate">Birthdate</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <input type="date" id="ik_birthdate" v-model="form.ik_birthdate"
                                        class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60">
                                </span>
                            </div>
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="ik_sex">Sex</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <select id="ik_sex" class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60" v-model="form.ik_sex">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    
                                </span>
                            </div>
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="ik_civil_status">Civil Status</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <select id="ik_civil_status" class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60" v-model="form.ik_civil_status" >
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Widowed">Widowed</option>
                                    </select>
                                
                                </span>
                            </div>
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="ik_education">Education</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <input type="text" id="ik_education" v-model="form.ik_education"
                                        class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60">
                                </span>
                            </div>
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="ik_mobile_number">Mobile Number</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <input type="text" id="ik_mobile_number" v-model="form.ik_mobile_number"
                                        class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60">
                                </span>
                            </div>

                        </div>
                    </div>

                    <div class="my-8">
                        <span class="me-8">II.</span>
                        <span>
                            <span class="font-bold">
                                IMPORMASYON NG BENEPISARYU
                            </span>
                            <span class="italic">
                                (Beneficiary's Identifying Information)
                            </span>
                        </span>
                    </div>

                    <!--Impormasyon ng Benepisaryu-->
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-2 md:col-span-1">
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="name">Name *</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <input type="text" id="name" v-model="form.name"
                                        class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60" :class="{'border-red-700 outline-red-700': errors.name}">
                                </span>
                            </div>
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="age">Age *</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <input type="text" id="age" v-model="form.age"
                                        class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60 " :class="{'border-red-700 outline-red-700': errors.age}">
                                </span>
                            </div>
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="address">Address *</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <input type="text" id="address" v-model="form.address"
                                        class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60" :class="{'border-red-700 outline-red-700': errors.address}">
                                </span>
                            </div>
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="occupation">Occupation</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <input type="text" id="occupation" v-model="form.occupation"
                                        class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60">
                                </span>
                            </div>
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="monthly_income">Monthly Income</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <input type="text" id="monthly_income" v-model="form.monthly_income"
                                        class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60">
                                </span>
                            </div>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="birthdate">Birthdate *</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <input type="date" id="birthdate" v-model="form.birthdate"
                                        class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60" :class="{'border-red-700 outline-red-700': errors.birthdate}">
                                </span>
                            </div>
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="sex">Sex *</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <select id="sex" class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60" v-model="form.sex" :class="{'border-red-700': errors.sex}">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </span>
                            </div>
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="civil_status">Civil Status</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <select id="civil_status" class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60" v-model="form.civil_status" :class="{'border-red-700': errors.civil_status}">
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Divorced">Divorced</option>
                                        <option value="Widowed">Widowed</option>
                                    </select>
                                </span>
                            </div>
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="education">Education</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <input type="text" id="education" v-model="form.education"
                                        class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60">
                                </span>
                            </div>
                            <div class="flex flex-col lg:flex-row justify-between  ml-8">
                                <label for="mobile_number">Mobile Number</label>
                                <span class="flex items-center">
                                    <span>:</span>
                                    <input type="text" id="mobile_number" v-model="form.mobile_number"
                                        class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full lg:w-60">
                                </span>
                            </div>

                        </div>
                    </div>

                    <div class="my-8">
                        <span class="me-8">III.</span>
                        <span>
                            <span class="font-bold">
                                KOMPOSISYUN NG PAMILYA
                            </span>
                            <span class="italic">
                                (Family's Composition)
                            </span>
                        </span>
                    </div>

                    <button type="button" @click="addRow" class="btn rounded-none btn-primary mb-1">
                        <span><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </span>
                    </button>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-center border border-collapse">
                            <thead class="text-xs uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" colspan="2" class="px-4 py-3 border">Name</th>
                                    <th scope="col" class="px-4 py-3 border">Age</th>
                                    <th scope="col" class="px-4 py-3 border">Relation to Client</th>
                                    <th scope="col" class="px-4 py-3 border">Education</th>
                                    <th scope="col" class="px-4 py-3 border">Occupation</th>
                                    <th scope="col" class="px-4 py-3 border">Monthly Income</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(composition, index) in form.compositions" :key="index">
                                    <td colspan="2" class="px-4 py-3 border"><input type="text"
                                            v-model="composition.comp_name" class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0"></td>
                                    <td class="px-4 py-3 border"><input type="text" v-model="composition.comp_age" class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0">
                                    </td>
                                    <td class="px-4 py-3 border"><input type="text"
                                            v-model="composition.comp_relation_to_client" class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0">
                                    </td>
                                    <td class="px-4 py-3 border"><input type="text"
                                            v-model="composition.comp_education" class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0">
                                    </td>
                                    <td class="px-4 py-3 border"><input type="text"
                                            v-model="composition.comp_occupation" class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0">
                                    </td>
                                    <td class="px-4 py-3 border"><input type="text"
                                            v-model="composition.comp_monthly_income" class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0">
                                    </td>
                                    <td>
                                        <button type="button" @click="removeRow(index)" class="text-red-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="my-8">
                        <span class="me-8">IV.</span>
                        <label class="font-bold" for="swa">
                            SOCIAL WORKER'S ASSESSMENT:
                        </label>
                    </div>

                    <!--Social Worker's Assessment-->
                    <div class="w-full">
                        <textarea id="swa" class="w-full" rows="5" v-model="form.social_worker_assessment"></textarea>
                    </div>
                    <div class="my-8">
                        <div class="flex flex-col lg:flex-row lg:items-center">
                            <span class="me-8">V.</span>
                            <span class="grid grid-cols-2 items-center">
                                <label class="font-bold col-span-2 lg:col-span-1" for="toa">
                                    TYPE OF ASSISTANCE:
                                </label>
                                <select id="toa" class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full col-span-2 lg:col-span-1" v-model="form.type_of_assistance">
                                    <option v-for="assistance in assistance_types" :key="assistance.id" :value="assistance.id">
                                        {{ assistance.assistance_type }}
                                    </option>
                                </select>
                            </span>
                            <span class="grid grid-cols-2 items-center">
                                <label class="font-bold col-span-2 lg:col-span-1" for="amount_provided">
                                    AMOUNT PROVIDED:
                                </label>
                                <input type="text" id="amount_provided" v-model="form.amount_provided"
                                    class="border-t-0 border-r-0 border-l-0 focus:outline-none focus:ring-0 w-full col-span-2 lg:col-span-1">
                            </span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <div class="grid grid-cols-3 border border-gray-900 min-w-[900px]">
                            <!-- Left Section -->
                            <div class="col-span-1 border-r border-gray-900">
                                <div class="flex flex-col p-3 items-center justify-center">
                                    <span class="w-full border-b border-gray-900 mt-20"></span>
                                    <span class="my-2 text-sm italic">Signature Over Printed Name of Client</span>
                                    <input type="checkbox" class="p-10">
                                    <span class="text-sm italic">Thumb Mark (if unable to sign)</span>
                                </div>
                            </div>

                            <!-- Right Section -->
                            <div class="col-span-2">
                                <div class="grid grid-cols-2 gap-2">
                                    <!-- Column 1 -->
                                    <div class="col-span-1 p-1">
                                        <div class="flex flex-col py-5">
                                            <label for="interview">Interviewed/Prepared By:</label>
                                            <select id="interview" class="w-full mt-5" v-model="form.interviewee" :class="{'border-red-700': errors.interviewee}">
                                                <option :value="staff.id" v-for="staff in aics_staff" :key="staff.id">{{ staff.name }}</option>
                                            </select>
                                            <span class="flex flex-row justify-start items-center mt-5">
                                                <label for="position_int">Position:</label>
                                                <span class="flex-grow border-b border-gray-900 text-center">{{ interviewee_position }}</span>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Column 2 -->
                                    <div class="col-span-1 p-1">
                                        <div class="flex flex-col py-5">
                                            <label for="interview">Reviewed/Recommending Aproval:</label>
                                            <select id="interview" class="w-full mt-5" v-model="form.reviewee" :class="{'border-red-700': errors.reviewee}">
                                                <option :value="staff.id" v-for="staff in aics_staff" :key="staff.id">{{ staff.name }}</option>
                                            </select>
                                            <span class="flex flex-row justify-start items-center mt-5">
                                                <label for="position_int">Position:</label>
                                                <span class="flex-grow border-b border-gray-900 text-center">{{ reviewee_position }}</span>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Full Width -->
                                    <div class="col-span-2 p-1 mb-4">
                                        <span class="text-start mb-2">Approved by:</span>
                                        <div class="flex justify-center items-center flex-col">
                                            <span class="font-bold text-lg underline">{{ mayor.name }}</span>
                                            <span>{{ mayor.position }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full flex justify-end items-center gap-2 mt-5">
                        <ProcessingButton type="submit" :process="form.process" :text="'Add'" class="rounded-none"/>
                        <Link :href="route('staff.aics')" class="btn rounded-none">Cancel</Link>
                    </div>
                    </form>
                </div>
            </div>
        </section>


    </Staffpage>
</template>