<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    reservations: Object, // Viene paginado desde Laravel
    filters: Object       // Recibe los filtros actuales de la URL
});

// Variables reactivas para los filtros, inicializadas con lo que venga del servidor
const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || '');

// Observador: Escucha cambios en search o status y hace la petición automática
watch([search, status], () => {
    router.get(route('reservations.index'), {
        search: search.value,
        status: status.value
    }, { 
        preserveState: true, 
        replace: true 
    });
});

// Función para ejecutar los cambios de estado (llama a nuestras rutas POST)
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
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    
                    <div class="flex flex-col md:flex-row justify-between mb-6 space-y-4 md:space-y-0 md:space-x-4">
                        <div class="flex-1">
                            <input v-model="search" type="text" placeholder="Buscar por cliente o correo..." 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="w-full md:w-64">
                            <select v-model="status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todos los estados</option>
                                <option value="pendiente">Pendientes</option>
                                <option value="confirmada">Confirmadas</option>
                                <option value="rechazada">Rechazadas</option>
                                <option value="cancelada">Canceladas</option>
                            </select>
                        </div>
                    </div>

                    <div v-if="$page.props.flash && $page.props.flash.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ $page.props.flash.success }}
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Cancha</th>
                                    <th class="py-3 px-6 text-left">Cliente</th>
                                    <th class="py-3 px-6 text-center">Fecha y Hora</th>
                                    <th class="py-3 px-6 text-center">Estado</th>
                                    <th class="py-3 px-6 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                <tr v-for="reservation in reservations.data" :key="reservation.id" class="border-b border-gray-200 hover:bg-gray-100">
                                    
                                    <td class="py-3 px-6 text-left font-medium">
                                        {{ reservation.space.name }}
                                    </td>
                                    
                                    <td class="py-3 px-6 text-left">
                                        <div>{{ reservation.user_name }}</div>
                                        <div class="text-xs text-gray-400">{{ reservation.user_email }}</div>
                                    </td>
                                    
                                    <td class="py-3 px-6 text-center">
                                        <div class="font-bold text-gray-700">{{ new Date(reservation.start_time).toLocaleDateString() }}</div>
                                        <div class="text-xs text-blue-600">
                                            {{ new Date(reservation.start_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }} - 
                                            {{ new Date(reservation.end_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}
                                        </div>
                                    </td>
                                    
                                    <td class="py-3 px-6 text-center">
                                        <span :class="{
                                            'bg-yellow-200 text-yellow-700': reservation.status === 'pendiente',
                                            'bg-green-200 text-green-700': reservation.status === 'confirmada',
                                            'bg-red-200 text-red-700': reservation.status === 'rechazada' || reservation.status === 'cancelada',
                                        }" class="py-1 px-3 rounded-full text-xs uppercase font-bold">
                                            {{ reservation.status }}
                                        </span>
                                    </td>
                                    
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex item-center justify-center space-x-2">
                                            <button v-if="reservation.status === 'pendiente'" @click="updateStatus(reservation.id, 'accept')" class="bg-green-500 text-white px-3 py-1 rounded shadow hover:bg-green-600 text-xs font-bold transition">
                                                Aprobar
                                            </button>
                                            <button v-if="reservation.status === 'pendiente'" @click="updateStatus(reservation.id, 'reject')" class="bg-red-500 text-white px-3 py-1 rounded shadow hover:bg-red-600 text-xs font-bold transition">
                                                Rechazar
                                            </button>
                                            <button v-if="reservation.status === 'confirmada'" @click="updateStatus(reservation.id, 'cancel')" class="bg-gray-500 text-white px-3 py-1 rounded shadow hover:bg-gray-600 text-xs font-bold transition">
                                                Cancelar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex justify-between items-center" v-if="reservations.links">
                        <span class="text-sm text-gray-600">Mostrando página {{ reservations.current_page }} de {{ reservations.last_page }}</span>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>