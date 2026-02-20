<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Skeleton from '@/Components/UI/Skeleton.vue';

const props = defineProps({
    orders: Object,
    filters: Object,
    loading: Boolean,
});

const emit = defineEmits(['edit', 'delete']);

const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || '');

const updateFilters = debounce(() => {
    router.get(route('orders.index'), {
        search: search.value,
        status: status.value,
    }, {
        preserveState: true,
        replace: true,
    });
}, 300);

watch([search, status], updateFilters);

const getStatusClass = (status) => {
    return status === 'completed' 
        ? 'bg-emerald-100 text-emerald-700' 
        : 'bg-amber-100 text-amber-700';
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(value);
};
</script>

<template>
    <div class="mt-8">
        <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between">
            <div class="flex-1 max-w-sm">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search orders or clients..."
                    class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                />
            </div>
            <div class="w-full sm:w-48">
                <select
                    v-model="status"
                    class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                >
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Order</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Client</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Amount</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <template v-if="loading">
                        <tr v-for="i in 5" :key="'skeleton-order-'+i">
                            <td class="px-6 py-4">
                                <Skeleton class="h-4 w-32 mb-2" />
                                <Skeleton class="h-3 w-16" />
                            </td>
                            <td class="px-6 py-4">
                                <Skeleton class="h-4 w-24 mb-2" />
                                <Skeleton class="h-3 w-20" />
                            </td>
                            <td class="px-6 py-4"><Skeleton class="h-4 w-16" /></td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-2">
                                    <Skeleton class="h-4 w-8" />
                                    <Skeleton class="h-4 w-12" />
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr v-for="order in orders.data" :key="order.id" class="hover:bg-slate-50/50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-800">{{ order.title }}</div>
                                <div class="text-xs text-slate-400">ID: #ORD-{{ order.id.toString().padStart(4, '0') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-slate-600">{{ order.client?.name }}</div>
                                <div class="text-xs text-slate-400 font-medium">{{ order.client?.company }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-black text-slate-800 tracking-tight">{{ formatCurrency(order.amount) }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span :class="['px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider', getStatusClass(order.status)]">
                                    {{ order.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button @click="emit('edit', order)" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 mr-4 transition-colors">Edit</button>
                                <button @click="emit('delete', order.id)" class="text-sm font-bold text-red-600 hover:text-red-800 transition-colors">Delete</button>
                            </td>
                        </tr>
                        <tr v-if="orders.data.length === 0">
                            <td colspan="5" class="px-6 py-12 text-center">
                                <p class="text-slate-400 text-sm font-bold uppercase tracking-wider">No orders found matching your criteria.</p>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div v-if="orders.meta" class="mt-6 flex items-center justify-between">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                Showing {{ orders.meta.from }} - {{ orders.meta.to }} of {{ orders.meta.total }}
            </p>
            <nav class="inline-flex -space-x-px rounded-xl shadow-sm overflow-hidden border border-slate-200">
                <button
                    v-for="link in orders.meta.links"
                    :key="link.label"
                    @click="router.get(link.url)"
                    v-html="link.label"
                    :class="[
                        link.active ? 'z-10 bg-indigo-600 text-white' : 'bg-white text-slate-500 hover:bg-slate-50',
                        'px-4 py-2 text-xs font-bold disabled:opacity-50 transition-colors'
                    ]"
                />
            </nav>
        </div>
    </div>
</template>
