<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'w-full p-3 text-white bg-gray-500 text-center font-bold'
        : 'w-full p-3 bg-gray-400 text-white hover:bg-gray-500 text-center font-bold'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
