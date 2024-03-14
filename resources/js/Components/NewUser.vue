<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm, usePage} from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import { computed, ref, useAttrs, watch } from "vue";

const emits = defineEmits(["closeModal"]);
const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    isEditing: {
        type: Boolean,
        default: false
    },
    payload: {
        type: Object,
        default: {}
    }
});
const page = usePage()

const show = computed(() => props.show);
const isEditing = computed(() => props.isEditing);

watch(isEditing, () => {

    if(isEditing.value) {
        let name = props.payload.name.split(" ")

        form.id = props.payload.id
        form.lastname = name[0]
        form.firstname = name[1]
        form.email = props.payload.email
        form.position = props.payload.position
        form.role = props.payload.role
        image.value = props.payload.image
    }
})

const file = ref(null)
const image = ref(null)
const form = useForm({
    id:null,
    firstname: "",
    lastname: "",
    position: "",
    email: "",
    password: "",
    image: null,
    role: "user",
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        _method: isEditing.value ? "put" : "post"
    })).post(route("user-account-register"), {
        onFinish: () => {
            form.reset()
            emits("closeModal")
        }
    });
};
const resetPassword = () => form.password = ""
const handleFile = () => {
    if(file.value) {
        file.value.click()
    }
}
const handleFilePreview = () => {
    let doc = file.value.files[0]
    let path = URL.createObjectURL(doc)
    form.image = doc
    image.value = path
}
const handleDelete = async () => {
    if(isEditing.value) {
        await axios.delete(`/user/delete/${props.payload.id}`)

        emits("closeModal")
    }
}
</script>

<template>
    <Modal :show="show" :closeable="true">
        <div class="w-full h-10 bg-blue-500 p-5 flex items-center">
            <h1 class="font-bold text-white">USER REGISTRATION</h1>
        </div>
        <div class="p-5">

            <form @submit.prevent="submit">
                <div class="grid grid-cols-12 gap-3">
                    <div class="col-span-9">
                        <div>
                            <InputLabel for="firstname" value="Firstname" />

                            <TextInput
                                id="firstname"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.firstname"
                                required
                                autofocus
                                autocomplete="firstname"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.firstname"
                            />
                        </div>

                        <div>
                            <InputLabel for="lastname" value="Lastname" />

                            <TextInput
                                id="lastname"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.lastname"
                                required
                                autocomplete="lastname"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.lastname"
                            />
                        </div>

                        <div>
                            <InputLabel for="position" value="Position" />

                            <TextInput
                                id="position"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.position"
                                required
                                autocomplete="position"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.position"
                            />
                        </div>

                        <div>
                            <InputLabel for="email" value="Email" />

                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.email"
                                required
                                autocomplete="username"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.email"
                            />
                        </div>

                        <div>
                            <InputLabel for="password" value="Password" />
                            <div class="flex gap-3 items-stretch">
                                <div class="flex-1">
                                    <TextInput
                                        id="password"
                                        type="password"
                                        class="mt-1 block w-full"
                                        v-model="form.password"
                                        :required="!isEditing"
                                        autocomplete="new-password"
                                    />

                                    <InputError
                                        class="mt-2"
                                        :message="form.errors.password"
                                    />
                                </div>
                                 <PrimaryButton
                                    class="bg-gray-500 text-white rounded-md hover:bg-gray-600"
                                    @click="resetPassword"
                                    type="button"
                                >
                                    RESET
                                </PrimaryButton>
                            </div>
                        </div>

                        <div>
                            <InputLabel for="role" value="Role" />

                            <select
                                name="role"
                                class="w-full rounded-md border border-gray-300"
                                v-model="form.role"
                            >
                                <option value="system-admin">
                                    System Admin
                                </option>
                                <option value="hr">HR</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-span-3 pt-6">
                        <div class="w-full h-40 bg-blue-500 overflow-hidden cursor-pointer flex justify-center items-center font-bold text-white rounded-md relative" @click="handleFile">
                            <h1>PHOTO</h1>
                            <div class="absolute right-0 top-0 left-0 bottom-0" v-if="image">
                                <img :src="image" alt="image" class="w-full h-full object-cover">
                            </div>
                            <input type="file" class="w-full h-full absolute left-0 right-0 top-0 bottom-0 invisible" ref="file" @change="handleFilePreview">
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-start gap-4 mt-4">
                    <PrimaryButton
                        class="bg-gray-500 text-white rounded-md hover:bg-gray-600"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        type="submit"
                    >
                        {{ isEditing ? "UPDATE" : "SAVE" }}
                    </PrimaryButton>
                    <PrimaryButton
                        class="bg-gray-500 text-white rounded-md hover:bg-gray-600"
                        @click="handleDelete"
                        type="button"
                        >DELETE</PrimaryButton
                    >
                    <PrimaryButton
                    type="button"
                        class="bg-gray-500 text-white rounded-md hover:bg-gray-600"
                        @click="$emit('closeModal')"
                        >CLOSE</PrimaryButton
                    >
                </div>
            </form>
        </div>
    </Modal>
</template>
