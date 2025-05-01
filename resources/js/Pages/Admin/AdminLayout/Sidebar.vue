<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const { component } = usePage();
const { props } = usePage();

const unreadNotifications = props.auth.unreadNotifications;

function isInRequestRoutes() {
    if (component == 'Admin/Pages/Requests/Program' || component == 'Admin/Pages/Requests/Birthday' ||  component == 'Admin/Pages/Requests/NewBeneficiary' || component == 'Admin/Pages/Requests/RequestInfo' || component == 'Admin/Pages/Requests/ProgramInfo') {

        return true;
    } else {
        return false;
    }
}

function isInBeneficiaryRoutes() {
    if (component == 'Admin/Pages/Beneficiaries/Women' || component == 'Admin/Pages/Beneficiaries/Pwd' || component == 'Admin/Pages/Beneficiaries/Senior' || component == 'Admin/Pages/Beneficiaries/Aics' || component == 'Admin/Pages/Beneficiaries/Mapping' || component == 'Admin/Pages/Beneficiaries/BeneficiariesBarangay' || component == 'Admin/Pages/Beneficiaries/Beneficiaries' || component == 'Admin/Pages/Requests/AicsEditForm') {
        return true;
    } else {
        return false;
    }
}

const hasUnreadOfRequestBeneficiaries = computed(() => {
    return unreadNotifications.some(
        (notification) => notification.data.type === 0
    );
});

const hasUnreadOfRequestBirthday = computed(() => {
    return unreadNotifications.some(
        (notification) => notification.data.type === 3
    );
});

const hasUnreadOfRequestProgram = computed(() => {
    return unreadNotifications.some(
        (notification) => notification.data.type === 2
    );
});

const hasUnreadOfRequestAics = computed(() => {
    return unreadNotifications.some(
        (notification) => notification.data.type === 4
    );
});

</script>

