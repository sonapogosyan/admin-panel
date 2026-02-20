<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import ClientsTable from '@/Components/Clients/ClientsTable.vue';
import ClientForm from '@/Components/Clients/ClientForm.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref, onMounted } from 'vue';

const props = defineProps({
    clients: Object,
    filters: Object,
});

const showForm = ref(false);
const selectedClient = ref(null);
const loading = ref(false);

// Listen for Inertia events to show loading state
onMounted(() => {
    router.on('start', () => (loading.value = true));
    router.on('finish', () => (loading.value = false));
});

const openCreateForm = () => {
    selectedClient.value = null;
    showForm.value = true;
};

const openEditForm = (client) => {
    selectedClient.value = client;
    showForm.value = true;
};

const deleteClient = (id) => {
    if (confirm('Are you sure you want to delete this client?')) {
        router.delete(route('api.clients.destroy', id), {
            preserveScroll: true,
        });
    }
};

const closeForm = () => {
    showForm.value = false;
    selectedClient.value = null;
};
</script>

<template>
    <Head title="Clients" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Clients</h2>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-end mb-6">
                    <PrimaryButton @click="openCreateForm" class="!rounded-xl shadow-lg shadow-indigo-100">
                        Add New Client
                    </PrimaryButton>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl p-6 border border-slate-100">
                    <ClientsTable
                        :clients="clients"
                        :filters="filters"
                        :loading="loading"
                        @edit="openEditForm"
                        @delete="deleteClient"
                    />
                </div>
            </div>
        </div>

        <ClientForm
            :show="showForm"
            :client="selectedClient"
            @close="closeForm"
        />
    </AuthenticatedLayout>
</template>
