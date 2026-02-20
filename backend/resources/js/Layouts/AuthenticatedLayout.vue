<script setup>
import { ref, onMounted } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import SidebarLink from '@/Components/SidebarLink.vue';

const isSidebarOpen = ref(true);

const toggleSidebar = () => {
};

onMounted(() => {
    const handleResize = () => {
        if (window.innerWidth < 1024) {
            isSidebarOpen.value = false;
        } else {
            isSidebarOpen.value = true;
        }
    };
    window.addEventListener('resize', handleResize);
    handleResize();
});

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex overflow-hidden font-sans">
        <aside 
            :class="[
                'fixed inset-y-0 left-0 z-50 transition-all duration-300 transform bg-white border-r border-slate-200 shadow-xl lg:relative lg:translate-x-0',
                isSidebarOpen ? 'w-72 translate-x-0' : 'w-20 -translate-x-full lg:translate-x-0'
            ]"
        >
            <div class="h-full flex flex-col">
                <div class="h-20 flex items-center px-6 border-b border-slate-100">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span v-if="isSidebarOpen" class="text-xl font-black text-slate-800 tracking-tight">TradeIQ <span class="text-indigo-600">Pulse</span></span>
                    </div>
                </div>

                <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
                    <p v-if="isSidebarOpen" class="px-3 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Main Menu</p>
                    
                    <SidebarLink :href="route('dashboard')" :active="route().current('dashboard')">
                        <template #icon>
                            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </template>
                        <span v-if="isSidebarOpen" class="font-semibold">Dashboard</span>
                    </SidebarLink>

                    <SidebarLink :href="route('orders.index')" :active="route().current('orders.index')">
                        <template #icon>
                            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </template>
                        <span v-if="isSidebarOpen" class="font-semibold">Orders</span>
                    </SidebarLink>

                    <SidebarLink :href="route('clients.index')" :active="route().current('clients.index')">
                        <template #icon>
                            <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </template>
                        <span v-if="isSidebarOpen" class="font-semibold">Clients</span>
                    </SidebarLink>
                </nav>

                <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                    <div v-if="isSidebarOpen" class="flex items-center justify-between">
                        <div class="flex items-center min-w-0">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white font-bold shadow-md shadow-indigo-100 shrink-0 uppercase transition-all duration-300">
                                {{ $page.props.auth.user.name.charAt(0) }}
                            </div>
                            <div class="ml-3 truncate">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $page.props.auth.user.name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ $page.props.auth.user.role }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-40">
                <div class="flex items-center">
                    <button @click="toggleSidebar" class="p-2.5 rounded-xl text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path v-if="isSidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7" />
                        </svg>
                    </button>
                    <h2 class="ml-6 text-xl font-bold text-slate-800 tracking-tight">
                        <slot name="header" />
                    </h2>
                </div>

                <div class="flex items-center space-x-4">
                    <button @click="logout" class="flex items-center space-x-2 px-4 py-2 rounded-xl text-slate-500 hover:text-red-600 hover:bg-red-50 font-bold text-sm transition-all">
                        <span>Sign Out</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-slate-50/50">
                <slot />
            </main>
        </div>
    </div>
</template>
