<script setup>
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import ProcessingButton from '@/Components/ProcessingButton.vue';
import Adminpage from '../../AdminLayout/Adminpage.vue';
import { ref, watch } from 'vue';
import axios from 'axios';

defineProps({
    requested_beneficiaries: Array
});

const isAcceptModalOpen = ref(false);
const processing = ref(false);

const form = useForm({
    gis_ids: [],
});


function gotoAicsEditForm(id) {
    router.get(route('admin.aics_edit_form', id));
}

function acceptAicsBeneficiary() {
    processing.value = true;
    form.put(route("admin.accept-aics-beneficiary"), {
        onSuccess: (page) => {
            isAcceptModalOpen.value = false;
            Swal.fire({
                toast: true,
                icon: "success",
                position: "top-end",
                showConfirmation: true,
                title: page.props.flash.success,
            });
            processing.value = false;
        },
        onError: (page) => {
            isAcceptModalOpen.value = false;
            Swal.fire({
                toast: true,
                icon: "success",
                position: "top-end",
                showConfirmation: true,
                title: page.props.flash.error,
            });
            processing.value = false;
        }
    });
}

const isAllSelected = ref(false);

const handleSelectAll = () => {
    if (isAllSelected.value) {
        form.gis_ids = [];
    } else {
        form.gis_ids = usePage().props.requested_beneficiaries.map(
            (beneficiary) => beneficiary.id
        );
        console.log(form.gis_ids);

    }
    isAllSelected.value = !isAllSelected.value;
};

watch(() => form.gis_ids.length, (newLength) => {
    if (newLength != usePage().props.requested_beneficiaries.length) {
        isAllSelected.value = false;
    } else {
        isAllSelected.value = true;
    }
});



</script>

<template>

    <Head title="AICS" />
    <Adminpage>
        <section class="bg-gray-50 dark:bg-gray-900 p-4 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">

                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="flex justify-between items-center">
                        <div class="text-2xl p-2 mb-2">
                            AICS Requests
                        </div>
                        <ProcessingButton :process="processing" :disabled="form.gis_ids.length <= 0" type="button"
                            @click="acceptAicsBeneficiary" :text="'Approve'" />
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3">
                                        <input type="checkbox" id="select-all" @change="handleSelectAll" class="me-2"
                                            :checked="isAllSelected">
                                        <label for="select-all">All</label>
                                    </th>
                                    <th scope="col" class="px-4 py-3">GIS no.</th>
                                    <th scope="col" class="px-4 py-3">Name</th>
                                    <th scope="col" class="px-4 py-3">Sex</th>
                                    <th scope="col" class="px-4 py-3">Birthdate</th>
                                    <th scope="col" class="px-4 py-3">Type of Assistance</th>
                                    <th scope="col" class="px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="benefeciary in requested_beneficiaries" :key="benefeciary.id"
                                    class="border-b dark:border-gray-700">
                                    <td class="px-4 py-3">
                                        <input type="checkbox" :value="benefeciary.id" v-model="form.gis_ids">
                                    </td>
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ benefeciary.name }}
                                    </th>
                                    <td class="px-4 py-3">{{ benefeciary.age }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.sex }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.birthdate }}</td>
                                    <td class="px-4 py-3">{{ benefeciary.address }}</td>

                                    <td class="px-4 py-3 gap-2 flex flex-row">
                                        <button type="btn" class="text-blue-700"
                                            @click="gotoAicsEditForm(benefeciary.id)"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>

                                        </button>

                                    </td>

                                </tr>
                                <tr v-if="requested_beneficiaries.length <= 0">
                                    <td colspan="7" class="px-4 py-3 text-center">
                                        No Data Found!
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </section>

        <!--Delete Modal-->
        <input class="modal-state" id="deleteModal" type="checkbox" v-model="isAcceptModalOpen" />
        <div class="modal" style="z-index: 9999;">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <label for="deleteModal" style="cursor: pointer;"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </label>
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to
                            approve this benefeciary?</h3>

                        <ProcessingButton :process="processing" type="button" :text="'Confirm'"
                            @click="acceptAicsBeneficiary" class="bg-green-600 hover:bg-green-800" />
                        <label for="deleteModal" style="cursor: pointer;"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No,
                            cancel</label>

                    </div>
                </div>
            </div>
        </div>



    </Adminpage>
</template>
