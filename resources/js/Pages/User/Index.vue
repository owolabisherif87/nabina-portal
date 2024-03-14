<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import NewUser from "@/Components/NewUser.vue";
import { onMounted, ref } from "vue";

const show = ref(false)
const users = ref([])
const payload = ref()
const isEditing = ref(false)
const isLoading = ref(false)

onMounted(() => {
    getUsers()
})

const handleModal = () => {
    show.value = !show.value

    getUsers()

    if(!show.value) {
        payload.value = []
        isEditing.value = false
    }
}
const getUsers = async () => {
    isLoading.value = true
    const res = await axios.get("/user-list")

    users.value = res.data
    isLoading.value = false
}
const handleEdit = (user) => {
    payload.value = user
    isEditing.value = true
    show.value = true
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                User Account
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="w-full flex justify-between items-center mb-10">
                    <h1 class="font-bold text-xl">Users</h1>
                    <div>
                        <PrimaryButton class="mr-3 bg-gray-500 text-white" @click="handleModal">+ NEW</PrimaryButton>
                        <PrimaryButton class="bg-gray-500 text-white" @click="getUsers">REFRESH</PrimaryButton>
                    </div>
                </div>
                <div>
                    <template v-if="users.length">
                        <table class="table table-auto w-full border">
                            <thead class="bg-gray-300">
                                <th class="text-start p-3">Name</th>
                                <th class="text-start p-3">Email</th>
                                <th class="text-start p-3">Position</th>
                                <th class="text-start p-3">Role</th>
                            </thead>
                            <tbody>

                                <tr v-for="user in users" :key="user.id" class="cursor-pointer" @click="handleEdit(user)">
                                    <td class="p-3">{{ user.name }}</td>
                                    <td class="p-3">{{ user.email }}</td>
                                    <td class="p-3">{{ user.position }}</td>
                                    <td class="p-3 capitalize">{{ user.role.split("-").join(" ") }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </template>
                    <div v-else-if="isLoading">
                        <h1 class="font-bold text-xl">Fetching user.....</h1>
                    </div>
                    <template v-else>
                        <div>
                            <h1>No users yet.</h1>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <NewUser :show="show" :payload="payload" :is-editing="isEditing" @close-modal="handleModal"/>

    </AuthenticatedLayout>
</template>