<template>
    <aside
        class="fixed top-0 left-0 w-64 h-screen pt-14 transition-transform -translate-x-full bg-white border-r border-gray-200 md:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
        aria-label="Sidenav" id="drawer-navigation" style="z-index: 1001;">
        <div class="overflow-y-auto py-5 px-3 h-full bg-white dark:bg-gray-800">
            <ul class="space-y-2">
                <li>
                    <Link :href="route('admin.dashboard')"
                        class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group"
                        :class="{ 'bg-gray-300 disabled:opacity-50 pointer-events-none': $page.component.startsWith('Admin/Pages/Dashboard') }">
                    <svg aria-hidden="true"
                        class="w-6 h-6 text-gray-800 transition duration-75 dark:text-white group-hover:text-gray-900 dark:group-hover:text-white"
                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                    </Link>
                </li>
                <li>
                    <Link :href="route('admin.users')" type="button"
                        class="flex items-center p-2 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        :class="{ 'bg-gray-300 disabled:opacity-50 pointer-events-none preserve-state': $page.component.startsWith('Admin/Pages/Users') }">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M17 10v1.126c.367.095.714.24 1.032.428l.796-.797 1.415 1.415-.797.796c.188.318.333.665.428 1.032H21v2h-1.126c-.095.367-.24.714-.428 1.032l.797.796-1.415 1.415-.796-.797a3.979 3.979 0 0 1-1.032.428V20h-2v-1.126a3.977 3.977 0 0 1-1.032-.428l-.796.797-1.415-1.415.797-.796A3.975 3.975 0 0 1 12.126 16H11v-2h1.126c.095-.367.24-.714.428-1.032l-.797-.796 1.415-1.415.796.797A3.977 3.977 0 0 1 15 11.126V10h2Zm.406 3.578.016.016c.354.358.574.85.578 1.392v.028a2 2 0 0 1-3.409 1.406l-.01-.012a2 2 0 0 1 2.826-2.83ZM5 8a4 4 0 1 1 7.938.703 7.029 7.029 0 0 0-3.235 3.235A4 4 0 0 1 5 8Zm4.29 5H7a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h6.101A6.979 6.979 0 0 1 9 15c0-.695.101-1.366.29-2Z"
                            clip-rule="evenodd" />
                    </svg>

                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Users</span>
                    </Link>
                </li>

                <!--Requests Dropdown-->
                <li>
                    <button type="button"
                        class="flex items-center p-2 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        aria-controls="dropdown-request" data-collapse-toggle="dropdown-request">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M2.038 5.61A2.01 2.01 0 0 0 2 6v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6c0-.12-.01-.238-.03-.352l-.866.65-7.89 6.032a2 2 0 0 1-2.429 0L2.884 6.288l-.846-.677Z" />
                            <path
                                d="M20.677 4.117A1.996 1.996 0 0 0 20 4H4c-.225 0-.44.037-.642.105l.758.607L12 10.742 19.9 4.7l.777-.583Z" />
                        </svg>

                        <span class="flex-1 ml-3 text-left whitespace-nowrap">
                            <span class="me-2">Requests</span>
                            <span
                                v-if="hasUnreadOfRequestBeneficiaries || hasUnreadOfRequestProgram || hasUnreadOfRequestAics || hasUnreadOfRequestBirthday"
                                class="dot dot-error">
                            </span>
                        </span>
                        <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <ul id="dropdown-request" class="py-2 space-y-2" :class="{ 'hidden': !isInRequestRoutes() }">
                        <li>
                            <Link :href="route('admin.request.programs')"
                                class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group dark:text-white dark:hover:bg-gray-700"
                                :class="{ 'bg-gray-300 disabled pointer-events-none': component == 'Admin/Pages/Requests/Program' || component == 'Admin/Pages/Requests/ProgramInfo' }">
                            <span class="me-2">Programs</span>
                            <span v-if="hasUnreadOfRequestProgram" class="dot dot-error"></span>
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('admin.request.beneficiaries')"
                                class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group dark:text-white dark:hover:bg-gray-700"
                                :class="{ 'bg-gray-300 disabled pointer-events-none': component == 'Admin/Pages/Requests/NewBeneficiary' || component == 'Admin/Pages/Requests/RequestInfo' }">
                            <span class="me-2">
                                New Beneficiary
                            </span> 
                            <span v-if="hasUnreadOfRequestBeneficiaries" class="dot dot-error"></span>
                        </Link>
                        </li>
                        <li>
                            <Link :href="route('admin.request.birthdays')"
                                class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group dark:text-white dark:hover:bg-gray-700"
                                :class="{ 'bg-gray-300 disabled pointer-events-none': component == 'Admin/Pages/Requests/Birthday' }">
                            <span class="me-2">
                                Birthday
                            </span> <span v-if="hasUnreadOfRequestBirthday" class="dot dot-error"></span></Link>
                        </li>
                        
                    </ul>
                </li>

                <!--Beneficiaries Dropdown-->
                <li>
                    <button type="button"
                        class="flex items-center p-2 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                        aria-controls="dropdown-benefeciary" data-collapse-toggle="dropdown-benefeciary">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-6 h-6 text-gray-800">
                            <path fill-rule="evenodd"
                                d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z"
                                clip-rule="evenodd" />
                            <path
                                d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                        </svg>

                        <span class="flex-1 ml-3 text-left whitespace-nowrap">Beneficiaries</span>
                        <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <ul id="dropdown-benefeciary" class="py-2 space-y-2"
                        :class="{ 'hidden': !isInBeneficiaryRoutes() }">
                        <li>
                            <Link :href="route('admin.report.beneficiaries')"
                                class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group dark:text-white dark:hover:bg-gray-700"
                                :class="{ 'bg-gray-300 disabled pointer-events-none': component == 'Admin/Pages/Beneficiaries/Beneficiaries' }">
                            Beneficiary List</Link>
                        </li>
                        <li>
                            <Link :href="route('admin.beneficiaries.aics')"
                                class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group dark:text-white dark:hover:bg-gray-700"
                                :class="{ 'bg-gray-300 disabled pointer-events-none': component == 'Admin/Pages/Beneficiaries/Aics' || component == 'Admin/Pages/Requests/AicsEditForm' }">
                            AICS</Link>
                        </li>
                        <li>
                            <Link :href="route('admin.beneficiaries.mapping')"
                                class="flex items-center p-2 pl-11 w-full text-base font-medium text-gray-900 rounded-lg transition duration-75 group dark:text-white dark:hover:bg-gray-700"
                                :class="{ 'bg-gray-300 disabled pointer-events-none': component == 'Admin/Pages/Beneficiaries/Mapping' || component == 'Admin/Pages/Beneficiaries/BeneficiariesBarangay' }">
                            Mapping</Link>
                        </li>
                    </ul>
                </li>


            </ul>

        </div>

    </aside>
</template>
