<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    stats: Object,
    recent_reservations: Array
});
</script>

<template>
    <AppLayout title="Reservaciones">

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">Resumen General</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-400">
                    <p class="text-sm text-gray-500 uppercase font-bold">Pendientes</p>
                    <p class="text-3xl font-black text-gray-800">{{ stats.pendientes }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-400">
                    <p class="text-sm text-gray-500 uppercase font-bold">Confirmadas</p>
                    <p class="text-3xl font-black text-gray-800">{{ stats.confirmadas }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-400">
                    <p class="text-sm text-gray-500 uppercase font-bold">Próximas Reservas</p>
                    <p class="text-3xl font-black text-gray-800">{{ stats.proximas }}</p>
                </div>
            </div>

            <div class="flex space-x-4 mb-10">
                <Link :href="route('reservations.index')" class="bg-gray-800 text-white px-6 py-3 rounded-lg font-bold hover:bg-gray-700 transition">
                    Gestionar Reservas
                </Link>
                <Link :href="route('admin.calendar')" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-500 transition">
                    Ver Calendario
                </Link>
            </div>
        </div>
    </div>
    </AppLayout>
</template>