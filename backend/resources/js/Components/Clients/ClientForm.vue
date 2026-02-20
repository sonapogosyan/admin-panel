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
    client: Object,
});

const emit = defineEmits(['close']);

const form = useForm({
    name: '',
    email: '',
    phone: '',
    company: '',
    status: 'active',
});

watch(() => props.client, (newClient) => {
    if (newClient) {
        form.name = newClient.name;
        form.email = newClient.email;
        form.phone = newClient.phone || '';
        form.company = newClient.company || '';
        form.status = newClient.status;
    } else {
        form.reset();
    }
});

const submit = () => {
    if (props.client) {
        form.put(route('api.clients.update', props.client.id), {
            onSuccess: () => {
                form.reset();
                emit('close');
            },
        });
    } else {
        form.post(route('api.clients.store'), {
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
            <h2 class="text-lg font-medium text-gray-900">
                {{ client ? 'Edit Client' : 'Create Client' }}
            </h2>

            <div class="mt-6">
                <InputLabel for="name" value="Name" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="form.errors.email" class="mt-2" />
            </div>

            <div class="mt-4">
                <InputLabel for="phone" value="Phone" />
                <TextInput
                    id="phone"
                    v-model="form.phone"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.phone" class="mt-2" />
            </div>

            <div class="mt-4">
                <InputLabel for="company" value="Company" />
                <TextInput
                    id="company"
                    v-model="form.company"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.company" class="mt-2" />
            </div>

            <div class="mt-4">
                <InputLabel for="status" value="Status" />
                <select
                    id="status"
                    v-model="form.status"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    required
                >
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <InputError :message="form.errors.status" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
                <PrimaryButton class="ms-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{ client ? 'Update' : 'Create' }}
                </PrimaryButton>
            </div>
        </form>
    </Modal>
</template>
