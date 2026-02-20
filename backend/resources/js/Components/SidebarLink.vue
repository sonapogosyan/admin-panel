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
        ? 'flex items-center px-5 py-3.5 text-sm font-bold rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-200 transition-all duration-300 transform translate-x-1 group'
        : 'flex items-center px-5 py-3.5 text-sm font-semibold rounded-2xl text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 transition-all duration-300 hover:translate-x-1 group'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <div :class="[active ? 'text-white' : 'text-slate-400 group-hover:text-indigo-500', 'transition-colors duration-300']">
            <slot name="icon" />
        </div>
        <div class="ml-4 whitespace-nowrap overflow-hidden transition-all duration-300">
            <slot />
        </div>
    </Link>
</template>
