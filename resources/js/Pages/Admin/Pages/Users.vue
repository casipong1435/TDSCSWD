<script setup>
import InputError from '@/Components/InputError.vue';
import ProcessingButton from '@/Components/ProcessingButton.vue';
import Pagination from '@/Components/Pagination.vue';
import Adminpage from '../AdminLayout/Adminpage.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { onMounted, ref, watch, reactive } from 'vue';

let focal_role_name;
let imageExists = ref(false);
const imagePreviewUrl = ref(null);
const searchInput = ref('');
const isNotFocal = ref(true);

const isAddModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const isUpdatePasswordModalOpen = ref(false);
const processing = ref(false);

defineProps({
    users: Object,
    errors: Object,
    barangays: Array,
});

const user_id = ref(null);
const isAdd = ref(true);

const form = reactive({

    //NAME
    'name': null,
    'barangay_id': null,
    'email': null,
    'password': null,

    //BIO DETAILS
    'role': null,
    'focal_role': null,
    'user_image': null,
})


const openAddModal = () => {
    isAddModalOpen.value = true;
    isAdd.value = true;
}

const openEditModal = (user) => {
    isAdd.value = false;
    isAddModalOpen.value = true;
    getUserData(user);

}

const openEditPasswordModal = (user) => {
    isUpdatePasswordModalOpen.value = true;
    getUserData(user);

}

const openDeleteModal = (id) => {
    isDeleteModalOpen.value = true;
    setUserID(id);
}

const setUserID = (id) => {
    user_id.value = id;
}

function filter(){
    router.get(route('admin.users'),
        { searchInput: searchInput.value },
        {
            preserveState: true,
            replace: true
        })
}

watch(searchInput, (value) => {
    if (value == ""){
        router.get(route('admin.users'),
        { searchInput: value },
        {
            preserveState: true,
            replace: true
        })
    }
});



watch(
    () => form.role,
    (newRole) => {

        if (newRole) {
            isNotFocal.value = newRole != 0 ? true : false;
            form.focal_role = 0;
        } else {
            isNotFocal.value = false;
        }
    }
);

const resetFields = () => {

    //NAME
    form.name = null;
    form.barangay_id = null;
    form.email = null;
    form.password = null;

    //BIO DETAILS
    form.role = null;
    form.focal_role = null;
    form.username = null;

    //IMAGE
    form.user_image = null;
    imagePreviewUrl.value = null;
    resetErrors();
}

const resetErrors = () => {
    for (const key in usePage().props.errors) {
        delete usePage().props.errors[key];
    }
};

const getUserData = (user) => {
    setUserID(user.id);

    form.name = user.name;
    form.barangay_id = user.barangay_id;
    form.email = user.email;
    form.username = user.username;
    //BIO DETAILS
    form.role = getRole(user.role);
    form.focal_role = user.focal_role;
    form.user_image = user.user_image;
    imageExists = checkImageIfExist(form.user_image);
    imagePreviewUrl.value = imageExists ? '/profile_images/' + user.user_image : null;
    

}

const checkImageIfExist = (image) => {
    return image ? true : false;
}


const getFocalRoleName = (focal_role) => {
    switch (focal_role) {
        case 0:
            focal_role_name = "--"
            break;

        case 1:
            focal_role_name = "Senior";
            break;

        case 2:
            focal_role_name = "PWD";
            break;

        case 3:
            focal_role_name = "Women";
            break;
    }

    return focal_role_name;
}


const getRole = (role) => {
    switch (role) {

        case 'focal':
            role = 0;
            break;

        case 'barangay':
            role = 1;
            break;

        case 'staff':
            role = 2;
            break;

        case 'admin':
            role = 3;
            break;
    }

    return role;
}

const AddUser = () => {
    processing.value = true;
    try {
        router.post(route('admin.create-user'), form, {
            onSuccess: page => {
                resetFields(),
                    isAddModalOpen.value = false,
              
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        position: 'top-end',
                        showConfirmation: true,
                        title: page.props.flash.success
                    }),
                    processing.value = false
            },
            onError: page => {
                    processing.value = false

            },
        })
    } catch (err) {
        console.log(err);
    }
}


const saveEditUserData = () => {
    processing.value = true;
    try {
        router.post(route('admin.update-user', user_id.value),
        {
            _method: 'put',
            ...form,
        },
        {
            onSuccess: page => {
                isAddModalOpen.value = false,
        
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        position: 'top-end',
                        showConfirmation: true,
                        title: page.props.flash.success
                    }),
                    processing.value = false
            },
            onError: page => {
                    processing.value = false

            },
        })
    } catch (err) {
        console.log(err);

    }
}

const cancelEdit = () => {
    resetFields();
    isAddModalOpen.value = false;
    processing.value = false;
}

