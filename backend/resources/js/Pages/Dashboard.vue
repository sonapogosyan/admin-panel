<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import StatCard from '@/Components/Dashboard/StatCard.vue';
import Skeleton from '@/Components/UI/Skeleton.vue';
import axios from 'axios';

const loading = ref(true);
const stats = ref({
    users_count: 0,
    clients_count: 0,
    active_clients_count: 0,
    inactive_clients_count: 0,
    recent_clients: []
});

const fetchDashboardData = async () => {
    try {
        const response = await axios.get(route('api.dashboard'));
        stats.value = response.data;
    } catch (error) {
        console.error('Failed to fetch dashboard data:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchDashboardData();
});

const getStatusClass = (status) => {
    return status === 'active' 
        ? 'bg-green-100 text-green-700' 
        : 'bg-slate-100 text-slate-600';
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            Dashboard Overview
        </template>

        <div class="p-6 lg:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <StatCard 
                    title="Total Users" 
                    :value="stats.users_count" 
                    :loading="loading"
                    icon-color="text-blue-600"
                >
                    <template #icon>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </template>
                </StatCard>

                <StatCard 
                    title="Total Clients" 
                    :value="stats.clients_count" 
                    :loading="loading"
                    icon-color="text-indigo-600"
                >
                    <template #icon>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </template>
                </StatCard>

                <StatCard 
                    title="Active Clients" 
                    :value="stats.active_clients_count" 
                    :loading="loading"
                    icon-color="text-emerald-600"
                >
                    <template #icon>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </template>
                </StatCard>

                <StatCard 
                    title="Inactive" 
                    :value="stats.inactive_clients_count" 
                    :loading="loading"
                    icon-color="text-slate-400"
                >
                    <template #icon>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </template>
                </StatCard>
            </div>

            <div class="mt-10">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-800 tracking-tight">Recent Clients</h3>
                    <Link :href="route('clients.index')" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors uppercase tracking-wider">
                        View All Clients →
                    </Link>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Client</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest">Company</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 uppercase tracking-widest">Date Added</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <template v-if="loading">
                                <tr v-for="i in 3" :key="'row-loader-'+i">
                                    <td class="px-6 py-4"><Skeleton class="h-4 w-32 mb-2" /><Skeleton class="h-3 w-24" /></td>
                                    <td class="px-6 py-4"><Skeleton class="h-4 w-28" /></td>
                                    <td class="px-6 py-4 text-right"><Skeleton class="h-4 w-20 ml-auto" /></td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr v-for="client in stats.recent_clients" :key="client.id" class="hover:bg-slate-50/50 transition-colors duration-200">
                                    <td class="px-6 py-4 text-sm font-medium text-slate-900">
                                        {{ client.name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500">
                                        {{ client.company || '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span :class="['px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider', getStatusClass(client.status)]">
                                            {{ client.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm text-slate-400 font-medium">
                                        {{ new Date(client.created_at).toLocaleDateString() }}
                                    </td>
                                </tr>
                                <tr v-if="stats.recent_clients.length === 0">
                                    <td colspan="4" class="px-6 py-12 text-center text-sm font-medium text-slate-500">
                                        No recent clients found.
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
