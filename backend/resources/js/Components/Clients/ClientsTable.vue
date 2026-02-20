<script setup>
import { ref, watch, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import Skeleton from '@/Components/UI/Skeleton.vue';

const props = defineProps({
    clients: Object,
    filters: Object,
    loading: Boolean,
});

const emit = defineEmits(['edit', 'delete']);
const page = usePage();

const canDelete = computed(() => page.props.auth.user?.can?.delete_clients ?? false);

const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || '');

const updateFilters = debounce(() => {
    router.get(route('clients.index'), {
        search: search.value,
        status: status.value,
    }, {
        preserveState: true,
        replace: true,
    });
}, 300);

watch([search, status], updateFilters);

const getStatusClass = (status) => {
    return status === 'active' 
        ? 'bg-green-100 text-green-800' 
        : 'bg-red-100 text-red-800';
};
</script>

<template>
    <div class="mt-8 flex flex-col">
        <div class="mb-4 flex flex-col sm:flex-row gap-4 justify-between">
            <div class="flex-1 max-w-sm">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search clients..."
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                />
            </div>
            <div class="w-full sm:w-48">
                <select
                    v-model="status"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                >
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Company</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Email</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <template v-if="loading">
                                <tr v-for="i in 5" :key="'skeleton-'+i">
                                    <td class="py-4 pl-4 pr-3 sm:pl-6"><Skeleton class="h-4 w-32" /></td>
                                    <td class="px-3 py-4"><Skeleton class="h-4 w-24" /></td>
                                    <td class="px-3 py-4"><Skeleton class="h-4 w-40" /></td>
                                    <td class="py-4 pl-3 pr-4 text-right sm:pr-6">
                                        <div class="flex justify-end space-x-2">
                                            <Skeleton class="h-4 w-8" />
                                            <Skeleton class="h-4 w-12" />
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr v-for="client in clients.data" :key="client.id">
                                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                        {{ client.name }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ client.company || '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        {{ client.email }}
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <span :class="['inline-flex rounded-full px-2 text-xs font-semibold leading-5', getStatusClass(client.status)]">
                                            {{ client.status }}
                                        </span>
                                    </td>
                                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                        <button @click="emit('edit', client)" class="text-indigo-600 hover:text-indigo-900 mr-4 font-bold">Edit</button>
                                        <button v-if="canDelete" @click="emit('delete', client.id)" class="text-red-600 hover:text-red-900 font-bold underline decoration-2 underline-offset-4">Delete</button>
                                    </td>
                                </tr>
                                <tr v-if="clients.data.length === 0">
                                    <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500 uppercase tracking-wider">
                                        No clients found.
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="clients.meta" class="mt-4 flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
            <div class="flex flex-1 justify-between sm:hidden">
                <button
                    @click="router.get(clients.links.prev)"
                    class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50"
                >
                    Previous
                </button>
                <button
                    @click="router.get(clients.links.next)"
                    class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 disabled:opacity-50"
                >
                    Next
                </button>
            </div>
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium">{{ clients.meta.from }}</span>
                        to
                        <span class="font-medium">{{ clients.meta.to }}</span>
                        of
                        <span class="font-medium">{{ clients.meta.total }}</span>
                        results
                    </p>
                </div>
                <div>
                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                        <button
                            v-for="link in clients.meta.links"
                            :key="link.label"
                            @click="router.get(link.url)"
                            v-html="link.label"
                            :class="[
                                link.active ? 'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600' : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0',
                                'relative inline-flex items-center px-4 py-2 text-sm font-semibold focus:z-20 disabled:opacity-50'
                            ]"
                        />
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>
