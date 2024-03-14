<script setup>
import { ref, computed } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import Avarta from "@/Components/Avarta.vue";
import { Link, usePage } from "@inertiajs/vue3";

const showingNavigationDropdown = ref(false);
const page = usePage();

// const handlePermission = () => {

// }

const handlePermission = computed(() => {
    if (page.props.auth.user.role == "system-admin") {
        return [
            "dashboard",
            "user-account",
            "human-resource",
            "profile",
        ];
    } else if (page.props.auth.user.role == "hr") {
        return ["dashboard", "human-resource", "profile"];
    } else {
        return ["dashboard", "profile"];
    }
});

const navs = ref([
    {
        name: "Dashboard",
        route: "dashboard",
        show: handlePermission.value,
    },
    {
        name: "User Account",
        route: "user-account",
        show: handlePermission.value,
    },
    {
        name: "Human Resource",
        route: "human-resource",
        show: handlePermission.value,
    },
    {
        name: "Employee Profile",
        route: "profile",
        show: handlePermission.value,
    },
]);
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex w-full">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex flex-1 justify-end items-center"
                            >
                                <h1>Welcome, {{ $page.props.auth.user.name }}</h1>
                                <Avarta />
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="grid grid-cols-12 gap-2 h-screen">
                <div class="col-span-12 sm:col-span-4 bg-gray-200 p-10">
                    <div class="flex w-full gap-2 mb-10">
                        <slot name="header" />
                        <span
                            v-if="$page.props.auth.user?.role"
                            class="uppercase"
                            >|
                            {{
                                $page.props.auth.user?.role.split("-").join(" ")
                            }}
                        </span>
                    </div>
                    <div class="flex flex-col">
                        <template v-for="item in navs" :key="item.name">
                            <NavLink
                                :href="route(item.route)"
                                :active="route().current(item.route)"
                                class="mb-3 rounded-sm"
                                v-if="item.show.includes(item.route)"
                            >
                                {{ item.name }}
                            </NavLink>
                        </template>
                        <NavLink
                            :href="route('logout')"
                            :active="false"
                            as="button"
                            method="post"
                            class="rounded-sm"
                        >
                            Quit
                        </NavLink>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
