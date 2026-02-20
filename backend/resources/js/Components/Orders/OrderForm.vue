<script setup>
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { watch } from 'vue';

const props = defineProps({
    show: Boolean,
    order: Object,
    clients: Array,
});

const emit = defineEmits(['close']);

const form = useForm({
    client_id: '',
    title: '',
    amount: '',
    status: 'pending',
});

watch(() => props.order, (newOrder) => {
    if (newOrder) {
        form.client_id = newOrder.client_id;
        form.title = newOrder.title;
        form.amount = newOrder.amount;
        form.status = newOrder.status;
    } else {
        form.reset();
    }
});

const submit = () => {
    if (props.order) {
        form.put(route('api.orders.update', props.order.id), {
            onSuccess: () => {
                form.reset();
                emit('close');
            },
        });
    } else {
        form.post(route('api.orders.store'), {
            onSuccess: () => {
                form.reset();
                emit('close');
            },
        });
    }
};

const closeModal = () => {
    form.reset();
    form.clearErrors();
    emit('close');
};
</script>

<template>
    <Modal :show="show" @close="closeModal">
        <form @submit.prevent="submit" class="p-6">
            <h2 class="text-lg font-bold text-slate-800 tracking-tight mb-6">
                {{ order ? 'Edit Order' : 'Create New Order' }}
            </h2>

            <div class="space-y-4">
                <div>
                    <InputLabel for="client_id" value="Client" />
                    <select
                        id="client_id"
                        v-model="form.client_id"
                        class="mt-1 block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm"
                        required
                    >
                        <option value="" disabled>Select a client</option>
                        <option v-for="client in clients" :key="client.id" :value="client.id">
                            {{ client.name }} ({{ client.company }})
                        </option>
                    </select>
                    <InputError :message="form.errors.client_id" class="mt-2" />
                </div>

                <div>
                    <InputLabel for="title" value="Order Title" />
                    <TextInput
                        id="title"
                        v-model="form.title"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="e.g. Website Development"
                        required
                    />
                    <InputError :message="form.errors.title" class="mt-2" />
                </div>

                <div>
                    <InputLabel for="amount" value="Amount ($)" />
                    <TextInput
                        id="amount"
                        v-model="form.amount"
                        type="number"
                        step="0.01"
                        class="mt-1 block w-full"
                        placeholder="0.00"
                        required
                    />
                    <InputError :message="form.errors.amount" class="mt-2" />
                </div>

                <div>
                    <InputLabel for="status" value="Status" />
                    <select
                        id="status"
                        v-model="form.status"
                        class="mt-1 block w-full border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm"
                        required
                    >
                        <option value="pending">Pending</option>
                        <option value="completed">Completed</option>
                    </select>
                    <InputError :message="form.errors.status" class="mt-2" />
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <PrimaryButton 
                    :class="{ 'opacity-25': form.processing }" 
                    :disabled="form.processing"
                >
                    {{ order ? 'Update Order' : 'Create Order' }}
                </PrimaryButton>
            </div>
        </form>
    </Modal>
</template>
