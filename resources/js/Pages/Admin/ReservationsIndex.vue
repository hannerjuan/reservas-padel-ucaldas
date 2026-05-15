<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    reservations: Object,
    filters: Object,
    spaces: Array // Recibimos las canchas desde el backend
});

// Variables reactivas conectadas a los filtros de la URL
const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || '');
const space_id = ref(props.filters?.space_id || '');
const date = ref(props.filters?.date || '');

// Observador: si el usuario cambia CUALQUIER filtro, busca automáticamente en la BD
watch([search, status, space_id, date], () => {
    router.get(route('reservations.index'), {
        search: search.value,
        status: status.value,
        space_id: space_id.value,
        date: date.value
    }, { 
        preserveState: true, 
        replace: true 
    });
});

// Función para limpiar todos los filtros
const resetFilters = () => {
    search.value = '';
    status.value = '';
    space_id.value = '';
    date.value = '';
};

// Función de acciones (Aprobar, Rechazar, Cancelar)
const updateStatus = (id, action) => {
    const actionText = action === 'accept' ? 'confirmar' : (action === 'reject' ? 'rechazar' : 'cancelar');
    if (confirm(`¿Estás seguro de que deseas ${actionText} esta reserva? Se enviará un correo al usuario.`)) {
        router.post(route(`reservations.${action}`, id), {}, {
            preserveScroll: true,
            preserveState: true
        });
    }
};
</script>

<template>
    <AppLayout title="Gestión de Reservas">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Reservas de Pádel
            </h2>
        </template>

        <div class="py-12 bg-gray-100 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div class="bg-white p-6 rounded-lg shadow-sm mb-6 border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Buscar Cliente</label>
                            <input v-model="search" type="text" placeholder="Nombre o correo..." class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Cancha</label>
                            <select v-model="space_id" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todas las canchas</option>
                                <option v-for="space in spaces" :key="space.id" :value="space.id">{{ space.name }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Fecha Exacta</label>
                            <input v-model="date" type="date" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="flex items-end space-x-2">
                            <div class="flex-grow">
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Estado</label>
                                <select v-model="status" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Todos los estados</option>
                                    <option value="pendiente">Pendientes</option>
                                    <option value="confirmada">Confirmadas</option>
                                    <option value="rechazada">Rechazadas</option>
                                    <option value="cancelada">Canceladas</option>
                                </select>
                            </div>
                            <button @click="resetFilters" title="Limpiar Filtros" class="mb-1 p-2 bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200 border border-gray-300 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div v-if="$page.props.flash && $page.props.flash.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-sm font-bold">
                        {{ $page.props.flash.success }}
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 uppercase text-xs leading-normal border-b">
                                    <th class="py-3 px-6 text-left">Cancha</th>
                                    <th class="py-3 px-6 text-left">Cliente</th>
                                    <th class="py-3 px-6 text-center">Fecha y Hora</th>
                                    <th class="py-3 px-6 text-center">Estado</th>
                                    <th class="py-3 px-6 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                <tr v-if="reservations.data.length === 0">
                                    <td colspan="5" class="py-8 text-center text-gray-400 italic font-medium">
                                        No se encontraron reservas con los filtros seleccionados.
                                    </td>
                                </tr>
                                <tr v-for="reservation in reservations.data" :key="reservation.id" class="border-b border-gray-200 hover:bg-gray-50 transition">
                                    <td class="py-3 px-6 text-left font-bold text-gray-800">
                                        {{ reservation.space.name }}
                                    </td>
                                    <td class="py-3 px-6 text-left">
                                        <div class="font-semibold text-gray-800">{{ reservation.user_name }}</div>
                                        <div class="text-xs text-gray-500">{{ reservation.user_email }}</div>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="font-bold text-gray-700">{{ new Date(reservation.start_time).toLocaleDateString() }}</div>
                                        <div class="text-xs text-blue-600 font-bold bg-blue-50 rounded px-2 py-1 inline-block mt-1">
                                            {{ new Date(reservation.start_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }} - 
                                            {{ new Date(reservation.end_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <span :class="{
                                            'bg-yellow-100 text-yellow-800 border border-yellow-200': reservation.status === 'pendiente',
                                            'bg-green-100 text-green-800 border border-green-200': reservation.status === 'confirmada',
                                            'bg-red-100 text-red-800 border border-red-200': reservation.status === 'rechazada' || reservation.status === 'cancelada',
                                        }" class="py-1 px-3 rounded-full text-xs uppercase font-extrabold tracking-wider shadow-sm">
                                            {{ reservation.status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex item-center justify-center space-x-2">
                                            <button v-if="reservation.status === 'pendiente'" @click="updateStatus(reservation.id, 'accept')" class="bg-green-500 text-white px-3 py-1.5 rounded shadow-sm hover:bg-green-600 text-xs font-bold transition">
                                                Aprobar
                                            </button>
                                            <button v-if="reservation.status === 'pendiente'" @click="updateStatus(reservation.id, 'reject')" class="bg-red-500 text-white px-3 py-1.5 rounded shadow-sm hover:bg-red-600 text-xs font-bold transition">
                                                Rechazar
                                            </button>
                                            <button v-if="reservation.status === 'confirmada'" @click="updateStatus(reservation.id, 'cancel')" class="bg-gray-500 text-white px-3 py-1.5 rounded shadow-sm hover:bg-gray-600 text-xs font-bold transition">
                                                Cancelar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex justify-between items-center" v-if="reservations.links && reservations.total > 0">
                        <span class="text-sm text-gray-600 font-medium">Mostrando página {{ reservations.current_page }} de {{ reservations.last_page }} (Total: {{ reservations.total }})</span>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>