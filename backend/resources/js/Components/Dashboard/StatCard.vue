<script setup>
import Skeleton from '@/Components/UI/Skeleton.vue';

defineProps({
    title: String,
    value: [String, Number],
    loading: Boolean,
    iconColor: {
        type: String,
        default: 'text-indigo-600'
    },
    trend: String,
    trendUp: Boolean
});
</script>

<template>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow duration-300 h-32 flex flex-col justify-center">
        <template v-if="loading">
            <div class="flex items-center justify-between mb-4">
            </div>
            <div>
                <Skeleton class="h-3 w-24 mb-2" />
                <Skeleton class="h-6 w-16" />
            </div>
        </template>
        <template v-else>
            <div class="flex items-center justify-between mb-4">
                <div :class="['p-3 rounded-xl bg-slate-50', iconColor]">
                    <slot name="icon" />
                </div>
                <div v-if="trend" :class="[trendUp ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50', 'px-2.5 py-0.5 rounded-full text-xs font-bold']">
                    {{ trend }}
                </div>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">{{ title }}</p>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight">{{ value }}</h3>
            </div>
        </template>
    </div>
</template>
