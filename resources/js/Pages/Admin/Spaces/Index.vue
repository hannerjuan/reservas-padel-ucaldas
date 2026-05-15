<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    spaces: Array
});

const destroy = (slug) => {
    if (confirm('¿Estás seguro de que deseas eliminar esta cancha?')) {
        router.delete(route('spaces.destroy', slug));
    }
};
</script>

<template>
    <AppLayout title="Gestión de Canchas">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mis Canchas
            </h2>
        </template>

        <div class="py-12 bg-gray-100 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold text-gray-800">Canchas de Pádel</h1>
                        <Link :href="route('spaces.create')" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition">
                            + Nueva Cancha
                        </Link>
                    </div>

                    <div v-if="$page.props.flash && $page.props.flash.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ $page.props.flash.success }}
                    </div>
                    <div v-if="$page.props.flash && $page.props.flash.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ $page.props.flash.error }}
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left">Nombre</th>
                                    <th class="py-3 px-6 text-left">Tipo</th>
                                    <th class="py-3 px-6 text-center">Precio/Hora</th>
                                    <th class="py-3 px-6 text-center">Estado</th>
                                    <th class="py-3 px-6 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">
                                <tr v-for="space in spaces" :key="space.id" class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-6 text-left font-medium">{{ space.name }}</td>
                                    <td class="py-3 px-6 text-left">{{ space.type }}</td>
                                    <td class="py-3 px-6 text-center font-bold text-green-600">${{ space.price_per_hour }}</td>
                                    <td class="py-3 px-6 text-center">
                                        <span :class="space.is_active ? 'bg-green-200 text-green-700' : 'bg-red-200 text-red-700'" class="py-1 px-3 rounded-full text-xs font-bold uppercase">
                                            {{ space.is_active ? 'Activa' : 'Inactiva' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 text-center flex justify-center space-x-2">
                                       <Link :href="route('availabilities.edit', space.slug)" class="bg-blue-500 text-white px-3 py-1 rounded shadow hover:bg-blue-600 text-xs font-bold transition">
        Horarios
    </Link>
                                        <Link :href="route('spaces.edit', space.slug)" class="bg-yellow-500 text-white px-3 py-1 rounded shadow hover:bg-yellow-600 text-xs font-bold transition">
                                            Editar
                                        </Link>
                                        <button @click="destroy(space.slug)" class="bg-red-500 text-white px-3 py-1 rounded shadow hover:bg-red-600 text-xs font-bold transition">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>