const savePasswordEditUserData = () => {
    processing.value = true;
    try {
        router.put(route('admin.update-password', user_id.value), form, {
            onSuccess: page => {
                isUpdatePasswordModalOpen.value = false,
          
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        position: 'top-end',
                        showConfirmation: true,
                        title: page.props.flash.success
                    }),
                    processing.value = false
            },
            onError: page => {
                    processing.value = false

            },
        })
    } catch (err) {
        console.log(err);

    }
}


const deleteUserData = () => {
    processing.value = true;
    try {
        router.delete(route('admin.delete-user', user_id.value), {
            onSuccess: page => {
                isDeleteModalOpen.value = false,
      
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        position: 'top-end',
                        showConfirmation: true,
                        title: page.props.flash.success
                    }),
                    processing.value = false

            },
            onError: page => {
                    processing.value = false

            },
        })
    } catch (err) {
        console.log(err);
    }
};

const handleImageUpload = (event) => {
    form.user_image = event.target.files[0];
    //console.log(form.image);
    imagePreviewUrl.value = URL.createObjectURL(form.user_image);
};

</script>

<template>

    <Head title="Users" />

    <Adminpage>
        <section class="bg-gray-50 dark:bg-gray-900 p-4 sm:p-5">
            <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                    <div class="text-2xl p-2 mb-2">Users List</div>
                    <div
                        class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">

                        <div class="w-full md:w-1/2">
                            <div class="flex items-center">
                                <label for="simple-search" class="sr-only">Search</label>
                                <div class="relative w-full">
                                    <button type="button" class="absolute inset-y-0 right-0 flex items-center btn bg-blue-800 rounded-none h-full rounded-r" @click="filter()">
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
                            </div>
                        </div>
                        <div
                            class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">


                            <button @click="openAddModal" type="button"
                                class="flex items-center justify-center text-white bg-blue-800 hover:bg-blue-900 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                + Add User
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Name</th>
                                    <th scope="col" class="px-4 py-3">Barangay</th>
                                    <th scope="col" class="px-4 py-3">Role</th>
                                    <th scope="col" class="px-4 py-3">Focal</th>
                                    <th scope="col" class="px-4 py-3">
                                        <span class="sr-only">Actions</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in users.data" :key="user.id" class="border-b dark:border-gray-700">
                                    <th scope="row"
                                        class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{
                                            user.name
                                        }}</th>
                                    <td class="px-4 py-3">{{ user.barangay.barangay_name }}</td>
                                    <td class="px-4 py-3">{{ user.role }}</td>
                                    <td class="px-4 py-3">{{ getFocalRoleName(user.focal_role) }}</td>
                                    <td class="px-4 py-3 flex flex-row gap-3">
                                        <button type="button" @click="openEditModal(user)"
                                            class="text-blue-600 hover:text-blue-800"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </button>
                                        <button type="button" @click="openEditPasswordModal(user)"
                                            class="text-orange-400 hover:text-orange-700"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                            </svg>
                                        </button>
                                        <button type="button" @click="openDeleteModal(user.id)"
                                            class="text-red-600 hover:text-red-800"><svg
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM4 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 10.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                                            </svg>

                                        </button>

                                    </td>
                                </tr>
                                <tr v-if="users.length <= 0">
                                    <td colspan="7" class="px-4 py-3 text-center">No Data Found!</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <Pagination :data="users" />
                </div>
            </div>
        </section>

        <!--Delete Modal-->
        <input class="modal-state" id="deleteModal" type="checkbox" v-model="isDeleteModalOpen" />
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
                            remove this user?</h3>
                        
                        <ProcessingButton :process="processing" type="button"
                                :text="'Confirm'" @click="deleteUserData" class="bg-red-600 hover:bg-red-800" />
                        <label for="deleteModal" style="cursor: pointer;"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No,
                            cancel</label>

                    </div>
                </div>
            </div>
        </div>

        <!--Update Password Modal-->
        <input class="modal-state" id="updateModal" type="checkbox" v-model="isUpdatePasswordModalOpen" />
        <div class="modal" style="z-index: 9999;">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <label for="updateModal" style="cursor: pointer"
                        class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </label>

                    <form @submit.prevent="savePasswordEditUserData" @method="PUT">
                        <div class="p-2">
                            <div class="p-2 border-bottom">
                                <div class="text-1xl text-black">
                                    Update Password
                                </div>
                            </div>
                            <div class="p-2 w-full">
                                <div class="relative mb-2">
                                    <input v-model="form.name" type="text" id="floating_name"
                                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " disabled />
                                    <label for="floating_name"
                                        class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Name
                                    </label>
                                </div>
                                <div class="relative mb-2">
                                    <input v-model="form.password" type="password" id="floating_newpassword"
                                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                        placeholder=" " />
                                    <label for="floating_newpassword"
                                        class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">New
                                        Password
                                    </label>
                                </div>
                                <div v-if="errors != null">
                                    <InputError :message="errors.password"/>
                                </div>
                            </div>
                            <div class="flex w-full justify-end border-t py-1">
                                <label for="updateModal"
                                    class="py-2.5 px-5 mx-2 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                                    style="cursor: pointer">Cancel</label>
                                <ProcessingButton :process="processing" type="submit" :text="'Save'" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--Add User Modal-->
        <input class="modal-state" id="addUserModal" type="checkbox" v-model="isAddModalOpen" />
        <div class="modal !h-full !items-start overflow-y-auto " style="z-index: 9999">
            <label class="modal-overlay"></label>
            <form @submit.prevent="isAdd ? AddUser() : saveEditUserData()" @method="isAdd ? 'POST' : 'PUT'">
                <div class="relative p-4 w-full max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-2 md:p-2 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ isAdd ? "Add User" : "Edit User" }}
                            </h3>
                            <button @click="cancelEdit" type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-5 space-y-4">
                            <div v-if="Object.keys(errors).length > 0" class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                                role="alert">
                                <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                </svg>
                                
                                <div>
                                    <InputError :message="errors.name" />
                                    <InputError :message="errors.username" />
                                    <InputError :message="errors.password" />
                                    <InputError :message="errors.focal_role" />
                                    <InputError :message="errors.role" />
                                    <InputError :message="errors.barangay_id" />
                                </div>
                            </div>
                            <div class="grid grid-cols-10 gap-5 w-full">
                                <div class="col-span-10 grid md:grid-cols-2 ">
                                    <div class="relative">
                                        <div class=" relative mb-2 lg:px-5">
                                            <div class="relative flex items-center justify-center w-full">
                                                <label for="image-file"
                                                    class="flex flex-col items-center justify-center w-full h-auto md:w-50 p-4 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                                    <div v-if="!imagePreviewUrl"
                                                        class=" flex flex-col items-center justify-center pt-5 pb-6">
                                                        <div
                                                            class="flex flex-col items-center justify-center pt-5 pb-6">
                                                            <svg class="w-8 h-8 mb-4 text-gray-500  dark:text-gray-400"
                                                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                                fill="none" viewBox="0 0 20 16">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                                            </svg>
                                                            <p
                                                                class="mb-2 text-sm text-center text-gray-500 dark:text-gray-400">
                                                                <span class="font-semibold">Click to upload image</span>
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div v-else class=" flex items-center justify-center">
                                                        <img :src="imagePreviewUrl"
                                                            class="w-auto rounded object-cover h-48 w-96 cursor-pointer "
                                                            alt="Image preview">
                                                    </div>

                                                    <input @change="handleImageUpload" id="image-file" type="file"
                                                        class="hidden" />

                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="relative">
                                        <div class="relative mb-2">
                                            <input v-model="form.name" type="text" id="floating_first_name"
                                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                placeholder=" " />
                                            <label for="floating_first_name"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">First
                                                Name</label>
                                        </div>

                                        <div class="relative mb-2">
                                            <input v-model="form.email" type="email" id="floating_email"
                                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                placeholder=" " />
                                            <label for="floating_email"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Email
                                            </label>
                                        </div>

                                        <div class="relative mb-2">
                                            <select v-model="form.barangay_id" id="floating_barangay"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 pt-4 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option v-for="barangay in barangays" :key="barangay.id"
                                                    :value="barangay.id">{{
                                                        barangay.barangay_name }}</option>

                                            </select>
                                            <label for="floating_barangay"
                                                class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Barangay</label>
                                        </div>

                                    </div>


                                </div>

                                <div class="col-span-10">
                                    <div class="relative mb-2">
                                        <select v-model="form.role" id="floating_role"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 pt-4 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="0">Focal</option>
                                            <option value="1">Barangay</option>
                                            <option value="2">Staff</option>
                                            <option value="3">Admin</option>
                                        </select>
                                        <label for="floating_barangay"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Role</label>
                                    </div>



                                    <div class="relative mb-2">
                                        <select v-model="form.focal_role" id="floating_focal_role"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 pt-4 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            :disabled="isNotFocal">
                                            <option value="1">Senior</option>
                                            <option value="2">PWD</option>
                                            <option value="3">Women</option>
                                        </select>
                                        <label for="floating_focal_role"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">Focal
                                            Type</label>
                                    </div>

                                    <div class="relative mb-2">
                                        <input v-model="form.username" type="text" id="floating_username"
                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " />
                                        <label for="floating_username"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">User
                                            Name</label>
                                    </div>

                                    <div v-if="isAdd" class="relative mb-2">
                                        <input v-model="form.password" type="password" id="floating_password"
                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border-1 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                            placeholder=" " />
                                        <label for="floating_password"
                                            class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">{{
                                                !isAdd ? "New Password" : "Password" }}
                                        </label>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <!-- Modal footer -->
                        <div
                            class="flex justify-end p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button @click="cancelEdit" type="button"
                                class="py-2.5 px-5 ms-3 mx-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                Cancel
                            </button>

                            <ProcessingButton :process="processing" type="submit"
                                :text="isAdd ? 'Submit' : 'Save'" />
                        </div>
                    </div>
                </div>
            </form>
        </div>


    </Adminpage>
</template>