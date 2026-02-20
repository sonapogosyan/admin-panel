<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import OrdersTable from '@/Components/Orders/OrdersTable.vue';
import OrderForm from '@/Components/Orders/OrderForm.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref, onMounted } from 'vue';

const props = defineProps({
    orders: Object,
    filters: Object,
    clients: Object,
});

const showForm = ref(false);
const selectedOrder = ref(null);
const loading = ref(false);

onMounted(() => {
    router.on('start', () => (loading.value = true));
    router.on('finish', () => (loading.value = false));
});

const openCreateForm = () => {
    selectedOrder.value = null;
    showForm.value = true;
};

const openEditForm = (order) => {
    selectedOrder.value = order;
    showForm.value = true;
};

const deleteOrder = (id) => {
    if (confirm('Are you sure you want to delete this order?')) {
        router.delete(route('api.orders.destroy', id), {
            preserveScroll: true,
        });
    }
};

const closeForm = () => {
    showForm.value = false;
    selectedOrder.value = null;
};
</script>

<template>
    <Head title="Orders" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <span>Orders Overview</span>
            </div>
        </template>

        <div class="p-6 lg:p-8">
            <div class="flex justify-end mb-6">
                <PrimaryButton @click="openCreateForm" class="!rounded-xl shadow-lg shadow-indigo-100">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    New Order
                </PrimaryButton>
            </div>

            <OrdersTable 
                :orders="orders" 
                :filters="filters"
                :loading="loading"
                @edit="openEditForm"
                @delete="deleteOrder"
            />
        </div>

        <OrderForm 
            :show="showForm" 
            :order="selectedOrder" 
            :clients="clients.data"
            @close="closeForm"
        />
    </AuthenticatedLayout>
</template>
